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

use PHT\Network;
use PHT\Xml;
use PHT\Config;

class World
{
    /**
     * @param integer $id
     * @return \PHT\Xml\World\Region
     */
    public static function region($id)
    {
        $url = Network\Request::buildUrl(array('file' => 'regiondetails', 'regionID' => $id, 'version' => Config\Version::REGIONDETAILS));
        return new Xml\World\Region(Network\Request::fetchUrl($url));
    }

    /**
     * @param boolean $regions
     * @return \PHT\Xml\World\World
     */
    public static function world($regions = false)
    {
        $params = array('file' => 'worlddetails', 'version' => Config\Version::WORLDDETAILS);
        if ($regions === true) {
            $params['includeRegions'] = 'true';
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\World\World(Network\Request::fetchUrl($url));
    }

    /**
     *
     * @param integer $leagueId
     * @param integer $countryId
     * @param boolean $regions
     * @return \PHT\Xml\World\Country
     */
    public static function country($leagueId = null, $countryId = null, $regions = false)
    {
        $params = array('file' => 'worlddetails', 'version' => Config\Version::WORLDDETAILS);
        if ($leagueId !== null) {
            $params['leagueID'] = $leagueId;
        }
        if ($countryId !== null) {
            $params['countryID'] = $countryId;
        }
        if ($regions === true) {
            $params['includeRegions'] = 'true';
        }
        $url = Network\Request::buildUrl($params);
        $world = new Xml\World\World(Network\Request::fetchUrl($url));
        return $world->getCountryByIndex(Config\Config::$forIndex);
    }

    /**
     * @param integer $cupId
     * @param integer $round
     * @param integer $season
     * @param integer $afterMatchId
     * @return \PHT\Xml\World\Cup
     */
    public static function cup($cupId, $round = null, $season = null, $afterMatchId = null)
    {
        $params = array('file' => 'cupmatches', 'cupID' => $cupId, 'version' => Config\Version::CUPMATCHES);
        if ($round !== null) {
            $params['cupRound'] = $round;
        }
        if ($season !== null) {
            $params['season'] = $season;
        }
        if ($afterMatchId !== null) {
            $params['startAfterMatchId'] = $afterMatchId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\World\Cup(Network\Request::fetchUrl($url));
    }
}
