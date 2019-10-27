<?php

declare(strict_types=1);

namespace App\Services\TheRake\DTO;

final class Product
{
    /** @var float */
    private $price;

    /** @var string */
    private $name;

    /** @var string|null */
    private $thumbnail;

    public function __construct(float $price, string $name, ?string $thumbnail = null)
    {
        $this->price     = $price;
        $this->name      = $name;
        $this->thumbnail = $thumbnail;
    }

    public function getPrice() : float
    {
        return $this->price;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getThumbnail() : ?string
    {
        return $this->thumbnail;
    }
}
