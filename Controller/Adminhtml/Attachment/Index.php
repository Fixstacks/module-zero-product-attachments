<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\ProductAttachments\Controller\Adminhtml\Attachment;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Catalog > Product Attachments: add a file to a product (by SKU), and every
 * product's files, or one product's (?sku=, or ?product= from its form).
 */
class Index extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Zero_ProductAttachments::attachments';

    public function execute()
    {
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->setActiveMenu('Zero_ProductAttachments::attachments');
        $page->getConfig()->getTitle()->prepend(__('Product Attachments'));
        return $page;
    }
}
