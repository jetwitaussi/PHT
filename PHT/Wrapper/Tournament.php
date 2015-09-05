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

class Tournament
{
    /**
     * @param integer $teamId
     * @return \PHT\Xml\Tournaments\Listing
     */
    public static function listing($teamId = null)
    {
        $params = array('file' => 'tournamentlist', 'version' => Config\Version::TOURNAMENTLIST);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Tournaments\Listing(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $tournamentId
     * @return \PHT\Xml\Tournaments\Tournament
     */
    public static function tournament($tournamentId)
    {
        $url = Network\Request::buildUrl(array('file' => 'tournamentdetails', 'tournamentId' => $tournamentId, 'version' => Config\Version::TOURNAMENTDETAILS));
        return new Xml\Tournaments\Tournament(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $tournamentId
     * @return \PHT\Xml\Tournaments\League
     */
    public static function league($tournamentId)
    {
        $url = Network\Request::buildUrl(array('file' => 'tournamentleaguetables', 'tournamentId' => $tournamentId, 'version' => Config\Version::TOURNAMENTLEAGUETABLES));
        return new Xml\Tournaments\League(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $tournamentId
     * @return \PHT\Xml\Tournaments\Matches
     */
    public static function matches($tournamentId)
    {
        $url = Network\Request::buildUrl(array('file' => 'tournamentfixtures', 'tournamentId' => $tournamentId, 'version' => Config\Version::TOURNAMENTFIXTURES));
        return new Xml\Tournaments\Matches(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\Tournaments\Ladders\Listing
     */
    public static function ladders($teamId = null)
    {
        $params = array('file' => 'ladderlist', 'version' => Config\Version::LADDERLIST);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Tournaments\Ladders\Listing(Network\Request::fetchUrl($url), $teamId);
    }

    /**
     * @param integer $ladderId
     * @param integer $teamId
     * @param integer $page
     * @param integer $size
     * @return \PHT\Xml\Tournaments\Ladders\Ladder
     */
    public static function ladder($ladderId, $teamId = null, $page = 0, $size = 25)
    {
        $params = array('file' => 'ladderdetails', 'ladderId' => $ladderId, 'version' => Config\Version::LADDERDETAILS, 'pageindex' => $page, 'pagesize' => $size);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Tournaments\Ladders\Ladder(Network\Request::fetchUrl($url), $teamId);
    }
}
