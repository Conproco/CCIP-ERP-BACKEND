<?php

declare(strict_types=1);

namespace Src\HumanResource\Domain\Enums\Payroll;

/**
 * Document types for payroll expenses
 */
enum PayrollDocType: string
{
    case BOLETA_DE_PAGO = 'Boleta de Pago';
    case FACTURA = 'Factura';
    case RECIBO = 'Recibo';
    case RECIBO_HONORARIOS = 'Recibo por Honorarios';
    case COMPROBANTE_DE_PAGO = 'Comprobante de Pago';
    case VOUCHER_DE_PAGO = 'Voucher de Pago';
    case SIN_COMPROBANTE = 'Sin Comprobante';

    /**
     * Get all doc type values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get count of doc types
     */
    public static function count(): int
    {
        return count(self::cases());
    }
}
