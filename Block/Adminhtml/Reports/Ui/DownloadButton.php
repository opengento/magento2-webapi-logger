<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Block\Adminhtml\Reports\Ui;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DownloadButton implements ButtonProviderInterface
{
    public function __construct(
        private Context $context
    ) {}

    public function getButtonData(): array
    {
        $logId = (int)$this->context->getRequest()->getParam('log_id');
        if ($logId <= 0) {
            return [];
        }

        return [
            'label' => __('Download'),
            'on_click' => sprintf(
                "location.href = '%s';",
                $this->context->getUrlBuilder()->getUrl('webapi_logs/reports/download', ['log_id' => $logId])
            ),
            'class' => 'secondary',
            'sort_order' => 20
        ];
    }
}
