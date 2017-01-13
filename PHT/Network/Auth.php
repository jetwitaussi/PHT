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
use PHT\Log;
use PHT\Xml;

class Auth
{
    /**
     * @param string $callback
     * @param string $scope
     * @return boolean|\PHT\Config\AuthData
     */
    public static function getAuthorizeUrl($callback = null, $scope = null)
    {
        return self::getAuthUrl(Url::AUTH_URL, $callback, $scope);
    }

    /**
     * @param string $callback
     * @param string $scope
     * @return boolean|\PHT\Config\AuthData
     */
    public static function getAuthenticateUrl($callback = null, $scope = null)
    {
        return self::getAuthUrl(Url::AUTHC_URL, $callback, $scope);
    }

    /**
     * @param string $authPage
     * @param string $callback
     * @param string $scope
     * @return boolean|\PHT\Config\AuthData
     */
    protected static function getAuthUrl($authPage, $callback, $scope)
    {
        $log = Log\Logger::getInstance();
        $params = array(
            'oauth_consumer_key' => Config\Config::$consumerKey,
            'oauth_signature_method' => Request::SIGN_METHOD,
            'oauth_timestamp' => Request::getTimestamp(),
            'oauth_nonce' => Request::getNonce(),
            'oauth_callback' => $callback,
            'oauth_version' => Request::VERSION
        );
        $signature = Request::buildSignature(Url::OAUTH_SERVER . Url::REQUEST_URL, $params);
        $params['oauth_signature'] = $signature;
        uksort($params, 'strcmp');
        $log->debug("[NETWORK] Query params:", $params);
        $url = Request::buildOauthUrl(Url::OAUTH_SERVER . Url::REQUEST_URL, $params);
        $oauth_token = $oauth_token_secret = null;
        $return = Request::fetchUrl($url, false);
        $result = explode('&', $return);
        foreach ($result as $val) {
            $t = explode('=', $val);
            ${$t[0]} = urldecode($t[1]);
        }

        if (!isset($oauth_token) || !isset($oauth_token_secret)) {
            $log->error('[OAUTH] No request token received');
            return false;
        }

        $url = Url::OAUTH_SERVER . $authPage . '?oauth_token=' . urlencode($oauth_token);
        if ($scope !== null) {
            $url .= '&scope=' . $scope;
        }

        $log->debug('[OAUTH] Request token: ' . $oauth_token);
        $log->debug('[OAUTH] Request token secret: ' . $oauth_token_secret);
        $log->debug('[OAUTH] Authorize url: ' . $url);

        $conf = new Config\AuthData();
        $conf->temporaryToken = $oauth_token_secret;
        $conf->url = $url;

        return $conf;
    }

    /**
     * @param string $oauthFirstTokenSecret
     * @param string $token
     * @param string $verifier
     * @return boolean|\PHT\Config\AuthData
     */
    public static function retrieveAccessToken($oauthFirstTokenSecret, $token, $verifier)
    {
        $log = Log\Logger::getInstance();
        $params = array(
            'oauth_consumer_key' => Config\Config::$consumerKey,
            'oauth_signature_method' => Request::SIGN_METHOD,
            'oauth_timestamp' => Request::getTimestamp(),
            'oauth_nonce' => Request::getNonce(),
            'oauth_version' => Request::VERSION,
            'oauth_token' => $token,
            'oauth_verifier' => $verifier,
        );
        $signature = Request::buildSignature(Url::OAUTH_SERVER . Url::ACCESS_URL, $params, $oauthFirstTokenSecret);
        $params['oauth_signature'] = $signature;
        uksort($params, 'strcmp');
        $log->debug("[NETWORK] Query params:", $params);
        $url = Request::buildOauthUrl(Url::OAUTH_SERVER . Url::ACCESS_URL, $params);
        $oauth_token = $oauth_token_secret = null;
        $return = Request::fetchUrl($url, false);
        $result = explode('&', $return);
        foreach ($result as $val) {
            $t = explode('=', $val);
            ${$t[0]} = urldecode($t[1]);
        }

        if (!isset($oauth_token) || !isset($oauth_token_secret)) {
            $log->error('[OAUTH] No access token received');
            return false;
        }

        $log->debug('[OAUTH] Access token: ' . $oauth_token);
        $log->debug('[OAUTH] Access token secret: ' . $oauth_token_secret);

        $conf = new Config\AuthData();
        $conf->oauthToken = $oauth_token;
        $conf->oauthTokenSecret = $oauth_token_secret;

        return $conf;
    }

    /**
     * Returns xml token object
     *
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     * @return \PHT\Xml\Token
     */
    public static function checkToken($oauthToken = null, $oauthTokenSecret = null)
    {
        if ($oauthToken === null) {
            $oauthToken = Config\Config::$oauthToken;
        }
        if ($oauthTokenSecret === null) {
            $oauthTokenSecret = Config\Config::$oauthTokenSecret;
        }
        $params = array(
            'oauth_consumer_key' => Config\Config::$consumerKey,
            'oauth_signature_method' => Request::SIGN_METHOD,
            'oauth_timestamp' => Request::getTimestamp(),
            'oauth_nonce' => Request::getNonce(),
            'oauth_version' => Request::VERSION,
            'oauth_token' => $oauthToken,
        );
        $signature = Request::buildSignature(Url::OAUTH_SERVER . Url::CHECK_URL, $params, $oauthTokenSecret);
        $params['oauth_signature'] = $signature;
        uksort($params, 'strcmp');
        Log\Logger::getInstance()->debug("[NETWORK] Query params:", $params);
        $url = Request::buildOauthUrl(Url::OAUTH_SERVER . Url::CHECK_URL, $params);
        return new Xml\Token(Request::fetchUrl($url, false));
    }

    /**
     * Invalidate a token
     *
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     */
    public static function invalidateToken($oauthToken = null, $oauthTokenSecret = null)
    {
        if ($oauthToken === null) {
            $oauthToken = Config\Config::$oauthToken;
        }
        if ($oauthTokenSecret === null) {
            $oauthTokenSecret = Config\Config::$oauthTokenSecret;
        }
        $params = array(
            'oauth_consumer_key' => Config\Config::$consumerKey,
            'oauth_signature_method' => Request::SIGN_METHOD,
            'oauth_timestamp' => Request::getTimestamp(),
            'oauth_nonce' => Request::getNonce(),
            'oauth_version' => Request::VERSION,
            'oauth_token' => $oauthToken,
        );
        $signature = Request::buildSignature(Url::OAUTH_SERVER . Url::INVAL_URL, $params, $oauthTokenSecret);
        $params['oauth_signature'] = $signature;
        uksort($params, 'strcmp');
        Log\Logger::getInstance()->debug("[NETWORK] Query params:", $params);
        $url = Request::buildOauthUrl(Url::OAUTH_SERVER . Url::INVAL_URL, $params);
        Request::fetchUrl($url, false);
    }
}
