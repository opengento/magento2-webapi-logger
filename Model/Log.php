<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model;

use Opengento\WebapiLogger\Model\ResourceModel\LogResourceModel;
use Magento\Framework\Model\AbstractModel;

class Log extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(LogResourceModel::class);
    }
}
