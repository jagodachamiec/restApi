<?php

declare(strict_types=1);

namespace App\Services\Serializer;

use App\Services\Serializer\Exception\DeserializationFailed;
use App\Services\Serializer\Exception\SerializationFailed;

interface Serializer
{
    /**
     * Serializes data in the appropriate format.
     *
     * @param mixed   $data
     * @param mixed[] $context
     *
     * @throws SerializationFailed
     */
    public function serialize($data, string $format, array $context = []) : string;

    /**
     * Deserializes data into the given type.
     *
     * @param mixed[] $context
     *
     * @return object|mixed[]
     *
     * @throws DeserializationFailed
     */
    public function deserialize(string $data, string $type, string $format, array $context = []);
}
