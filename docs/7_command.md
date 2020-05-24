# Command 

We will start for our Command Handler with our Command class. It's quite a foundation for it, so it makes sense to start with it.
All commands should extend this Command class, otherwise it would be quite meaningless.

> It's recommended to use namespaces and composer's autoloader using PSR-4.

We will start with this as template:

```
namespace YasminGuide\CommandHandler;

abstract class Command {
    /** @var \CharlotteDunois\Yasmin\Client */
    protected $client;

    /** @var \YasminGuide\CommandHandler\CommandHandler */
    protected $handler;

    /** @var string */
    public $path; // Will be assigned by the Command Handler

    protected $name = null;
    protected $description = null;

    function __construct(\YasminGuide\CommandHandler\CommandHandler $handler, string $name, string $description) {
        $this->client = $handler->client;
        $this->handler = $handler;

        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Returns the command name.
     * @return string;
     */
    function getName() : string {
        return $this->name;
    }

    /**
     * Returns the command description.
     * @return string;
     */
    function getDescription() : string {
        return $this->description;
    }

    /**
     * Runs the command.
     * @return void
     * @throws \Throwable|\Exception|\Error
     */
    abstract function run(\CharlotteDunoi\Yasmin\Models\Message $message, array $args) : void;
}
```

I recommend to use docblocks to document property types and method return types. If you use something like PHP Integrator, 
it makes your life a lot easier. Also documenting type allows autocompletion.

We have a client property to make working with the client easier for us.
