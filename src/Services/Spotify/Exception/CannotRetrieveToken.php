<?php

declare(strict_types=1);

namespace App\Services\Spotify\Exception;

use Exception;
use function sprintf;

final class CannotRetrieveToken extends Exception
{
    public static function deserializationFailed() : self
    {
        return new self('Deserialization of response\'s body failed.');
    }

    public static function invalidStatusCode(int $statusCode, string $reasonPhrase) : self
    {
        return new self(sprintf('Client returned incorrect status code: %s. %s', $statusCode, $reasonPhrase));
    }
}
