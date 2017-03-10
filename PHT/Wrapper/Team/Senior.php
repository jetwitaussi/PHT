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

class Senior
{
    /**
     * @param integer $id
     * @param integer $userId
     * @return \PHT\Xml\Team\Senior
     */
    public static function team($id = null, $userId = null)
    {
        $params = array('file' => 'teamdetails', 'version' => Config\Version::TEAMDETAILS);
        if ($id !== null) {
            $params['teamID'] = $id;
        }
        if ($userId !== null) {
            $params['userID'] = $userId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Senior(Network\Request::fetchUrl($url), $id);
    }

    /**
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @param integer $teamId
     * @return \PHT\Xml\Team\Economy
     */
    public static function economy($countryCurrency = null, $teamId = null)
    {
        $url = Network\Request::buildUrl(array('file' => 'economy', 'version' => Config\Version::ECONOMY, 'teamId' => $teamId));
        return new Xml\Team\Economy(Network\Request::fetchUrl($url), $countryCurrency);
    }

    /**
     * @param integer $arenaId
     * @param integer $teamId
     * @return \PHT\Xml\Team\Arena
     */
    public static function arena($arenaId = null, $teamId = null)
    {
        $params = array('file' => 'arenadetails', 'version' => Config\Version::ARENADETAILS);
        if ($arenaId !== null) {
            $params['arenaID'] = $arenaId;
        } elseif ($teamId !== null) {
            $params['teamID'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Arena(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Team\Senior\Players
     */
    public static function players($teamId = null, $includeMatchInfo = true)
    {
        $params = array('file' => 'players', 'version' => Config\Version::PLAYERS, 'actionType' => 'view');
        if ($teamId !== null) {
            $params['teamID'] = $teamId;
        }
        if ($includeMatchInfo == true) {
            $params['includeMatchInfo'] = 'true';
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Senior\Players(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Team\Senior\Players
     */
    public static function grownplayers($teamId = null, $includeMatchInfo = true)
    {
        $params = array('file' => 'players', 'version' => Config\Version::PLAYERS, 'actionType' => 'viewOldies');
        if ($teamId !== null) {
            $params['teamID'] = $teamId;
        }
        if ($includeMatchInfo == true) {
            $params['includeMatchInfo'] = 'true';
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Senior\Players(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Team\Senior\Players
     */
    public static function trainedcoaches($teamId = null, $includeMatchInfo = true)
    {
        $params = array('file' => 'players', 'version' => Config\Version::PLAYERS, 'actionType' => 'viewOldCoaches');
        if ($teamId !== null) {
            $params['teamID'] = $teamId;
        }
        if ($includeMatchInfo == true) {
            $params['includeMatchInfo'] = 'true';
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Senior\Players(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\Team\Senior\Training
     */
    public static function training($teamId = null)
    {
        $params = array('file' => 'training', 'version' => Config\Version::TRAINING);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Senior\Training(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @param integer $type
     * @param integer $intensity
     * @param integer $stamina
     * @return boolean
     */
    public static function train($teamId, $type = null, $intensity = null, $stamina = null)
    {
        if ($type === null && $intensity === null && $stamina === null) {
            return false;
        }
        $params = array('file' => 'training', 'actionType' => 'setTraining', 'version' => Config\Version::TRAINING, 'teamId' => $teamId);
        if ($type !== null) {
            $params['trainingType'] = $type;
        }
        if ($intensity !== null) {
            $params['trainingLevel'] = $intensity;
        }
        if ($stamina !== null) {
            $params['trainingLevelStamina'] = $stamina;
        }
        $url = Network\Request::buildUrl($params);
        $xml = new Xml\File(Network\Request::fetchUrl($url));
        return strtolower($xml->getXml()->getElementsByTagName('TrainingSet')->item(0)->nodeValue) == 'true';
    }

    /**
     * @param integer $teamId
     * @param string $showBeforeDate (format should be : yyyy-mm-dd  - If not specified : returned matches are from now minus 28 days to now plus 28 days)
     * @return \PHT\Xml\Team\Match\Listing
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public static function matches($teamId = null, $showBeforeDate = null)
    {
        if ($showBeforeDate !== null && !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $showBeforeDate)) {
            throw new Exception\InvalidArgumentException('Parameter $showBeforeDate must have format yyyy-mm-dd');
        }
        $params = array('file' => 'matches', 'version' => Config\Version::MATCHES);
        if ($showBeforeDate !== null) {
            $params['lastMatchDate'] = $showBeforeDate;
        }
        if ($teamId !== null) {
            $params['teamID'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Match\Listing(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @param string $startDate
     * @param string $endDate
     * @param integer $season
     * @return \PHT\Xml\Team\Match\Archive
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public static function archives($teamId = null, $startDate = null, $endDate = null, $season = null)
    {
        if ($startDate !== null && !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $startDate)) {
            throw new Exception\InvalidArgumentException('Parameter $startDate must have format yyyy-mm-dd');
        }
        if ($endDate !== null && !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $endDate)) {
            throw new Exception\InvalidArgumentException('Parameter $endDate must have format yyyy-mm-dd');
        }

        Utils\Date::analyse($startDate, $endDate);

        $params = array('file' => 'matchesarchive', 'isYouth' => 'false', 'version' => Config\Version::MATCHESARCHIVE);
        if ($teamId !== null) {
            $params['teamID'] = $teamId;
        }
        if ($startDate !== null) {
            $params['firstMatchDate'] = $startDate;
        }
        if ($endDate !== null) {
            $params['lastMatchDate'] = $endDate;
        }
        if ($season !== null) {
            $params['season'] = $season;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Match\Archive(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\Team\Senior\Hof
     */
    public static function hofplayers($teamId = null)
    {
        $params = array('file' => 'hofplayers', 'version' => Config\Version::HOFPLAYERS);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Senior\Hof(Network\Request::fetchUrl($url), $teamId);
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\Team\Avatars
     */
    public static function avatars($teamId = null)
    {
        $params = array('file' => 'avatars', 'version' => Config\Version::AVATARS);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Avatars(Network\Request::fetchUrl($url), Config\Config::SENIOR, $teamId);
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\Team\Avatars
     */
    public static function hofavatars($teamId = null)
    {
        $params = array('file' => 'avatars', 'actionType' => 'hof', 'version' => Config\Version::AVATARS);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Avatars(Network\Request::fetchUrl($url), Config\Config::HOF, $teamId);
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\Team\Avatars
     */
    public static function staffavatars($teamId = null)
    {
        $params = array('file' => 'staffavatars', 'version' => Config\Version::STAFFAVATARS);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Avatars(Network\Request::fetchUrl($url), Config\Config::STAFF, $teamId);
    }

    /**
     * @param integer $teamId
     * @param integer $page
     * @return \PHT\Xml\Team\Transfer\History
     */
    public static function transfershistory($teamId = null, $page = 1)
    {
        $params = array('file' => 'transfersteam', 'pageIndex' => $page, 'version' => Config\Version::TRANSFERSTEAM);
        if ($teamId !== null) {
            $params['teamID'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Transfer\History(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @param integer $onlyType
     * @return \PHT\Xml\Team\Transfer\Bids
     */
    public static function bids($teamId = null, $onlyType = null)
    {
        $params = array('file' => 'currentbids', 'version' => Config\Version::CURRENTBIDS, 'actionType' => 'view');
        if ($teamId !== null) {
            $params['teamID'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Transfer\Bids(Network\Request::fetchUrl($url), $onlyType);
    }

    /**
     * @param integer $teamId
     */
    public static function deletetransfers($teamId = null)
    {
        $params = array('file' => 'currentbids', 'version' => Config\Version::CURRENTBIDS, 'actionType' => 'deleteAllFinished');
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        Network\Request::fetchUrl($url);
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\Team\Fans
     */
    public static function fans($teamId = null)
    {
        $params = array('file' => 'fans', 'version' => Config\Version::FANS);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Fans(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\Team\Staff
     */
    public static function staff($teamId = null)
    {
        $params = array('file' => 'stafflist', 'version' => Config\Version::STAFFLIST);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Staff(Network\Request::fetchUrl($url), $teamId);
    }

    /**
     * @param integer $teamId
     * @param integer $userId
     * @return \PHT\Xml\Team\Flags
     */
    public static function flags($teamId = null, $userId = null)
    {
        $params = array('file' => 'teamdetails', 'version' => Config\Version::TEAMDETAILS, 'includeFlags' => 'true', 'includeDomesticFlags' => 'true');
        if ($teamId !== null) {
            $params['teamID'] = $teamId;
        }
        if ($userId !== null) {
            $params['userID'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Flags(Network\Request::fetchUrl($url), $teamId);
    }

    /**
     * @param integer $teamId
     * @param boolean $weekendFriendly
     * @return \PHT\Xml\Team\Challengeable\Listing
     */
    public static function challenges($teamId = null, $weekendFriendly = false)
    {
        $params = array('file' => 'challenges', 'version' => Config\Version::CHALLENGES, 'isWeekendFriendly' => (int)$weekendFriendly);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Challengeable\Listing(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $oppenentTeamId
     * @param integer $matchType (see \PHT\Config\Config CHALLENGE_* constants)
     * @param integer $matchPlace (see \PHT\Config\Config CHALLENGE_* constants)
     * @param integer $arenaId (only for neutral arena)
     * @param integer $teamId
     * @param boolean $weekendFriendly
     */
    public static function challenge($oppenentTeamId, $matchType, $matchPlace, $arenaId = null, $teamId = null, $weekendFriendly = false)
    {
        $params = array('file' => 'challenges', 'version' => Config\Version::CHALLENGES, 'actionType' => 'challenge', 'opponentTeamId' => $oppenentTeamId, 'matchType' => $matchType, 'matchPlace' => $matchPlace, 'isWeekendFriendly' => (int)$weekendFriendly);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        if ($arenaId !== null) {
            $params['neutralArenaId'] = $arenaId;
        }
        $url = Network\Request::buildUrl($params);
        Network\Request::fetchUrl($url);
    }

}
