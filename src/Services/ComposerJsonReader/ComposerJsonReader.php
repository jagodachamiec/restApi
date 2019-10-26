<?php

declare(strict_types=1);

namespace App\Services\ComposerJsonReader;

use InvalidArgumentException;
use RuntimeException;
use function file_exists;
use function file_get_contents;
use function json_decode;
use function sprintf;

final class ComposerJsonReader
{
    /** @var mixed */
    private $data;

    public function __construct(string $composerPath)
    {
        if (! file_exists($composerPath)) {
            throw new InvalidArgumentException(sprintf('File with path %s doesn\'t exist', $composerPath));
        }

        $content = file_get_contents($composerPath);

        if ($content === false) {
            throw new RuntimeException(sprintf('Reading content of the file %s failed.', $composerPath));
        }

        $this->data = json_decode($content, true);
    }

    public function getVersion() : ?string
    {
        return $this->data['version'] ?? null;
    }
}
