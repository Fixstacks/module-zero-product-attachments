<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\ProductAttachments\ViewModel;

use Magento\Catalog\Model\Product;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Zero\ProductAttachments\Model\Attachments;
use Zero\ProductAttachments\Model\Config;
use Zero\ProductAttachments\Model\FileTypes;

/**
 * The product page's files, for this store view.
 */
class Downloads implements ArgumentInterface
{
    public function __construct(
        private readonly Attachments $attachments,
        private readonly FileTypes $fileTypes,
        private readonly Config $config,
        private readonly StoreManagerInterface $storeManager
    ) {
    }

    public function title(): string
    {
        return $this->config->title();
    }

    /**
     * @return array<int, array{title: string, url: string, type: string, kind: string, size: string}>
     */
    public function files(?Product $product): array
    {
        if (!$product || !$product->getId() || !$this->config->isEnabled()) {
            return [];
        }
        $store = $this->storeManager->getStore();
        $media = rtrim($store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA), '/') . '/';
        $files = [];
        foreach ($this->attachments->forProduct((int) $product->getId(), (int) $store->getId()) as $row) {
            $files[] = [
                'title' => (string) $row['title'],
                'url' => $media . $row['file'],
                'type' => $this->fileTypes->label((string) $row['file_name']),
                'kind' => $this->fileTypes->kind((string) $row['file_name']),
                'size' => $this->fileTypes->size((int) $row['size']),
            ];
        }
        return $files;
    }
}
