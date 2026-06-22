ALTER TABLE `ct_pedido`
    MODIFY COLUMN `metodo_pago` ENUM('transferencia','contra_entrega','tarjeta_stripe') NOT NULL DEFAULT 'contra_entrega',
    ADD COLUMN IF NOT EXISTS `stripe_intent_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `notas`;
