<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Cron;

use Magento\Framework\Exception\LocalizedException;
use Opengento\WebapiLogger\Model\Clean;

class Cleaner
{
    public function __construct(private Clean $clean) {}

    /**
     * @throws LocalizedException
     */
    public function execute(): void
    {
        $this->clean->clean();
    }
}
