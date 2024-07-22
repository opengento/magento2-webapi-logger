<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model;

use Magento\Framework\Model\AbstractModel;
use Opengento\WebapiLogger\Model\ResourceModel\Log as LogResource;

class Log extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(LogResource::class);
    }
}
