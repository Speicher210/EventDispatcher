<?php

namespace Wingu\OctopusCore\EventDispatcher;

/**
 * Interface for an event.
 */
interface EventInterface extends \ArrayAccess, \Iterator {

    /**
     * Set the source object that raised this event.
     *
     * @param Object $sender The source object that raised this event.
     * @return \Wingu\OctopusCore\EventDispatcher\EventInterface
     */
    public function setSender($sender);

    /**
     * Get the event sender.
     *
     * @return Object
     */
    public function getSender();

    /**
     * Set the name of the event.
     *
     * @param string $name The event name.
     * @return \Wingu\OctopusCore\EventDispatcher\EventInterface
     */
    public function setName($name);

    /**
     * Get the name of the event.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the arguments for this event.
     *
     * @return array
     */
    public function getArguments();

    /**
     * Get the value of an argument of the event.
     *
     * @param string $name The name of the argument.
     * @param mixed $default The default value if parameter is not found.
     * @return mixed
     */
    public function getArgument($name, $default = null);

    /**
     * Stop the event propagation.
     *
     * @return \Wingu\OctopusCore\EventDispatcher\EventInterface
     */
    public function stopPropagation();

    /**
     * Check if propagation is stopped.
     *
     * @return boolean
     */
    public function isPropagationStopped();
}