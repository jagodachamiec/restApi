<?php

declare(strict_types=1);

namespace App\Services\TheRake\ValueObject;

final class Posts
{
    /** @var Post[] */
    private $posts;

    /**
     * @param Post[] $posts
     */
    public function __construct(array $posts)
    {
        $this->posts = $posts;
    }

    /**
     * @return Post[]
     */
    public function getPosts() : array
    {
        return $this->posts;
    }
}
