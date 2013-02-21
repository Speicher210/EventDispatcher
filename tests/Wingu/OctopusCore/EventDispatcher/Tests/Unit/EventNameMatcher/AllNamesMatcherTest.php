<?php

namespace Wingu\OctopusCore\EventDispatcher\Tests\Unit\EventNameMatcher;

use Wingu\OctopusCore\EventDispatcher\Tests\Unit\TestCase;
use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\AllNamesMatcher;

class AllNamesMatcherTest extends TestCase {

    protected function getEventMock() {
        return $this->getMock('\Wingu\OctopusCore\EventDispatcher\EventInterface');
    }

    public function getDataMatch() {
        return array(
            ['myevent'], ['MYEVENT'], ['system.log'], ['system.*.log'],
            [true], [false], [null], [new \stdClass()], [array(1,2,3)], [654321],
        );
    }

    /**
     * @dataProvider getDataMatch
     */
    public function testMatch($event) {
        $event = $this->getEventMock();
        $matcher = new AllNamesMatcher();
        $this->assertTrue($matcher->match($event));
    }

    /**
     * @dataProvider getDataMatch
     */
    public function testMatchByName($eventName) {
        $matcher = new AllNamesMatcher();
        $this->assertTrue($matcher->matchByName($eventName));
    }

    public function testGetHash() {
        $matcher = new AllNamesMatcher();
        $this->assertSame(get_class($matcher), $matcher->getHash());
    }
}