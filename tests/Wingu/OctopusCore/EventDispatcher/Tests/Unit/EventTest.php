<?php

namespace Wingu\OctopusCore\EventDispatcher\Tests\Unit;

use Wingu\OctopusCore\EventDispatcher\Event;

class EventTest extends TestCase {

    public function getDataEventNames() {
        return array(
            ['', ''], [null, ''],
            ['myEvent', 'myEvent'], ['system.log.db', 'system.log.db']
        );
    }

    /**
     * @dataProvider getDataEventNames
     */
    public function testToString($eventName, $expected) {
        $event = new Event(null);
        $event->setName($eventName);
        $this->assertSame($expected, (string)$event);
    }

    public function testStopPropagation() {
        $event = new Event(null);

        $this->assertFalse($event->isPropagationStopped());

        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }

    public function getDataSender() {
        return array(
            [null],[1],['some string'],[new \stdClass()],[$this],[STDIN],[array(1,2,3)]
        );
    }

    /**
     * @dataProvider getDataSender
     */
    public function testSetGetSender($sender) {
        $event = new Event($sender);
        $this->assertSame($sender, $event->getSender());
    }

    public function testGetArgumentsDefaultValue() {
        $args = [];
        $event = new Event(null, $args);

        $this->assertNull($event->getArgument('inexistent_arg'));
        $this->assertTrue($event->getArgument('inexistent_arg', true));
        $this->assertFalse($event->getArgument('inexistent_arg', false));
        $this->assertSame('some_string', $event->getArgument('inexistent_arg', 'some_string'));
        $this->assertSame(STDERR, $event->getArgument('inexistent_arg', STDERR));
        $this->assertSame(array(), $event->getArgument('inexistent_arg', array()));
        $this->assertSame(array(1,2,[]), $event->getArgument('inexistent_arg', array(1,2,[])));
        $this->assertSame(123, $event->getArgument('inexistent_arg', 123));

        $this->assertFalse(isset($event['inexistent_arg']));
    }

    public function testGetArguments() {
        $stdClass = new \stdClass();
        $args = array(
            'null' => null,
            'integer' => 123,
            'float' => -123.456,
            'array' => [1,2,3],
            'obj' => $stdClass,
            'string' => 'some_string',
            'false' => false,
            'true' => true,
        );
        $event = new Event(null, $args);

        $this->assertNull($event->getArgument('null'));
        $this->assertNull($event->getArgument('null', true));
        $this->assertSame(123, $event->getArgument('integer'));
        $this->assertSame(-123.456, $event->getArgument('float'));
        $this->assertSame([1,2,3], $event->getArgument('array'));
        $this->assertSame($stdClass, $event->getArgument('obj'));
        $this->assertFalse($event->getArgument('false'));
        $this->assertFalse($event->getArgument('false', true));
        $this->assertTrue($event->getArgument('true'));
        $this->assertTrue($event->getArgument('true'), false);

        $this->assertSame($args, $event->getArguments());
    }

    public function getDataSetArgument() {
        return array(
            ['null', null], ['integer', 123], ['float', -123.456], ['array', [1,2,3]],
            ['obj', new \stdClass()], ['string', 'some_string'], ['false', false], ['true', true]
        );
    }

    /**
     * @dataProvider getDataSetArgument
     */
    public function testSetArguments($name, $value) {
        $event = new Event(null);

        $event->setArgument($name, $value);
        $this->assertSame($value, $event->getArgument($name));
    }
}