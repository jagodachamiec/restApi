<?php

declare(strict_types=1);

namespace App\Tests\Services\TheRake;

use App\Services\Serializer\Exception\DeserializationFailed;
use App\Services\Serializer\Serializer;
use App\Services\TheRake\DTO\Post;
use App\Services\TheRake\DTO\Posts;
use App\Services\TheRake\DTO\Product;
use App\Services\TheRake\DTO\Products;
use App\Services\TheRake\DTO\Result;
use App\Services\TheRake\DTO\SearchResult;
use App\Services\TheRake\Exception\CannotRetrieveTheRakeProductsAndPostsList;
use App\Services\TheRake\TheRakeClient;
use App\Services\TheRake\TheRakeResponseProvider;
use App\Services\TheRake\ValueObject\Post as PostItem;
use App\Services\TheRake\ValueObject\Posts as PostsItem;
use App\Services\TheRake\ValueObject\Product as ProductItem;
use App\Services\TheRake\ValueObject\Products as ProductsItem;
use App\Services\TheRake\ValueObject\ProductsAndPosts;
use App\Services\TheRake\ValueObject\SearchParameters;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class TheRakeResponseProviderTest extends TestCase
{
    private const PRICE     = 2.34;
    private const NAME      = 'name';
    private const TITLE     = 'title';
    private const POST_DATE = '2018-09-09';
    private const THUMBNAIL = 'thumbnail';
    /** @var TheRakeClient|MockObject */
    private $client;
    /** @var Serializer|MockObject */
    private $serializer;
    /** @var TheRakeResponseProvider */
    private $provider;

    /** @var SearchParameters */
    private $searchParameters;

    public function testGetProductsAndPostsList() : void
    {
        $response = $this->createMock(ResponseInterface::class);
        $stream   = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn('{}');
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);
        $this->client->method('search')->with($this->searchParameters)->willReturn($response);

        $products         = new Products([new Product(self::PRICE, self::NAME, self::THUMBNAIL)]);
        $posts            = new Posts([new Post(self::TITLE, self::POST_DATE, self::THUMBNAIL)]);
        $productsAndPosts = new SearchResult(new Result($products, $posts));
        $this->serializer->method('deserialize')->with('{}', SearchResult::class, 'json')->willReturn($productsAndPosts);

        $productsValueObject = new ProductsItem([new ProductItem(self::NAME, self::PRICE, self::THUMBNAIL)]);
        $postsValueObject    = new PostsItem([new PostItem(self::TITLE, self::POST_DATE, self::THUMBNAIL)]);
        $productsAndPosts    = new ProductsAndPosts($productsValueObject, $postsValueObject);
        $this->assertEquals($productsAndPosts, $this->provider->getProductsAndPostsList($this->searchParameters));
    }

    public function testGetProductsAndPostsListThrowInvalidCode() : void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(400);
        $response->method('getReasonPhrase')->willReturn('exception message');
        $this->client->method('search')->with($this->searchParameters)->willReturn($response);
        $this->expectException(CannotRetrieveTheRakeProductsAndPostsList::class);

        $this->provider->getProductsAndPostsList($this->searchParameters);
    }

    public function testGetProductsAndPostsThrowClientException() : void
    {
        $exception = $this->createMock(ClientExceptionInterface::class);
        $this->client->method('search')->willThrowException($exception);
        $this->expectException(CannotRetrieveTheRakeProductsAndPostsList::class);

        $this->provider->getProductsAndPostsList($this->searchParameters);
    }

    public function testGetProductsAndPostsThrowDeserializeException() : void
    {
        $response = $this->createMock(ResponseInterface::class);
        $stream   = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn('{}');
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);
        $this->client->method('search')->with($this->searchParameters)->willReturn($response);

        $exception = new DeserializationFailed('test message', 500);
        $this->serializer->method('deserialize')->with('{}', SearchResult::class, 'json')->willThrowException($exception);

        $this->expectException(CannotRetrieveTheRakeProductsAndPostsList::class);
        $this->provider->getProductsAndPostsList($this->searchParameters);
    }

    public function setUp() : void
    {
        $this->client     = $this->createMock(TheRakeClient::class);
        $this->serializer = $this->createMock(Serializer::class);
        $this->provider   = new TheRakeResponseProvider($this->client, $this->serializer);

        $this->searchParameters = new SearchParameters('test', 'type', [], []);
    }
}
