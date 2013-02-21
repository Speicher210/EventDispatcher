<?php

namespace Wingu\OctopusCore\EventDispatcher;

/**
 * A colection with the results for each event subscriber.
 */
interface ResponseCollectionInterface extends \Iterator, \ArrayAccess, \Countable {

    /**
     * Set the event that was raised.
     *
     * @param \Wingu\OctopusCore\EventDispatcher\EventInterface $event The raised event.
     * @return \Wingu\OctopusCore\EventDispatcher\ResponseCollectionInterface
     */
    public function setRaisedEvent(EventInterface $event);

    /**
     * Get the raised event.
     *
     * @return \Wingu\OctopusCore\EventDispatcher\EventInterface
     */
    public function getRaisedEvent();
}