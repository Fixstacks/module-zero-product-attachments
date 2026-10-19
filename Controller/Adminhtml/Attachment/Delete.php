<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\ProductAttachments\Controller\Adminhtml\Attachment;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Zero\ProductAttachments\Model\Attachments;

/**
 * Removes an attachment and its file.
 */
class Delete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Zero_ProductAttachments::attachments';

    public function __construct(Context $context, private readonly Attachments $attachments)
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $this->attachments->delete((int) $this->getRequest()->getParam('id'));
        $this->messageManager->addSuccessMessage(__('The file is removed.'));
        $sku = trim((string) $this->getRequest()->getParam('sku'));
        return $this->resultRedirectFactory->create()->setPath('*/*/index', $sku !== '' ? ['sku' => $sku] : []);
    }
}
