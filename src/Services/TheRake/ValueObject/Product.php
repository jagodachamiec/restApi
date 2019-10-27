<?php

declare(strict_types=1);

namespace App\Services\TheRake\ValueObject;

final class Product
{
    /** @var string */
    private $name;

    /** @var string|null */
    private $thumbnail;

    /** @var float */
    private $price;

    public function __construct(string $name, float $price, ?string $thumbnail = null)
    {
        $this->name      = $name;
        $this->thumbnail = $thumbnail;
        $this->price     = $price;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getThumbnail() : ?string
    {
        return $this->thumbnail;
    }

    public function getPrice() : float
    {
        return $this->price;
    }
}
