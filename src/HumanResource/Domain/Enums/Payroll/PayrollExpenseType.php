<?php

declare(strict_types=1);

namespace Src\HumanResource\Domain\Enums\Payroll;

/**
 * Expense types for payroll detail expenses
 */
enum PayrollExpenseType: string
{
    case SCTR_PENSIONARIO = 'SCTR pensionario';
    case SCTR_SALUD = 'SCTR salud';
    case AFP = 'AFP';
    case ONP = 'ONP';
    case SALARIO_BASICO = 'Salario básico';
    case REF_NO_PRINCIPAL = 'Ref no principal';
    case FINANCIAL_EXPENSE = 'Gasto financiero';
    case CTS = 'CTS';
    case BONUS = 'Gratificación';

    /**
     * Get all expense type values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get count of expense types
     */
    public static function count(): int
    {
        return count(self::cases());
    }
}
