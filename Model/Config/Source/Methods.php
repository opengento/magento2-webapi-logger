<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model\Config\Source;

use Opengento\WebapiLogger\Model\ResourceModel\Entity\LogCollection;
use Opengento\WebapiLogger\Model\ResourceModel\Entity\LogCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Methods implements OptionSourceInterface
{
    public function __construct(private LogCollectionFactory $logCollectionFactory) {}

    public function toOptionArray(): array
    {
        /** @var LogCollection $logsCollection */
        $logsCollection = $this->logCollectionFactory->create();
        $logsCollection->addFieldToSelect('request_method');
        $logsCollection->distinct(true);

        return $logsCollection->requestMethodToOptionArray();
    }
}
