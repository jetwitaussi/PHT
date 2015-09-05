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

namespace PHT\Wrapper;

use PHT\Xml;
use PHT\Config;
use PHT\Network;

class Federation
{
    /**
     * @param array $params
     * @return \PHT\Xml\Federations\Listing
     */
    public static function search($params)
    {
        $all = array_merge(array('file' => 'alliances', 'version' => Config\Version::ALLIANCES), $params);
        $url = Network\Request::buildUrl($all);
        return new Xml\Federations\Listing(Network\Request::fetchUrl($url), $params);
    }

    /**
     * @param integer $federationId
     * @return \PHT\Xml\Federations\Federation
     */
    public static function detail($federationId)
    {
        $url = Network\Request::buildUrl(array('file' => 'alliancedetails', 'allianceID' => $federationId, 'version' => Config\Version::ALLIANCEDETAILS));
        return new Xml\Federations\Federation(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $federationId
     * @param string $onlyLetter
     * @return \PHT\Xml\Federations\Members
     */
    public static function members($federationId, $onlyLetter = null)
    {
        if ($onlyLetter === null) {
            $url = Network\Request::buildUrl(array('file' => 'alliancedetails', 'actionType' => 'members', 'allianceID' => $federationId, 'version' => Config\Version::ALLIANCEDETAILS));
            return new Xml\Federations\Members(Network\Request::fetchUrl($url));
        } else {
            $letter = ord(strtoupper($onlyLetter));
            if ($letter < 65 || $letter > 90) {
                $letter = 0;
            }
            $url = Network\Request::buildUrl(array('file' => 'alliancedetails', 'actionType' => 'membersSubset', 'allianceID' => $federationId, 'subSet' => $letter, 'version' => Config\Version::ALLIANCEDETAILS));
            return new Xml\Federations\Members(Network\Request::fetchUrl($url));
        }
    }

    /**
     * @param integer $federationId
     * @return \PHT\Xml\Federations\Roles
     */
    public static function roles($federationId)
    {
        $url = Network\Request::buildUrl(array('file' => 'alliancedetails', 'actionType' => 'roles', 'allianceID' => $federationId, 'version' => Config\Version::ALLIANCEDETAILS));
        return new Xml\Federations\Roles(Network\Request::fetchUrl($url));
    }
}
