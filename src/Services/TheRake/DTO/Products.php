<?php

declare(strict_types=1);

namespace App\Services\TheRake\DTO;

final class Products
{
    /** @var Product[] */
    private $data;

    /**
     * @param Product[] $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return Product[]
     */
    public function getData() : array
    {
        return $this->data;
    }
}
