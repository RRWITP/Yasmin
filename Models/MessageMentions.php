<?php
/**
 * Yasmin
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Yasmin/blob/master/LICENSE
*/

namespace CharlotteDunois\Yasmin\Models;

/**
 * Holds message mentions.
 */
class MessageMentions extends ClientBase {
    /**
     * RegEx pattern to match channel mentions.
     * @var string
     */
    const PATTERN_CHANNELS = '/<#(\d+)>/';
    
    /**
     * RegEx pattern to match role mentions.
     * @var string
     */
    const PATTERN_ROLES = '/<@&(\d+)>/';
    
    /**
     * RegEx pattern to match user mentions.
     * @var string
     */
    const PATTERN_USERS = '/<@!?(\d+)>/';
    
    protected $message;
    
    protected $channels;
    protected $members;
    protected $roles;
    protected $users;
    
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Yasmin\Client $client, \CharlotteDunois\Yasmin\Models\Message $message, array $msg) {
        parent::__construct($client);
        $this->message = $message;
        
        $this->channels = new \CharlotteDunois\Yasmin\Utils\Collection();
        $this->members = new \CharlotteDunois\Yasmin\Utils\Collection();
        $this->roles = new \CharlotteDunois\Yasmin\Utils\Collection();
        $this->users = new \CharlotteDunois\Yasmin\Utils\Collection();
        
        \preg_match_all(self::PATTERN_CHANNELS, $message->content, $matches);
        if(!empty($matches[1])) {
            foreach($matches[1] as $match) {
                $channel = $this->client->channels->get($match);
                if($channel) {
                    $this->channels->set($channel->id, $channel);
                }
            }
        }
        
        if(!empty($msg['mentions'])) {
            foreach($msg['mentions'] as $mention) {
                $user = $this->client->users->patch($mention);
                if($user) {
                    $member = null;
                    
                    $this->users->set($user->id, $user);
                    if($message->guild) {
                        $member = $message->guild->members->get($mention['id']);
                        if($member) {
                            $this->members->set($member->id, $member);
                        }
                    }
                }
            }
        }
        
        if($message->channel->guild && !empty($msg['mention_roles'])) {
            foreach($msg['mention_roles'] as $id) {
                $role = $message->channel->guild->roles->get($id);
                if($role) {
                    $this->roles->set($role->id, $role);
                }
            }
        }
    }
    
    /**
     * @property-read \CharlotteDunois\Yasmin\Utils\Collection    $channels  The collection which holds all channel mentions.
     * @property-read \CharlotteDunois\Yasmin\Utils\Collection    $members   The collection which holds all members mentions (only in guild channels).
     * @property-read \CharlotteDunois\Yasmin\Utils\Collection    $roles     The collection which holds all roles mentions.
     * @property-read \CharlotteDunois\Yasmin\Utils\Collection    $users     The collection which holds all users mentions.
     * @property-read \CharlotteDunois\Yasmin\Models\Message      $message   The message this reaction belongs to.
     *
     * @throws \Exception
     */
    function __get($name) {
        if(\property_exists($this, $name)) {
            return $this->$name;
        }
        
        return parent::__get($name);
    }
    
    
}
