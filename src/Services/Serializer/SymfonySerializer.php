<?php

declare(strict_types=1);

namespace App\Services\Serializer;

use App\Services\Serializer\Exception\DeserializationFailed;
use App\Services\Serializer\Exception\SerializationFailed;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SymfonySerializer implements Serializer
{
    /** @var SerializerInterface */
    private $symfonySerializer;

    public function __construct(SerializerInterface $symfonySerializer)
    {
        $this->symfonySerializer = $symfonySerializer;
    }

    /**
     * @inheritDoc
     */
    public function serialize($data, string $format, array $context = []) : string
    {
        try {
            return $this->symfonySerializer->serialize($data, $format, $context);
        } catch (ExceptionInterface $e) {
            throw new SerializationFailed($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function deserialize(string $data, string $type, string $format, array $context = [])
    {
        try {
            return $this->symfonySerializer->deserialize($data, $type, $format, $context);
        } catch (ExceptionInterface $e) {
            throw new DeserializationFailed($e->getMessage(), $e->getCode(), $e);
        }
    }
}
