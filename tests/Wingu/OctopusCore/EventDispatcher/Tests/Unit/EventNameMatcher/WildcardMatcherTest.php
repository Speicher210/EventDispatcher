<?php

namespace Wingu\OctopusCore\EventDispatcher\Tests\Unit\EventNameMatcher;

use Wingu\OctopusCore\EventDispatcher\Tests\Unit\TestCase;
use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\WildcardMatcher;

class WildcardMatcherTest extends TestCase {

    protected function getEventMock($eventName) {
        $mock = $this->getMock('\Wingu\OctopusCore\EventDispatcher\EventInterface');
        $mock->expects($this->any())->method('getName')->will($this->returnValue($eventName));
        return $mock;
    }

    public function getDataInvalidNameToMatch() {
        return array(
            [1], [new \stdClass()], [STDIN]
        );
    }

    public function getDataMatch() {
        return array(
            ['myevent', 'myevent', true], ['myevent', 'MYEVENT', true],
            ['system.log', 'system.#', true], ['system.db', 'system.#', true], ['system.log.error', 'system.#', false],
            ['system.log.error', 'system.#.error', true], ['system.db.error', 'system.#.error', true],
            ['system.log.error.info', 'system.#.error', false], ['system.log.test.error.', 'system.#.error', false],
            ['system.log', 'system.*', true], ['system.db', 'system.*', true], ['system.log.error', 'system.*', true],
            ['system.log.error_2', 'system.#.error*', true], ['system.db.error', 'system.#.error*', true], ['system.log.error.info', 'system.#.error.*', true],
            ['system.log.test.error.info', 'system.#.error*', false],
            ['myevent', '#', true], ['system.log', '*', true], ['system.log', '#', false],
        );
    }

    /**
     * @dataProvider getDataInvalidNameToMatch
     * @expectedException Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException
     */
    public function testInvalidArgumentExceptionThrownIfNameToMatchIsNotSctring($pattern) {
        $matcher = new WildcardMatcher($pattern);
    }

    /**
     * @dataProvider getDataMatch
     */
    public function testMatch($event, $pattern, $match) {
        $event = $this->getEventMock($event);
        $matcher = new WildcardMatcher($pattern);
        $this->assertSame($match, $matcher->match($event));
    }
}