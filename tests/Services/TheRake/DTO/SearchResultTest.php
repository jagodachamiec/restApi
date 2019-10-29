<?php

declare(strict_types=1);

namespace App\Tests\Services\TheRake\DTO;

use App\Services\TheRake\DTO\Post as PostDTO;
use App\Services\TheRake\DTO\Posts as PostsDTO;
use App\Services\TheRake\DTO\Product as ProductDTO;
use App\Services\TheRake\DTO\Products as ProductsDTO;
use App\Services\TheRake\DTO\Result;
use App\Services\TheRake\DTO\SearchResult;
use App\Services\TheRake\ValueObject\Post;
use App\Services\TheRake\ValueObject\Posts;
use App\Services\TheRake\ValueObject\Product;
use App\Services\TheRake\ValueObject\Products;
use PHPUnit\Framework\TestCase;

class SearchResultTest extends TestCase
{
    public function testToProductsAndPostsEmpty() : void
    {
        $result           = new Result();
        $searchResult     = new SearchResult($result);
        $productsAndPosts = $searchResult->toProductsAndPosts();

        $this->assertEquals(new Products([]), $productsAndPosts->getProducts());
        $this->assertEquals(new Posts([]), $productsAndPosts->getPosts());
    }

    public function testToProductsAndPostsOnlyProducts() : void
    {
        $product          = new ProductDTO(2.34, 'name', 'thumbnail');
        $result           = new Result(new ProductsDTO([$product]));
        $searchResult     = new SearchResult($result);
        $productsAndPosts = $searchResult->toProductsAndPosts();

        $this->assertEquals(new Product('name', 2.34, 'thumbnail'), $productsAndPosts->getProducts()->getProducts()[0]);
        $this->assertEquals(new Posts([]), $productsAndPosts->getPosts());
    }

    public function testToProductsAndPostsOnlyPosts() : void
    {
        $post             = new PostDTO('title', 'date', 'thumbnail');
        $result           = new Result(null, new PostsDTO([$post]));
        $searchResult     = new SearchResult($result);
        $productsAndPosts = $searchResult->toProductsAndPosts();

        $this->assertEquals(new Post('title', 'date', 'thumbnail'), $productsAndPosts->getPosts()->getPosts()[0]);
        $this->assertEquals(new Products([]), $productsAndPosts->getProducts());
    }
}
