<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Ui\DataProvider\Log;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Opengento\WebapiLogger\Model\LogFactory;
use Opengento\WebapiLogger\Model\RequestBodyStorage;
use Opengento\WebapiLogger\Model\ResourceModel\Log\CollectionFactory;

use function strlen;

class DetailDataProvider extends AbstractDataProvider
{
    private const MAX_REQUEST_BODY_VIEW_BYTES = 2097152;

    private ?array $loadedData = null;

    public function __construct(
        CollectionFactory $collectionFactory,
        private RequestInterface $request,
        private LogFactory $logFactory,
        private RequestBodyStorage $requestBodyStorage,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    public function getData(): array
    {
        if ($this->loadedData !== null) {
            return $this->loadedData;
        }

        $this->loadedData = [];
        $logId = (int)$this->request->getParam($this->getRequestFieldName());
        if ($logId <= 0) {
            return $this->loadedData;
        }

        $log = $this->logFactory->create()->load($logId);
        if (!$log->getId()) {
            return $this->loadedData;
        }

        $data = $log->getData();
        $rawRequestBody = (string)($data['request_body'] ?? '');
        $requestBody = $this->requestBodyStorage->resolve($rawRequestBody);

        if ($this->requestBodyStorage->isDiskReference($rawRequestBody) && $requestBody === '') {
            $requestBody = (string)__('Request body file is not available on disk.');
        }
        if (strlen($requestBody) > self::MAX_REQUEST_BODY_VIEW_BYTES) {
            $requestBody = (string)__('Request too large to view, download instead.');
        }

        $data['request_body'] = $requestBody;
        $this->loadedData[$logId] = $data;

        return $this->loadedData;
    }
}
