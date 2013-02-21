<?php

namespace Wingu\OctopusCore\EventDispatcher\Tests\Unit;

class EventDispatcherTraitTest extends TestCase {

    protected function getMockForEventDispatcherTrait() {
        return $this->getObjectForTrait('Wingu\OctopusCore\EventDispatcher\EventDispatcherTrait', [], '', false);
    }

    public function testSetGetEventDispatcher() {
        $trait = $this->getMockForEventDispatcherTrait();
        $this->assertInstanceOf('\Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface', $trait->getEventDispatcher());

        $EventDispatcher = $this->getMock('\Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface');
        $trait->setEventDispatcher($EventDispatcher);
        $this->assertSame($EventDispatcher, $trait->getEventDispatcher());
    }

    public function testProxyMethods() {
        $trait = $this->getMockForEventDispatcherTrait();
        $EventDispatcher = $this->getMock('\Wingu\OctopusCore\EventDispatcher\EventDispatcherInterface');

        // Raise event.
        $eventName = 'testEvent';
        $event = $this->getMock('\Wingu\OctopusCore\EventDispatcher\EventInterface');
        $EventDispatcher->expects($this->once())->method('raiseEvent')->with($eventName, $event)->will($this->returnSelf());

        // Raise event until.
        $callback = function() {};
        $EventDispatcher->expects($this->once())->method('raiseEventUntil')->with($eventName, $event, $callback)->will($this->returnSelf());

        // Add subscription.
        $subscription = $this->getMock('\Wingu\OctopusCore\EventDispatcher\SubscriptionInterface');
        $EventDispatcher->expects($this->once())->method('addSubscription')->with($subscription)->will($this->returnSelf());

        // Subscribe.
        $eventNameMatcher = $this->getMock('\Wingu\OctopusCore\EventDispatcher\EventNameMatcher\EventNameMatcherInterface');
        $EventDispatcher->expects($this->once())->method('subscribe')->with($eventNameMatcher, $callback, 27)->will($this->returnSelf());

        // On.
        $EventDispatcher->expects($this->once())->method('on')->with($eventName, $callback, 27)->will($this->returnSelf());

        // Add subscriber.
        $subscriber = $this->getMock('\Wingu\OctopusCore\EventDispatcher\EventSubscriberInterface');
        $EventDispatcher->expects($this->once())->method('addSubscriber')->with($subscriber)->will($this->returnSelf());

        // Unsubscribe.
        $EventDispatcher->expects($this->once())->method('unsubscribe')->with($eventNameMatcher, $callback)->will($this->returnSelf());

        // Off.
        $EventDispatcher->expects($this->once())->method('off')->with($eventName, $callback)->will($this->returnSelf());

        // Remove subscriber.
        $EventDispatcher->expects($this->once())->method('removeSubscriber')->with($subscriber)->will($this->returnSelf());

        // Get subscriptions.
        $EventDispatcher->expects($this->once())->method('getSubscriptions')->will($this->returnValue('getSubscriptions'));

        // Get event subscriptions.
        $EventDispatcher->expects($this->once())->method('getEventSubscriptions')->with($eventName)->will($this->returnValue('getEventSubscriptions'));

        // Has subscriptions.
        $EventDispatcher->expects($this->once())->method('hasSubscriptions')->will($this->returnValue(false));

        // Has event subscriptions.
        $EventDispatcher->expects($this->once())->method('hasEventSubscriptions')->with($eventName)->will($this->returnValue(false));

        $this->assertSame($trait, $trait->setEventDispatcher($EventDispatcher));
        $this->assertSame($EventDispatcher, $trait->raiseEvent($eventName, $event));
        $this->assertSame($EventDispatcher, $trait->raiseEventUntil($eventName, $event, $callback));
        $this->assertSame($EventDispatcher, $trait->addSubscription($subscription));
        $this->assertSame($EventDispatcher, $trait->subscribe($eventNameMatcher, $callback, 27));
        $this->assertSame($EventDispatcher, $trait->on($eventName, $callback, 27));
        $this->assertSame($EventDispatcher, $trait->addSubscriber($subscriber));
        $this->assertSame($EventDispatcher, $trait->unsubscribe($eventNameMatcher, $callback));
        $this->assertSame($EventDispatcher, $trait->off($eventName, $callback));
        $this->assertSame($EventDispatcher, $trait->removeSubscriber($subscriber));
        $this->assertSame('getSubscriptions', $trait->getSubscriptions());
        $this->assertSame('getEventSubscriptions', $trait->getEventSubscriptions($eventName));
        $this->assertFalse($trait->hasSubscriptions());
        $this->assertFalse($trait->hasEventSubscriptions($eventName));
    }
}