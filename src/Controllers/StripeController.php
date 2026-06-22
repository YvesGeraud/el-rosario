<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\Carrito;
use App\Models\Configuracion;

class StripeController {

    private function getConfig() {
        return (new Configuracion())->getAll();
    }

    /**
     * Crear un PaymentIntent en Stripe y devolver el client_secret al frontend.
     * Se llama vía AJAX desde el checkout cuando el usuario elige "tarjeta".
     */
    public function createPaymentIntent() {
        header('Content-Type: application/json');

        $secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';
        if (empty($secretKey) || str_starts_with($secretKey, 'sk_test_REEMPLAZA')) {
            http_response_code(500);
            echo json_encode(['error' => 'Stripe no está configurado. Añade tu clave secreta en el .env.']);
            exit;
        }

        $config   = $this->getConfig();
        $subtotal = Carrito::subtotal();
        $envio    = Carrito::costoEnvio($config, $subtotal);
        $total    = $subtotal + $envio;

        if ($total <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'El carrito está vacío.']);
            exit;
        }

        try {
            \Stripe\Stripe::setApiKey($secretKey);

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount'   => (int) round($total * 100), // Stripe trabaja en centavos
                'currency' => 'mxn',
                'metadata' => [
                    'tienda' => 'Blancos El Rosario',
                ],
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            echo json_encode([
                'clientSecret' => $paymentIntent->client_secret,
                'amount'       => $total,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }

        exit;
    }

    /**
     * Verificar el estado de un PaymentIntent y procesar el pedido si fue exitoso.
     * Se llama vía AJAX/POST tras la confirmación de Stripe en el frontend.
     */
    public function confirmarPago() {
        header('Content-Type: application/json');

        $secretKey       = $_ENV['STRIPE_SECRET_KEY'] ?? '';
        $paymentIntentId = Request::post('payment_intent_id');
        $datosPedido     = json_decode(Request::post('datos_pedido'), true);

        if (!$paymentIntentId || !$datosPedido) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos.']);
            exit;
        }

        try {
            \Stripe\Stripe::setApiKey($secretKey);
            $intent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

            if ($intent->status !== 'succeeded') {
                http_response_code(400);
                echo json_encode(['error' => 'El pago no se completó. Estado: ' . $intent->status]);
                exit;
            }

            // El pago fue exitoso → crear el pedido
            $config   = (new \App\Models\Configuracion())->getAll();
            $items    = Carrito::getItems();
            $subtotal = Carrito::subtotal();
            $envio    = Carrito::costoEnvio($config, $subtotal);
            $total    = $subtotal + $envio;

            $pedidoModel = new \App\Models\Pedido();
            $result = $pedidoModel->crear(array_merge($datosPedido, [
                'id_ct_cliente'    => $_SESSION['cliente_id'] ?? null,
                'metodo_pago'      => 'tarjeta_stripe',
                'stripe_intent_id' => $paymentIntentId,
                'subtotal'         => $subtotal,
                'costo_envio'      => $envio,
                'total'            => $total,
            ]), $items);

            // Vaciar carrito
            Carrito::vaciar();

            echo json_encode([
                'success' => true,
                'folio'   => $result['folio'],
                'redirect'=> URL_BASE . '/pedido/' . $result['folio'],
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }

        exit;
    }
}
