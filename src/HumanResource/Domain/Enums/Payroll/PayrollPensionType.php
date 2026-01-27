<?php

declare(strict_types=1);

namespace Src\HumanResource\Domain\Enums\Payroll;

/**
 * Pension types for payroll
 */
enum PayrollPensionType: string
{
    case HABITAT = 'Habitat';
    case INTEGRA = 'Integra';
    case PRIMA = 'Prima';
    case PROFUTURO = 'Profuturo';
    case HABITAT_MX = 'HabitadMX';
    case INTEGRA_MX = 'IntegraMX';
    case PRIMA_MX = 'PrimaMX';
    case PROFUTURO_MX = 'ProfuturoMX';
    case ONP = 'ONP';

    /**
     * Get all pension type values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get count of pension types
     */
    public static function count(): int
    {
        return count(self::cases());
    }
}
