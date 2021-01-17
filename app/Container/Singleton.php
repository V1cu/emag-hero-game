<?php

namespace App\Container;

/**
 * Class Singleton
 *
 * @package App\Container
 */
class Singleton
{
    /**
     * @var array
     */
    private static array $instances = [];

    /**
     * Singleton constructor.
     */
    protected function __construct()
    {
    }

    /**
     * Cloning and unserialization are not permitted for singletons.
     */
    protected function __clone()
    {
    }

    /**
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    /**
     * @return mixed|static
     */
    public static function getInstance()
    {
        $subclass = static::class;

        if ( ! isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static();
        }

        return self::$instances[$subclass];
    }
}