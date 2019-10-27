<?php

declare(strict_types=1);

namespace App\Services\Spotify\DTO;

final class Item
{
    /** @var Track */
    private $track;

    public function __construct(Track $track)
    {
        $this->track = $track;
    }

    public function getTrack() : Track
    {
        return $this->track;
    }
}
