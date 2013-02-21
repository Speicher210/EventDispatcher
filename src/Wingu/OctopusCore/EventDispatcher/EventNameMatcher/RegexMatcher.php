<?php

namespace Wingu\OctopusCore\EventDispatcher\EventNameMatcher;

use Wingu\OctopusCore\EventDispatcher\EventInterface;
use Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException;

/**
 * Event matcher of events with names that match a regular expression.
 */
class RegexMatcher implements EventNameMatcherInterface {

    /**
     * The regular expression to use for matching.
     *
     * @var string
     */
    protected $pattern;

    /**
     * Constructor.
     *
     * @param string $pattern The regular expression to use for matching.
     * @throws \Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException If the pattern is not a string.
     */
    public function __construct($pattern) {
        if (is_string($pattern) === false) {
            throw new InvalidArgumentException('The pattern must be a string.');
        }

        $this->pattern = $pattern;
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
        return (boolean) preg_match($this->pattern, $eventName);
    }

    /**
     * Get a hash of the name matcher.
     *
     * @return string
     */
    public function getHash() {
        return $this->pattern;
    }
}