# Multiple commands 

Using an `if/elseif` to handle commands is great for the start. But it gets quite hard to read and to maintain. For this reason, we need something better. This is where command handlers come into play.

Advanced Command Handlers handle and run your commands, parse messages for commands and extract arguments. We will now create a basic command handler, 
which registers commands, listens on the `message` event for commands, gets the arguments from the command message and runs the command.

For the start we define what we want.

We want to create a class, we will call it `CommandHandler`, it will have methods to load commands (by defining the file name (path)) and it will have methods to reload and unload the commands.

We will create another class called `Command`, it defines basic properties and allows a basic format.

But first we need to define, whether we allow bots to trigger commands or not. In general, it's good practice to ignore all bots. At minimum,
you should ignore messages from the bot account itself (the client user). This is quite easy to implement using an `if` condition.

```
// in message event listener

if($message->author->bot) {
    return;
}

// or
if($message->author->id === $message->client->user->id) {
    return;
}

// rest of the code
```

> Most of the Yasmin models have a reference to the client as property.
