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

class Apc implements CacheInterface
{
    private $all = 'ALL';

    /**
     * Delete keys related to xml filename
     *
     * @param string $file
     */
    public function clear($file)
    {
        $all = apc_fetch(Config\Config::$cachePrefix . $this->all);
        if ($all === false) {
            return;
        }
        $new = array();
        foreach ($all as $name => $data) {
            if ($name != $file) {
                $new[$name] = $data;
                continue;
            }
            foreach ($data as $key) {
                $this->delete($key);
            }
        }
        if (count($all) !== count($new)) {
            apc_store(Config\Config::$cachePrefix . $this->all, $new);
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
        return apc_delete(Config\Config::$cachePrefix . $key);
    }

    /**
     * Retreive key value
     *
     * @param string $key
     * @return string|boolean
     */
    public function get($key)
    {
        $value = apc_fetch(Config\Config::$cachePrefix . $key);
        if ($value !== false) {
            return gzuncompress($value);
        }
        return false;
    }

    /**
     * Purge all keys
     */
    public function purge()
    {
        $all = apc_fetch(Config\Config::$cachePrefix . $this->all);
        if ($all === false) {
            return;
        }
        foreach ($all as $file) {
            foreach ($file as $key) {
                $this->delete($key);
            }
        }
        $this->delete($this->all);
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
            $do = apc_store(Config\Config::$cachePrefix . $key, gzcompress($data, 9), $ttl);
            if ($do) {
                $value = apc_fetch(Config\Config::$cachePrefix . $this->all);
                if ($value === false || !isset($value[$found[1]]) || !in_array($key, $value[$found[1]])) {
                    $value[$found[1]][] = $key;
                    $do2 = apc_store(Config\Config::$cachePrefix . $this->all, $value);
                    if (!$do2) {
                        $this->delete($key);
                        $do = false;
                    }
                }
            }
            return $do;
        }
        return false;
    }

}
