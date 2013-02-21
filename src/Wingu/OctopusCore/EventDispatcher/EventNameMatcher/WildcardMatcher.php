<?php

namespace Wingu\OctopusCore\EventDispatcher\EventNameMatcher;

use Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException;

/**
 * Event name matcher of events with names with wildcards.
 *
 * Use # wildcard to match namespaces of events.
 * Example:
 * system.# will match system.log, system.db but not system.log.error.
 * system.#.error will match system.log.error, system.db.error, etc.
 * system.#.# will match system.log.error, system.db.error but not system.log.error.info.
 *
 * Use * to match anything after it.
 * Example:
 * system.* will match system.log, system.db and also system.log.error.
 *
 * You can also combine them.
 * Example:
 * system.#.error* will match system.log.error, system.db.error, system.log.error.info.
 */
class WildcardMatcher extends RegexMatcher {

    /**
     * The regular expression equivalent for wildcards.
     *
     * @var array
     */
    protected static $replacements = array(
            // Trailing single-wildcard with separator prefix.
            '/\\\\\.#$/' => '(?:\.\w+)?',
            // Single-wildcard with separator prefix.
            '/\\\\\.#/' => '(?:\.\w+)',
            // Single-wildcard without separator prefix.
            '/(?<!\\\\\.)#/' => '(?:\w+)',
            // Multi-wildcard with separator prefix.
            '/\\\\\.\\\\\*/' => '(?:\.\w+)*',
            // Multi-wildcard without separator prefix.
            '/(?<!\\\\\.)\\\\\*/' => '(?:|\w+(?:\.\w+)*)');

    /**
     * Constructor.
     *
     * @param string $pattern The pattern to use for matching.
     * @throws \Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException If the pattern is not a string.
     */
    public function __construct($pattern) {
        if (is_string($pattern) === false) {
            throw new InvalidArgumentException('The pattern must be a string.');
        }

        $pattern = $this->wildcardToRegexPattern($pattern);
        parent::__construct($pattern);
    }

    /**
     * Transform the wiledcard pattern into a regular expression pattern.
     *
     * @param string $pattern The wildcard pattern.
     * @return string
     */
    protected function wildcardToRegexPattern($pattern) {
        $regex = preg_replace(array_keys(self::$replacements), array_values(self::$replacements), preg_quote($pattern, '/'));
        return '/^' . $regex . '$/i';
    }
}