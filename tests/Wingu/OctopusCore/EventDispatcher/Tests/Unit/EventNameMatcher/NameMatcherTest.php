<?php

namespace Wingu\OctopusCore\EventDispatcher\Tests\Unit\EventNameMatcher;

use Wingu\OctopusCore\EventDispatcher\Tests\Unit\TestCase;
use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\NameMatcher;

class NameMatcherTest extends TestCase {

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
            ['system.log', 'system.log', true], ['system.log.error', 'system.log.ERROR', true],
            ['myevent', 'myevent2', false], ['myevent', 'MYEVENT2', false],
        );
    }

    /**
     * @dataProvider getDataInvalidNameToMatch
     * @expectedException Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException
     */
    public function testInvalidArgumentExceptionThrownIfNameToMatchIsNotSctring($eventName) {
        $matcher = new NameMatcher($eventName);
    }

    /**
     * @dataProvider getDataMatch
     */
    public function testMatch($event, $eventName, $match) {
        $event = $this->getEventMock($event);
        $matcher = new NameMatcher($eventName);
        $this->assertSame($match, $matcher->match($event));
    }

    /**
     * @dataProvider getDataMatch
     */
    public function testGetHash($eventName) {
    	$matcher = new NameMatcher($eventName);
    	$this->assertSame($eventName, $matcher->getHash());
    }
}