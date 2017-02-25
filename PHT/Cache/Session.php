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

class Session implements CacheInterface
{
    /**
     * Instantiate, create session if needed
     */
    function __construct()
    {
        if ((function_exists('session_status') && session_status() == PHP_SESSION_NONE) || session_id() === '') {
            session_start();
            $_SESSION[Config\Config::$cachePrefix] = array();
        }
    }

    /**
     * Delete keys related to xml filename
     *
     * @param string $file
     */
    public function clear($file)
    {
        if (isset($_SESSION[Config\Config::$cachePrefix][$file])) {
            foreach ($_SESSION[Config\Config::$cachePrefix][$file] as $key) {
                $this->delete($key);
            }
            unset($_SESSION[Config\Config::$cachePrefix][$file]);
        }
    }

    /**
     * Delete key
     *
     * @param string $key
     * @return boolean
     */
    public function delete($key)
    {
        unset($_SESSION[Config\Config::$cachePrefix][$key]);
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
        if (isset($_SESSION[Config\Config::$cachePrefix][$key])) {
            $data = unserialize(gzuncompress($_SESSION[Config\Config::$cachePrefix][$key]));
            if (!isset($data['time']) || $data['time'] > time()) {
                return $data['data'];
            }
            $this->delete($key);
        }
        return false;
    }

    /**
     * Purge all keys
     */
    public function purge()
    {
        $_SESSION[Config\Config::$cachePrefix] = array();
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
        $found = array();
        preg_match('/file=([^&]*)/', $key, $found);
        if (isset($found[1])) {
            $data = array('data' => $data);
            if ($ttl > 0) {
                $data['time'] = time() + $ttl;
            }
            $value = gzcompress(serialize($data), 9);
            $_SESSION[Config\Config::$cachePrefix][$key] = $value;
            if (!isset($_SESSION[Config\Config::$cachePrefix][$found[1]])) {
                $_SESSION[Config\Config::$cachePrefix][$found[1]] = array();
            }
            if (isset($found[1]) && !in_array($key, $_SESSION[Config\Config::$cachePrefix][$found[1]])) {
                $_SESSION[Config\Config::$cachePrefix][$found[1]][] = $key;
            }
            return true;
        }
        return false;
    }

}
