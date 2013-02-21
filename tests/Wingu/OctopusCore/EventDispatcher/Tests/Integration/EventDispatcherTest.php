<?php

namespace Wingu\OctopusCore\EventDispatcher\Tests\Integration;

use Wingu\OctopusCore\EventDispatcher\EventDispatcher;
use Wingu\OctopusCore\EventDispatcher\EventInterface;
use Wingu\OctopusCore\EventDispatcher\Event;
use Wingu\OctopusCore\EventDispatcher\Subscription;
use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\AllNamesMatcher;
use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\NameMatcher;
use Wingu\OctopusCore\EventDispatcher\EventNameMatcher\WildcardMatcher;

class EventDispatcherTest extends TestCase {

    public function testRaiseEventNoSubscriptions() {
        $ed = new EventDispatcher();
        $event = new Event(null);
        $response = $ed->raiseEvent('test', $event);

        $this->assertFalse($ed->hasEventSubscriptions('test'));
        $this->assertCount(0, $response);
        $this->assertSame($event, $response->getRaisedEvent());
    }

    public function testRaiseEventUntilCallbackCalledAfterEvent() {
        $ed = new EventDispatcher();
        $event = new Event(null);

        $callbackCalled = false;
        $untilCallback = function () use (&$callbackCalled) {
        	$callbackCalled = true;
        };

        $ed->on('test', function(EventInterface $event) use ($callbackCalled) {
        	$event->stopPropagation();
            $this->assertFalse($callbackCalled);
        });

        $ed->raiseEventUntil('test', $event, $untilCallback);

        $this->assertTrue($callbackCalled);
    }

    public function testRaiseEventUntilCallbackStops() {
        $ed = new EventDispatcher();
        $event = new Event(null);

        $untilCallback = function () {
            return false;
        };

        $ed->on('test', function(EventInterface $event) {});
        $ed->on('test', function(EventInterface $event) {
            $this->fail('Callback returning FALSE did not stop the event.');
        });

        $ed->raiseEventUntil('test', $event, $untilCallback);
    }

    public function testStopEventPropagation() {
        $ed = new EventDispatcher();
        $event = new Event(null);

        $responseReturn1 = new \stdClass();
        $ed->on('test', function(EventInterface $event) use ($responseReturn1) {
            $event->stopPropagation();
            return $responseReturn1;
        });
        $ed->on('test', function() {
            $this->fail('Event propagation did not stop event handler trigger.');
        });
        $ed->on('test2', function() {
            $this->fail('Event should have not fired.');
        });

        $this->assertSame(3, count($ed->getSubscriptions()));
        $this->assertTrue($ed->hasEventSubscriptions('test'));
        $this->assertSame(2, count($ed->getEventSubscriptions('test')));

        $response = $ed->raiseEvent('test', $event);

        $this->assertCount(1, $response);
        $this->assertSame($event, $response->getRaisedEvent());
        $this->assertSame($responseReturn1, $response[0]);
    }

    public function testEventUnsubscribing() {
        $ed = new EventDispatcher();
        $event = new Event(null);
        $handler = function() {
        	$this->fail('Event unsubscribing failed.');
        };
        $ed->on('test', $handler);
        $ed->off('test', $handler);

        $ed->raiseEvent('test', $event);
    }

    public function testRemoveSubscription() {
        $ed = new EventDispatcher();

        $handler1 = function() {};
        $handler2 = function() {};
        $subscription1 = new Subscription(new NameMatcher('test'), $handler1);
        $subscription2 = new Subscription(new AllNamesMatcher(), $handler2);

        $ed->addSubscription($subscription1);
        $ed->addSubscription($subscription2);

        $this->assertCount(2, $ed->getEventSubscriptions('test'));

        $ed->removeSubscription($subscription1);
        $this->assertCount(1, $ed->getEventSubscriptions('test'));

        $ed->removeSubscription(new Subscription(new AllNamesMatcher(), $handler2));
        $this->assertCount(0, $ed->getEventSubscriptions('test'));
    }

    public function testAddSubscriber() {
        $ed = new EventDispatcher();

        $handler = function() {};
        $subscription1 = new Subscription(new NameMatcher('test'), $handler);
        $subscription2 = new Subscription(new NameMatcher('test'), $handler);

        $subscriber = $this->getMock('Wingu\OctopusCore\EventDispatcher\EventSubscriberInterface');
        $subscriber->expects($this->any())->method('getSubscriptions')->will($this->returnValue([$subscription1, $subscription2]));

        $ed->addSubscriber($subscriber);

        $this->assertCount(2, $ed->getEventSubscriptions('test'));

        $ed->removeSubscriber($subscriber);
        $this->assertFalse($ed->hasSubscriptions());
    }
}