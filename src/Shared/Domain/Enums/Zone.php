<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Enums;

/**
 * Zone Enum
 * Shared across multiple modules (Projects, Expenses, Administrative Costs, etc.)
 */
enum Zone: string
{
    case AREQUIPA = 'Arequipa';
    case CHALA = 'Chala';
    case MOQUEGUA = 'Moquegua';
    case TACNA = 'Tacna';
    case MDD1_PM = 'MDD1-PM';
    case MDD2_MAZ = 'MDD2-MAZ';
    case SANDIA = 'Sandia';
    case MOQUEGUA_PEXT = 'Moquegua-PEXT';
    case SANDIA_PEXT = 'Sandia-PEXT';
    case MDD2_MAZ_PEXT = 'MDD2-MAZ-PEXT';
    case OFICINA = 'Oficina';
    case PUNO = 'Puno';
    case CUZCO = 'Cuzco';
    case GENERAL = 'General';

    /**
     * Get all zone values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get count of zones
     */
    public static function count(): int
    {
        return count(self::cases());
    }

    /**
     * Get zones for Projects
     */
    public static function forProjects(): array
    {
        return [
            self::AREQUIPA->value,
            self::CHALA->value,
            self::MOQUEGUA->value,
            self::TACNA->value,
            self::MDD1_PM->value,
            self::MDD2_MAZ->value,
            self::SANDIA->value,
            self::PUNO->value,
            self::CUZCO->value,
        ];
    }

    /**
     * Get zones for Additional Costs  
     */
    public static function forAdditionalCosts(): array
    {
        return [
            self::AREQUIPA->value,
            self::CHALA->value,
            self::MOQUEGUA->value,
            self::TACNA->value,
            self::MDD1_PM->value,
            self::MDD2_MAZ->value,
            self::SANDIA->value,
            self::MOQUEGUA_PEXT->value,
            self::SANDIA_PEXT->value,
            self::MDD2_MAZ_PEXT->value,
            self::OFICINA->value,
            self::PUNO->value,
            self::CUZCO->value,
        ];
    }

    /**
     * Get zones for Static Costs
     */
    public static function forStaticCosts(): array
    {
        return [
            self::AREQUIPA->value,
            self::CHALA->value,
            self::MOQUEGUA->value,
            self::TACNA->value,
            self::MDD1_PM->value,
            self::MDD2_MAZ->value,
            self::SANDIA->value,
            self::MOQUEGUA_PEXT->value,
            self::SANDIA_PEXT->value,
            self::MDD2_MAZ_PEXT->value,
            self::OFICINA->value,
            self::PUNO->value,
            self::CUZCO->value,
            self::GENERAL->value,
        ];
    }

    /**
     * Get zones for Mobile module
     */
    public static function forMobile(): array
    {
        return [
            self::AREQUIPA->value,
            self::CHALA->value,
            self::MOQUEGUA->value,
            self::TACNA->value,
            self::MDD1_PM->value,
            self::MDD2_MAZ->value,
            self::SANDIA->value,
            self::MOQUEGUA_PEXT->value,
            self::SANDIA_PEXT->value,
            self::MDD2_MAZ_PEXT->value,
            self::PUNO->value,
            self::CUZCO->value,
        ];
    }

    /**
     * Get zones without IGV (tax-exempt zones)
     */
    public static function withoutIgv(): array
    {
        return [
            self::MDD1_PM->value,
            self::MDD2_MAZ->value,
        ];
    }
}
