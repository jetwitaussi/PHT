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

namespace PHT;

use PHT\Config;
use PHT\Network;

class Connection extends Config\Base
{
    /**
     * Get hattrick authorize url to get a permanent chpp access
     *
     * @param string $callback
     * @param string $scope
     * @return boolean|\PHT\Config\AuthData
     */
    public function getPermanentAuthorization($callback, $scope = null)
    {
        return Network\Auth::getAuthorizeUrl($callback, $scope);
    }

    /**
     * Get hattrick authenticate url to get a temporary chpp access
     *
     * @param string $callback
     * @param string $scope
     * @return boolean|\PHT\Config\AuthData
     */
    public function getTemporaryAuthorization($callback, $scope = null)
    {
        return Network\Auth::getAuthenticateUrl($callback, $scope);
    }

    /**
     * Get chpp token to be able to request xml files
     *
     * @param string $firstOauthTokenSecret
     * @param string $oauthToken
     * @param string $oauthVerifier
     * @return boolean|\PHT\Config\AuthData
     */
    public function getChppAccess($firstOauthTokenSecret, $oauthToken, $oauthVerifier)
    {
        return Network\Auth::retrieveAccessToken($firstOauthTokenSecret, $oauthToken, $oauthVerifier);
    }

    /**
     * Check if chpp access is still valid
     *
     * @param string $oauthToken Use current if not set
     * @param string $oauthTokenSecret Use current if not set
     * @return \PHT\Xml\Token
     */
    public function checkChppAccess($oauthToken, $oauthTokenSecret)
    {
        return Network\Auth::checkToken($oauthToken, $oauthTokenSecret);
    }

    /**
     * Invalidate a token
     *
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     */
    public function deleteChppAccess($oauthToken, $oauthTokenSecret)
    {
        Network\Auth::invalidateToken($oauthToken, $oauthTokenSecret);
    }

    /**
     * Check if chpp server id down or not
     *
     * @param integer $wait Number of second to wait
     * @param integer $retry Number of retry if server is down
     * @return boolean
     */
    public function isChppDown($wait = 3, $retry = 1)
    {
        do {
            $up = Network\Request::pingChppServer($wait * 1000);
        } while (--$retry > 0 && $up === false);

        return !$up;
    }
}
