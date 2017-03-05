<?php

declare(strict_types = 1);

namespace Tests\PHPStan\Reflection\Guzzle;

use GuzzleHttp\Client;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Guzzle\ClientMethodReflection;
use PHPStan\Reflection\Guzzle\ClientMethodsClassReflectionExtension;

class ClientMethodsClassReflectionExtensionTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var ClientMethodsClassReflectionExtension
	 */
	private $extension;

	public function setUp()
	{
		$this->extension = new ClientMethodsClassReflectionExtension();
	}

	/**
	 * @dataProvider hasMethodProvider
	 *
	 * @param boolean $expected
	 * @param string $className
	 * @param string $methodName
	 */
	public function testHasMethod(bool $expected, string $className, string $methodName)
	{
		/** @var \PHPUnit_Framework_MockObject_Builder_MethodNameMatch $classReflection */
		$classReflection = $this->createMock(ClassReflection::class);
		$classReflection->method('getName')->will($this->returnValue($className));
		$this->assertSame($expected, $this->extension->hasMethod($classReflection, $methodName));
	}

	public function hasMethodProvider(): array
	{
		return [
			[true, Client::class, 'get'],
			[true, Client::class, 'getAsync'],
			[false, Client::class, 'foo'],
		];
	}

	/**
	 * @dataProvider getMethodProvider
	 *
	 * @param string $methodName
	 */
	public function testGetMethod(string $methodName)
	{
		$classReflection = $this->createMock(ClassReflection::class);
		$methodReflection = $this->extension->getMethod($classReflection, $methodName);
		$this->assertInstanceOf(ClientMethodReflection::class, $methodReflection);
	}

	public function getMethodProvider(): array
	{
		return [
			['get'],
			['getAsync'],
		];
	}

}
