<?php

declare(strict_types=1);

namespace App\Services\TheRake;

use App\Services\Serializer\Exception\DeserializationFailed;
use App\Services\Serializer\Serializer;
use App\Services\TheRake\DTO\SearchResult;
use App\Services\TheRake\Exception\CannotRetrieveTheRakeProductsAndPostsList;
use App\Services\TheRake\ValueObject\ProductsAndPosts;
use App\Services\TheRake\ValueObject\SearchParameters;
use Psr\Http\Client\ClientExceptionInterface;

final class TheRakeResponseProvider
{
    /** @var TheRakeClient */
    private $theRakeClient;
    /** @var Serializer */
    private $serializer;

    public function __construct(TheRakeClient $theRakeClient, Serializer $serializer)
    {
        $this->theRakeClient = $theRakeClient;
        $this->serializer    = $serializer;
    }

    /**
     * @throws CannotRetrieveTheRakeProductsAndPostsList
     */
    public function getProductsAndPostsList(SearchParameters $searchParameters) : ProductsAndPosts
    {
        try {
            $response = $this->theRakeClient->search($searchParameters);
        } catch (ClientExceptionInterface $exception) {
            throw CannotRetrieveTheRakeProductsAndPostsList::clientExceptionThrow($exception);
        }

        if ($response->getStatusCode() !== 200) {
            throw CannotRetrieveTheRakeProductsAndPostsList::invalidStatusCode($response->getStatusCode(), $response->getReasonPhrase());
        }

        $list = $response->getBody()->getContents();
        try {
            /**
             * @var SearchResult $searchResult
             */
            $searchResult = $this->serializer->deserialize($list, SearchResult::class, 'json');
        } catch (DeserializationFailed $exception) {
            throw CannotRetrieveTheRakeProductsAndPostsList::deserializationFailed($exception);
        }

        return $searchResult->toProductsAndPosts();
    }
}
