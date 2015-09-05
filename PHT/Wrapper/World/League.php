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

namespace PHT\Wrapper\World;

use PHT\Xml;
use PHT\Config;
use PHT\Network;

class League
{
    /**
     * @param integer $id
     * @return \PHT\Xml\World\League\Youth
     */
    public static function youth($id)
    {
        $url = Network\Request::buildUrl(array('file' => 'youthleaguedetails', 'youthleagueid' => $id, 'version' => Config\Version::YOUTHTEAMDETAILS));
        return new Xml\World\League\Youth(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $id
     * @return \PHT\Xml\World\League\Senior
     */
    public static function senior($id)
    {
        $url = Network\Request::buildUrl(array('file' => 'leaguedetails', 'leagueLevelUnitID' => $id, 'version' => Config\Version::LEAGUEDETAILS));
        return new Xml\World\League\Senior(Network\Request::fetchUrl($url));
    }
}
