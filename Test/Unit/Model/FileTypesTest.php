<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\ProductAttachments\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Zero\ProductAttachments\Model\FileTypes;

class FileTypesTest extends TestCase
{
    private FileTypes $types;

    protected function setUp(): void
    {
        $this->types = new FileTypes();
    }

    public function testAcceptsFilesThatAreWhatTheySay(): void
    {
        self::assertTrue($this->types->accepts('Manual.PDF', 'application/pdf'));
        self::assertTrue($this->types->accepts('sheet.xlsx', 'application/zip'));
        self::assertTrue($this->types->accepts('photo.jpg', 'image/jpeg'));
    }

    public function testRefusesWhatABrowserWouldRunOrALie(): void
    {
        self::assertFalse($this->types->accepts('logo.svg', 'image/svg+xml'));
        self::assertFalse($this->types->accepts('page.html', 'text/html'));
        self::assertFalse($this->types->accepts('manual.pdf', 'text/html'));
        self::assertFalse($this->types->accepts('shell.php', 'text/x-php'));
        self::assertFalse($this->types->accepts('photo.jpg', 'image/svg+xml'));
    }

    public function testLabelsKindsAndSizes(): void
    {
        self::assertSame('PDF', $this->types->label('a.pdf'));
        self::assertSame('Word', $this->types->label('a.docx'));
        self::assertSame('image', $this->types->kind('a.webp'));
        self::assertSame('archive', $this->types->kind('a.zip'));
        self::assertSame('2.3 MB', $this->types->size(2411725));
        self::assertSame('1 KB', $this->types->size(10));
    }
}
