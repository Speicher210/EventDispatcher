<?php

namespace Wingu\OctopusCore\EventDispatcher;

/**
 * An event for exceptions.
 */
class ExceptionEvent extends Event {

    /**
     * The Exception.
     *
     * @var \Exception
     */
    protected $exception;

    /**
     * Constructor.
     *
     * @param Object $sender The source object that raised this event.
     * @param \Exception $exception The exception that was thrown.
     * @param array $args The arguments of for this event.
     */
    public function __construct($sender, \Exception $exception, array $args = array()) {
        parent::__construct($sender, $args);
        $this->exception = $exception;
    }

    /**
     * Get the exception that was thrown.
     *
     * @return \Exception
     */
    public function getException() {
        return $this->exception;
    }
}