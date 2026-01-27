<?php

declare(strict_types=1);

namespace Src\HumanResource\Domain\Enums\Payroll;

/**
 * State types for payroll expenses (sc = Static Costs)
 */
enum PayrollExpenseStateType: string
{
    case PENDIENTE = 'Pendiente';
    case ACEPTADO_VALIDADO = 'Aceptado - Validado';

    /**
     * Get all state type values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get count of state types
     */
    public static function count(): int
    {
        return count(self::cases());
    }
}
