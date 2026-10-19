<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\ProductAttachments\ViewModel;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\UrlInterface as FrameworkUrl;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Zero\ProductAttachments\Model\Attachments;
use Zero\ProductAttachments\Model\Config;
use Zero\ProductAttachments\Model\FileTypes;

/**
 * Catalog > Product Attachments.
 */
class Admin implements ArgumentInterface
{
    private ?string $sku = null;

    public function __construct(
        private readonly Attachments $attachments,
        private readonly FileTypes $fileTypes,
        private readonly Config $config,
        private readonly RequestInterface $request,
        private readonly UrlInterface $url,
        private readonly FormKey $formKey,
        private readonly StoreManagerInterface $storeManager,
        private readonly \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
    }

    /** The SKU the page is about (?sku=, or the product ?product= opened from its form), '' for all. */
    public function sku(): string
    {
        if ($this->sku === null) {
            $this->sku = trim((string) $this->request->getParam('sku', ''));
            $productId = (int) $this->request->getParam('product');
            if ($this->sku === '' && $productId) {
                try {
                    $this->sku = (string) $this->productRepository->getById($productId)->getSku();
                } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                    $this->sku = '';
                }
            }
        }
        return $this->sku;
    }

    /** @return array<int, array> */
    public function rows(): array
    {
        return $this->attachments->all($this->sku());
    }

    /** @return array<int, string> */
    public function stores(): array
    {
        $stores = [0 => (string) __('All Store Views')];
        foreach ($this->storeManager->getStores() as $store) {
            $stores[(int) $store->getId()] = $store->getWebsite()->getName() . ' / ' . $store->getName();
        }
        return $stores;
    }

    public function fileUrl(array $row): string
    {
        return rtrim($this->storeManager->getStore()->getBaseUrl(FrameworkUrl::URL_TYPE_MEDIA), '/') . '/' . $row['file'];
    }

    public function productUrl(array $row): string
    {
        return $this->url->getUrl('catalog/product/edit', ['id' => $row['product_id']]);
    }

    public function url(string $route, array $params = []): string
    {
        return $this->url->getUrl($route, $params);
    }

    public function formKey(): string
    {
        return $this->formKey->getFormKey();
    }

    public function size(int $bytes): string
    {
        return $this->fileTypes->size($bytes);
    }

    public function accept(): string
    {
        return '.' . implode(',.', $this->fileTypes->extensions());
    }

    public function maxSize(): string
    {
        return $this->fileTypes->size($this->config->maxSize());
    }

    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }
}
