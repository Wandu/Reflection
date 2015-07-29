<?php
namespace Wandu\Reflection\Stub;

class StubCaller
{
    public function instanceSum($numberX, $numberY)
    {
        return "instance, sum=" . ($numberX + $numberY);
    }

    public static function staticSum($numberX, $numberY)
    {
        return "static, sum=" . ($numberX + $numberY);
    }
}
