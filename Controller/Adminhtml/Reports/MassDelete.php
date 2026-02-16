<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Controller\Adminhtml\Reports;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\DB\Select;
use Magento\Ui\Component\MassAction\Filter;
use Opengento\WebapiLogger\Model\RequestBodyStorage;
use Opengento\WebapiLogger\Model\ResourceModel\Log\CollectionFactory;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_WebapiLogger::reports_webapilogs';

    public function __construct(
        Context $context,
        private Filter $filter,
        private CollectionFactory $collectionFactory,
        private RequestBodyStorage $requestBodyStorage
    ) {
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = (int)$collection->getSize();

            $connection = $collection->getConnection();
            $requestBodySelect = clone $collection->getSelect();
            $requestBodySelect->reset(Select::COLUMNS);
            $requestBodySelect->reset(Select::ORDER);
            $requestBodySelect->reset(Select::LIMIT_COUNT);
            $requestBodySelect->reset(Select::LIMIT_OFFSET);
            $requestBodySelect->columns(['request_body']);

            $statement = $connection->query($requestBodySelect);
            while (($row = $statement->fetch()) !== false) {
                $this->requestBodyStorage->delete((string)($row['request_body'] ?? ''));
            }

            $deleteSelect = clone $collection->getSelect();
            $connection->query($deleteSelect->deleteFromSelect('main_table'));

            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $collectionSize)
            );
        } catch (Exception $exception) {
            $this->messageManager->addExceptionMessage($exception);
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/index', ['_current' => true]);
    }
}
