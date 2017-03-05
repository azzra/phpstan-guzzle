<?php

declare(strict_types=1);

namespace Tests\PHPStan\Reflection\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Guzzle\GuzzleClientMethodReflection;
use PHPStan\Type\ObjectType;
use Psr\Http\Message\ResponseInterface;

class GuzzleMethodExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider methodExtensionProvider
     *
     * @param string $methodName
     */
    public function testMethodExtension(string $methodName)
    {
        $broker = $this->createMock(Broker::class);
        $methodReflection = new GuzzleClientMethodReflection($broker, $methodName);

        $this->assertFalse($methodReflection->isStatic());
        $this->assertFalse($methodReflection->isPrivate());
        $this->assertTrue($methodReflection->isPublic());
        $this->assertSame($methodName, $methodReflection->getName());
        $this->assertFalse($methodReflection->isVariadic());

        $classReflection = $this->createMock(ClassReflection::class);
        $broker->expects($this->once())->method('getClass')->with(Client::class)
            ->willReturn($classReflection);
        $this->assertSame($classReflection, $methodReflection->getDeclaringClass());
    }

    public function methodExtensionProvider()
    {
        return [
            ['get'],
            ['getAsync'],
        ];
    }

    /**
     * @dataProvider getReturnTypeProvider
     *
     * @param string $className
     * @param string $methodName
     */
    public function testGetReturnType(string $className, string $methodName)
    {
        $broker = $this->createMock(Broker::class);
        $methodReflection = new GuzzleClientMethodReflection($broker, $methodName);
        $type = $methodReflection->getReturnType();

        $this->assertInstanceOf(ObjectType::class, $type);
        $this->assertSame($className, $type->getClass());
        $this->assertFalse($type->isNullable());
    }

    public function getReturnTypeProvider(): array
    {
        return [
            [ResponseInterface::class, 'get'],
            [PromiseInterface::class, 'getAsync'],
        ];
    }
}
