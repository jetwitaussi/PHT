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
use PHT\Utils;
use PHT\Network;

class Stats
{
    /**
     * @param integer $leagueId
     * @return \PHT\Xml\Stats\Training\Listing
     */
    public static function training($leagueId = null)
    {
        $params = array('file' => 'training', 'actionType' => 'stats', 'version' => Config\Version::TRAINING);
        if ($leagueId !== null) {
            $params['leagueID'] = $leagueId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Stats\Training\Listing(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @param string $type (see \PHT\Config\Config STATS_NATIONAL_* constants)
     * @param boolean $showAll
     * @return \PHT\Xml\Stats\National\Players
     */
    public static function nationalplayers($teamId, $type = Config\Config::STATS_NATIONAL_NT, $showAll = true)
    {
        $params = array('file' => 'nationalplayers', 'actionType' => 'supporterStats', 'teamID' => $teamId, 'matchTypeCategory' => $type, 'showAll' => $showAll, 'version' => Config\Version::NATIONALPLAYERS);
        $url = Network\Request::buildUrl($params);
        return new Xml\Stats\National\Players(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $arenaId
     * @param string $matchType (see \PHT\Config\Config STATS_ARENA_* constants)
     * @param string $startDate (format should be : yyyy-mm-dd)
     * @param string $endDate (format should be : yyyy-mm-dd)
     * @return \PHT\Xml\Stats\Arena\User
     */
    public static function arena($arenaId = null, $matchType = Config\Config::STATS_ARENA_ALL, $startDate = null, $endDate = null)
    {
        Utils\Date::analyse($startDate, $endDate, false);
        $params = array('file' => 'arenadetails', 'statsType' => 'MyArena', 'matchType' => $matchType, 'version' => Config\Version::ARENADETAILS);
        if ($arenaId !== null) {
            $params['arenaID'] = $arenaId;
        }
        if ($startDate !== null) {
            $params['firstDate'] = $startDate;
        }
        if ($endDate !== null) {
            $params['lastDate'] = $endDate;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Stats\Arena\User(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $leagueId (by default 0 = global statistics)
     * @return \PHT\Xml\Stats\Arena\Arenas
     */
    public static function arenas($leagueId = 0)
    {
        $params = array('file' => 'arenadetails', 'statsType' => 'OtherArenas', 'statsLeagueID' => $leagueId, 'version' => Config\Version::ARENADETAILS);
        $url = Network\Request::buildUrl($params);
        return new Xml\Stats\Arena\Arenas(Network\Request::fetchUrl($url));
    }
}
