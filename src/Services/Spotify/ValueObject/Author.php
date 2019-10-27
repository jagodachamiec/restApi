<?php

declare(strict_types=1);

namespace App\Services\Spotify\ValueObject;

final class Author
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }
}
