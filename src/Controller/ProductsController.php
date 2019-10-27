<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\TheRake\Exception\CannotRetrieveTheRakeProductsAndPostsList;
use App\Services\TheRake\TheRakeResponseProvider;
use App\Services\TheRake\ValueObject\SearchParameters;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

final class ProductsController extends AbstractFOSRestController
{
    /** @var TheRakeResponseProvider */
    private $theRakeProvider;

    public function __construct(TheRakeResponseProvider $theRakeProvider)
    {
        $this->theRakeProvider = $theRakeProvider;
    }

    /**
     * @param string[] $includeProduct
     * @param string[] $includePost
     *
     * @throws CannotRetrieveTheRakeProductsAndPostsList
     *
     * @Rest\Get(path="/products")
     * @Rest\QueryParam(name="search", requirements="[a-zA-Z]+", description="Search term", strict=true)
     * @Rest\QueryParam(name="type", requirements="[a-zA-Z]+", default="all", description="Type of searching objects", strict=true)
     * @Rest\QueryParam(map=true, name="includeProduct", default={"name", "thumbnail", "price", "brand", "url_key", "option_text_product_tag", "sku", "tax_class_id"}, requirements="[a-zA-Z]+", description="Include product's field", strict=true)
     * @Rest\QueryParam(map=true, name="includePost", default={"post_id", "post_title", "post_date", "permalink"}, requirements="[a-zA-Z]+", description="Include post's field", strict=true)
     * @Rest\QueryParam(name="size", requirements="\d+", default=0, description="Number of items per page", strict=true)
     * @Rest\QueryParam(name="productPage", requirements="\d+", default=0, description="Number of elements in result", strict=true)
     * @Rest\QueryParam(name="productPage", requirements="\d+", default=0, description="Product search result page number", strict=true)
     * @Rest\QueryParam(name="postPage", requirements="\d+", default=0, description="Post search result page number", strict=true)
     */
    public function getProducts(string $search, string $type, array $includeProduct, array $includePost, int $size, int $productPage, int $postPage) : Response
    {
        $size        = $size === 0 ? null : $size;
        $productPage = $productPage === 0 ? null : $productPage;
        $postPage    = $postPage === 0 ? null : $postPage;
        $searchParam = new SearchParameters($search, $type, $includeProduct, $includePost, $size, $productPage, $postPage);

        $result = $this->theRakeProvider->getProductsAndPostsList($searchParam);

        return $this->handleView($this->view($result, Response::HTTP_OK));
    }
}
