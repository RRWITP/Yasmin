# Creatinjg the bot

We are now to the more exciting parts - creating the necessary logic for our bot. 

Open your preferred editor of choice (I use PHPStorm, but you'tre free to ue anything else, except notepad and notepad++). Create a new file 
in your directory where you installed Yasmin, generally it's suggested to use something like `index.php`, `boot.php` or `app.php` as filename, 
but you're free ti name your name whatever you want, as long as it has the extension `.php`.

## Login into Discord 

Before we can do anything with Yasmin, we need to require composer's autoloader and create a reactPHP event loop. reactPHP suggest to use 
`\React\EventLoop\factory::create()` to create an event loop. You are of course free to specify the full namespace, I won't do this, as i think its cleaner to
import them at the top of the file. 

```php
// Include composer autoloader
require(__DIR__.'/vendor/autoload.php');

// Create ReactPHP event loop
$loop = \React\EventLoop\Factory::create();
```

Now we required composer's autoloader and created an event loop! But this doens't do anything yet, the event loop doesn't run yet, and we haven't created our Yasmin client yet. 
Now we create a Yasmin client, we pass any client options we wish to use (refer to the docs of `Client::__construct()`) and our event loop. 

```
// Create the client 
$client = new \CharlotteDunois\Yasmin\Client([], $loop);
```

**IMPORTANT NOTE: The order of parameters will change in the future.**

Now we got our client and we can add event listeners. We need event listeners, because Yasmin is asynchronous (that doesn't mean it's multithreaded), 
Yasmin emits events whenever something happens.
You can listen on any events and run any code.

In my case, I will now listen on the `ready` event and print something whenever the client logs into the account (or successfully reconnects).

```
$client->on('ready', static function () use ($client) {
    echo 'Successfully logged into ' . $client->user->tag . PHP_EOL;
});
``` 

A list of available events can be found in the docs, in the `ClientEvents` interface. 

The `ready` event gets emitted once Yasmin has received and processed all necessary informations by Discord 
to start and is ready to serve your events. 

Add now any event listeners you wish to add, of ourse you can add event listeners at a later point too. 

To login into Discord, we run `Client::login`, presenting our token. This method returns a promise and resolves, 
if a connection could successfully be established. This does not mean that the client is ready, it just 
means our connection and token was accepted by Discord. Of course, this promise can also rejected. 

```
$client->login('YOUR_TOKEN_GOES_HERE');
```

After logiing, we need to run the event loop. It doesn't run automatically, that's something i don't want to do, because you may want to do something else fist. 

To run the event loop, a simple 

```
$loop->run();
```

will be sufficient. Remember, this call will now block anything below form running, until you stop the event loop. 
