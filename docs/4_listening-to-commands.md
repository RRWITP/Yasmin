# Listening to commands 

In order to listen to commands, we need to listen on the `message` event. The event listener will receive an instance of `Message`, 
which contains all the necessary information. The event gets emitted, once the bot receives a message from a channel he has access to. 
That means the bot won't receive messages from a channel he doesn't have access to, obviously. 

```
use CharlotteDunois\Yasmin\Models\Message; 

$client->on('message', static function (Message $message): void {
    var_dump($message->content);
});
```
*I recommend to install Xdebug for development, as there are circular references in most Yasmin models, so logging them will yield a really huge output.*

## Replying to commands 

Logging is all great, but even better is it to make you bot respond to them! To do that, we need to implement a logic for your bot. For our bot, we use the prefix `!` for our commands.

**It's recommend to always use a prefix. Prefixless bots can be very annoying.**

For the start, we will use an if condition for our commands. It's recommended to use a more advanced command handler. If you don't 
want to write your own, consider Using Livia. 

```
use CharlotteDunois\yasmin\Models\Message $message 

$client->on('message', static function (Message $message) {
    if ($message->content === '!ping') {
        $message->channel->send('PONG!');
    }
});
```

If you did that, restarted your bot and sent a message with the content `!ping`, and everything goes well, 
the bot should reply with `PONG!`

Congratulations, you've created your first functional bot command! But this isn't the end of it, this is jus the start.
