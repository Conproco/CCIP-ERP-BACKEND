<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Enums;

/**
 * Origin Type Enum
 * Used to indicate if an expense/item is original or a partition
 */
enum OriginType: string
{
    case ORIGINAL = 'Original';
    case PARTICION = 'Partición';

    /**
     * Get all origin type values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
