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

class National
{
    /**
     * @param integer $id
     * @return \PHT\Xml\Team\National
     */
    public static function team($id)
    {
        $url = Network\Request::buildUrl(array('file' => 'nationalteamdetails', 'teamID' => $id, 'version' => Config\Version::NATIONALTEAMDETAILS));
        return new Xml\Team\National(Network\Request::fetchUrl($url));
    }

    /**
     * @param boolean $u20
     * @return \PHT\Xml\National\Teams
     */
    public static function teams($u20 = false)
    {
        $url = Network\Request::buildUrl(array('file' => 'nationalteams', 'leagueOfficeTypeID' => ($u20 ? 4 : 2), 'version' => Config\Version::NATIONALTEAMS));
        return new Xml\National\Teams(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\National\Players
     */
    public static function players($teamId)
    {
        $url = Network\Request::buildUrl(array('file' => 'nationalplayers', 'actionType' => 'view', 'teamID' => $teamId, 'version' => Config\Version::NATIONALPLAYERS));
        return new Xml\National\Players(Network\Request::fetchUrl($url));
    }

    /**
     * @param boolean $u20
     * @return \PHT\Xml\National\Matches
     */
    public static function matches($u20 = false)
    {
        $url = Network\Request::buildUrl(array('file' => 'nationalteammatches', 'leagueOfficeTypeID' => ($u20 ? 4 : 2), 'version' => Config\Version::NATIONALTEAMMATCHES));
        return new Xml\National\Matches(Network\Request::fetchUrl($url));
    }
}
