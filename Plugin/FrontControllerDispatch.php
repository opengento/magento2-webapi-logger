<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Plugin;

use Opengento\WebapiLogger\Model\Config;
use Opengento\WebapiLogger\Model\LogHandle;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Webapi\Controller\Rest;

class FrontControllerDispatch
{
    public function __construct(
        private Config $config,
        private DateTime $date,
        private LogHandle $logHandle
    ) {}

    public function beforeDispatch(Rest $subject, RequestInterface $request): array
    {
        if ($this->config->isEnabled() && !$request->isXmlHttpRequest()) {
            $requestMethod = $request->getMethod();
            $requestorIp = $request->getClientIp();
            $requestPath = $request->getUriString();
            $requestHeaders = $request->getHeaders()->toString();
            $requestBody = $request->getContent();
            $requestDateTime = $this->date->gmtDate();

            $this->logHandle->before(
                $requestMethod,
                $requestorIp,
                $requestPath,
                $requestHeaders,
                $requestBody,
                $requestDateTime
            );
        }

        return [$request];
    }

    public function afterDispatch(Rest $subject, $result, RequestInterface $request): mixed
    {
        if ($this->config->isEnabled() && !$request->isXmlHttpRequest()) {
            $exceptions = $result->getException();

            if (!empty($exceptions)) {
                $responseCode = '';
                $responseBody = '';
                foreach ($exceptions as $exception) {
                    $responseCode .= (string)$exception->getHttpCode() . ' ';
                    $responseBody .= $exception->getMessage() . ' ';
                }
                $responseCode = rtrim($responseCode);
                $responseBody = rtrim($responseBody);
            } else {
                $responseCode = (string)$result->getStatusCode();
                $responseBody = $result->getContent();
                $responseBody = trim($responseBody, '"');
            }

            $responseDateTime = $this->date->gmtDate();
            $this->logHandle->after($responseCode, $responseBody, $responseDateTime);
        }

        return $result;
    }
}
