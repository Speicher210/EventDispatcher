<?php

namespace Wingu\OctopusCore\EventDispatcher;

use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface;

/**
 * Trait to enable any object to dispatch events.
 */
trait EventDispatcherTrait {

    /**
     * The event dispatcher.
     *
     * @var \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Set the event dispatcher trait.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface $eventDispatcher The dispatcher to set.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherTrait
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher) {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    /**
     * Get the event dispatcher.
     *
     * If no event dispatcher was set a new one will be instantiated.
     *
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function getEventDispatcher() {
        if ($this->eventDispatcher === null) {
            $this->eventDispatcher = new EventDispatcher();
        }

        return $this->eventDispatcher;
    }

    /**
     * Raise an event.
     *
     * @param string $eventName The event name.
     * @param \Wingu\OctopusCore\EventDispatcher\EventInterface $event The event to raise.
     * @return \Wingu\OctopusCore\EventDispatcher\ResponseCollectionInterface
     */
    public function raiseEvent($eventName, EventInterface $event) {
        return $this->getEventDispatcher()->raiseEvent($eventName, $event);
    }

    /**
     * Raise an event for each subscriber and run the callback after each subscriber.
     *
     * The callback can stop the event propagation.
     *
     * @param string $eventName The event name.
     * @param \Wingu\OctopusCore\EventDispatcher\EventInterface $event The event to raise.
     * @param Callable $callback A callable to call after each event subscriber.
     * @return \Wingu\OctopusCore\EventDispatcher\ResponseCollectionInterface
     */
    public function raiseEventUntil($eventName, EventInterface $event, Callable $callback = null) {
        return $this->getEventDispatcher()->raiseEventUntil($eventName, $event, $callback);
    }

    /**
     * Add a subscription.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\SubscriptionInterface $subscription The subscription to add.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function addSubscription(SubscriptionInterface $subscription) {
        return $this->getEventDispatcher()->addSubscription($subscription);
    }

    /**
     * Subscribe to an event.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface $eventNameMatcher The event name matcher.
     * @param Callable $callback The callback to run.
     * @param integer $priority The priority.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function subscribe(EventNameMatcherInterface $eventNameMatcher, Callable $callback, $priority = 0) {
        return $this->getEventDispatcher()->subscribe($eventNameMatcher, $callback, $priority);
    }

    /**
     * Subscribe to an event.
     *
     * The event name matcher will be constructed by auto detection.
     *
     * @param mixed $eventName The event name to subscribe to.
     * @param Callable $callback The callback to run.
     * @param integer $priority The priority.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function on($eventName, Callable $callback, $priority = 0) {
        return $this->getEventDispatcher()->on($eventName, $callback, $priority);
    }

    /**
     * Add an event subscriber.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventSubscriberInterface $subscriber The subscriber.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function addSubscriber(EventSubscriberInterface $subscriber) {
        return $this->getEventDispatcher()->addSubscriber($subscriber);
    }

    /**
     * Unsubscribe from an event.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface $eventNameMatcher The event name matcher.
     * @param Callable $callback The callback to unsubscribe.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function unsubscribe(EventNameMatcherInterface $eventNameMatcher, Callable $callback) {
        return $this->getEventDispatcher()->unsubscribe($eventNameMatcher, $callback);
    }

    /**
     * Unsubscribe from an event.
     *
     * The event name matcher will be constructed by auto detection.
     *
     * @param mixed $eventName The event name to unsubscribe from.
     * @param Callable $callback The callback to unsubscribe.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function off($eventName, Callable $callback) {
        return $this->getEventDispatcher()->off($eventName, $callback);
    }

    /**
     * Remove an event subscriber.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventSubscriberInterface $subscriber The subscriber to remove.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber) {
        return $this->getEventDispatcher()->removeSubscriber($subscriber);
    }

    /**
     * Get all the subscriptions.
     *
     * @return \Iterator
     */
    public function getSubscriptions() {
        return $this->getEventDispatcher()->getSubscriptions();
    }

    /**
     * Get all event subscriptions for an event.
     *
     * @param string $eventName The event name.
     * @return \Iterator
     */
    public function getEventSubscriptions($eventName) {
        return $this->getEventDispatcher()->getEventSubscriptions($eventName);
    }

    /**
     * Check if there are subscriptions.
     *
     * @return boolean
     */
    public function hasSubscriptions() {
        return $this->getEventDispatcher()->hasSubscriptions();
    }

    /**
     * Check if an event has subscriptions.
     *
     * @param string $eventName The name of the event.
     * @return boolean
     */
    public function hasEventSubscriptions($eventName) {
        return $this->getEventDispatcher()->hasEventSubscriptions($eventName);
    }
}