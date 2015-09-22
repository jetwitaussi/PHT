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

class Factory
{
    /**
     * Create a cache instance
     *
     * @param string $type
     * @return \PHT\Cache\CacheInterface
     * @throws \InvalidArgumentException
     */
    public static function create($type)
    {
        switch ($type) {

            case 'apc':
                if (strtolower(ini_get('apc.enabled')) != 'on') {
                    throw new \InvalidArgumentException("APC cache is not available");
                }
                return new Apc();

            case 'session':
                if (function_exists('session_status') && session_status() == PHP_SESSION_DISABLED) {
                    throw new \InvalidArgumentException("Session is disabled and can't be used");
                }
                return new Session();

            case 'none':
                return new Void();

            case 'memcached':
                if (!extension_loaded('memcached')) {
                    throw new \InvalidArgumentException("Memcached extension is not available");
                }
                return new Memcached();

            case 'memory':
                return new Memory();

            default:
                throw new \InvalidArgumentException("Unknow cache driver: $type");
        }
    }

}
