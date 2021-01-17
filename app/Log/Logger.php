<?php

namespace App\Log;

use App\Container\Singleton;

/**
 * Class Logger
 *
 * @package App\Log
 */
class Logger extends Singleton
{
    /**
     * @var array
     */
    private array $messages = [];

    /**
     * @param  string  $msg
     * @param  mixed   $params
     */
    public static function addMessage(string $msg, $params = [])
    {
        if ( ! is_array($params) && ! empty($params)) {
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
     * @return bool
     */
    public static function hasMessages(): bool
    {
        return ! empty(self::getMessages());
    }

    /**
     * @return void
     */
    public static function showMessages()
    {
        foreach (self::getMessages() as $message) {
            echo $message."</br>";
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
