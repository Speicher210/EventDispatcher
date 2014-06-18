<?php

namespace Wingu\OctopusCore\EventDispatcher;

use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface;

/**
 * Interface for an event subscription.
 */
interface SubscriptionInterface {

    /**
     * Set the event name matcher.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface $eventNameMatcher The event name matcher to set.
     * @return \Wingu\OctopusCore\EventDispatcher\SubscriptionInterface
     */
    public function setEventNameMatcher(EventNameMatcherInterface $eventNameMatcher);

    /**
     * Get the event name to subscribe.
     *
     * @return \Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface
     */
    public function getEventNameMatcher();

    /**
     * Set the callback.
     *
     * @param Callable $callback The callback.
     * @return \Wingu\OctopusCore\EventDispatcher\SubscriptionInterface
     */
    public function setCallback(Callable $callback);

    /**
     * Get the callback for the event.
     *
     * @return Callable
     */
    public function getCallback();

    /**
     * Set the priority.
     *
     * @param integer $priority The priority to set.
     * @return \Wingu\OctopusCore\EventDispatcher\SubscriptionInterface
     * @throws \Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException If the priority is not not numeric.
     */
    public function setPriority($priority);

    /**
     * Get the priority of the subscription.
     *
     * @return integer
     */
    public function getPriority();
}