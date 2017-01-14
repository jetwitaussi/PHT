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

namespace PHT\Network;

use PHT\Config;
use PHT\Exception;
use PHT\Log;
use PHT\Cache;

class Request
{
    const SIGN_METHOD = 'HMAC-SHA1';
    const VERSION = '1.0';

    /**
     * @param string $file
     * @param array $params
     * @return string
     */
    public static function buildOauthUrl($file, $params)
    {
        $url = $file . "?";
        foreach ($params as $param => $value) {
            $url .= $param . "=" . self::urlencodeRfc3986($value) . "&";
        }
        return substr($url, 0, -1);
    }

    /**
     * @param array $params Url parameters
     * @param array $postParams Post parameters
     * @return string
     */
    public static function buildUrl($params, $postParams = array())
    {
        if (Config\Config::$htSupporter == 1 || Config\Config::$htSupporter == -1) {
            $params['overrideIsSupporter'] = Config\Config::$htSupporter;
        }
        $params = array_merge($params, array(
            'oauth_consumer_key' => Config\Config::$consumerKey,
            'oauth_signature_method' => self::SIGN_METHOD,
            'oauth_timestamp' => self::getTimestamp(),
            'oauth_nonce' => self::getNonce(),
            'oauth_token' => Config\Config::$oauthToken,
            'oauth_version' => self::VERSION
        ));
        $signature = self::buildSignature(Url::XML_SERVER . Url::CHPP_URL, array_merge($params, $postParams), Config\Config::$oauthTokenSecret, ($postParams == array() ? 'GET' : 'POST'));
        $params['oauth_signature'] = $signature;
        uksort($params, 'strcmp');
        Log\Logger::getInstance()->debug("[NETWORK] Query params:", $params);
        return self::buildOauthUrl(Url::XML_SERVER . Url::CHPP_URL, $params);
    }

    /**
     * @param string $url
     * @param boolean $check
     * @param array $postParams
     * @return string
     */
    public static function fetchUrl($url, $check = true, $postParams = array())
    {
        $log = Log\Logger::getInstance();
        $cacheKey = null;
        $infos = parse_url($url);
        if ($infos['path'] == Url::CHPP_URL && !count($postParams)) {
            $params = array();
            parse_str($infos['query'], $params);
            $params = array_diff_key($params, array('oauth_consumer_key' => '', 'oauth_signature_method' => '', 'oauth_timestamp' => '', 'oauth_nonce' => '', 'oauth_version' => '', 'oauth_signature' => ''));
            $key = array();
            foreach ($params as $k => $v) {
                $key[] = $k . '=' . $v;
            }
            $cacheKey = implode('&', $key);
            $log->debug('[CACHE] Get key: ' . $cacheKey);
            $xmlData = Cache\Driver::getInstance()->get($cacheKey);
            if ($xmlData !== false) {
                $log->debug('[CACHE] Hit');
                if ($check === true) {
                    self::checkXmlData($xmlData);
                }
                return $xmlData;
            }
            $log->debug('[CACHE] Miss');
        }
        $log->debug('[NETWORK] Init request: ' . $url);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        if (Config\Config::$proxyIp != '') {
            $log->debug('[NETWORK] Use proxy: ' . Config\Config::$proxyIp . ' ' . Config\Config::$proxyPort);
            curl_setopt($curl, CURLOPT_PROXY, Config\Config::$proxyIp);
            if (Config\Config::$proxyPort) {
                curl_setopt($curl, CURLOPT_PROXYPORT, Config\Config::$proxyPort);
            }
            if (Config\Config::$proxyLogin && Config\Config::$proxyPasswd) {
                $log->debug('[NETWORK] Use proxy authentication');
                curl_setopt($curl, CURLOPT_PROXYUSERPWD, Config\Config::$proxyLogin . ':' . Config\Config::$proxyPasswd);
            }
        }
        if (count($postParams)) {
            $log->debug("[NETWORK] POST data:", $postParams);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postParams));
        } else {
            curl_setopt($curl, CURLOPT_POST, false);
        }
        $log->debug("[NETWORK] Start fetching url");
        $startTime = microtime(true);
        $xmlData = curl_exec($curl);
        $endTime = microtime(true);
        $log->debug("[NETWORK] Fetch done in: " . ($endTime - $startTime));
        curl_close($curl);
        if ($check === true) {
            self::checkXmlData($xmlData);
        }
        if ($cacheKey !== null) {
            $done = Cache\Driver::getInstance()->set($cacheKey, $xmlData, Config\Config::$cacheTtl);
            if($done) {
                $log->debug('[CACHE] Save');
            } else {
                $log->debug('[CACHE] Fail');
            }
        }
        return $xmlData;
    }

    /**
     * @param string $xmlData
     * @throws \PHT\Exception\NetworkException
     * @throws \PHT\Exception\ChppException
     */
    public static function checkXmlData($xmlData)
    {
        $tmpXml = xml_parser_create();
        if (!xml_parse($tmpXml, $xmlData, true)) {
            Log\Logger::getInstance()->critical("[API] Invalid xml: " . $xmlData);
            throw new Exception\NetworkException($xmlData);
        }
        xml_parser_free($tmpXml);

        $tmpXml = new \DOMDocument('1.0', 'UTF-8');
        $tmpXml->loadXML($xmlData);
        $filename = $tmpXml->getElementsByTagName('FileName');
        if ($filename->length == 0) {
            Log\Logger::getInstance()->critical("[API] CHPP error: " . $xmlData);
            throw new Exception\ChppException($xmlData);
        }
        if ($filename->item(0)->nodeValue == Url::ERROR_FILE) {
            Log\Logger::getInstance()->critical("[API] CHPP Error: " . $xmlData);
            throw new Exception\ChppException($xmlData);
        }
    }

    /**
     * Get oauth signature
     *
     * @param string $url
     * @param array $params
     * @param string $token
     * @param string $method
     * @return string
     */
    public static function buildSignature($url, $params, $token = '', $method = 'GET')
    {
        $parts = array($method, $url, self::buildHttpQuery($params));
        $parts = implode('&', self::urlencodeRfc3986($parts));
        Log\Logger::getInstance()->debug('[OAUTH] Base string: ' . $parts);
        $key_parts = array(Config\Config::$consumerSecret, $token);
        $key = implode('&', self::urlencodeRfc3986($key_parts));
        $sign = base64_encode(hash_hmac('sha1', $parts, $key, true));
        Log\Logger::getInstance()->debug('[OAUTH] Signature: ' . $sign);
        return $sign;
    }

    /**
     * Get url parameters for oauth query
     *
     * @param array $params
     * @return string
     */
    public static function buildHttpQuery($params)
    {
        if (!count($params)) {
            return '';
        }
        $keys = self::urlencodeRfc3986(array_keys($params));
        $values = self::urlencodeRfc3986(array_values($params));
        $newparams = array_combine($keys, $values);
        uksort($newparams, 'strcmp');
        $pairs = array();
        foreach ($newparams as $parameter => $value) {
            if (is_array($value)) {
                sort($value, SORT_STRING);
                foreach ($value as $duplicate_value) {
                    $pairs[] = $parameter . '=' . $duplicate_value;
                }
            } else {
                $pairs[] = $parameter . '=' . $value;
            }
        }
        return implode('&', $pairs);
    }

    /**
     * Urlencode parameters properly for oauth query
     *
     * @param array|string $input
     * @return array|string
     */
    public static function urlencodeRfc3986($input)
    {
        if (is_array($input)) {
            return array_map(array(__CLASS__, 'urlencodeRfc3986'), $input);
        }
        return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($input)));
    }

    /**
     * @return integer
     */
    public static function getTimestamp()
    {
        return time();
    }

    /**
     * @return string
     */
    public static function getNonce()
    {
        return sha1(microtime() . mt_rand());
    }

    /**
     * Return if chpp server is available or not
     *
     * @param integer $timeout (Timeout in milliseconds)
     * @return boolean
     */
    public static function pingChppServer($timeout = 1000)
    {
        $log = Log\Logger::getInstance();
        $log->debug('[NETWORK] Init request: ' . Url::XML_SERVER);
        $curl = curl_init(Url::XML_SERVER);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, (int)$timeout);
        if (Config\Config::$proxyIp != '') {
            $log->debug('[NETWORK] Use proxy: ' . Config\Config::$proxyIp . ' ' . Config\Config::$proxyPort);
            curl_setopt($curl, CURLOPT_PROXY, Config\Config::$proxyIp);
            if (Config\Config::$proxyPort) {
                curl_setopt($curl, CURLOPT_PROXYPORT, Config\Config::$proxyPort);
            }
            if (Config\Config::$proxyLogin && Config\Config::$proxyPasswd) {
                $log->debug('[NETWORK] Use proxy authentication');
                curl_setopt($curl, CURLOPT_PROXYUSERPWD, Config\Config::$proxyLogin . ':' . Config\Config::$proxyPasswd);
            }
        }
        $log->debug("[NETWORK] Start fetching url");
        $startTime = microtime(true);
        curl_exec($curl);
        $endTime = microtime(true);
        $log->debug("[NETWORK] Fetch done in: " . ($endTime - $startTime));
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $online = (int)$code === 200;
        if (!$online) {
            $log->critical('[NETWORK] CHPP server seems down (' . $code . ')');
        }
        return $online;
    }
}
