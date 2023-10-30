<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class RowAction extends Column
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        foreach ($dataSource['data']['items'] ?? [] as &$item) {
            $item[$this->getData('name')]['edit'] = [
                'href' =>
                    $this->urlBuilder->getUrl(
                        'webapi_logs/reports/detail',
                        ['log_id' => $item['log_id']]
                    ),
                'label' => __('View More'),
                'hidden' => false
            ];
        }

        return $dataSource;
    }
}
