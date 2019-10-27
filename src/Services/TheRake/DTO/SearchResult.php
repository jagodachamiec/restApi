<?php

declare(strict_types=1);

namespace App\Services\TheRake\DTO;

use App\Services\TheRake\ValueObject\Post as PostItem;
use App\Services\TheRake\ValueObject\Posts as PostsItem;
use App\Services\TheRake\ValueObject\Product as ProductItem;
use App\Services\TheRake\ValueObject\Products as ProductsItem;
use App\Services\TheRake\ValueObject\ProductsAndPosts;

final class SearchResult
{
    /** @var Result */
    private $result;

    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    public function getResult() : Result
    {
        return $this->result;
    }

    public function toProductsAndPosts() : ProductsAndPosts
    {
        $posts = [];

        if ($this->result->getPosts() !== null) {
            foreach ($this->result->getPosts()->getData() as $post) {
                $posts[] = new PostItem($post->getPostTitle(), $post->getPostDate(), $post->getThumbnail());
            }
        }

        $products = [];

        if ($this->result->getProducts() !== null) {
            foreach ($this->result->getProducts()->getData() as $product) {
                $products[] = new ProductItem($product->getName(), $product->getPrice(), $product->getThumbnail());
            }
        }

        return new ProductsAndPosts(new ProductsItem($products), new PostsItem($posts));
    }
}
