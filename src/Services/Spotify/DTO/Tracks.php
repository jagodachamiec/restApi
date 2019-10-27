<?php

declare(strict_types=1);

namespace App\Services\Spotify\DTO;

final class Tracks
{
    /** @var Item[] */
    private $items;

    /**
     * @param Item[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return Item[]
     */
    public function getItems() : array
    {
        return $this->items;
    }
}
