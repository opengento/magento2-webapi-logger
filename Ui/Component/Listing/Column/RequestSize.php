<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

use function count;
use function is_numeric;
use function max;
use function number_format;
use function pow;
use function round;

class RequestSize extends Column
{
    private const UNITS = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            $bytes = is_numeric($item['request_size'] ?? null) ? (int)$item['request_size'] : 0;
            $item[$this->getData('name')] = $this->formatSize($bytes);
        }

        return $dataSource;
    }

    private function formatSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return number_format($bytes) . ' b';
        }

        $value = (float)$bytes;
        $unitIndex = 0;
        $lastUnitIndex = count(self::UNITS) - 1;
        while ($value >= 1024 && $unitIndex < $lastUnitIndex) {
            $value /= 1024;
            $unitIndex++;
        }

        $base = max(1, (int)pow(1024, $unitIndex));
        if ($bytes % $base === 0) {
            return number_format((int)round($value)) . ' ' . self::UNITS[$unitIndex];
        }

        return number_format($value, 2) . ' ' . self::UNITS[$unitIndex];
    }
}
