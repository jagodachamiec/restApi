<?php

declare(strict_types=1);

namespace App\Tests\Services\TheRake\ValueObject;

use App\Services\TheRake\ValueObject\SearchParameters;
use PHPUnit\Framework\TestCase;

class SearchParametersTest extends TestCase
{
    public function testToQueryString() : void
    {
        $searchParameters = new SearchParameters('test', 'all', ['price'], ['postTitle']);

        $this->assertEquals('search=test&type=all&include_product=price&include_post=postTitle', $searchParameters->toQueryString());
    }
}
