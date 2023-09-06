<?php

namespace Core\Decorators;

use Core\Data\Doctrine\EntityManagerBuilder;
use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class ReopeningEntityManagerDecorator extends EntityManagerDecorator
{
    public function __construct(private ContainerInterface $container)
    {
        parent::__construct(
            EntityManagerBuilder::produce(
                $container->get("settings")["doctrine"]
            )
        );
    }

    public function open(): EntityManagerInterface
    {
        if (!$this->wrapped->isOpen()) {
            $this->wrapped = $this->generateNewEm();
        }

        return $this->wrapped;
    }

    private function generateNewEm()
    {
        $settings = $this->container->get('settings');
        /** @var array */
        $doctrine = $settings['doctrine'];
        return EntityManagerBuilder::produce($doctrine);
    }
}