<?php

namespace Wingu\OctopusCore\EventDispatcher;

/**
 * A colection with the results for each event subscriber.
 */
class ResponseCollection extends \SplStack implements ResponseCollectionInterface {

    /**
     * The raised event.
     *
     * @var \Wingu\OctopusCore\EventDispatcher\EventInterface
     */
    protected $raisedEvent;

    /**
     * Constructor.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventInterface $raisedEvent The raised event.
     */
    public function __construct(EventInterface $raisedEvent) {
        $this->setRaisedEvent($raisedEvent);
    }

    /**
     * Set the event that was raised.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventInterface $event The raised event.
     * @return \Wingu\OctopusCore\EventDispatcher\ResponseCollectionInterface
     */
    public function setRaisedEvent(EventInterface $event) {
        $this->raisedEvent = $event;
        return $this;
    }

    /**
     * Get the raised event.
     *
     * @return \Wingu\OctopusCore\EventDispatcher\EventInterface
     */
    public function getRaisedEvent() {
        return $this->raisedEvent;
    }
}