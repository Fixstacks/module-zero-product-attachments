<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\ProductAttachments\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Stores > Configuration > Zero > Product Attachments.
 */
class Config
{
    public function __construct(private readonly ScopeConfigInterface $scopeConfig)
    {
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag('zero_attachments/general/enabled', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function title(?int $storeId = null): string
    {
        $title = trim((string) $this->scopeConfig->getValue('zero_attachments/general/title', ScopeInterface::SCOPE_STORE, $storeId));
        return $title !== '' ? $title : (string) __('Downloads');
    }

    /** Bytes. */
    public function maxSize(): int
    {
        return max(1, (int) $this->scopeConfig->getValue('zero_attachments/general/max_size')) * 1048576;
    }
}
