<?php

namespace Wingu\OctopusCore\EventDispatcher\Tests\Unit;

class SubscriptionTest extends TestCase {

    public function getMockSubscription() {
        return $this->getMockBuilder('\Wingu\OctopusCore\EventDispatcher\Subscription')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
    }

    public function getDataSetPriorityInvalid() {
        return array(
            [' '], [''], ['a'], [array()], [new \stdClass()], [STDIN], ['4 4']
        );
    }

    /**
     * @dataProvider getDataSetPriorityInvalid
     * @expectedException \Wingu\OctopusCore\EventDispatcher\Exceptions\InvalidArgumentException
     */
    public function testSetPriorityThrowsExceptionIfNotInteger($priority) {
        $subscription = $this->getMockSubscription();
        $subscription->setPriority($priority);
    }

    public function getDataSetPriority() {
    	return array(
			[1], [-1], [0], ['27'], [INF], [0xFF], ['1e4'], [4.27]
    	);
    }

    /**
     * @dataProvider getDataSetPriority
     */
    public function testSetPriority($priority) {
        $subscription = $this->getMockSubscription();
        $subscription->setPriority($priority);
        $this->assertSame($priority, $subscription->getPriority());
    }
}