<?php

declare(strict_types=1);

namespace App\Tests\Services\Spotify\DTO;

use App\Services\Spotify\DTO\Artist;
use App\Services\Spotify\DTO\Item;
use App\Services\Spotify\DTO\ListOfTracks;
use App\Services\Spotify\DTO\Track;
use App\Services\Spotify\DTO\Tracks;
use PHPUnit\Framework\TestCase;

class ListOfTracksTest extends TestCase
{
    public function testToSpotifyListEmptyTracks() : void
    {
        $tracks       = new Tracks([]);
        $listOfTracks = new ListOfTracks($tracks);
        $spotifyList  = $listOfTracks->toSpotifyList();

        $this->assertEquals(0, $spotifyList->getTracks());
        $this->assertEquals([], $spotifyList->getItems());
    }

    public function testToSpotifyListWithTracks() : void
    {
        $track        = new Track('name', [new Artist('name'), new Artist('second name')]);
        $item         = new Item($track);
        $tracks       = new Tracks([$item]);
        $listOfTracks = new ListOfTracks($tracks);
        $spotifyList  = $listOfTracks->toSpotifyList();

        $this->assertEquals(1, $spotifyList->getTracks());
        $this->assertEquals($track->getName(), $spotifyList->getItems()[0]->getTitle());
        $this->assertEquals($track->getArtists()[0]->getName(), $spotifyList->getItems()[0]->getAuthors()[0]->getName());
        $this->assertEquals($track->getArtists()[1]->getName(), $spotifyList->getItems()[0]->getAuthors()[1]->getName());
    }
}
