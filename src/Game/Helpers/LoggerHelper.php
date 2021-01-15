<?php

namespace Game\Helpers;

/**
 * Class LoggerHelper
 *
 * @package Game\Helpers
 */
class LoggerHelper
{
    /**
     * @var self
     */
    public static $instance;

    /**
     * @var array
     */
    private $messages = [];

    /**
     * LoggerHelper constructor.
     */
    private function __construct()
    {
        // PREVENT CREATING INSTANCE
    }

    /**
     * @return \Game\Helpers\LoggerHelper
     */
    public static function getInstance(): self
    {
        if ( !self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param  string  $msg
     * @param  mixed   $params
     */
    public static function addMessage(string $msg, array $params = [])
    {
        if ( !is_array($params) && !empty($params) ) {
            $params = [$params];
        }

        self::getInstance()->messages[] = vsprintf($msg, $params);
    }

    /**
     * @return array
     */
    public static function getMessages(): array
    {
        return self::getInstance()->messages;
    }

    /**
     * @return void
     */
    public static function showMessages()
    {
        foreach ( self::getMessages() as $message ) {
            echo $message . "</br>";
        }
    }

    /**
     * @return void
     */
    public static function clearMessages()
    {
        self::getInstance()->messages = [];
    }
}
