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

## Installation

```
$ composer require wandu/reflection
```

## Documents

 - [ReflectionCallable](#reflectioncallable)
 
### ReflectionCallable

It supports 6 types of callable object. (ref.
[Wani Blog, Callable](http://blog.wani.kr/posts/2015/05/02/php-something-4-callable/))

1. string of function's name.
2. string of class and static method's name.
3. array of class and static method's name.
4. array of object and method's name.
5. object of class that has `__invoke` method. (ref. [Magic Method `__invoke`](http://php.net/manual/language.oop5.magic.php#object.invoke))
6. object of `Closure`.

and two more.

1. `__call` magic method.
2. `__callStatic` magic method.

```php
namespace Wandu\Reflection;

use ReflectionFunctionAbstract;
use Reflector;

class ReflectionCallable extends ReflectionFunctionAbstract implement Reflector {

    /* Method */
    public __construct( callable $callee )
    public mixed __invoke( ...$parameters )
    public boolean isMagicMethod()
    public int getReflectionType()
    public ReflectionFunctionAbstract getRawReflection()
    
    /* Inherited methods */
    final private void ReflectionFunctionAbstract::__clone ( void )
    public ReflectionClass ReflectionFunctionAbstract::getClosureScopeClass ( void )
    public object ReflectionFunctionAbstract::getClosureThis ( void )
    public string ReflectionFunctionAbstract::getDocComment ( void )
    public int ReflectionFunctionAbstract::getEndLine ( void )
    public ReflectionExtension ReflectionFunctionAbstract::getExtension ( void )
    public string ReflectionFunctionAbstract::getExtensionName ( void )
    public string ReflectionFunctionAbstract::getFileName ( void )
    public string ReflectionFunctionAbstract::getName ( void )
    public string ReflectionFunctionAbstract::getNamespaceName ( void )
    public int ReflectionFunctionAbstract::getNumberOfParameters ( void )
    public int ReflectionFunctionAbstract::getNumberOfRequiredParameters ( void )
    public array ReflectionFunctionAbstract::getParameters ( void )
    public ReflectionType ReflectionFunctionAbstract::getReturnType ( void )
    public string ReflectionFunctionAbstract::getShortName ( void )
    public int ReflectionFunctionAbstract::getStartLine ( void )
    public array ReflectionFunctionAbstract::getStaticVariables ( void )
    public bool ReflectionFunctionAbstract::hasReturnType ( void )
    public bool ReflectionFunctionAbstract::inNamespace ( void )
    public bool ReflectionFunctionAbstract::isClosure ( void )
    public bool ReflectionFunctionAbstract::isDeprecated ( void )
    public bool ReflectionFunctionAbstract::isGenerator ( void )
    public bool ReflectionFunctionAbstract::isInternal ( void )
    public bool ReflectionFunctionAbstract::isUserDefined ( void )
    public bool ReflectionFunctionAbstract::isVariadic ( void )
    public bool ReflectionFunctionAbstract::returnsReference ( void )
    abstract public void ReflectionFunctionAbstract::__toString ( void )
}
```

(ref. [ReflectionFunctionAbstract Class](http://php.net/manual/class.reflectionfunctionabstract.php))

#### Example.

```php
use Wandu\Reflection\ReflectionCallable;

// 1. string of function's name.
$reflection = new ReflectionCallable('yourfunctionname'); // OK
$reflection = new ReflectionCallable('Your\OwnNamespace\yourfunctionname'); // with namespace also OK.

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

// 7. __call
$reflection = new ReflectionCallable([new Your\OwnNamespace\HasCallClass, 'anything']);

$reflection->getNumberOfParameters(); // always return 0
$reflection->getNumberOfRequiredParameters(); // always return 0
$reflection->getParameters(); // always return []
$reflection->getShortName(); // return 'anything'
$reflection->getName(); // return 'anything'

// 8. __callStatic
$reflection = new ReflectionCallable([Your\OwnNamespace\HasCallStaticClass::class, 'anything']);

$reflection->getNumberOfParameters(); // always return 0
$reflection->getNumberOfRequiredParameters(); // always return 0
$reflection->getParameters(); // always return []
$reflection->getShortName(); // return 'anything'
$reflection->getName(); // return 'anything'
```
