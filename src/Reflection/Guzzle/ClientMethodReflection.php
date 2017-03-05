<?php

declare(strict_types = 1);

namespace PHPStan\Reflection\Guzzle;

use GuzzleHttp\Promise\PromiseInterface;
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

class ClientMethodReflection implements MethodReflection
{

	private $classReflection;

	private $name;

	public function __construct(ClassReflection $classReflection, string $name)
	{
		$this->classReflection = $classReflection;
		$this->name = $name;
	}

	public function getDeclaringClass(): ClassReflection
	{
		return $this->classReflection;
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
		return new ObjectType(substr($this->name, -5) !== 'Async' ? ResponseInterface::class : PromiseInterface::class, false);
	}
}
