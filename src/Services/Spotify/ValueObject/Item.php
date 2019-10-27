<?php

declare(strict_types=1);

namespace App\Services\Spotify\ValueObject;

final class Item
{
    /** @var string */
    private $title;

    /** @var Author[] */
    private $authors;

    /**
     * @param Author[] $authors
     */
    public function __construct(string $title, array $authors)
    {
        $this->title   = $title;
        $this->authors = $authors;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @return Author[]
     */
    public function getAuthors() : array
    {
        return $this->authors;
    }
}
