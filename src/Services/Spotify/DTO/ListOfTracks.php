<?php

declare(strict_types=1);

namespace App\Services\Spotify\DTO;

use App\Services\Spotify\ValueObject\Author;
use App\Services\Spotify\ValueObject\Item as SpotifyListItem;
use App\Services\Spotify\ValueObject\SpotifyList;
use function count;

final class ListOfTracks
{
    /** @var Tracks */
    private $tracks;

    public function __construct(Tracks $tracks)
    {
        $this->tracks = $tracks;
    }

    public function getTracks() : Tracks
    {
        return $this->tracks;
    }

    public function toSpotifyList() : SpotifyList
    {
        $tracksCount = count($this->tracks->getItems());
        $items       = [];
        foreach ($this->tracks->getItems() as $item) {
            $authors = [];
            foreach ($item->getTrack()->getArtists() as $artist) {
                $authors[] = new Author($artist->getName());
            }
            $items[] = new SpotifyListItem($item->getTrack()->getName(), $authors);
        }

        return new SpotifyList($tracksCount, $items);
    }
}
