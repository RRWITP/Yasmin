# Finishing 

We have got our two classes - Command Handler and Command - done and can now initiate adding it to our bot to handle commands.

We got the usual thing:

```
require_once(__DIR__.'/vendor/autoload.php');

$loop = \React\EventLoop\Factory::create();
$client = new \CharlotteDunois\Yasmin\Client(array(), $loop);

$client->on('ready', function () use ($client) {
    echo 'Successfully logged in!'.PHP_EOL;
});

$client->login('YOUR_TOKEN');
$loop->run();
```

Now we need to create an instance of `CommandHandler` and pass it our client. This should be preferably done after creating the client.

```
$handler = new \YasminGuide\CommandHandler\CommandHandler($client);
```

After we created our instance, we need to add a message event listener, which calls our `CommandHandler::handleMessage` method. This is fairly trivial.

```
$client->on('message', function (\CharlotteDunois\Yasmin\Models\Message $message) use ($handler) {
    $handler->handleMessage($message);
});
```

And we're done! Our Command Handler now handles incoming messages and looks properly for commands!

But at this point, it doesn't do too much. The next topic will add some more in-depth features.
