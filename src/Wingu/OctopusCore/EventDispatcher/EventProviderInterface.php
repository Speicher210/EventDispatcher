<?php

namespace Wingu\OctopusCore\EventDispatcher;

/**
 * Interface for objects that provide events.
 */
interface EventProviderInterface {

    /**
     * Get the event names provided.
     *
     * @return array
     */
    public function getEventNames();
}