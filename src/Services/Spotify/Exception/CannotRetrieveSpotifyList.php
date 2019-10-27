<?php

declare(strict_types=1);

namespace App\Services\Spotify\Exception;

use App\Services\Serializer\Exception\DeserializationFailed;
use Exception;
use Psr\Http\Client\ClientExceptionInterface;
use function sprintf;

final class CannotRetrieveSpotifyList extends Exception
{
    public static function deserializationFailed(DeserializationFailed $e) : self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }

    public static function invalidStatusCode(int $statusCode, string $reasonPhrase) : self
    {
        return new self(sprintf('Client returned incorrect status code: %s. %s', $statusCode, $reasonPhrase));
    }

    public static function cannotRetrieveToken(CannotRetrieveToken $e) : self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }

    public static function clientExceptionThrow(ClientExceptionInterface $e) : self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }
}
