<?php

declare(strict_types=1);

namespace App\Tests\Services\ComposerJsonReader;

use App\Services\ComposerJsonReader\ComposerJsonReader;
use InvalidArgumentException;
use PHPStan\Testing\TestCase;

final class ComposerJsonReaderTest extends TestCase
{
    public function testGetVersionReturnNull() : void
    {
        $path   = __DIR__ . '/../../Fixtures/composerWithoutVersion.json';
        $reader = new ComposerJsonReader($path);

        $this->assertEquals(null, $reader->getVersion());
    }

    public function testGetVersionReturnVersion() : void
    {
        $path   = __DIR__ . '/../../Fixtures/composerWithVersion.json';
        $reader = new ComposerJsonReader($path);

        $this->assertEquals('1.0.0', $reader->getVersion());
    }

    public function testGetVersionThrowException() : void
    {
        $this->expectException(InvalidArgumentException::class);

        $path   = __DIR__ . '/../../composerWithVersion.json';
        $reader = new ComposerJsonReader($path);
    }
}
