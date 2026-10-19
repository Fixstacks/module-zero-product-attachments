<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\ProductAttachments\Block\Adminhtml;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * "Attachments" on a saved product's form: its files in Catalog > Product Attachments.
 */
class AttachmentsButton implements ButtonProviderInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly UrlInterface $url,
        private readonly AuthorizationInterface $authorization
    ) {
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $id = (int) $this->request->getParam('id');
        if (!$id || !$this->authorization->isAllowed('Zero_ProductAttachments::attachments')) {
            return [];
        }
        return [
            'label' => __('Attachments'),
            'class' => 'zero-attachments',
            'on_click' => sprintf("location.href = '%s';", $this->url->getUrl('zero_attachments/attachment/index', ['product' => $id])),
            'sort_order' => 27,
        ];
    }
}
