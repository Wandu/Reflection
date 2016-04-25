<?php
namespace Wandu\Reflection;

use ReflectionClass;

class ReflectionClassExtend extends ReflectionClass
{
    /**
     * @return string
     */
    public function getParentClassName()
    {
        $parentRefl = $this->getParentClass();
        if ($parentRefl) {
            return $parentRefl->getName();
        }
    }

    /**
     * @return string[]
     */
    public function getAncestorClassNames()
    {
        return array_map(function (ReflectionClass $refl) {
            return $refl->getName();
        }, $this->getAncestorClasses());
    }

    /**
     * @return \ReflectionClass[]
     */
    public function getAncestorClasses()
    {
        $ancestors = [];
        $refl = $this->getParentClass();
        while ($refl) {
            $ancestors[] = $refl;
            $refl = $refl->getParentClass();
        }
        return $ancestors;
    }

    /**
     * @return string[]
     */
    public function getAncestorInterfaceNames()
    {
        $ancestorClasses = $this->getAncestorClasses();
        $index = count($ancestorClasses);
        $beforeInterfaces = [];
        $ancestors = [];
        while ($index--) {
            $ancestor = $ancestorClasses[$index]->getInterfaceNames();
            $ancestors[] = array_values(array_diff($ancestor, $beforeInterfaces));
            $beforeInterfaces = array_merge($beforeInterfaces, $ancestor);
        }
        $ancestor = $this->getInterfaceNames();
        $ancestors[] = array_values(array_diff($ancestor, $beforeInterfaces));
        return array_reverse($ancestors);
    }

    /**
     * @return string[]
     */
    public function getAncestorNames()
    {
        $ancestorClassNames = $this->getAncestorClassNames();
        $ancestorNames = [];
        foreach ($this->getAncestorInterfaceNames() as $key => $interfaces) {
            $ancestorNames[] = array_merge([
                isset($ancestorClassNames[$key]) ? $ancestorClassNames[$key] : null
            ], $interfaces);
        }
        return $ancestorNames;
    }
}
