# Configuraion files 

As you go deeper into development, it's recommend to use configuration files to store sensitive information, 
but also regular information for your bot. Like the following: 

- Discord token 
- More tokens or keys (fot other APIs)
- Database credentials 
- Command prefix(es)
- Owner ID(s)

Hardcoding these in a lot of files isn't a good solution, so having a configuration file helps a lot. Also 
you can ignore the configuration file and commit only code to your version control system. 

## Implementation 

First we create a new configuration file, for the ease we use JSON. Of course you can use any other format, 
as long as you have a parser and encoder fot it. 

```
{
    "prefix": "!",
    "token": "your-token-goes-here"
}
```

This is our configuration file called `config.json`. We will now read and parse the file before creating the client. 

```
$config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);
```

> Please note that you should not use synchronous functions while the bot is running. Yasmin will
> require react/filesystem once ReactPHP fixed the dependency issues. Yasmin will use it to
> asychronously read files (such as avatars or attachments) from the disc.

We will now change our message event listener to use the prefix from the config file and we will also change the login line.

```
use CharlotteDunois\Yasmin\Models\Message; 

$client->on('message', static function (Message $message) use (&$config) {
    if ($message->content === $config['prefix'].'ping') {
        $message->channel->send('Pong!');
    }
});

$client->login($config['token']);
```

## Additional data 

As I have mentioned, you probably want to store more than just the prefix and the token in the configuration file. You can just add them as key/value pair (following the JSON specification).
A example would be the database credentials.

```
{
    "prefix": "$",
    "token": "token",
    "database": {
        "host": "localhost",
        "port": 3306,
        "user": "root",
        "password": "",
        "database": "discord-bot"
    }
}
```
