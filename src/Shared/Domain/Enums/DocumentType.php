<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Enums;

/**
 * Document Type Enum
 * Shared document types across multiple modules (Expenses, Payments, Invoices, etc.)
 */
enum DocumentType: string
{
    case SIN_COMPROBANTE = 'Sin Comprobante';
    case RH = 'Recibo por Honorarios';
    case FACTURA = 'Factura';
    case BOLETA = 'Boleta';
    case BOLETA_DE_VENTA = 'Boleta de Venta';
    case VOUCHER_DE_PAGO = 'Voucher de Pago';
    case BOLETA_DE_PAGO = 'Boleta de Pago';
    case COMPROBANTE_DE_PAGO = 'Comprobante de Pago';
    case RECIBO = 'Recibo';

    /**
     * Get all document type values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get count of document types
     */
    public static function count(): int
    {
        return count(self::cases());
    }

    /**
     * Get document types for Additional Costs module
     */
    public static function forAdditionalCosts(): array
    {
        return [
            self::SIN_COMPROBANTE->value,
            self::FACTURA->value,
            self::BOLETA->value,
            self::VOUCHER_DE_PAGO->value,
            self::RH->value,
        ];
    }

    /**
     * Get document types for Static Costs module
     */
    public static function forStaticCosts(): array
    {
        return [
            self::SIN_COMPROBANTE->value,
            self::FACTURA->value,
            self::BOLETA->value,
            self::VOUCHER_DE_PAGO->value,
            self::RH->value,
        ];
    }

    /**
     * Get document types for Administrative Costs module
     */
    public static function forAdministrativeCosts(): array
    {
        return [
            self::BOLETA_DE_VENTA->value,
            self::BOLETA_DE_PAGO->value,
            self::FACTURA->value,
            self::RECIBO->value,
            self::RH->value,
            self::SIN_COMPROBANTE->value,
            self::COMPROBANTE_DE_PAGO->value,
            self::VOUCHER_DE_PAGO->value,
        ];
    }

    /**
     * Get document types for Mobile module
     */
    public static function forMobile(): array
    {
        return [
            self::SIN_COMPROBANTE->value,
            self::FACTURA->value,
            self::BOLETA->value,
            self::VOUCHER_DE_PAGO->value,
            self::RH->value,
        ];
    }

    /**
     * Check if document type requires tax (boleta/factura)
     */
    public function requiresTax(): bool
    {
        return $this === self::FACTURA ||
            $this === self::BOLETA ||
            $this === self::BOLETA_DE_VENTA ||
            $this === self::BOLETA_DE_PAGO;
    }

    /**
     * Check if document type is a receipt
     */
    public function isReceipt(): bool
    {
        return $this === self::RECIBO || $this === self::RH;
    }
}
