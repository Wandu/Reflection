<?php
namespace Wandu\Reflection;

use PHPUnit_Framework_TestCase;

class ReflectionClassExtendTest extends PHPUnit_Framework_TestCase
{
    public function testGetParentClassName()
    {
        $classRefl = new ReflectionClassExtend(PHPUnit_Framework_TestCase::class);
        $this->assertEquals('PHPUnit_Framework_Assert', $classRefl->getParentClassName());

        $classRefl = new ReflectionClassExtend(StubHasNoParentClass::class);
        $this->assertNull($classRefl->getParentClassName());
    }

    public function testGetAncestorClassesName()
    {
        $classRefl = new ReflectionClassExtend(StubHasTwoAncestorClass::class);

        $this->assertEquals([
            StubHasOneAncestorClass::class,
            StubHasNoParentClass::class,
        ], $classRefl->getAncestorClassNames());
    }

    public function testGetAncestorNames()
    {
        $classRefl = new ReflectionClassExtend(StubHasTwoAncestorClass::class);
        $this->assertEquals([
            [StubHasOneAncestorClass::class],
            [StubHasNoParentClass::class, OneParentInterface::class, MoreThanOneParentInterface::class],
            [null, NoParentInterface1::class, NoParentInterface2::class],
        ], $classRefl->getAncestorNames());
    }
}

interface NoParentInterface1 {}
interface NoParentInterface2 {}
class StubHasNoParentClass implements NoParentInterface1, NoParentInterface2 {}

interface OneParentInterface {}
interface MoreThanOneParentInterface {}
class StubHasOneAncestorClass extends StubHasNoParentClass implements OneParentInterface, MoreThanOneParentInterface {}
class StubHasTwoAncestorClass extends StubHasOneAncestorClass implements MoreThanOneParentInterface {}
