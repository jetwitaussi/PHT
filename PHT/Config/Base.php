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

namespace PHT\Config;

use PHT\Exception;
use PHT\Log;

class Base
{
    /**
     * @param array $config
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public function __construct($config)
    {
        if (isset($config['LOG_TYPE'])) {
            Config::$logType = $config['LOG_TYPE'];
        }
        if (isset($config['LOG_LEVEL'])) {
            Config::$logLevel = $config['LOG_LEVEL'];
        }
        if (isset($config['LOG_TIME'])) {
            Config::$logTime = $config['LOG_TIME'];
        }
        if (isset($config['LOG_FILE'])) {
            Config::$logFile = $config['LOG_FILE'];
        }
        $log = Log\Logger::getInstance();
        if (!isset($config['CONSUMER_KEY']) || !isset($config['CONSUMER_SECRET'])) {
            $log->critical('[PHT] Missing configuration parameters: CONSUMER_KEY and CONSUMER_SECRET');
            throw new Exception\InvalidArgumentException("CONSUMER_KEY and CONSUMER_SECRET must be defined in PHT config");
        }
        Config::$consumerKey = $config['CONSUMER_KEY'];
        Config::$consumerSecret = $config['CONSUMER_SECRET'];
        if (isset($config['HT_SUPPORTER'])) {
            Config::$htSupporter = $config['HT_SUPPORTER'];
        }
        if (isset($config['CACHE']) && in_array($config['CACHE'], array('none', 'session', 'apc', 'memory'))) {
            $log->debug('[PHT] Use cache: ' . $config['CACHE']);
            Config::$cache = $config['CACHE'];
        }
        if (isset($config['CACHE']) && $config['CACHE'] == 'memcached' && isset($config['MEMCACHED_SERVER_IP'])) {
            $log->debug('[PHT] Use cache: ' . $config['CACHE']);
            Config::$cache = $config['CACHE'];
            Config::$memcachedIp = $config['MEMCACHED_SERVER_IP'];
        }
        if (isset($config['CACHE']) && $config['CACHE'] == 'memcached' && isset($config['MEMCACHED_SERVER_PORT'])) {
            Config::$cache = $config['CACHE'];
            Config::$memcachedPort = $config['MEMCACHED_SERVER_PORT'];
        }
        if (isset($config['CACHE_PREFIX'])) {
            Config::$cachePrefix = $config['CACHE_PREFIX'];
        }
        if (isset($config['CACHE_TTL'])) {
            Config::$cacheTtl = $config['CACHE_TTL'];
        }
        if (isset($config['PROXY_IP'])) {
            Config::$proxyIp = $config['PROXY_IP'];
        }
        if (isset($config['PROXY_PORT'])) {
            Config::$proxyPort = $config['PROXY_PORT'];
        }
        if (isset($config['PROXY_LOGIN'])) {
            Config::$proxyLogin = $config['PROXY_LOGIN'];
        }
        if (isset($config['PROXY_PASSWORD'])) {
            Config::$proxyPasswd = $config['PROXY_PASSWORD'];
        }
        if (isset($config['OAUTH_TOKEN'])) {
            Config::$oauthToken = $config['OAUTH_TOKEN'];
        }
        if (isset($config['OAUTH_TOKEN_SECRET'])) {
            Config::$oauthTokenSecret = $config['OAUTH_TOKEN_SECRET'];
        }
        if (isset($config['STARTING_INDEX'])) {
            Config::$forIndex = $config['STARTING_INDEX'];
        }
    }
}
