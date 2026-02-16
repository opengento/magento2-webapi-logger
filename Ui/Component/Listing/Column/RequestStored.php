<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

use function str_starts_with;

class RequestStored extends Column
{
    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            $requestStored = (string)($item['request_stored'] ?? '');
            $requestBody = (string)($item['request_body'] ?? '');

            if (str_starts_with($requestBody, '@disk:')) {
                $requestStored = 'disk';
            } elseif ($requestStored === '') {
                $requestStored = 'db';
            }

            $item[$this->getData('name')] = $requestStored;
        }

        return $dataSource;
    }
}
