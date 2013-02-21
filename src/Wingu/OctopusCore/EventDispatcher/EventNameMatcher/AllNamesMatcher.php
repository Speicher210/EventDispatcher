<?php

namespace Wingu\OctopusCore\EventDispatcher\EventNameMatcher;

use Wingu\OctopusCore\EventDispatcher\EventInterface;

/**
 * Event name matcher that mathces all events.
 */
class AllNamesMatcher implements EventNameMatcherInterface {

    /**
     * Check if an event matches.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventInterface $event The raised event.
     * @return boolean
     */
    public function match(EventInterface $event) {
        return true;
    }

    /**
     * Check if an event name matches.
     *
     * @param string $eventName The event name to match.
     * @return boolean
     */
    public function matchByName($eventName) {
        return true;
    }

    /**
     * Get a hash of the name matcher.
     *
     * @return string
     */
    public function getHash() {
        return __CLASS__;
    }
}