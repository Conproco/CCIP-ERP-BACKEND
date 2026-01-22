<?php

namespace Src\Shared\Domain\Constants;

class DocumentConstants
{
    public const SCTR = 'SCTR';
    public const POLIZA = 'Póliza';

    public static function grupalDocuments(): array
    {
        return [
            self::SCTR,
            self::POLIZA
        ];
    }
}
