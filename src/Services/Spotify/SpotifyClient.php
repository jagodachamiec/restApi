<?php

declare(strict_types=1);

namespace App\Services\Spotify;

use App\Services\Spotify\Exception\CannotRetrieveToken;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use function base64_encode;
use function is_array;
use function json_decode;
use function sprintf;

class SpotifyClient
{
    private const SPOTIFY_API_ENDPOINT   = 'https://api.spotify.com/v1/playlists/';
    private const SPOTIFY_TOKEN_ENDPOINT = 'https://accounts.spotify.com/api/token';
    /** @var ClientInterface */
    private $client;
    /** @var RequestFactoryInterface */
    private $requestFactory;
    /** @var StreamFactoryInterface */
    private $streamFactory;
    /** @var string */
    private $clientId;
    /** @var string */
    private $clientSecret;

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        string $clientId,
        string $clientSecret
    ) {
        $this->client         = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory  = $streamFactory;
        $this->clientId       = $clientId;
        $this->clientSecret   = $clientSecret;
    }

    /**
     * @throws CannotRetrieveToken
     * @throws ClientExceptionInterface
     */
    public function getProfileList(string $profileId) : ResponseInterface
    {
        $token = $this->getToken();

        $request = $this->requestFactory->createRequest('GET', self::SPOTIFY_API_ENDPOINT . $profileId);
        $request = $this->withBearerAuthentication($request, $token);

        return $this->client->sendRequest($request);
    }

    private function createPostRequest(string $requestBody, string $uri) : RequestInterface
    {
        $request = $this->requestFactory->createRequest('POST', $uri);
        $request = $request->withBody($this->streamFactory->createStream($requestBody));

        return $request;
    }

    private function withBearerAuthentication(RequestInterface $request, string $token) : RequestInterface
    {
        return $request->withHeader('Authorization', sprintf('Bearer %s', $token));
    }

    private function getTokenResponseBasedOnClientCredentials() : ResponseInterface
    {
        $request = $this->createPostRequest(
            'grant_type=client_credentials',
            self::SPOTIFY_TOKEN_ENDPOINT
        );
        $request = $request->withHeader(
            'Authorization',
            sprintf('Basic %s', base64_encode($this->clientId . ':' . $this->clientSecret))
        );
        $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');

        return $this->client->sendRequest($request);
    }

    /**
     * @throws CannotRetrieveToken
     */
    private function getToken() : string
    {
        $tokenResponse = $this->getTokenResponseBasedOnClientCredentials();

        if ($tokenResponse->getStatusCode() !== 200) {
            throw CannotRetrieveToken::invalidStatusCode(
                $tokenResponse->getStatusCode(),
                $tokenResponse->getReasonPhrase()
            );
        }
        $contents = json_decode($tokenResponse->getBody()->getContents(), true);

        if (! is_array($contents)) {
            throw CannotRetrieveToken::deserializationFailed();
        }

        return $contents['access_token'];
    }
}
