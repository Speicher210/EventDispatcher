<?php

namespace Wingu\OctopusCore\EventDispatcher\Tests\Unit\EventNameMatcher;

use Wingu\OctopusCore\EventDispatcher\Tests\Unit\TestCase;
use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherFactory;

class EventNameMatcherFactoryTest extends TestCase {

    public function getDataPatterns() {
        return array(
            [EventNameMatcherFactory::EVENT_NAME_ALL, 'AllNamesMatcher'],
            ['/^def/', 'RegexMatcher'], ['#/.+/[a-zA-Z]*#', 'RegexMatcher'], ['/^event(.*)/i', 'RegexMatcher'], ['/\\\\n/', 'RegexMatcher'],
            ['/[\x{0600}-\x{06FF}\x]{1,32}/u', 'RegexMatcher'], ['/(?P<name>\w+): (?P<digit>\d+)/', 'RegexMatcher'], ['/[0-9]+/', 'RegexMatcher'],
            ['system.*', 'WildcardMatcher'], ['system.*.error', 'WildcardMatcher'], ['system#', 'WildcardMatcher'], ['system.*.error.#', 'WildcardMatcher'],
            ['system', 'NameMatcher'], ['system.log', 'NameMatcher'], ['system-error', 'NameMatcher'], ['system(error)', 'NameMatcher']
        );
    }

    /**
     * @dataProvider getDataPatterns
     */
    public function testGetEventNameMatcher($pattern, $expectedInstance) {
        $actual = EventNameMatcherFactory::getEventNameMatcher($pattern);
        $this->assertInstanceOf('Wingu\OctopusCore\EventDispatcher\EventNameMatcher\\'.$expectedInstance, $actual);
    }

    public function testGetEventNameMatcherInstancePattern() {
        $pattern = $this->getMock('Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface');
        $actual = EventNameMatcherFactory::getEventNameMatcher($pattern);
        $this->assertSame($pattern, $actual);
    }

    public function getDataInvalidPattern() {
        return array(
            [array()], [null], [123], [new \stdClass()], [STDIN]
        );
    }

    /**
     * @dataProvider getDataInvalidPattern
     * @expectedException Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException
     */
    public function testGetEventNameMatcherInstancePatternThrowsException($pattern) {
        EventNameMatcherFactory::getEventNameMatcher($pattern);
    }
}