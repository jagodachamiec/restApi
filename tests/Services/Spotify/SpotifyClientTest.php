<?php

declare(strict_types=1);

namespace App\Tests\Services\Spotify;

use App\Services\Spotify\Exception\CannotRetrieveToken;
use App\Services\Spotify\SpotifyClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class SpotifyClientTest extends TestCase
{
    private const SPOTIFY_API_ENDPOINT   = 'https://api.spotify.com/v1/playlists/';
    private const SPOTIFY_TOKEN_ENDPOINT = 'https://accounts.spotify.com/api/token';
    private const CLIENT_ID              = 'client_id';
    private const CLIENT_SECRET          = 'client_secret';
    private const PROFILE_ID             = 'profileId';
    /** @var MockObject|ClientInterface */
    private $clientInterface;
    /** @var MockObject|RequestFactoryInterface */
    private $requestFactory;
    /** @var MockObject|StreamFactoryInterface */
    private $streamFactory;
    /** @var SpotifyClient */
    private $spotifyClient;
    /** @var MockObject|StreamInterface */
    private $stream;
    /** @var MockObject|RequestInterface */
    private $request;
    /** @var MockObject|RequestInterface */
    private $requestWithBody;
    /** @var MockObject|RequestInterface */
    private $requestWithBodyAndHeader;
    /** @var MockObject|RequestInterface */
    private $requestWithBodyAndTwoHeaders;
    /** @var MockObject|ResponseInterface */
    private $tokenResponse;
    /** @var MockObject|RequestInterface */
    private $requestWithBearerToken;
    /** @var MockObject|ResponseInterface */
    private $response;

    public function testGetProfileList() : void
    {
        $this->stream->method('getContents')->willReturn('{"access_token": "test_token"}');
        $this->tokenResponse->method('getStatusCode')->willReturn(200);
        $this->tokenResponse->method('getBody')->willReturn($this->stream);

        $this->clientInterface->method('sendRequest')->with($this->requestWithBodyAndTwoHeaders)->willReturn($this->tokenResponse);
        $this->requestFactory->expects($this->exactly(2))->method('createRequest')->withConsecutive(
            ['POST', self::SPOTIFY_TOKEN_ENDPOINT],
            ['GET', self::SPOTIFY_API_ENDPOINT . self::PROFILE_ID]
        )->willReturnOnConsecutiveCalls($this->request, $this->request);

        $this->request->method('withHeader')->with('Authorization', 'Bearer test_token')->willReturn($this->requestWithBearerToken);
        $this->clientInterface->expects($this->exactly(2))->method('sendRequest')->withConsecutive(
            [$this->requestWithBodyAndTwoHeaders],
            [$this->requestWithBearerToken]
        )->willReturnOnConsecutiveCalls($this->tokenResponse, $this->response);

        $this->spotifyClient->getProfileList(self::PROFILE_ID);
    }

    public function testGetProfileListThrowCannotRetrieveTokenException() : void
    {
        $this->requestFactory->method('createRequest')->with('POST', self::SPOTIFY_TOKEN_ENDPOINT)->willReturn($this->request);

        $this->tokenResponse->method('getStatusCode')->willReturn(400);
        $this->tokenResponse->method('getReasonPhrase')->willReturn('exception message');
        $this->clientInterface->method('sendRequest')->with($this->requestWithBodyAndTwoHeaders)->willReturn($this->tokenResponse);

        $this->expectException(CannotRetrieveToken::class);
        $this->spotifyClient->getProfileList(self::PROFILE_ID);
    }

    public function testGetProfileListThrowCannotDeserializeReponseWithTokenException() : void
    {
        $this->requestFactory->method('createRequest')->with('POST', self::SPOTIFY_TOKEN_ENDPOINT)->willReturn($this->request);

        $this->stream->method('getContents')->willReturn('"date":');
        $this->tokenResponse->method('getStatusCode')->willReturn(200);
        $this->tokenResponse->method('getBody')->willReturn($this->stream);

        $this->clientInterface->method('sendRequest')->with($this->requestWithBodyAndTwoHeaders)->willReturn($this->tokenResponse);

        $this->expectException(CannotRetrieveToken::class);
        $this->spotifyClient->getProfileList(self::PROFILE_ID);
    }

    public function setUp() : void
    {
        $this->clientInterface = $this->createMock(ClientInterface::class);
        $this->requestFactory  = $this->createMock(RequestFactoryInterface::class);
        $this->streamFactory   = $this->createMock(StreamFactoryInterface::class);
        $this->spotifyClient   = new SpotifyClient($this->clientInterface, $this->requestFactory, $this->streamFactory, self::CLIENT_ID, self::CLIENT_SECRET);

        $this->stream                       = $this->createMock(StreamInterface::class);
        $this->request                      = $this->createMock(RequestInterface::class);
        $this->requestWithBody              = $this->createMock(RequestInterface::class);
        $this->requestWithBodyAndHeader     = $this->createMock(RequestInterface::class);
        $this->requestWithBodyAndTwoHeaders = $this->createMock(RequestInterface::class);
        $this->requestWithBearerToken       = $this->createMock(RequestInterface::class);
        $this->tokenResponse                = $this->createMock(ResponseInterface::class);
        $this->response                     = $this->createMock(ResponseInterface::class);

        $this->streamFactory->method('createStream')->with('grant_type=client_credentials')->willReturn($this->stream);
        $this->request->method('withBody')->with($this->stream)->willReturn($this->requestWithBody);
        $this->requestWithBody->method('withHeader')->with('Authorization', 'Basic Y2xpZW50X2lkOmNsaWVudF9zZWNyZXQ=')->willReturn($this->requestWithBodyAndHeader);
        $this->requestWithBodyAndHeader->method('withHeader')->with('Content-Type', 'application/x-www-form-urlencoded')->willReturn($this->requestWithBodyAndTwoHeaders);
    }
}
