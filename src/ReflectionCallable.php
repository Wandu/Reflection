<?php
namespace Wandu\Reflection;

use Closure;
use ReflectionClass;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionObject;
use Reflector;
use RuntimeException;

class ReflectionCallable extends ReflectionFunctionAbstract implements Reflector
{
    const TYPE_FUNCTION = 1;
    const TYPE_STATIC_METHOD = 2;
    const TYPE_INSTANCE_METHOD = 3;
    const TYPE_INVOKER = 4;
    const TYPE_CLOSURE = 5;
        
    /** @var callable */
    private $callee;

    /** @var ReflectionFunctionAbstract */
    private $reflection;

    /** @var int */
    private $reflectionType;
    
    /**
     * @param callable $callee
     */
    public function __construct(callable $callee)
    {
        $this->callee = $callee;
        $this->reflection = $this->getFunctionAbstractReflection($callee);
    }
    
    /**
     * @param callable $callee
     * @return ReflectionFunctionAbstract
     */
    protected function getFunctionAbstractReflection(callable $callee)
    {
        // closure, or function name,
        if ($callee instanceof Closure) {
            $this->reflectionType = static::TYPE_CLOSURE;
            return new ReflectionFunction($callee);
        } elseif (is_string($callee) && strpos($callee, '::') === false) {
            $this->reflectionType = static::TYPE_FUNCTION;
            return new ReflectionFunction($callee);
        }
        if (is_string($callee)) {
            $callee = explode('::', $callee);
        } elseif (is_object($callee)) {
            $this->reflectionType = static::TYPE_INVOKER;
            $callee = [$callee, '__invoke'];
        }
        if (is_object($callee[0])) {
            if (!isset($this->reflectionType)) {
                $this->reflectionType = static::TYPE_INSTANCE_METHOD;
            }
            $reflectionObject = new ReflectionObject($callee[0]);
        } else {
            $this->reflectionType = static::TYPE_STATIC_METHOD;
            $reflectionObject = new ReflectionClass($callee[0]);
        }
        return $reflectionObject->getMethod($callee[1]);
    }

    /**
     * @return int
     */
    public function getReflectionType()
    {
        return $this->reflectionType;
    }

    /**
     * @return \ReflectionFunctionAbstract
     */
    public function getRawReflection()
    {
        return $this->reflection;
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
    public function getReturnType()
    {
        return $this->reflection->getReturnType();
    }

    /**
     * {@inheritdoc}
     */
    public function isGenerator()
    {
        return $this->reflection->isGenerator();
    }

    /**
     * {@inheritdoc}
     */
    public function isVariadic()
    {
        return $this->reflection->isVariadic();
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
}
