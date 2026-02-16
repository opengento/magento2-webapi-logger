<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class RequestStored implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['label' => __('DB'), 'value' => 'db'],
            ['label' => __('Disk'), 'value' => 'disk'],
            ['label' => __('PSR'), 'value' => 'psr']
        ];
    }
}
