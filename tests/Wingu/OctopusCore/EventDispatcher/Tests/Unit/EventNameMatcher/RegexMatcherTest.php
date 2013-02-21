<?php

namespace Wingu\OctopusCore\EventDispatcher\Tests\Unit\EventNameMatcher;

use Wingu\OctopusCore\EventDispatcher\Tests\Unit\TestCase;
use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\RegexMatcher;

class RegexMatcherTest extends TestCase {

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
            ['myevent', '/myevent$/', true], ['myevent', '/MYEVENT$/i', true],
            ['myevent', '/myevent2$/', false], ['myevent', '/MYEVENT$/', false],
            ['abcdef', '/def$/', true], ['system.core', '/\bsystem\b/i', true],
            ['system.log.error.53', '/[0-9]/', true],
            ['abcdef', '/^def/', false],

        );
    }

    /**
     * @dataProvider getDataInvalidNameToMatch
     * @expectedException Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException
     */
    public function testInvalidArgumentExceptionThrownIfNameToMatchIsNotSctring($pattern) {
        $matcher = new RegexMatcher($pattern);
    }

    /**
     * @dataProvider getDataMatch
     */
    public function testMatch($event, $pattern, $match) {
        $event = $this->getEventMock($event);
        $matcher = new RegexMatcher($pattern);
        $this->assertSame($match, $matcher->match($event));
    }

    /**
     * @dataProvider getDataMatch
     */
    public function testGetHash($event, $pattern) {
    	$matcher = new RegexMatcher($pattern);
    	$this->assertSame($pattern, $matcher->getHash());
    }
}