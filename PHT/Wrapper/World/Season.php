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

class Season
{
    /**
     * @param integer $leagueLevelId
     * @param integer $season
     * @return \PHT\Xml\World\League\Season\Senior
     */
    public static function senior($leagueLevelId = null, $season = null)
    {
        $params = array('file' => 'leaguefixtures', 'version' => Config\Version::LEAGUEFIXTURES);
        if ($leagueLevelId !== null) {
            $params['leagueLevelUnitID'] = $leagueLevelId;
        }
        if ($season !== null) {
            $params['season'] = $season;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\World\League\Season\Senior(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $leagueId
     * @param integer $season
     * @return \PHT\Xml\World\League\Season\Youth
     */
    public static function youth($leagueId = null, $season = null)
    {
        $params = array('file' => 'youthleaguefixtures', 'version' => Config\Version::YOUTHLEAGUEFIXTURES);
        if ($leagueId !== null) {
            $params['youthleagueid'] = $leagueId;
        }
        if ($season !== null) {
            $params['season'] = $season;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\World\League\Season\Youth(Network\Request::fetchUrl($url));
    }
}
