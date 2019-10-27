<?php

declare(strict_types=1);

namespace App\Services\TheRake\ValueObject;

use Symfony\Component\Serializer\Annotation\SerializedName;

final class Post
{
    /**
     * @var string
     * @SerializedName("post_title")
     */
    private $postTitle;

    /**
     * @var string
     * @SerializedName("post_date")
     */
    private $postDate;

    /** @var string|null */
    private $thumbnail;

    public function __construct(string $postTitle, string $postDate, ?string $thumbnail = null)
    {
        $this->postTitle = $postTitle;
        $this->postDate  = $postDate;
        $this->thumbnail = $thumbnail;
    }

    public function getPostTitle() : string
    {
        return $this->postTitle;
    }

    public function getPostDate() : string
    {
        return $this->postDate;
    }

    public function getThumbnail() : ?string
    {
        return $this->thumbnail;
    }
}
