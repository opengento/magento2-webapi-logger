<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model\ResourceModel\Log;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Opengento\WebapiLogger\Model\Log;
use Opengento\WebapiLogger\Model\ResourceModel\Log as LogResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'log_id';
    protected $_eventPrefix = 'webapi_log_collection';
    protected $_eventObject = 'logs_collection';

    protected function _construct(): void
    {
        $this->_init(Log::class, LogResource::class);
    }

    public function responseCodeToOptionArray(): array
    {
        return $this->_toOptionArray('response_code', 'response_code');
    }

    public function requestMethodToOptionArray(): array
    {
        return $this->_toOptionArray('request_method', 'request_method');
    }

    public function requestorIpToOptionArray(): array
    {
        return $this->_toOptionArray('requestor_ip', 'requestor_ip');
    }
}
