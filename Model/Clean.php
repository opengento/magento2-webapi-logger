<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model;

use Magento\Framework\Exception\LocalizedException;
use Opengento\WebapiLogger\Model\ResourceModel\Log as LogResource;

class Clean
{
    public function __construct(
        private Config $config,
        private LogResource $logResourceModel
    ) {}

    /**
     * @throws LocalizedException
     */
    public function cleanAll(): void
    {
        $this->logResourceModel->getConnection()->truncateTable($this->logResourceModel->getMainTable());
    }

    /**
     * @throws LocalizedException
     */
    public function clean(): void
    {
        $this->logResourceModel->getConnection()->delete(
            $this->logResourceModel->getMainTable(),
            sprintf(
                '%s < NOW() - INTERVAL %s HOUR',
                LogResource::CREATED_AT,
                $this->config->getCleanOlderThanHours()
            )
        );
    }
}
