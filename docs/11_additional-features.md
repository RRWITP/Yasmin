# Additional features 

Of course we probably want some more features. Examples would be:

- Guild only commands 
- Cooldowns 
- Command aliases 
- Dynamic help command 

All of these features can be implemented without sacrificing your firstborn son to satan. I will leave proper code documentation to you.

> Protip: Instead of appending even more arguments to the Command class constructor, you probably
> want to switch to a single array property, which holds all details.

## Additional Feature: Guild Only Commands

This feature requires minimal adjustment of both classes. Add an `if` condition to `handleMessage`, which checks if it's a guild only command and if the channel is from a guild. 
Depending on these two conditions, we exit the method or not.

```
if($cmd->isGuildOnly() && $message->channel->type !== 'text') {
    return;
}
```

and in the Command class, basically just assignments and returns.

```
protected $guildOnly = false;

function __construct(\YasminGuide\CommandHandler\CommandHandler $handler, string $name, string $description, bool $guildOnly = false) {
    $this->client = $handler->client;
    $this->handler = $handler;

    $this->name = $name;
    $this->description = $description;
    $this->guildOnly = $guildOnly;
}

function isGuildOnly() : bool {
    return $this->guildOnly;
}
```

The opposite of this, DM only commands, can of course be implemented the same way.

## Additional Feature: Cooldowns

You may want a command to be only a specific amount of times in a specific duration by one user. Like one can use a cute kittens command only every 10 seconds.

To implement this, we need to add two new properties and method to the Command class and call the new method from the Command Handler. 
Depending on the return code, we reply with a message and abort handling the command.

In our command class, we add two properties, one called `throttles`, which will hold a `Collection`. And another one called throttling, which is an array and hold the throttling details. 
And we will add a method called `throttle`, which takes an user ID (the author's ID). Depending on the throttling details, it will return a boolean.

```
protected $throttles = null;
protected $throttling = array();

function __construct(\YasminGuide\CommandHandler\CommandHandler $handler, string $name, string $description, array $throttling = array()) {
    $this->client = $handler->client;
    $this->handler = $handler;
    $this->throttles = new \CharlotteDunois\Yasmin\Utils\Collection();

    $this->name = $name;
    $this->description = $description;
    $this->throttling = $throttling;
}

function throttle($userid) : bool {
    if(empty($this->throttling) || empty($this->throttling['duration']) || empty($this->throttling['usages'])) {
        return false;
    }

    if(!$this->throttles->has($userid)) {
        $this->throttles->set($userid, array(
            'timeline' => \time(),
            'usages' => 0,
            'timer' => $this->client->addTimer($this->throttling['duration'], function () use ($userid) {
                $this->throttles->delete($userid);
            });
        ));
    }

    $throttle = $this->throttles->get($userid);

    if($throttle['usages'] >= $this->throttling['usages']) {
        return true;
    }

    $throttle['usages']++;
    $this->throttles->set($userid, $throttle);
    return false;
}
```

With this, we've got cooldowns by userID. Let's analyze the `throttle` method, what does it do?

### Cooldowns: Analysis

First we got: 

```
if(empty($this->throttling) || empty($this->throttling['duration']) || empty($this->throttling['usages'])) {
    return false;
}
```

With this line we will check if the throttling details are empty, any details. If any is, we will not throttle at all, this is done by returning false - for not throttling.

Now we go to the next line.

```
if (! $this->throttles->has($userid)) {
    // Do logic
}
```

We check here if we have not gotten the user in our Collection yet. If we don't, we create the user-specific throttle details using

```
$this->throttles->set($userid, array(
    'timeline' => \time(),
    'usages' => 0,
    'timer' => $this->client->addTimer($this->throttling['duration'], function () use ($userid) {
        $this->throttles->delete($userid);
    });
));
```

We automatically add a timer to delete the throttle details as soon as the throttling duration is over.

Here we get the throttle details:

```
$throttle = $this->throttles->get($userid);
```

And we check if the author has already exceeded the limit:

```
if($throttle['usages'] >= $this->throttling['usages']) {
    return true;
}
```

If so, we will return true - for throttling.

If not, we will increase the usage and return false. We also need to update the array in the Collection, of course.

```
$throttle['usages']++;
$this->throttles->set($userid, $throttle);

return false;
```

And this is all of it in the Command class.

We've gone so far, but this still won't work yet - we need to call the method in the Command Handler before we run a command!

This can be simply implemented with an `if` condition before calling the `run` method.

```
if($cmd->throttle($message->author->id)) {
    return $message->reply('You are going too fast! Please calm down.');
}
```

It's up to you if you want to respond at all - if you wish you can also just do nothing. 

### Cooldowns: Conclusion

Cooldowns can be implemented with a little logic, but it will still cooldown you - the owner. So what do?

The answer is simple! Make your bot know who the owner is! The simplest way is adding a property with the ownerID(s) to a class and then check that property. 
If the check determines true, return false in `throttle`, otherwise continue with the usual.

# Additional Feature: Command Aliases
