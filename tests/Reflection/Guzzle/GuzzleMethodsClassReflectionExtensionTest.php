<?php

declare(strict_types=1);

namespace Tests\PHPStan\Reflection\Guzzle;

use GuzzleHttp\Client;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Guzzle\GuzzleMethodReflection;
use PHPStan\Reflection\Guzzle\GuzzleMethodsClassReflectionExtension;

class GuzzleMethodsClassReflectionExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GuzzleMethodsClassReflectionExtension
     */
    private $extension;

    public function setUp()
    {
        $this->extension = new GuzzleMethodsClassReflectionExtension();
    }

    /**
     * @dataProvider hasMethodProvider
     *
     * @param bool   $expected
     * @param string $className
     * @param string $methodName
     */
    public function testHasMethod(bool $expected, string $className, string $methodName)
    {
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
        $broker = $this->createMock(Broker::class);
        $this->extension->setBroker($broker);
        $classReflection = $this->createMock(ClassReflection::class);
        $methodReflection = $this->extension->getMethod($classReflection, $methodName);
        $this->assertInstanceOf(GuzzleMethodReflection::class, $methodReflection);
    }

    public function getMethodProvider(): array
    {
        return [
            ['get'],
            ['getAsync'],
        ];
    }
}
