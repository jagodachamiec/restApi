<?php

declare(strict_types=1);

namespace App\Services\TheRake\DTO;

final class Result
{
    /** @var Products|null */
    private $products;

    /** @var Posts|null */
    private $posts;

    public function __construct(?Products $products = null, ?Posts $posts = null)
    {
        $this->products = $products;
        $this->posts    = $posts;
    }

    public function getPosts() : ?Posts
    {
        return $this->posts;
    }

    public function getProducts() : ?Products
    {
        return $this->products;
    }
}
