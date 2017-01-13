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

namespace PHT\Wrapper\Team;

use PHT\Xml;
use PHT\Config;
use PHT\Network;
use PHT\Utils;
use PHT\Exception;

class Youth
{
    /**
     * @param integer $id
     * @return \PHT\Xml\Team\Youth
     */
    public static function team($id)
    {
        $params = array('file' => 'youthteamdetails', 'youthTeamId' => $id, 'version' => Config\Version::YOUTHTEAMDETAILS);
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Youth(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $id
     * @param boolean $unlockSkills
     * @param boolean $showScoutCall
     * @param boolean $showLastMatch
     * @param string $orderBy
     * @return \PHT\Xml\Team\Youth\Players
     */
    public static function players($id = null, $unlockSkills = false, $showScoutCall = true, $showLastMatch = true, $orderBy = null)
    {
        $params = array('file' => 'youthplayerlist', 'version' => Config\Version::YOUTHPLAYERLIST, 'actionType' => 'details');
        if ($unlockSkills === true) {
            $params['actionType'] = 'unlockskills';
        }
        if ($id !== null) {
            $params['youthTeamId'] = $id;
        }
        if ($showScoutCall === true) {
            $params['showScoutCall'] = 'true';
        }
        if ($showLastMatch === true) {
            $params['showLastMatch'] = 'true';
        }
        if ($orderBy !== null) {
            $params['orderBy'] = $orderBy;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Youth\Players(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\Team\Youth\Scouts
     */
    public static function scouts($teamId)
    {
        $params = array('file' => 'youthteamdetails', 'youthTeamId' => $teamId, 'version' => Config\Version::YOUTHPLAYERLIST, 'showScouts' => 'true');
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Youth\Scouts(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @param string $showBeforeDate
     * @return \PHT\Xml\Team\Match\Listing
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public static function matches($teamId, $showBeforeDate = null)
    {
        if ($showBeforeDate !== null && !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $showBeforeDate)) {
            throw new Exception\InvalidArgumentException('Parameter $showBeforeDate must have format yyyy-mm-dd');
        }
        $params = array('file' => 'matches', 'teamID' => $teamId, 'isYouth' => 'true', 'version' => Config\Version::MATCHES);
        if ($showBeforeDate !== null) {
            $params['lastMatchDate'] = $showBeforeDate;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Match\Listing(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @param string $startDate
     * @param string $endDate
     * @return \PHT\Xml\Team\Match\Archive
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public static function archives($teamId, $startDate = null, $endDate = null)
    {
        if ($startDate !== null && !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $startDate)) {
            throw new Exception\InvalidArgumentException('Parameter $startDate must have format yyyy-mm-dd');
        }
        if ($endDate !== null && !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $endDate)) {
            throw new Exception\InvalidArgumentException('Parameter $endDate must have format yyyy-mm-dd');
        }

        Utils\Date::analyse($startDate, $endDate);

        $params = array('file' => 'matchesarchive', 'teamID' => $teamId, 'isYouth' => 'true', 'version' => Config\Version::MATCHESARCHIVE);
        if ($startDate !== null) {
            $params['firstMatchDate'] = $startDate;
        }
        if ($endDate !== null) {
            $params['lastMatchDate'] = $endDate;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Match\Archive(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\Team\Avatars
     */
    public static function avatars($teamId = null)
    {
        $params = array('file' => 'youthavatars', 'version' => Config\Version::YOUTHAVATARS);
        if ($teamId !== null) {
            $params['youthTeamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Avatars(Network\Request::fetchUrl($url), Config\Config::YOUTH, $teamId);
    }
}
