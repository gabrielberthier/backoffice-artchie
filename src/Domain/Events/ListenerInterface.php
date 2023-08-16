<?php
namespace App\Domain\Events;

/**
 * @template T Event
 */
interface ListenerInterface
{
    /**
     * @param T $subject
     */
    public function execute(Event $subject): void;
}