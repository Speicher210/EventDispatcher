<?php

namespace Wingu\OctopusCore\EventDispatcher;

/**
 * Interface for an event subscriber.
 *
 * It can be used to define multiple subscription and add them in bulk.
 */
interface EventSubscriberInterface {

    /**
     * Get all the event subscriptions.
     *
     * @return \Wingu\OctopusCore\EventDispatcher\SubscriptionInterface[]
     */
    public function getSubscriptions();
}