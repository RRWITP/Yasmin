# Introduction 

You have come to this place to probably learn to make bots with Yasmin, Right?
But before u continue, you need to understand basic concepts, such as Event loops an Promises, and generally asynchronous programming. 

Please refer to these resources: [https://github.com/elazar/asynchronous-php](https://github.com/elazar/asynchronous-php)

Also you should be familiar with PHP itself? Yasmin requires at least PHP 7.1, anything below is not supported and will throw a Fatal Error. 

## About Yasmin 

Yasmin builds upon react PHP. React PHP provides an event loop, promises, streams and more to asynchronously interface with anything. 
We use the event loop, streams and sockets to interface with the Discord gateway. `ratchet/pawl` wraps it up nicely for us. 

For the HTTP part, Yasmin uses `guzzlehttp` to asynchronously issue HTTP requests to the Discord REST API. 
GuzzleHTTP does this using `curl_multi`.

yasmin handles all ratelimits, both on the gateway and REST part. That does not mean you're allowed to spam requests, that's
considered API abuse and can get you banned. 

Please note that using any asnchronous, "long-running" PHP functions will block the thread, which means the event loop will **not** run.
If you see yopur bot stop responsing or going offline, then you use a PHP function that runs to long and blocks the thread. 

Consider using Pthreads of a child process to do the job, if you can't use of find an asynchronous version.
This is the case for all database wrappers, such as PDO and MySQLi (without native driver and `mysqli_poll`).
