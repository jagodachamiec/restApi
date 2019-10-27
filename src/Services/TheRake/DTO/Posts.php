<?php

declare(strict_types=1);

namespace App\Services\TheRake\DTO;

final class Posts
{
    /** @var Post[] */
    private $data;

    /**
     * @param Post[] $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return Post[]
     */
    public function getData() : array
    {
        return $this->data;
    }
}
