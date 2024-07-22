<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Opengento\WebapiLogger\Model\Config\SaveMode;

class SaveModes implements OptionSourceInterface
{
    private ?array $options = null;

    public function toOptionArray(): array
    {
        return $this->options ??= SaveMode::toOptionArray();
    }
}
