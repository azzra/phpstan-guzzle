<?php

declare(strict_types = 1);

namespace PHPStan\Reflection\Guzzle;

use GuzzleHttp\Client;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

class ClientMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{
	const METHODS_SYNC = ['get', 'head', 'put', 'post', 'patch', 'delete'];
	const METHODS_ASYNC = ['getAsync', 'headAsync', 'putAsync', 'postAsync', 'patchAsync', 'deleteAsync'];

	public function hasMethod(ClassReflection $classReflection, string $methodName): bool
	{
		return $classReflection->getName() === Client::class && in_array($methodName, array_merge(self::METHODS_SYNC, self::METHODS_ASYNC), true);
	}

	public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
	{
		return new ClientMethodReflection($classReflection, $methodName);
	}
}
