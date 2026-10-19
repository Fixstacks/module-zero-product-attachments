<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\ProductAttachments\Model;

/**
 * What a product may carry: documents, pictures and archives, each checked
 * by its extension and by what the file really is. Nothing a browser would
 * run from the store's domain (HTML, SVG, scripts).
 */
class FileTypes
{
    /** extension => [what the shopper reads, MIME types the content may have] */
    private const TYPES = [
        'pdf' => ['PDF', ['application/pdf']],
        'jpg' => ['JPEG', ['image/jpeg']],
        'jpeg' => ['JPEG', ['image/jpeg']],
        'png' => ['PNG', ['image/png']],
        'webp' => ['WebP', ['image/webp']],
        'zip' => ['ZIP', ['application/zip', 'application/x-zip-compressed']],
        'txt' => ['TXT', ['text/plain']],
        'csv' => ['CSV', ['text/plain', 'text/csv', 'application/csv']],
        'docx' => ['Word', ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip']],
        'xlsx' => ['Excel', ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip']],
        'pptx' => ['PowerPoint', ['application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip']],
        'odt' => ['ODT', ['application/vnd.oasis.opendocument.text', 'application/zip']],
        'ods' => ['ODS', ['application/vnd.oasis.opendocument.spreadsheet', 'application/zip']],
    ];

    /** @return string[] */
    public function extensions(): array
    {
        return array_keys(self::TYPES);
    }

    public function label(string $fileName): string
    {
        return self::TYPES[$this->extension($fileName)][0] ?? strtoupper($this->extension($fileName));
    }

    /**
     * Whether a file may be attached: an allowed extension, and content that is what it says.
     */
    public function accepts(string $fileName, string $mime): bool
    {
        $extension = $this->extension($fileName);
        return isset(self::TYPES[$extension]) && in_array(strtolower($mime), self::TYPES[$extension][1], true);
    }

    /** "pdf", "image", "archive", "document": the icon the storefront shows. */
    public function kind(string $fileName): string
    {
        return match ($this->extension($fileName)) {
            'pdf' => 'pdf',
            'jpg', 'jpeg', 'png', 'webp' => 'image',
            'zip' => 'archive',
            default => 'document',
        };
    }

    public function extension(string $fileName): string
    {
        return strtolower((string) pathinfo($fileName, PATHINFO_EXTENSION));
    }

    /** 2.3 MB, 640 KB, 12 KB */
    public function size(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        return max(1, (int) round($bytes / 1024)) . ' KB';
    }
}
