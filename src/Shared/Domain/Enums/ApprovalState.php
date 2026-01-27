<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Enums;

/**
 * Approval State Enum
 * Shared state types across multiple modules (Expenses, Projects, Approvals, etc.)
 */
enum ApprovalState: string
{
    case PENDIENTE = 'Pendiente';
    case PROCESO = 'Proceso';
    case ACEPTADO = 'Aceptado';
    case ACEPTADO_VALIDADO = 'Aceptado - Validado';
    case RECHAZADO = 'Rechazado';
    case NO_DISPONIBLE = 'No Disponible';
    case EXCEDIDO = 'Excedido';
    case PROGRAMADO = 'Programado';
    case COMPLETADO = 'Completado';
    case RECHAZADO_TELECREDITO = 'Rechazado Telecredito';

    /**
     * Get all state values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get count of states
     */
    public static function count(): int
    {
        return count(self::cases());
    }

    /**
     * Get states for Additional Costs module
     */
    public static function forAdditionalCosts(): array
    {
        return [
            self::PENDIENTE->value,
            self::ACEPTADO->value,
            self::RECHAZADO->value,
            self::ACEPTADO_VALIDADO->value,
        ];
    }

    /**
     * Get admin states for Additional Costs module
     */
    public static function forAdditionalCostsAdmin(): array
    {
        return [
            self::NO_DISPONIBLE->value,
            self::PENDIENTE->value,
            self::ACEPTADO->value,
            self::RECHAZADO->value,
        ];
    }

    /**
     * Get states for Static Costs module
     */
    public static function forStaticCosts(): array
    {
        return [
            self::PENDIENTE->value,
            self::ACEPTADO_VALIDADO->value,
        ];
    }

    /**
     * Get states for Payment Approval module
     */
    public static function forPaymentApproval(): array
    {
        return [
            self::RECHAZADO->value,
            self::PENDIENTE->value,
            self::PROGRAMADO->value,
            self::COMPLETADO->value,
            self::RECHAZADO_TELECREDITO->value,
        ];
    }

    /**
     * Get states that represent pending or accepted (not rejected)
     */
    public static function pendingOrAccepted(): array
    {
        return [
            self::PENDIENTE->value,
            self::ACEPTADO->value,
            self::ACEPTADO_VALIDADO->value,
        ];
    }

    /**
     * Check if state is accepted
     */
    public function isAccepted(): bool
    {
        return $this === self::ACEPTADO || $this === self::ACEPTADO_VALIDADO;
    }

    /**
     * Check if state is rejected
     */
    public function isRejected(): bool
    {
        return $this === self::RECHAZADO || $this === self::RECHAZADO_TELECREDITO;
    }

    /**
     * Check if state is pending
     */
    public function isPending(): bool
    {
        return $this === self::PENDIENTE;
    }
}
