<?php

declare(strict_types=1);

namespace App\Tests\Services\TheRake;

use App\Services\TheRake\TheRakeClient;
use App\Services\TheRake\ValueObject\SearchParameters;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TheRakeClientTest extends TestCase
{
    /** @var MockObject|ClientInterface */
    private $client;
    /** @var MockObject|RequestFactoryInterface */
    private $requestFactory;
    /** @var TheRakeClient */
    private $theRakeClient;

    public function testSearch() : void
    {
        $searchParameter = new SearchParameters('test', 'all', ['price'], ['title']);

        $request  = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->requestFactory->method('createRequest')->with(
            'GET',
            'https://next.therake.com/api/ext/jam/search?search=test&type=all&include_product=price&include_post=title'
        )->willReturn($request);
        $this->client->method('sendRequest')->with($request)->willReturn($response);

        $this->assertEquals($response, $this->theRakeClient->search($searchParameter));
    }

    public function setUp() : void
    {
        $this->client         = $this->createMock(ClientInterface::class);
        $this->requestFactory = $this->createMock(RequestFactoryInterface::class);
        $this->theRakeClient  = new TheRakeClient($this->client, $this->requestFactory);
    }
}
