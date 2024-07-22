<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model;

use Exception;
use Psr\Log\LoggerInterface;

class LogHandle
{
    private ?Log $lastLog = null;

    public function __construct(
        private LogFactory $logFactory,
        private SecretParser $secretParser,
        private Config $config,
        private LoggerManager $loggerManager,
        private LoggerInterface $logger
    ) {}

    public function before(
        string $requestMethod,
        string $requestorIp,
        string $requestPath,
        string $requestHeaders,
        string $requestBody,
        string $requestDateTime
    ): void {
        try {
            if ($this->config->isSecretMode()) {
                $requestorIp = $this->secretParser->parseIp();
                $requestHeaders = $this->secretParser->parseHeaders($requestHeaders);
                $requestBody = $this->secretParser->parseBody($requestBody);
            }

            $log = $this->logFactory->create();
            $log->setData([
                'is_request' => true,
                'request_method' => $requestMethod,
                'requestor_ip' => $requestorIp,
                'request_url' => $requestPath,
                'request_headers' => $requestHeaders,
                'request_body' => $requestBody,
                'request_datetime' => $requestDateTime
            ]);
            $this->loggerManager->log($log);
            $this->lastLog = $log;
        } catch (Exception $exception) {
            $this->logger->error('Cant complete webapi log save because of error: ' . $exception->getMessage());
        }
    }

    public function after(
        string $responseCode,
        string $responseBody,
        string $responseDateTime
    ): void {
        if ($this->lastLog) {
            try {
                if ($this->config->isSecretMode()) {
                    $responseBody = $this->secretParser->parseBody($responseBody);
                }

                $this->lastLog->unsetData('is_request');
                $this->lastLog->addData([
                    'is_response' => true,
                    'response_body' => $responseBody,
                    'response_code' => $responseCode,
                    'response_datetime' => $responseDateTime
                ]);
                $this->loggerManager->log($log);
            } catch (Exception $exception) {
                $this->logger->error('Cant complete webapi log save because of error: ' . $exception->getMessage());
            }
        }
    }
}
