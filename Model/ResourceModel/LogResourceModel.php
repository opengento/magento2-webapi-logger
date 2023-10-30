<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class LogResourceModel extends AbstractDb
{
    public const CREATED_AT = 'created_at';

    protected function _construct(): void
    {
        $this->_init('webapi_logs', 'log_id');
    }
}
