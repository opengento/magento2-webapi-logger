<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model\Config;

use Magento\Framework\Phrase;

use function array_map;

enum SaveMode: string
{
    private const PSR = 'psr';
    private const DATABASE = 'database';
    private const DISK = 'disk';

    private const LABELS = [
        self::PSR => 'PSR Logger',
        self::DATABASE => 'Database',
        self::DISK => 'Disk (/var)'
    ];

    case Psr = self::PSR;
    case DataBase = self::DATABASE;
    case Disk = self::DISK;

    public function getLabel(): Phrase
    {
        return new Phrase(self::LABELS[$this->value] ?? $this->name);
    }

    public function requiresDbRow(): bool
    {
        return $this === self::DataBase || $this === self::Disk;
    }

    public static function shouldPersistToDb(array $saveModes): bool
    {
        foreach ($saveModes as $saveMode) {
            if ($saveMode instanceof self && $saveMode->requiresDbRow()) {
                return true;
            }
        }

        return false;
    }

    public static function toOptionArray(): array
    {
        return array_map(
            static fn (self $saveMode): array => ['label' => $saveMode->getLabel(), 'value' => $saveMode->value],
            self::cases()
        );
    }
}
