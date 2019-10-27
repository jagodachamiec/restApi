<?php

declare(strict_types=1);

namespace App\Services\TheRake;

use App\Services\TheRake\ValueObject\SearchParameters;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class TheRakeClient
{
    private const THE_RAKE_API_ENDPOINT = 'https://next.therake.com/api/ext/jam/search';
    /** @var ClientInterface */
    private $client;
    /** @var RequestFactoryInterface */
    private $requestFactory;

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory
    ) {
        $this->client         = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function search(SearchParameters $searchParameters) : ResponseInterface
    {
        $queryString = $searchParameters->toQueryString();

//        dd($queryString);
        $request = $this->requestFactory->createRequest('GET', self::THE_RAKE_API_ENDPOINT . '?' . $queryString);

        return $this->client->sendRequest($request);
    }
}
