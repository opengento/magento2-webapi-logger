<?php
/**
 * Copyright OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Controller\Adminhtml\Reports;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Opengento\WebapiLogger\Model\LogFactory;
use Opengento\WebapiLogger\Model\RequestBodyStorage;

class Download extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_WebapiLogger::reports_webapilogs';

    public function __construct(
        Context $context,
        private FileFactory $fileFactory,
        private LogFactory $logFactory,
        private RequestBodyStorage $requestBodyStorage
    ) {
        parent::__construct($context);
    }

    public function execute(): ResponseInterface
    {
        $logId = (int) $this->getRequest()->getParam('log_id');
        $log = $this->logFactory->create()->load($logId);

        if (!$log->getId()) {
            $this->getResponse()->setHttpResponseCode(404);

            return $this->getResponse();
        }

        return $this->fileFactory->create(
            sprintf('webapi-request-body-%d.json', $logId),
            $this->getPrettyJson(
                $this->requestBodyStorage->resolve((string)$log->getData('request_body'))
            ),
            DirectoryList::VAR_DIR,
            'application/json'
        );
    }

    private function getPrettyJson(string $requestBody): string
    {
        if ($requestBody === '') {
            return "{}\n";
        }

        $decoded = json_decode($requestBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $decoded = $requestBody;
        }

        $prettyJson = json_encode(
            $decoded,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        return ($prettyJson !== false ? $prettyJson : "{}") . "\n";
    }
}
