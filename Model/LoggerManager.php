<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model;

use Magento\Framework\Exception\AlreadyExistsException;
use Opengento\WebapiLogger\Model\Config\SaveMode;
use Opengento\WebapiLogger\Model\ResourceModel\Log as LogResource;
use Psr\Log\LoggerInterface;

use function in_array;

class LoggerManager
{
    public function __construct(
        private LogResource $logResourceModel,
        private Config $config,
        private LoggerInterface $logger
    ) {}

    /**
     * @throws AlreadyExistsException
     */
    public function log(Log $log): void
    {
        $saveModes = $this->config->getSaveModes();

        if (in_array(SaveMode::Psr, $saveModes, true)) {
            $this->savePsr($log);
        }
        if (in_array(SaveMode::DataBase, $saveModes, true)) {
            $this->saveDb($log);
        }
    }

    private function savePsr(Log $log): void
    {
        $this->logger->debug(
            match(true) {
                $log->getData('is_response') => 'Logged WEBAPI Response',
                $log->getData('is_request') => 'Logged WEBAPI Request',
                default => 'n/a',
            },
            $log->toArray()
        );
    }

    /**
     * @throws AlreadyExistsException
     */
    private function saveDb(Log $log): void
    {
        $this->logResourceModel->save($log);
    }
}
