<?php

namespace Wingu\OctopusCore\EventDispatcher;

/**
 * Generic event.
 */
class Event extends \ArrayIterator implements EventInterface {

    /**
     * The source object that raised this event.
     *
     * @var object
     */
    protected $sender;

    /**
     * The name of the event.
     *
     * @var string
     */
    protected $name;

    /**
     * Flag if the propagation of the event is stopped.
     *
     * @var boolean
     */
    protected $propagationStopped = false;

    /**
     * Constructor.
     *
     * @param Object $sender The source object that raised this event.
     * @param array $args The arguments of for this event.
     */
    public function __construct($sender, array $args = array()) {
        $this->setSender($sender);
        parent::__construct($args);
    }

    /**
     * Set the source object that raised this event.
     *
     * @param Object $sender The source object that raised this event.
     * @return \Wingu\OctopusCore\EventDispatcher\EventInterface
     */
    public function setSender($sender) {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Get the event sender.
     *
     * @return Object
     */
    public function getSender() {
        return $this->sender;
    }

    /**
     * Set the name of the event.
     *
     * @param string $name The event name.
     * @return \Wingu\OctopusCore\EventDispatcher\EventInterface
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the name of the event.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get the arguments for this event.
     *
     * @return array
     */
    public function getArguments() {
        return $this->getArrayCopy();
    }

    /**
     * Set an argument value.
     *
     * @param string $name The name of the argument to set.
     * @param mixed $value The value to set for the argument.
     */
    public function setArgument($name, $value) {
        $this->offsetSet($name, $value);
    }

    /**
     * Get the value of an argument of the event.
     *
     * @param sting $name The name of the argument.
     * @param mixd $default The default value if parameter is not found.
     * @return mixed
     */
    public function getArgument($name, $default = null) {
        if ($this->offsetExists($name) === true) {
            return $this->offsetGet($name);
        } else {
            return $default;
        }
    }

    /**
     * Stop the event propagation.
     *
     * @return \Wingu\OctopusCore\EventDispatcher\EventInterface
     */
    public function stopPropagation() {
        $this->propagationStopped = true;
        return $this;
    }

    /**
     * Check if propagation is stopped.
     *
     * @return boolean
     */
    public function isPropagationStopped() {
        return $this->propagationStopped;
    }

    /**
     * String conversion of the event.
     *
     * @return string
     */
    public function __toString() {
        return (string) $this->getName();
    }
}