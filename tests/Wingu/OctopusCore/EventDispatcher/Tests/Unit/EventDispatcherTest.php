<?php

namespace Wingu\OctopusCore\EventDispatcher\Tests\Unit;

use Wingu\OctopusCore\EventDispatcher\EventDispatcher;

class EventDispatcherTest extends TestCase {

    protected function getMockSubscription($match, $priority) {
        $matcher = $this->getMock('Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface');
        $matcher->expects($this->any())->method('matchByName')->will($this->returnValue($match));

        $mock = $this->getMock('Wingu\OctopusCore\EventDispatcher\SubscriptionInterface');
        $mock->expects($this->any())->method('getPriority')->will($this->returnValue($priority));
        $mock->expects($this->any())->method('getEventNameMatcher')->will($this->returnValue($matcher));
        return $mock;
    }

    public function testHasSubscriptions() {
        $ed = new EventDispatcher();

        $this->assertFalse($ed->hasSubscriptions());
    }

    public function testGetEventSubscriptions() {
        $ed = new EventDispatcher();

        $ed->addSubscription($this->getMockSubscription(true, 0));
        $ed->addSubscription($this->getMockSubscription(true, 1));
        $ed->addSubscription($this->getMockSubscription(true, 0));

        $this->assertCount(3, $ed->getEventSubscriptions('test'));
    }

    public function testRemoveSubscription() {
        $ed = new EventDispatcher();
        $subscription1 = $this->getMockSubscription(true, 0);
        $subscription2 = $this->getMockSubscription(true, 0);

        $ed->addSubscription($subscription1);
        $ed->addSubscription($subscription2);
        $this->assertCount(2, $ed->getEventSubscriptions('test'));

        $ed->removeSubscription($subscription1);
        $this->assertCount(1, $ed->getEventSubscriptions('test'));

        $ed->removeSubscription($subscription2);
        $this->assertCount(0, $ed->getEventSubscriptions('test'));
    }

    public function testSerialization() {
        $ed = new EventDispatcher();

        $subscription1 = $this->getMockSubscription(true, 0);
        $subscription2 = $this->getMockSubscription(true, 0);

        $ed->addSubscription($subscription1);
        $ed->addSubscription($subscription2);

        $this->assertTrue($ed->hasSubscriptions());

        $serialize = serialize($ed);
        $this->assertSame('O:49:"Wingu\OctopusCore\EventDispatcher\EventDispatcher":0:{}', $serialize);

        $ed = unserialize($serialize);
        $this->assertFalse($ed->hasSubscriptions());
    }
}