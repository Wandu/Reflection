<?php
namespace Wandu\Reflection\Stub;

class StubInvoker
{
    public function __invoke($numberX, $numberY)
    {
        return "invoker, sum=" . ($numberX + $numberY);
    }
}
