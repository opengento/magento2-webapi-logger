<?php

namespace Opengento\WebapiLogger\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class ResponseBody extends Column
{
    public function prepareDataSource(array $dataSource): array
    {
        foreach ($dataSource['data']['items'] ?? [] as & $item) {
            if (isset($item[$this->getData('name')], $item['response_code']) && (int)$item['response_code'] === 200) {
                $item[$this->getData('name')] = '';
            }
        }

        return $dataSource;
    }
}
