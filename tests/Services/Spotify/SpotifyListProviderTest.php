<?php

declare(strict_types=1);

namespace App\Tests\Services\Spotify;

use App\Services\Serializer\Exception\DeserializationFailed;
use App\Services\Serializer\SymfonySerializer;
use App\Services\Spotify\DTO\Artist;
use App\Services\Spotify\DTO\Item;
use App\Services\Spotify\DTO\ListOfTracks;
use App\Services\Spotify\DTO\Track;
use App\Services\Spotify\DTO\Tracks;
use App\Services\Spotify\Exception\CannotRetrieveSpotifyList;
use App\Services\Spotify\Exception\CannotRetrieveToken;
use App\Services\Spotify\SpotifyClient;
use App\Services\Spotify\SpotifyListProvider;
use App\Services\Spotify\ValueObject\Author;
use App\Services\Spotify\ValueObject\Item as ItemOfList;
use App\Services\Spotify\ValueObject\SpotifyList;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class SpotifyListProviderTest extends TestCase
{
    private const PROFILE_ID ='testprofile';
    private const TEST_BODY  = 'test body';
    private const TEST_TITLE = 'test title';
    private const TEST_NAME  = 'test name';
    /** @var SpotifyClient|MockObject */
    private $client;
    /** @var SymfonySerializer|MockObject */
    private $serializer;
    /** @var SpotifyListProvider */
    private $provider;

    public function testGetSpotifyListEmptyListOfTracks() : void
    {
        $response     = $this->createMock(ResponseInterface::class);
        $stream       = $this->createMock(StreamInterface::class);
        $listOfTracks = new ListOfTracks(new Tracks([]));
        $stream->method('getContents')->willReturn(self::TEST_BODY);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $this->serializer->method('deserialize')->with(self::TEST_BODY, ListOfTracks::class, 'json')
            ->willReturn($listOfTracks);
        $this->client->method('getProfileList')->with(self::PROFILE_ID)->willReturn($response);

        $this->assertEquals(new SpotifyList(0, []), $this->provider->getSpotifyList(self::PROFILE_ID));
    }

    public function testGetSpotifyListListOfTracks() : void
    {
        $response     = $this->createMock(ResponseInterface::class);
        $stream       = $this->createMock(StreamInterface::class);
        $listOfTracks = new ListOfTracks(new Tracks([new Item(new Track(self::TEST_TITLE, [new Artist(self::TEST_NAME)]))]));
        $stream->method('getContents')->willReturn(self::TEST_BODY);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $this->serializer->method('deserialize')->with(self::TEST_BODY, ListOfTracks::class, 'json')
            ->willReturn($listOfTracks);
        $this->client->method('getProfileList')->with(self::PROFILE_ID)->willReturn($response);

        $spotifyList = new SpotifyList(
            1,
            [new ItemOfList(self::TEST_TITLE, [new Author(self::TEST_NAME)])]
        );
        $this->assertEquals($spotifyList, $this->provider->getSpotifyList(self::PROFILE_ID));
    }

    public function testGetSpotifyListThrowInvalidCode() : void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(400);
        $response->method('getReasonPhrase')->willReturn('exception message');
        $this->client->method('getProfileList')->with(self::PROFILE_ID)->willReturn($response);
        $this->expectException(CannotRetrieveSpotifyList::class);
        $this->provider->getSpotifyList(self::PROFILE_ID);
    }

    public function testGetSpotifyListThrowCannotRetrieveTokenException() : void
    {
        $exception = CannotRetrieveToken::deserializationFailed();
        $this->client->method('getProfileList')->with(self::PROFILE_ID)->willThrowException($exception);
        $this->expectException(CannotRetrieveSpotifyList::class);
        $this->provider->getSpotifyList(self::PROFILE_ID);
    }

    public function testGetSpotifyListThrowClientException() : void
    {
        $exception = $this->createMock(ClientExceptionInterface::class);
        $this->client->method('getProfileList')->with(self::PROFILE_ID)->willThrowException($exception);
        $this->expectException(CannotRetrieveSpotifyList::class);
        $this->provider->getSpotifyList(self::PROFILE_ID);
    }

    public function testGetSpotifyListThrowDeserializationException() : void
    {
        $response  = $this->createMock(ResponseInterface::class);
        $stream    = $this->createMock(StreamInterface::class);
        $exception = new DeserializationFailed('test message', 123);
        $stream->method('getContents')->willReturn(self::TEST_BODY);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $this->serializer->method('deserialize')->with(self::TEST_BODY, ListOfTracks::class, 'json')
            ->willThrowException($exception);
        $this->client->method('getProfileList')->with(self::PROFILE_ID)->willReturn($response);

        $this->expectException(CannotRetrieveSpotifyList::class);
        $this->provider->getSpotifyList(self::PROFILE_ID);
    }

    public function setUp() : void
    {
        $this->client     = $this->createMock(SpotifyClient::class);
        $this->serializer = $this->createMock(SymfonySerializer::class);
        $this->provider   = new SpotifyListProvider($this->client, $this->serializer);
    }
}
