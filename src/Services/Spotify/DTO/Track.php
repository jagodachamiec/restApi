<?php

declare(strict_types=1);

namespace App\Services\Spotify\DTO;

final class Track
{
    /** @var string */
    private $name;

    /** @var Artist[] */
    private $artists;

    /**
     * @param Artist[] $artists
     */
    public function __construct(string $name, array $artists)
    {
        $this->name    = $name;
        $this->artists = $artists;
    }

    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return Artist[]
     */
    public function getArtists() : array
    {
        return $this->artists;
    }
}
