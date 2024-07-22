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
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Opengento\WebapiLogger\Model\Clean;

class Delete extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_WebapiLogger::reports_webapilogs';

    public function __construct(
        Context $context,
        private Clean $clean
    ) {
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        try {
            $this->clean->cleanAll();
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e);
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/index', ['_current' => true]);
    }
}
