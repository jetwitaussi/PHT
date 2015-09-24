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

namespace PHT\Log;

use PHT\Config;

class Logger
{
    private static $logger = null;

    /**
     * Create logger instance based on pht configuration
     *
     * @return \PHT\Log\AbstractLogger
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public static function getInstance()
    {
        if (self::$logger === null) {
            self::$logger = Factory::create(Config\Config::$logType, Config\Config::$logLevel);
        }
        return self::$logger;
    }
}
