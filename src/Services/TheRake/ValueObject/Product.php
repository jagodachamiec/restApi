<?php

declare(strict_types=1);

namespace App\Services\TheRake\ValueObject;

final class Product
{
    /** @var string|null */
    private $name;

    /** @var string|null */
    private $thumbnail;

    /** @var float|null */
    private $price;

    public function __construct(?string $name = null, ?float $price = null, ?string $thumbnail = null)
    {
        $this->name      = $name;
        $this->thumbnail = $thumbnail;
        $this->price     = $price;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function getThumbnail() : ?string
    {
        return $this->thumbnail;
    }

    public function getPrice() : ?float
    {
        return $this->price;
    }
}
