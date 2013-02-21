<?php

namespace Wingu\OctopusCore\EventDispatcher;

use Wingu\OctopusCore\EventDispatcher\EventInterface;
use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface;
use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherFactory;

/**
 * Event dispatcher.
 */
class EventDispatcher implements EventDispatcherInterface {

    /**
     * The event subscriptions.
     *
     * @var \SplObjectStorage
     */
    protected $subscriptions;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->subscriptions = new \SplObjectStorage();
    }

    /**
     * Raise an event.
     *
     * @param string $eventName The event name.
     * @param \Wingu\OctopusCore\EventDispatcher\EventInterface $event The event to raise.
     * @return \Wingu\OctopusCore\EventDispatcher\ResponseCollectionInterface
     */
    public function raiseEvent($eventName, EventInterface $event) {
        return $this->raiseEventUntil($eventName, $event, null);
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
        $event->setName($eventName);
        $responseCollection = new ResponseCollection($event);

        $eventSubscriptions = $this->getEventSubscriptions($eventName);
        foreach ($eventSubscriptions as $subscription) {
            $responseCollection->push($subscription($event));
            if ($callback !== null && call_user_func($callback) === false) {
                break;
            }

            if ($event->isPropagationStopped() === true) {
                break;
            }
        }

        return $responseCollection;
    }

    /**
     * Add a subscription.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\SubscriptionInterface $subscription The subscription to add.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function addSubscription(SubscriptionInterface $subscription) {
        $this->subscriptions->attach($subscription, $subscription->getPriority());
        return $this;
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
        $subscription = new Subscription($eventNameMatcher, $callback, $priority);
        return $this->addSubscription($subscription);
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
        $eventNameMatcher = EventNameMatcherFactory::getEventNameMatcher($eventName);
        return $this->subscribe($eventNameMatcher, $callback, $priority);
    }

    /**
     * Add an event subscriber.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventSubscriberInterface $subscriber The subscriber.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function addSubscriber(EventSubscriberInterface $subscriber) {
        foreach ($subscriber->getSubscriptions() as $subscription) {
            $this->addSubscription($subscription);
        }

        return $this;
    }

    /**
     * Add a subscription.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\SubscriptionInterface $subscription The subscription to remove.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function removeSubscription(SubscriptionInterface $subscription) {
        if ($this->subscriptions->contains($subscription) === true) {
            $this->subscriptions->detach($subscription);
            return $this;
        } else {
            return $this->unsubscribe($subscription->getEventNameMatcher(), $subscription->getCallback());
        }
    }

    /**
     * Unsubscribe from an event.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface $eventNameMatcher The event name matcher.
     * @param Callable $callback The callback to unsubscribe.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function unsubscribe(EventNameMatcherInterface $eventNameMatcher, Callable $callback) {
        foreach ($this->subscriptions as $subscription) {
            if ($eventNameMatcher->getHash() === $subscription->getEventNameMatcher()->getHash() && $callback === $subscription->getCallback()) {
                $this->subscriptions->detach($subscription);
            }
        }

        return $this;
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
        $eventNameMatcher = EventNameMatcherFactory::getEventNameMatcher($eventName);
        return $this->unsubscribe($eventNameMatcher, $callback);
    }

    /**
     * Remove an event subscriber.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventSubscriberInterface $subscriber The subscriber to remove.
     * @return \Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber) {
        foreach ($subscriber->getSubscriptions() as $subscription) {
            $this->unsubscribe($subscription->getEventNameMatcher(), $subscription->getCallback());
        }
    }

    /**
     * Get all the subscriptions.
     *
     * @return \Iterator
     */
    public function getSubscriptions() {
        return $this->subscriptions;
    }

    /**
     * Get all event subscriptions for an event.
     *
     * @param string $eventName The event name.
     * @return \Iterator
     */
    public function getEventSubscriptions($eventName) {
        $subscriptions = new \SplPriorityQueue();

        foreach ($this->subscriptions as $subscription) {
            if ($subscription->getEventNameMatcher()->matchByName($eventName) === true) {
                $subscriptions->insert($subscription, $subscription->getPriority());
            }
        }

        return $subscriptions;
    }

    /**
     * Check if there are subscriptions.
     *
     * @return boolean
     */
    public function hasSubscriptions() {
        return $this->subscriptions->count() > 0;
    }

    /**
     * Check if an event has subscriptions.
     *
     * @param string $eventName The name of the event.
     * @return boolean
     */
    public function hasEventSubscriptions($eventName) {
        foreach ($this->subscriptions as $subscription) {
            if ($subscription->getEventNameMatcher()->matchByName($eventName) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * The subscriptions can not be serialized.
     *
     * @return array
     */
    public function __sleep() {
        return [];
    }

    /**
     * Recreate the subscriptions storage.
     */
    public function __wakeup() {
        $this->subscriptions = new \SplObjectStorage();
    }
}