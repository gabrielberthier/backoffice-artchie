<?php

namespace Core\Decorators;

use Core\Data\Doctrine\EntityManagerBuilder;
use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Psr\Container\ContainerInterface;

class ReopeningEntityManagerDecorator extends EntityManagerDecorator
{
    public function __construct(private ContainerInterface $container)
    {
        parent::__construct(EntityManagerBuilder::produce($container->get('settings')['doctrine']));
    }

    public function open(): void
    {
        if (!$this->wrapped->isOpen()) {
            $settings = $this->container->get('settings');
            $doctrine = $settings['doctrine'];

            $this->wrapped = EntityManagerBuilder::produce($doctrine);
        }
    }
}