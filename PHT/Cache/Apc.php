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
     * Fetch cache data
     * @param mixed $key
     */
    private function fetch($key)
    {
        return function_exists('apcu_fetch') ? apcu_fetch($key) : apc_fetch($key);
    }
    
    /**
     * Store cache data
     * @param mixed $key
     * @param mixed $value
     * @param integer $ttl
     */
    private function store($key, $value, $ttl = 0)
    {
        return function_exists('apcu_store') ? apcu_store($key, $value, $ttl) : apc_store($key, $value, $ttl);
    }
  
    /**
     * Remove cache data
     * @param mixed $key
     */
    private function remove($key)
    {
        return function_exists('apcu_delete') ? apcu_delete($key) : apc_delete($key);
    }

    /**
     * Delete keys related to xml filename
     *
     * @param string $file
     */
    public function clear($file)
    {
        $all = $this->fetch(Config\Config::$cachePrefix . $this->all);
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
            $this->store(Config\Config::$cachePrefix . $this->all, $new);
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
        return $this->remove(Config\Config::$cachePrefix . $key);
    }

    /**
     * Retreive key value
     *
     * @param string $key
     * @return string|boolean
     */
    public function get($key)
    {
        $value = $this->fetch(Config\Config::$cachePrefix . $key);
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
        $all = $this->fetch(Config\Config::$cachePrefix . $this->all);
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
            $do = $this->store(Config\Config::$cachePrefix . $key, gzcompress($data, 9), $ttl);
            if ($do) {
                $value = $this->fetch(Config\Config::$cachePrefix . $this->all);
                if ($value === false) {
                    $value = array();
                } 
                if (!isset($value[$found[1]]) || !in_array($key, $value[$found[1]])) {
                    $value[$found[1]][] = $key;
                    $do2 = $this->store(Config\Config::$cachePrefix . $this->all, $value);
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
