Wandu Reflection
===

[![Latest Stable Version](https://poser.pugx.org/wandu/reflection/v/stable.svg)](https://packagist.org/packages/wandu/reflection)
[![Latest Unstable Version](https://poser.pugx.org/wandu/reflection/v/unstable.svg)](https://packagist.org/packages/wandu/reflection)
[![Total Downloads](https://poser.pugx.org/wandu/reflection/downloads.svg)](https://packagist.org/packages/wandu/reflection)
[![License](https://poser.pugx.org/wandu/reflection/license.svg)](https://packagist.org/packages/wandu/reflection)

[![Build Status](https://travis-ci.org/Wandu/Reflection.svg?branch=master)](https://travis-ci.org/Wandu/Reflection)
[![Code Coverage](https://scrutinizer-ci.com/g/Wandu/Reflection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Wandu/Reflection/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Wandu/Reflection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Wandu/Reflection/?branch=master)

Reflection Extend Library.

## Documents

### ReflectionCallable

It supports 6 types of callable object. (ref.
[Wani Blog, Callable](http://blog.wani.kr/dev/php/php-something-4-callable))

1. string of function's name.
2. string of class and static method's name.
3. array of class and static method's name.
4. array of object and method's name.
5. object of class that has `__invoke` method. (ref. [Magic Method `__invoke`](http://php.net/manual/language.oop5.magic.php#object.invoke))
6. object of `Closure`.

```php
namespace Wandu\Reflection;

class ReflectionCallable extends ReflectionFunctionAbstract
{
    /* Method */
    public function __invoke(...$parameters);

    /* Static Methods */
    public static function getFunctionAbstractReflection(callable $callee);

    /* Also, you can use every ReflectionFunctionAbstract's methods :-) */
}
```

(ref. [ReflectionFunctionAbstract Class](http://php.net/manual/class.reflectionfunctionabstract.php))

#### Example.

```php
use Wandu\Reflection\ReflectionCallable;

// 1. string of function's name.
$reflection = new ReflectionCallable('yourfunctionname`); // OK
$reflection = new ReflectionCallable('Your\OwnNamespace\yourfunctionname`); // with namespace also OK.

// 2. string of class and static method's name.
$reflection = new ReflectionCallable('Your\OwnNamespace\MyClass::callMyMethod'); // OK

// 3. array of class and static method's name.
$reflection = new ReflectionCallable(['Your\OwnNamespace\MyClass', 'callMyMethod']); // OK

// 4. array of object and method's name.
$reflection = new ReflectionCallable([new Your\OwnNamespace\MyClass(), 'callMyMethod']); // OK

// 5. object of class that has `__invoke` method.
$reflection = new ReflectionCallable(new Your\OwnNamespace\ClassWithInvoke()); // OK

// 6. object of `Closure`
$reflection = new ReflectionCallable(function ($param1, $param2) { /* do something */ });

$reflection->getNumberOfParameters(); // return 2
```

