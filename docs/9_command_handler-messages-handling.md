# Command Handler: Messages Handling

Good job getting this far! In this topic we are going to handle the incoming messages. For that we need to add a new method to the Command Handler class,
which gets called when a message gets received. I am going to call it `handleMessage`.

This is my method signature: 

```
function handleMessage(\CharlotteDunois\Yasmin\Models\Message $message)
```

In this method, we need to check if we want to handle the message - and if so, call the command. The Command Handler should catch all exceptions thrown 
from the commands and reply with a short message to the message author.

I am going to check, whether the author is a bot, whether the message starts with a prefix or a mention and if it's a valid command.

```
$prefix = '$';
$prefixL = \mb_strlen($prefix);

// Ignoring bots
if($message->author->bot) {
    return;
}

// Get the bot account's mention format
$mention = $message->guild->me->__toString();

// Check whether the message starts not with the configured prefix AND not with the mention
if(\mb_substr($message->content, 0, $prefixL) !== $prefix && \mb_substr($message->content, 0, \mb_strlen($mention)) !== $mention) {
    return;
}

// Explode the content by whitespace
$args = \explode(' ', $message->content);

// Get the command name by shifting the array and removing the prefix
$command = \mb_substr(\array_shift($args), $prefixL);

// Check whether the command exists
if(!$this->commands->has(\mb_strtolower($command))) {
    return;
}

try {
    // Run the command
    $this->commands->get($command)->run($message, $args);
} catch (\Throwable | \Exception | \Error $e) {
    $message->reply('I am sorry but we were unable to properly run the command! Please contact the bot owner.
Error: `'.$e->getMessage().'`');
}
```

As you can see, I hardcoded the prefix. But you can easily change this by using a class property (either on command handler or client) to keep track of the prefix. 
This would also work for multiple prefixes.
