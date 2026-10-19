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
use Magento\Framework\Exception\LocalizedException;
use Zero\ProductAttachments\Model\Attachments;

class Upload extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Zero_ProductAttachments::attachments';

    public function __construct(Context $context, private readonly Attachments $attachments)
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $sku = trim((string) $this->getRequest()->getParam('sku'));
        try {
            $productId = $this->attachments->productId($sku);
            if ($productId === null) {
                throw new LocalizedException(__('No product has the SKU "%1".', $sku));
            }
            $file = (array) ($this->getRequest()->getFiles('file') ?? []);
            if (empty($file['tmp_name']) || (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                throw new LocalizedException(__('Choose a file (and check it isn\'t larger than the server allows).'));
            }
            $this->attachments->add(
                $productId,
                (int) $this->getRequest()->getParam('store'),
                (string) $this->getRequest()->getParam('title'),
                (int) $this->getRequest()->getParam('sort_order'),
                (string) $file['tmp_name'],
                (string) $file['name']
            );
            $this->messageManager->addSuccessMessage(__('The file is attached to %1.', $sku));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/index', $sku !== '' ? ['sku' => $sku] : []);
    }
}
