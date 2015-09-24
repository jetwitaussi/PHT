<?php
/**
 * PHT
 *
 * @author Telesphore
 * @link https://github.com/jetwitaussi/PHT
 * @version 3.0
 * @license "THE BEER-WARE LICENSE" (Revision 42):
 *          Telesphore wrote this file.  As long as you retain this notice you
 *          can do whatever you want with this stuff. If we meet some day, and you think
 *          this stuff is worth it, you can buy me a beer in return.
 */

namespace PHT\Cache;

use PHT\Config;

class Driver
{
    private static $driver = null;

    /**
     * Create cache instance based on pht configuration
     *
     * @return \PHT\Cache\CacheInterface
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public static function getInstance()
    {
        if (self::$driver === null) {
            self::$driver = Factory::create(Config\Config::$cache);
        }
        return self::$driver;
    }

}
