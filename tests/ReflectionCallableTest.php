<?php
namespace Wandu\Reflection;

use PHPUnit_Framework_TestCase;
use ReflectionFunctionAbstract;

class ReflectionCallableTest extends PHPUnit_Framework_TestCase
{
    public function provideManyTypesOfCallable()
    {
        return [
            [__NAMESPACE__ . '\testFunction', ReflectionCallable::TYPE_FUNCTION, 'function'],
            [TestStringStaticSum::class . '::sum', ReflectionCallable::TYPE_STATIC_METHOD, 'string_static'],
            [[TestArrayStaticSum::class, 'sum'], ReflectionCallable::TYPE_STATIC_METHOD, 'array_static'],
            [[new TestInstanceSum, 'sum'], ReflectionCallable::TYPE_INSTANCE_METHOD, 'instance'],
            [new TestInvoker, ReflectionCallable::TYPE_INVOKER, 'invoker'],
            [
                function ($numberX, $numberY) {
                    return [$numberX + $numberY, 'closure'];
                },
                ReflectionCallable::TYPE_CLOSURE,
                'closure',
            ],
        ];
    }

    /**
     * @dataProvider provideManyTypesOfCallable
     */
    public function testGetFunctionAbstractReflection($callee, $reflectionType, $resultText)
    {
        $reflection = new ReflectionCallable($callee);

        $this->assertEquals(2, $reflection->getNumberOfParameters());

        // test invoke
        $this->assertEquals([30, $resultText], $reflection->__invoke(10, 20));
        
        $this->assertEquals($reflectionType, $reflection->getReflectionType());
        
        $this->assertInstanceOf(ReflectionFunctionAbstract::class, $reflection->getRawReflection());
        
        $this->assertFalse($reflection->isMagicMethod());
    }

    public function testMagicMethodCall()
    {
        $reflection = new ReflectionCallable([new TestMagicMethodCall(), 'sum']);

        $this->assertTrue($reflection->isMagicMethod());
        $this->assertEquals(ReflectionCallable::TYPE_INSTANCE_METHOD, $reflection->getReflectionType());

        $this->assertEquals(0, $reflection->getNumberOfParameters());
        $this->assertEquals(0, $reflection->getNumberOfRequiredParameters());
        $this->assertEquals([], $reflection->getParameters());
        $this->assertEquals('sum', $reflection->getShortName());
        $this->assertEquals('sum', $reflection->getName());

        $this->assertEquals(['sum', [20, 30]], $reflection->__invoke(20, 30));
    }

    public function testMagicMethodCallStatic()
    {
        $reflection = new ReflectionCallable([TestMagicMethodCallStatic::class, 'sum']);

        $this->assertTrue($reflection->isMagicMethod());
        $this->assertEquals(ReflectionCallable::TYPE_STATIC_METHOD, $reflection->getReflectionType());

        $this->assertEquals(0, $reflection->getNumberOfParameters());
        $this->assertEquals(0, $reflection->getNumberOfRequiredParameters());
        $this->assertEquals([], $reflection->getParameters());
        $this->assertEquals('sum', $reflection->getShortName());
        $this->assertEquals('sum', $reflection->getName());

        $this->assertEquals(['sum', [20, 30]], $reflection->__invoke(20, 30));
    }
}

function testFunction($numberX, $numberY)
{
    return [$numberX + $numberY, 'function'];
}

class TestStringStaticSum
{
    public static function sum($numberX, $numberY)
    {
        return [$numberX + $numberY, 'string_static'];
    }
}

class TestArrayStaticSum
{
    public static function sum($numberX, $numberY)
    {
        return [$numberX + $numberY, 'array_static'];
    }
}

class TestInstanceSum
{
    public function sum($numberX, $numberY)
    {
        return [$numberX + $numberY, 'instance'];
    }
}

class TestInvoker
{
    public function __invoke($numberX, $numberY)
    {
        return [$numberX + $numberY, 'invoker'];
    }
}

class TestMagicMethodCall
{
    public function __call($name, $arguments)
    {
        return [$name, $arguments];
    }
}

class TestMagicMethodCallStatic
{
    public static function __callStatic($name, $arguments)
    {
        return [$name, $arguments];
    }
}
