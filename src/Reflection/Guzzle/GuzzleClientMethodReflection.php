<?php

declare(strict_types=1);

namespace PHPStan\Reflection\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\Php\DummyParameter;
use PHPStan\Type\CommonUnionType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class GuzzleClientMethodReflection implements MethodReflection
{
    private $broker;
    private $name;

    public function __construct(Broker $broker, string $name)
    {
        $this->broker = $broker;
        $this->name = $name;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->broker->getClass(Client::class);
    }

    public function isStatic(): bool
    {
        return false;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function getPrototype(): MethodReflection
    {
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParameters(): array
    {
        return [
            new DummyParameter('uri', new CommonUnionType([
                new StringType(false),
                new ObjectType(UriInterface::class, false),
            ], false), false),
            new DummyParameter('options', new MixedType(), true),
        ];
    }

    public function isVariadic(): bool
    {
        return false;
    }

    public function getReturnType(): Type
    {
        return new ObjectType('Async' !== substr($this->name, -5) ? ResponseInterface::class : PromiseInterface::class, false);
    }
}
