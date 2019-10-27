<?php

declare(strict_types=1);

namespace App\Services\Spotify;

use App\Services\Serializer\Exception\DeserializationFailed;
use App\Services\Serializer\Serializer;
use App\Services\Spotify\DTO\ListOfTracks;
use App\Services\Spotify\Exception\CannotRetrieveSpotifyList;
use App\Services\Spotify\Exception\CannotRetrieveToken;
use App\Services\Spotify\ValueObject\SpotifyList;
use Psr\Http\Client\ClientExceptionInterface;

final class SpotifyListProvider
{
    /** @var SpotifyClient */
    private $spotifyClient;
    /** @var Serializer */
    private $serializer;

    public function __construct(SpotifyClient $spotifyClient, Serializer $serializer)
    {
        $this->spotifyClient = $spotifyClient;
        $this->serializer    = $serializer;
    }

    /**
     * @throws CannotRetrieveSpotifyList
     */
    public function getSpotifyList(string $profileId) : SpotifyList
    {
        try {
            $response = $this->spotifyClient->getProfileList($profileId);
        } catch (CannotRetrieveToken $exception) {
            throw CannotRetrieveSpotifyList::cannotRetrieveToken($exception);
        } catch (ClientExceptionInterface $exception) {
            throw CannotRetrieveSpotifyList::clientExceptionThrow($exception);
        }

        if ($response->getStatusCode() !== 200) {
            throw CannotRetrieveSpotifyList::invalidStatusCode($response->getStatusCode(), $response->getReasonPhrase());
        }

        $list = $response->getBody()->getContents();
        try {
            /**
             * @var ListOfTracks $listDTO
             */
            $listDTO = $this->serializer->deserialize($list, ListOfTracks::class, 'json');
        } catch (DeserializationFailed $exception) {
            throw CannotRetrieveSpotifyList::deserializationFailed($exception);
        }

        return $listDTO->toSpotifyList();
    }
}
