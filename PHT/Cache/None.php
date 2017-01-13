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

class None implements CacheInterface
{
    /**
     * Delete keys related to xml filename
     *
     * @param string $file
     */
    public function clear($file)
    {
        // noop
    }

    /**
     * Delete key
     *
     * @param string $key
     * @return boolean
     */
    public function delete($key)
    {
        return true;
    }

    /**
     * Retreive key value
     *
     * @param string $key
     * @return string|boolean
     */
    public function get($key)
    {
        return false;
    }

    /**
     * Purge all keys
     */
    public function purge()
    {
        // noop
    }

    /**
     * Store key/data value
     *
     * @param string $key
     * @param string $data
     * @param integer $ttl
     * @return boolean
     */
    public function set($key, $data, $ttl = 0)
    {
        return true;
    }

}
