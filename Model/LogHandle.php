<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model;

use Exception;
use Opengento\WebapiLogger\Model\ResourceModel\LogResourceModel;
use Psr\Log\LoggerInterface;

class LogHandle
{
    private ?Log $lastLog = null;

    public function __construct(
        private LogFactory $logFactory,
        private LogResourceModel $logResourceModel,
        private SecretParser $secretParser,
        private Config $config,
        private LoggerInterface $logger
    ) {}

    public function before(
        string $requestMethod,
        string $requestorIp,
        string $requestPath,
        string $requestHeaders,
        string $requestBody,
        string $requestDateTime
    ) {
        try {
            if ($this->config->isSecretMode()) {
                $requestorIp = $this->secretParser->parseIp();
                $requestHeaders = $this->secretParser->parseHeaders($requestHeaders);
                $requestBody = $this->secretParser->parseBody($requestBody);
            }

            $log = $this->logFactory->create();
            $log->setData([
                'request_method' => $requestMethod,
                'requestor_ip' => $requestorIp,
                'request_url' => $requestPath,
                'request_headers' => $requestHeaders,
                'request_body' => $requestBody,
                'request_datetime' => $requestDateTime
            ]);
            $this->logResourceModel->save($log);
            $this->lastLog = $log;
        } catch (Exception $exception) {
            $this->logger->error(__('Cant complete webapi log save because of error: %1', $exception->getMessage()));
        }
    }

    public function after(
        string $responseCode,
        string $responseBody,
        string $responseDateTime
    ) {
        if (!$this->lastLog) {
            return;
        }

        try {
            if ($this->config->isSecretMode()) {
                $responseBody = $this->secretParser->parseBody($responseBody);
            }

            $this->lastLog->setResponseBody($responseBody);
            $this->lastLog->setResponseCode($responseCode);
            $this->lastLog->setResponseDatetime($responseDateTime);
            if ($responseCode === '200') {
                $this->logResourceModel->delete($this->lastLog);
            } else {
                $this->logResourceModel->save($this->lastLog);
            }
        } catch (Exception $exception) {
            $this->logger->error(__('Cant complete webapi log save because of error: %1', $exception->getMessage()));
        }
    }
}
