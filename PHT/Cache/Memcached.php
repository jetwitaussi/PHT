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

class Memcached implements CacheInterface
{
    private $obj;
    private $all = 'ALL';

    /**
     * Instantiate memcached server
     */
    public function __construct()
    {
        $this->obj = new \Memcached();
        $this->obj->setOption(\Memcached::OPT_PREFIX_KEY, Config\Config::$cachePrefix);
        $this->obj->addServer(Config\Config::$memcachedIp, Config\Config::$memcachedPort);
    }

    /**
     * Delete keys related to xml filename
     *
     * @param string $file
     */
    public function clear($file)
    {
        $all = $this->obj->get($this->all);
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
        if (count($all) != count($new)) {
            $this->obj->set($this->all, $new, 0);
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
        $key = str_replace(' ', '_', $key);  
        return $this->obj->delete(sha1($key), 0);
    }

    /**
     * Retreive key value
     *
     * @param string $key
     * @return string|boolean
     */
    public function get($key)
    {
        $key = str_replace(' ', '_', $key);
        $value = $this->obj->get(sha1($key));
        if ($value !== false) {
            return $value;
        }
        return false;
    }

    /**
     * Purge all keys
     */
    public function purge()
    {
        $all = $this->obj->get($this->all);
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
            $key = str_replace(' ', '_', $key);
            $sha = sha1($key);
            $do = $this->obj->set($sha, $data, $ttl);
            if ($do) {
                $value = $this->obj->get($this->all);
                if ($value === false || !isset($value[$found[1]])) {
                    $value[$found[1]] = array();
                }
                if(!in_array($sha, $value[$found[1]])) {
                    $value[$found[1]][] = $sha;
                    $do2 = $this->obj->set($this->all, $value, 0);
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
