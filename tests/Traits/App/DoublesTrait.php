<?php

namespace Tests\Traits\App;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;

trait DoublesTrait
{
    protected function spyOn(string $className, string $method): AnyInvokedCount
    {
        $mock = $this->getMockBuilder($className)->getMock();
        $mock->expects($spy = $this->any())->method($method);

        return $spy;
    }

    /**
     * Add mock to container.
     *
     * @param string $class The class or interface
     *
     * @return MockObject The mock
     */
    protected function mock(string $class): MockObject
    {
        if (!class_exists($class)) {
            throw new InvalidArgumentException(sprintf('Class not found: %s', $class));
        }

        $mock = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->container->set($class, $mock);

        return $mock;
    }
}
