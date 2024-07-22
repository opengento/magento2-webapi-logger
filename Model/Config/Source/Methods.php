<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Opengento\WebapiLogger\Model\ResourceModel\Log\CollectionFactory;
use Opengento\WebapiLogger\Model\ResourceModel\Log\Collection;

class Methods implements OptionSourceInterface
{
    public function __construct(private CollectionFactory $collectionFactory) {}

    public function toOptionArray(): array
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('request_method');
        $collection->distinct(true);

        return $collection->requestMethodToOptionArray();
    }
}
