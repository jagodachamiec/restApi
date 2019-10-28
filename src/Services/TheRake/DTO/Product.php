<?php

declare(strict_types=1);

namespace App\Services\TheRake\DTO;

final class Product
{
    /** @var float|null */
    private $price;

    /** @var string|null */
    private $name;

    /** @var string|null */
    private $thumbnail;

    public function __construct(?float $price = null, ?string $name = null, ?string $thumbnail = null)
    {
        $this->price     = $price;
        $this->name      = $name;
        $this->thumbnail = $thumbnail;
    }

    public function getPrice() : ?float
    {
        return $this->price;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function getThumbnail() : ?string
    {
        return $this->thumbnail;
    }
}
