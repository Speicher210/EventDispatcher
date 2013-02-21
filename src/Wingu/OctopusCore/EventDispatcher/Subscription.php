<?php

namespace Wingu\OctopusCore\EventDispatcher;

use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface;
use Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException;

/**
 * Event subscription.
 */
class Subscription implements SubscriptionInterface {

    /**
     * The event name matcher.
     *
     * @var \Wingu\OctopusCore\EventDispatcher\EventNameMatcherInterface
     */
    protected $eventNameMatcher;

    /**
     * The callback.
     *
     * @var Callable
     */
    protected $callback;

    /**
     * The priority.
     *
     * @var integer
     */
    protected $priority = 0;

    /**
     * Constructor.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventNameMatcherInterface $eventNameMatcher The event name matcher.
     * @param Callable $callback The callback.
     * @param integer $priority The priority.
     */
    public function __construct(EventNameMatcherInterface $eventNameMatcher, Callable $callback, $priority = 0) {
        $this->setEventNameMatcher($eventNameMatcher);
        $this->setCallback($callback);
        $this->setPriority($priority);
    }

    /**
     * Set the event name matcher.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventNameMatcherInterface $eventNameMatcher The event name matcher to set.
     * @return \Wingu\OctopusCore\EventDispatcher\SubscriptionInterface
     */
    public function setEventNameMatcher(EventNameMatcherInterface $eventNameMatcher) {
        $this->eventNameMatcher = $eventNameMatcher;
        return $this;
    }

    /**
     * Get the event name to subscribe.
     *
     * @return \Wingu\OctopusCore\EventDispatcher\EventNameMatcher;
     */
    public function getEventNameMatcher() {
        return $this->eventNameMatcher;
    }

    /**
     * Set the priority.
     *
     * @param integer $priority The priority to set.
     * @return \Wingu\OctopusCore\EventDispatcher\SubscriptionInterface
     * @throws \Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException If the priority is not numeric.
     */
    public function setPriority($priority) {
        if (is_numeric($priority) !== true) {
            throw new InvalidArgumentException('The priority must be an integer.');
        }

        $this->priority = $priority;
        return $this;
    }

    /**
     * Get the priority of the subscription.
     *
     * @return integer
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * Set the callback.
     *
     * @param Callable $callback The callback.
     * @return \Wingu\OctopusCore\EventDispatcher\SubscriptionInterface
     */
    public function setCallback(Callable $callback) {
        $this->callback = $callback;
        return $this;
    }

    /**
     * Get the callback for the event.
     *
     * @return Callable
     */
    public function getCallback() {
        return $this->callback;
    }

    /**
     * Magic method for invoking this class.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventInterface $event The raised event.
     * @return mixed
     */
    public function __invoke(EventInterface $event) {
        return call_user_func($this->getCallback(), $event);
    }
}