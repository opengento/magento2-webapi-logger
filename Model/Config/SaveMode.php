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

    private const LABELS = [
        self::PSR => 'PSR Logger',
        self::DATABASE => 'Database'
    ];

    case Psr = self::PSR;
    case DataBase = self::DATABASE;

    public function getLabel(): Phrase
    {
        return new Phrase(self::LABELS[$this->value] ?? $this->name);
    }

    public static function toOptionArray(): array
    {
        return array_map(
            static fn (self $saveMode): array => ['label' => $saveMode->getLabel(), 'value' => $saveMode->value],
            self::cases()
        );
    }
}
