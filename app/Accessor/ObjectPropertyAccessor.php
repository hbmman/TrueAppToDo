<?php


namespace App\Accessor;


class ObjectPropertyAccessor
{

    private $object;
    /**
     * @var \ReflectionObject
     */
    private $reflectionObject;
    public function __construct($object)
    {
        $this->object = $object;
        $this->reflectionObject = new \ReflectionObject($object);
    }

    public function getPropertyValue(string $propName)
    {
        $prop = $this->reflectionObject->getProperty($propName);

        $prop->setAccessible(true);

        return $prop->getValue($this->object);
    }

    public function setPropertyValue(string $propName, $value):void
    {
        $prop = $this->reflectionObject->getProperty($propName);

        $prop->setAccessible(true);

        $prop->setValue($this->object, $value);
    }
}
