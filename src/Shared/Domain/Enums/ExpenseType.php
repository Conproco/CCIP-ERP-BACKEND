<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Enums;

/**
 * Expense Types Enum
 * Shared across multiple modules (Payroll, Projects, Administrative Costs, etc.)
 */
enum ExpenseType: string
{
    case HOSPEDAJE = 'Hospedaje';
    case ENCOMIENDA = 'Encomienda';
    case CONSUMIBLES = 'Consumibles';
    case PASAJE_INTERPROVINCIAL = 'Pasaje Interprovincial';
    case TAXIS_Y_PASAJES = 'Taxis y Pasajes';
    case BANDEOS = 'Bandeos';
    case PEAJE = 'Peaje';
    case HERRAMIENTAS = 'Herramientas';
    case EQUIPOS = 'Equipos';
    case DANOS_DE_VEHICULOS = 'Daños de Vehículos';
    case EPPS = 'EPPs';
    case SEGUROS_Y_POLIZAS = 'Seguros y Pólizas';
    case GASTOS_DE_REPRESENTACION = 'Gastos de Representación';
    case COCHERAS = 'Cocheras';
    case APOYOS = 'Apoyos';
    case ACARREOS = 'Acarreos';
    case ACTIVOS = 'Activos';
    case GASTOS_FINANCIEROS = 'Gastos Financieros';
    case OTROS = 'Otros';
    case ALQUILER_DE_VEHICULOS = 'Alquiler de Vehículos';
    case ALQUILER_DE_LOCALES = 'Alquiler de Locales';
    case COMBUSTIBLE_UM = 'Combustible UM';
    case COMBUSTIBLE_GEP = 'Combustible GEP';
    case CELULARES = 'Celulares';
    case PROVEIDOS = 'Proveídos';
    case TERCEROS = 'Terceros';
    case VIATICOS = 'Viáticos';
    case REPOSICION_DE_EQUIPO = 'Reposición de Equipo';
    case ADICIONALES = 'Adicionales';
    case FILTROS_Y_ACEITES = 'Filtros y Aceites';
    case PLANILLA = 'Planilla';
    case PRESTAMOS = 'Préstamos';
    case IMPLEMENTACION_DE_OFICINA = 'Implementación de Oficina';
    case RENOVACION_DE_OFICINA = 'Renovación de Oficina';
    case EMOS = 'EMOS';
    case VERICOM = 'Vericom';
    case CURSOS = 'Cursos';

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

    /**
     * Get expense types for Additional Costs module
     */
    public static function forAdditionalCosts(): array
    {
        return [
            self::HOSPEDAJE->value,
            self::ENCOMIENDA->value,
            self::CONSUMIBLES->value,
            self::PASAJE_INTERPROVINCIAL->value,
            self::TAXIS_Y_PASAJES->value,
            self::BANDEOS->value,
            self::PEAJE->value,
            self::DANOS_DE_VEHICULOS->value,
            self::EPPS->value,
            self::HERRAMIENTAS->value,
            self::GASTOS_DE_REPRESENTACION->value,
            self::COCHERAS->value,
            self::APOYOS->value,
            self::ACARREOS->value,
            self::COMBUSTIBLE_UM->value,
            self::COMBUSTIBLE_GEP->value,
            self::GASTOS_FINANCIEROS->value,
            self::OTROS->value,
        ];
    }

    /**
     * Get expense types for Static Costs module
     */
    public static function forStaticCosts(): array
    {
        return [
            self::ALQUILER_DE_VEHICULOS->value,
            self::ALQUILER_DE_LOCALES->value,
            self::COMBUSTIBLE_UM->value,
            self::COMBUSTIBLE_GEP->value,
            self::CELULARES->value,
            self::PROVEIDOS->value,
            self::TERCEROS->value,
            self::VIATICOS->value,
            self::SEGUROS_Y_POLIZAS->value,
            self::GASTOS_DE_REPRESENTACION->value,
            self::REPOSICION_DE_EQUIPO->value,
            self::HERRAMIENTAS->value,
            self::EQUIPOS->value,
            self::EPPS->value,
            self::ADICIONALES->value,
            self::FILTROS_Y_ACEITES->value,
            self::DANOS_DE_VEHICULOS->value,
            self::ACTIVOS->value,
            self::PLANILLA->value,
            self::GASTOS_FINANCIEROS->value,
            self::OTROS->value,
        ];
    }

    /**
     * Get expense types for Administrative Costs module
     */
    public static function forAdministrativeCosts(): array
    {
        return [
            self::PRESTAMOS->value,
            self::HOSPEDAJE->value,
            self::ENCOMIENDA->value,
            self::CONSUMIBLES->value,
            self::PASAJE_INTERPROVINCIAL->value,
            self::TAXIS_Y_PASAJES->value,
            self::BANDEOS->value,
            self::PEAJE->value,
            self::HERRAMIENTAS->value,
            self::EQUIPOS->value,
            self::DANOS_DE_VEHICULOS->value,
            self::EPPS->value,
            self::SEGUROS_Y_POLIZAS->value,
            self::GASTOS_DE_REPRESENTACION->value,
            self::COCHERAS->value,
            self::APOYOS->value,
            self::ACARREOS->value,
            self::ACTIVOS->value,
            self::GASTOS_FINANCIEROS->value,
            self::ALQUILER_DE_VEHICULOS->value,
            self::ALQUILER_DE_LOCALES->value,
            self::COMBUSTIBLE_UM->value,
            self::COMBUSTIBLE_GEP->value,
            self::CELULARES->value,
            self::PROVEIDOS->value,
            self::TERCEROS->value,
            self::VIATICOS->value,
            self::REPOSICION_DE_EQUIPO->value,
            self::ADICIONALES->value,
            self::FILTROS_Y_ACEITES->value,
            self::PLANILLA->value,
            self::IMPLEMENTACION_DE_OFICINA->value,
            self::RENOVACION_DE_OFICINA->value,
            self::OTROS->value,
            self::EMOS->value,
            self::CURSOS->value,
            self::VERICOM->value,
        ];
    }

    /**
     * Get expense types for Mobile module
     */
    public static function forMobile(): array
    {
        return [
            self::COMBUSTIBLE_UM->value,
            self::COMBUSTIBLE_GEP->value,
            self::HOSPEDAJE->value,
            self::PASAJE_INTERPROVINCIAL->value,
            self::PEAJE->value,
            self::TAXIS_Y_PASAJES->value,
            self::COCHERAS->value,
            self::APOYOS->value,
            self::ACARREOS->value,
            self::ENCOMIENDA->value,
            self::CONSUMIBLES->value,
            self::BANDEOS->value,
            self::OTROS->value,
        ];
    }

    /**
     * Get expense types that don't count toward budget calculations
     */
    public static function thatDontCount(): array
    {
        return [
            self::COMBUSTIBLE_GEP->value,
            self::ACTIVOS->value,
            self::REPOSICION_DE_EQUIPO->value,
        ];
    }
}
