<?php

declare(strict_types=1);

namespace App\Tests\Services\Serializer;

use App\Services\Serializer\Exception\DeserializationFailed;
use App\Services\Serializer\Exception\SerializationFailed;
use App\Services\Serializer\SymfonySerializer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SymfonySerializerTest extends TestCase
{
    private const SERIALIZED_DATA = 'example serialized data';
    private const TYPE            = 'example type';
    private const FORMAT          = 'example format';
    private const CONTEXT         = ['example context key' => 'example context value'];
    /** @var MockObject&SerializerInterface */
    private $symfonySerializer;
    /** @var SymfonySerializer */
    private $serializer;

    public function testSerialize() : void
    {
        $data = new stdClass();
        $this->symfonySerializer
            ->expects($this->once())
            ->method('serialize')
            ->with($data, self::FORMAT, self::CONTEXT)
            ->willReturn(self::SERIALIZED_DATA);
        $serializedData = $this->serializer->serialize($data, self::FORMAT, self::CONTEXT);
        $this->assertSame(self::SERIALIZED_DATA, $serializedData);
    }

    public function testDeserialize() : void
    {
        $expectedDeserializedData = new stdClass();
        $this->symfonySerializer
            ->expects($this->once())
            ->method('deserialize')
            ->with(self::SERIALIZED_DATA, self::TYPE, self::FORMAT, self::CONTEXT)
            ->willReturn($expectedDeserializedData);
        $deserializedData = $this->serializer->deserialize(self::SERIALIZED_DATA, self::TYPE, self::FORMAT, self::CONTEXT);
        $this->assertSame($expectedDeserializedData, $deserializedData);
    }

    public function testSerializeThrowsException() : void
    {
        $data      = new stdClass();
        $exception = $this->createMock(ExceptionInterface::class);
        $this->symfonySerializer
            ->expects($this->once())
            ->method('serialize')
            ->with($data, self::FORMAT, self::CONTEXT)
            ->willThrowException($exception);
        $this->expectException(SerializationFailed::class);
        $this->serializer->serialize($data, self::FORMAT, self::CONTEXT);
    }

    public function testDeserializeThrowsException() : void
    {
        $exception = $this->createMock(ExceptionInterface::class);
        $this->symfonySerializer
            ->expects($this->once())
            ->method('deserialize')
            ->with(self::SERIALIZED_DATA, self::TYPE, self::FORMAT, self::CONTEXT)
            ->willThrowException($exception);
        $this->expectException(DeserializationFailed::class);
        $this->serializer->deserialize(self::SERIALIZED_DATA, self::TYPE, self::FORMAT, self::CONTEXT);
    }

    public function setUp() : void
    {
        $this->symfonySerializer = $this->createMock(SerializerInterface::class);
        $this->serializer        = new SymfonySerializer($this->symfonySerializer);
    }
}
