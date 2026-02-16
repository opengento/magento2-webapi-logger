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
use Opengento\WebapiLogger\Model\RequestBodyStorage;
use Opengento\WebapiLogger\Model\ResourceModel\Log as LogResource;

class Detail implements ArgumentInterface
{
    private ?Log $log = null;
    private ?string $requestBody = null;

    public function __construct(
        private LogResource $logResourceModel,
        private LogFactory $logFactory,
        private RequestBodyStorage $requestBodyStorage,
        private RequestInterface $request
    ) {}

    public function getLog(): Log
    {
        if ($this->log === null) {
            $this->log = $this->logFactory->create();
            $this->logResourceModel->load($this->log, (int)$this->request->getParam('log_id'));
        }

        return $this->log;
    }

    public function getRequestBody(): string
    {
        if ($this->requestBody === null) {
            $this->requestBody = $this->requestBodyStorage->resolve((string)$this->getLog()->getData('request_body'));
        }

        return $this->requestBody;
    }

    public function isRequestBodyStoredOnDisk(): bool
    {
        return $this->requestBodyStorage->isDiskReference((string)$this->getLog()->getData('request_body'));
    }
}
