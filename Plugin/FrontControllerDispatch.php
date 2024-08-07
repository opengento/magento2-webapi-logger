<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Plugin;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Webapi\Controller\Rest;
use Opengento\WebapiLogger\Model\Config;
use Opengento\WebapiLogger\Model\LogHandle;

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
            $this->logHandle->before(
                $request->getMethod(),
                $request->getClientIp(),
                $request->getUriString(),
                $request->getHeaders()->toString(),
                $request->getContent(),
                $this->date->gmtDate()
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

            $this->logHandle->after($responseCode, $responseBody, $this->date->gmtDate());
        }

        return $result;
    }
}
