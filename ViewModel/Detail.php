<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Opengento\WebapiLogger\Model\Log;
use Opengento\WebapiLogger\Model\LogFactory;
use Opengento\WebapiLogger\Model\ResourceModel\Log as LogResource;

class Detail implements ArgumentInterface
{
    public function __construct(
        private LogResource $logResourceModel,
        private LogFactory $logFactory,
        private RequestInterface $request
    ) {}

    public function getLog(): Log
    {
        $log = $this->logFactory->create();
        $this->logResourceModel->load($log, (int)$this->request->getParam('log_id'));

        return $log;
    }
}
