<?php

declare(strict_types=1);

namespace App\Services\TheRake\ValueObject;

use function http_build_query;
use function implode;

final class SearchParameters
{
    /** @var string */
    private $search;
    /** @var string */
    private $type;

    /** @var string[] */
    private $includeProduct;

    /** @var string[] */
    private $includePost;

    /** @var int|null */
    private $size;

    /** @var int|null */
    private $productPage;

    /** @var int|null */
    private $postPage;

    /**
     * @param string[] $includeProduct
     * @param string[] $includePost
     */
    public function __construct(string $search, string $type, array $includeProduct, array $includePost, ?int $size = null, ?int $productPage = null, ?int $postPage = null)
    {
        $this->search         = $search;
        $this->type           = $type;
        $this->includeProduct = $includeProduct;
        $this->includePost    = $includePost;
        $this->size           = $size;
        $this->productPage    = $productPage;
        $this->postPage       = $postPage;
    }

    public function getSearch() : string
    {
        return $this->search;
    }

    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @return string[]
     */
    public function getIncludeProduct() : array
    {
        return $this->includeProduct;
    }

    /**
     * @return string[]
     */
    public function getIncludePost() : array
    {
        return $this->includePost;
    }

    public function getSize() : ?int
    {
        return $this->size;
    }

    public function getProductPage() : ?int
    {
        return $this->productPage;
    }

    public function getPostPage() : ?int
    {
        return $this->postPage;
    }

    public function toQueryString() : string
    {
        $queryArray = [
            'search' => $this->search,
            'type' => $this->type,
            'include_product' => implode(',', $this->includeProduct),
            'include_post' => implode(',', $this->includePost),
            'size' => $this->size,
            'productPage' => $this->productPage,
            'productPost' => $this->postPage,
        ];

        return http_build_query($queryArray);
    }
}
