<?php
namespace Wandu\Reflection;

use Closure;
use Reflection;
use ReflectionClass;
use ReflectionFunctionAbstract;
use ReflectionFunction;
use ReflectionObject;
use RuntimeException;

class ReflectionCallable extends ReflectionFunctionAbstract
{
    /** @var callable */
    private $callee;

    /** @var ReflectionFunctionAbstract */
    private $reflection;

    /**
     * @param callable $callee
     */
    public function __construct(callable $callee)
    {
        $this->callee = $callee;
        $this->reflection = static::getFunctionAbstractReflection($callee);
    }

    /**
     * @param ...mixed $params
     * @return mixed
     */
    public function __invoke()
    {
        return call_user_func_array($this->callee, func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->reflection->__toString();
    }

    /**
     * {@inheritdoc}
     */
    public static function export()
    {
        throw new RuntimeException('not implemented method.');
    }

    /**
     * {@inheritdoc}
     */
    public function inNamespace()
    {
        return $this->reflection->inNamespace();
    }

    /**
     * {@inheritdoc}
     */
    public function isClosure()
    {
        return $this->reflection->isClosure();
    }

    /**
     * {@inheritdoc}
     */
    public function isDeprecated()
    {
        return $this->reflection->isDeprecated();
    }

    /**
     * {@inheritdoc}
     */
    public function isInternal()
    {
        return $this->reflection->isInternal();
    }

    /**
     * {@inheritdoc}
     */
    public function isUserDefined()
    {
        return $this->reflection->isUserDefined();
    }

    /**
     * {@inheritdoc}
     */
    public function getClosureThis()
    {
        return $this->reflection->getClosureThis();
    }

    /**
     * {@inheritdoc}
     */
    public function getClosureScopeClass()
    {
        return $this->reflection->getClosureScopeClass();
    }

    /**
     * {@inheritdoc}
     */
    public function getDocComment()
    {
        return $this->reflection->getDocComment();
    }

    /**
     * {@inheritdoc}
     */
    public function getEndLine()
    {
        return $this->reflection->getEndLine();
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return $this->reflection->getExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionName()
    {
        return $this->reflection->getExtensionName();
    }

    /**
     * {@inheritdoc}
     */
    public function getFileName()
    {
        return $this->reflection->getFileName();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->reflection->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespaceName()
    {
        return $this->reflection->getNamespaceName();
    }

    /**
     * {@inheritdoc}
     */
    public function getNumberOfParameters()
    {
        return $this->reflection->getNumberOfParameters();
    }

    /**
     * {@inheritdoc}
     */
    public function getNumberOfRequiredParameters()
    {
        return $this->reflection->getNumberOfRequiredParameters();
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->reflection->getParameters();
    }

    /**
     * {@inheritdoc}
     */
    public function getShortName()
    {
        return $this->reflection->getShortName();
    }

    /**
     * {@inheritdoc}
     */
    public function getStartLine()
    {
        return $this->reflection->getStartLine();
    }

    /**
     * {@inheritdoc}
     */
    public function getStaticVariables()
    {
        return $this->reflection->getStaticVariables();
    }

    /**
     * {@inheritdoc}
     */
    public function returnsReference()
    {
        return $this->reflection->returnsReference();
    }

    /**
     * @param callable $callee
     * @return ReflectionFunctionAbstract
     */
    public static function getFunctionAbstractReflection(callable $callee)
    {
        // closure, or function name,
        if ($callee instanceof Closure || (is_string($callee) && strpos($callee, '::') === false)) {
            return new ReflectionFunction($callee);
        }
        if (is_string($callee)) {
            $callee = explode('::', $callee);
        } elseif (is_object($callee)) {
            $callee = [$callee, '__invoke'];
        }
        if (is_object($callee[0])) {
            $reflectionObject = new ReflectionObject($callee[0]);
        } else {
            $reflectionObject = new ReflectionClass($callee[0]);
        }
        return $reflectionObject->getMethod($callee[1]);
    }
}
