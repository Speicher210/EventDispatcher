<?php

namespace Wingu\OctopusCore\EventDispatcher\EventNameMatcher;

use Wingu\OctopusCore\EventDispatcher\EventInterface;
use Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException;

/**
 * Event name matcher of events with a specific name.
 */
class NameMatcher implements EventNameMatcherInterface {

    /**
     * The event name to listen to.
     *
     * @var string
     */
    protected $eventName;

    /**
     * Constructor.
     *
     * @param string $eventName The event name to match to.
     * @throws \Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException If the pattern is not a string.
     */
    public function __construct($eventName) {
        if (is_string($eventName) === false) {
            throw new InvalidArgumentException('The name of the event to match to must be a string.');
        }

        $this->eventName = $eventName;
    }

    /**
     * Check if an event matches.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventInterface $event The raised event.
     * @return boolean
     */
    public function match(EventInterface $event) {
        return $this->matchByName($event->getName());
    }

    /**
     * Check if an event name matches.
     *
     * @param string $eventName The event name to match.
     * @return boolean
     */
    public function matchByName($eventName) {
        return strcasecmp($this->eventName, $eventName) === 0;
    }

    /**
     * Get a hash of the name matcher.
     *
     * @return string
     */
    public function getHash() {
        return $this->eventName;
    }
}