<?php

namespace Wingu\OctopusCore\EventDispatcher\Tests\Unit;

use Wingu\OctopusCore\EventDispatcher\ExceptionEvent;

class ExceptionEventTest extends TestCase {

    public function testGetException() {
        $exception = $this->getMock('Exception');
        $event = new ExceptionEvent(null, $exception);
        $this->assertSame($exception, $event->getException());
    }
}