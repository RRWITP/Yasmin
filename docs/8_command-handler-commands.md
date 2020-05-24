# Command handler: Commands

Our next class, the command handler, handles running commands, as well as loading and unloading them.

To store our commands, we use a `Collection`. It's an utility class to store key-value pairs conventionally. 
You might know Map from JavaScript. They are similar.

For our commands, we will make for each command a file, each file **returns** a new anonymous class. Our 
class will include the command file and make sure, there is a variable called `$handler` defined, 
which is conventionally `$this` inside the Command Handler class.

In each of our command files, we extend the command class and call in the class constructor the parent constructor and initialize the class. 
Additionally the class implements the method `run`, which does whatever we want the command to do.

An example for a `ping` command would be:

```
<?php

/**
 * Ping command
 */

return (new class($handler) extends \YasminGuide\CommandHandler\Command {
    function __construct(\YasminGuide\CommandHandler\CommandHandler $handler) {
        parent::__construct($handler, 'ping', 'Responds with pong.');
    }

    function run(\CharlotteDunois\Yasmin\Models\Message $message, array $args) : void {
        $message->channel->send('Pong!');
    }
});
```

Each time we include this file, it will return a new instance of an anonymous class, which extends our Command class.

Our command handler class captures the class and adds it to the commands Collection. The method reload will just include the file again and add it to the 
collection (overwriting the old instance). The method unload will just remove the command from the collection.

The below code is reduced, but shows how registering/loading a command works.

```
namespace YasminGuide\CommandHandler;

class CommandHandler {
    /** @var \CharlotteDunois\Yasmin\Client */
    public $client;

    /**
     * Holds all our commands, mapped by their name.
     * @var \CharlotteDunois\Yasmin\Utils\Collection
     */
    protected $commands;

    /**
     * Constructor.
     * @param \CharlotteDunois\Yasmin\Client $client
     */
    function __construct(\CharlotteDunois\Yasmin\Client $client) {
        $this->client = $client;
        $this->commands = new \CharlotteDunois\Yasmin\Utils\Collection();
    }

    /**
     * Registers a command, by loading the specified file.
     * @param string $path
     * @return $this
     * @throws \RuntimeException
     */
    function registerCommand(string $path) {
        if(!\file_exists($path)) {
            throw new \RuntimeException('File does not exist');
        }

        try {
            $handler = $this; // Will be captured by the required file
            $command = include($path);

            // We put the path into a class property for convenience
            $command->path = $path;

            $this->commands->set(\mb_strtolower($command->getName()), $command);
        } catch (\Throwable | \Exception | \Error $e) {
            throw new \RuntimeException('Unable to load command. Error: '.$e->getMessage());
        }
    }
}
```

First we make sure the file exists, otherwise we throw an exception. Then we create the necessary variable `$handler`, which is our `CommandHandler` instance. 
Then we include the command file and store the return value (an anonymous class instance) in a variable. For convenience (commands reloading with command name), 
we store the path to the file in the class, as `path` property. Then we store the class instance in our commands Collection. To make it "case insensitive", we make the name all lowercase, 
and when we look up, we do the same and look up the command with the all lowercase name.

Reloading works about the same way. Just we look up the command name if it's not a filepath and then we include the file, 
capture return value and store it in the commands Collection.

Unloading just removes the command from the commands Collection.

For convenience, you should probably add a method which scans a directory and loads all files in that directory. `glob` should help with getting an array of files in a directory.

Now we get to the part, where we handle commands. Check the next topic.
