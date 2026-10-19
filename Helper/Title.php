<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\ProductAttachments\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Zero\ProductAttachments\Model\Config;

/**
 * The details section's heading (the theme reads it as the block's "title"
 * argument): the store's Section Title, "Downloads" by default.
 */
class Title extends AbstractHelper
{
    public function __construct(Context $context, private readonly Config $config)
    {
        parent::__construct($context);
    }

    public function get(): string
    {
        return $this->config->title();
    }
}
