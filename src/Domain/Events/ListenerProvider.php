<?php

namespace App\Domain\Events;


use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProvider implements ListenerProviderInterface
{

    private array $listeners = [];

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable[callable]
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        $eventType = get_class($event);
        if (array_key_exists($eventType, $this->listeners)) {
            return $this->listeners[$eventType];
        }

        return [];
    }

    public function addListener(string $eventType, ListenerInterface|callable $callable): self
    {
        $this->listeners[$eventType][] = $callable;
        return $this;
    }

    /**
     * @param string $eventType
     */
    public function clearListeners(string $eventType): void
    {
        if (array_key_exists($eventType, $this->listeners)) {
            unset($this->listeners[$eventType]);
        }
    }
}