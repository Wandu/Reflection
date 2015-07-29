<?php
namespace Wandu\Reflection;

use Closure;
use Mockery;
use PHPUnit_Framework_TestCase;
use ReflectionFunctionAbstract;
use Wandu\Reflection\Stub\StubCaller;
use Wandu\Reflection\Stub\StubInvoker;

class ReflectionCallableTest extends PHPUnit_Framework_TestCase
{
    public function provideManyTypesOfCallable()
    {
        return [
            [
                function ($numberX, $numberY) {
                    return "closure, sum=" . ($numberX + $numberY);
                },
                'closure'
            ],
            [[StubCaller::class, 'staticSum'], 'static'],
            [StubCaller::class . '::staticSum', 'static'],
            [[new StubCaller, 'instanceSum'], 'instance'],
            [new StubInvoker, 'invoker'],
            ['stubFunction', 'function'],
        ];
    }

    /**
     * @dataProvider provideManyTypesOfCallable
     */
    public function testGetFunctionAbstractReflection($callee, $type)
    {
        $this->assertInstanceOf(
            ReflectionFunctionAbstract::class,
            ReflectionCallable::getFunctionAbstractReflection($callee)
        );

        $reflection = new ReflectionCallable($callee);

        $this->assertEquals(2, $reflection->getNumberOfParameters());

        // call test
        $this->assertEquals("{$type}, sum=30", $reflection(10, 20));

        // call test
        $this->assertEquals("{$type}, sum=30", $reflection->__invoke(10, 20));
    }
}
