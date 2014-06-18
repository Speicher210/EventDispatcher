EventDispatcher Component
=========================

EventDispatcher implements a lightweight version of the Observer design pattern.

```php
use Wingu\OctopusCore\EventDispatcher\EventDispatcher;
use Wingu\OctopusCore\EventDispatcher\EventInterface;
use Wingu\OctopusCore\EventDispatcher\Event;

$dispatcher = new EventDispatcher();
$dispatcher->on('event_name', function (EventInterface $event) {
    // ...
});

$dispatcher->raiseEvent('event_name', new Event($sender));
```

Listening to events
===================

Events are raised through the EventDispatcher. The easiest way to register listeners to handle events is using the `on()` method:
	
```php
$dispatcher->on($eventName, $callback, $priority)
```

The $eventName can be a specific event name, a wildcard event name or a regular expression to match an event name.

```php
$dispatcher->on('core', function(Event $e){}); // by event name
$dispatcher->on('core.*', function(Event $e){}); // wildcard, match anything after *
$dispatcher->on('core.#.error', function(Event $e){}); // wildcard, match a namespace (core.log.error, core.db.error, etc)
$dispatcher->on('/^core\.(.+)$/', function(Event $e){}); // regex
```

There are other several methods to subscribe to events.
	
```php
// By passing an event name matcher.
$eventNameMatcher = new \Wingu\OctopusCore\EventDispatcher\EventNameMatcher\AllNamesMatcher();
$callback = function (EventInterface $e) {
	// do something ...
};
$dispatcher->subscribe($eventNameMatcher, $callback, $priority); // Will actually subscribe to all events.

// By passing a subscription object.
$eventNameMatcher = new \Wingu\OctopusCore\EventDispatcher\EventNameMatcher\NameMatcher('core.mail');
$callback = function (EventInterface $e) {
	// do something ...
};
$subscription = new \Wingu\OctopusCore\EventDispatcher\Subscription($eventNameMatcher, $callback);
$dispatcher->addSubscription($subscription);
```

To unsubscribe use:

```php
$dispatcher->off($eventNameMatcher, $callback);
$dispatcher->unsubscribe($eventNameMatcher, $callback); // $eventNameMatcher doesn't have to be the same instance, but has to match the same event(s).
$dispatcher->removeSubscription($subscription);
```

When an event is raised the return of each listener is stacked into a ResponseCollectionInterface object.

Raising / dispatching events
============================

Events can be raised / dispatched by calling the `raiseEvent($event)` or `raiseEventUntil($event, $callback)` method. 
The arguments of the event must be an array with the key as the argument name.

```php
$dispatcher = new EventDispatcher();

$args = ['param1' => 1, 'param2' => 2, 'date' => new \Datetime()];
$event = new Event($sender, $args);

$dispatcher->raiseEvent('event_name', $event);
$dispatcher->raiseEventUntil('event_name', $event, function() {
    echo "Event processed!";
});
```

For object instance event dispatching the EventDispatcherTrait trait can be attached to any object and then the API is the same as for the dispatcher.

Tests
=========================

You can run the unit tests with the following command:

    $ cd path/to/Wingu/OctopusCore/EventDispatcher/
    $ composer.phar install --dev
    $ phpunit