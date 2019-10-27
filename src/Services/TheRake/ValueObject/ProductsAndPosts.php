<?php

declare(strict_types=1);

namespace App\Services\TheRake\ValueObject;

final class ProductsAndPosts
{
    /** @var Products */
    private $products;

    /** @var Posts */
    private $posts;

    public function __construct(Products $products, Posts $posts)
    {
        $this->products = $products;
        $this->posts    = $posts;
    }

    public function getProducts() : Products
    {
        return $this->products;
    }

    public function getPosts() : Posts
    {
        return $this->posts;
    }
}
