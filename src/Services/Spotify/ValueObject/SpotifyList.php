<?php

declare(strict_types=1);

namespace App\Services\Spotify\ValueObject;

final class SpotifyList
{
    /** @var int */
    private $tracks;

    /** @var Item[] */
    private $items;

    /**
     * @param Item[] $items
     */
    public function __construct(int $tracks, array $items)
    {
        $this->tracks = $tracks;
        $this->items  = $items;
    }

    public function getTracks() : int
    {
        return $this->tracks;
    }

    /**
     * @return Item[]
     */
    public function getItems() : array
    {
        return $this->items;
    }
}
