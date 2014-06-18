<?php

namespace Wingu\OctopusCore\EventDispatcher\EventNameMatcher;

use Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException;

/**
 * Factory for event name matchers.
 *
 * The order of detection is: EventNameMatcherInterface, AllNamesMatcher, RegexMatcher, WildcardMatcher, NameMatcher.
 */
class EventNameMatcherFactory {

    const EVENT_NAME_ALL = '*#*#*__ALL__*#*#*';

    /**
     * Get an event name matcher based on a pattern.
     *
     * @param string $pattern The pattern to use for detection.
     * @return \Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface
     * @throws \Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException If the pattern is not valid.
     */
    public static function getEventNameMatcher($pattern) {
        // If the pattern is already an EventNameMatcher
        if ($pattern instanceof EventNameMatcherInterface) {
            return $pattern;
        }

        if (is_string($pattern) === false) {
            throw new InvalidArgumentException('The pattern must be a string or an instance of EventNameMatcherInterface.');
        }

        // If it should match all events.
        if ($pattern === self::EVENT_NAME_ALL) {
            return new AllNamesMatcher();
        }

        // If the pattern is regex.
        if (preg_match('#/.+/[a-zA-Z]*#', $pattern) === 1) {
            return new RegexMatcher($pattern);
        }

        // If the pattern has wildcards.
        if (strpos($pattern, '*') !== false || strpos($pattern, '#') !== false) {
            return new WildcardMatcher($pattern);
        }

        // Fallback on the name matcher.
        return new NameMatcher($pattern);
    }
}