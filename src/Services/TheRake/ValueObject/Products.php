<?php

declare(strict_types=1);

namespace App\Services\TheRake\ValueObject;

final class Products
{
    /** @var Product[] */
    private $products;

    /**
     * @param Product[] $products
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * @return Product[]
     */
    public function getProducts() : array
    {
        return $this->products;
    }
}
