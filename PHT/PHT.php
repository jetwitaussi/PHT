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

use PHT\Network;
use PHT\Xml;
use PHT\Wrapper;
use PHT\Config;
use PHT\Exception;

class PHT extends Config\Base
{
    /**
     * Return user object
     *
     * @param integer $userId
     * @return \PHT\Xml\User
     */
    public function getUser($userId = null)
    {
        return Wrapper\User::user($userId);
    }

    /**
     * Return user's club
     *
     * @param integer $teamId
     * @return \PHT\Xml\Club
     */
    public function getClub($teamId = null)
    {
        return Wrapper\User::club($teamId);
    }

    /**
     * Get data of user's senior team by default or of teamId if given
     *
     * @param \PHT\Config\Team $team
     * @return \PHT\Xml\Team\Senior
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public function getSeniorTeam(Config\Team $team = null)
    {
        if ($team === null) {
            return $this->findSeniorTeam();
        }

        if (!$team instanceof Config\Team) {
            throw new Exception\InvalidArgumentException('Parameter $team should be instanceof \PHT\Config\Team');
        }

        if ($team->primary === true) {
            return $this->getPrimarySeniorTeam($team->userId);
        } elseif ($team->secondary === true) {
            return $this->getSecondarySeniorTeam($team->userId);
        } elseif ($team->international === true) {
            return $this->getInternationalSeniorTeam($team->userId);
        } else {
            return $this->findSeniorTeam($team->id, $team->userId);
        }
    }

    /**
     * Get data of user's youth team by default or of teamId if given
     *
     * @param \PHT\Config\Team $team
     * @return \PHT\Xml\Team\Youth
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public function getYouthTeam(Config\Team $team = null)
    {
        if ($team === null) {
            return $this->findYouthTeam();
        }

        if (!$team instanceof Config\Team) {
            throw new Exception\InvalidArgumentException('Parameter $team should be instanceof \PHT\Config\Team');
        }

        if ($team->primary !== null) {
            return $this->getPrimaryYouthTeam($team->userId);
        } elseif ($team->secondary !== null) {
            return $this->getSecondaryYouthTeam($team->userId);
        } else {
            return $this->findYouthTeam($team->id, $team->userId);
        }
    }

    /**
     * @param integer $id
     * @param integer $userId
     * @return \PHT\Xml\Team\Senior
     */
    protected function findSeniorTeam($id = null, $userId = null)
    {
        return Wrapper\Team\Senior::team($id, $userId);
    }

    /**
     * @param integer $id
     * @param integer $userId
     * @return \PHT\Xml\Team\Youth
     */
    protected function findYouthTeam($id = null, $userId = null)
    {
        if ($id === null) {
            $id = $this->findSeniorTeam(null, $userId)->getYouthTeamId();
        }
        return Wrapper\Team\Youth::team($id);
    }

    /**
     * @param integer $userId
     * @return \PHT\Xml\Team\Senior
     */
    protected function getPrimarySeniorTeam($userId = null)
    {
        return $this->getSpecificSeniorTeam(Xml\Team\Senior::PRIMARY, $userId);
    }

    /**
     * @param integer $userId
     * @return \PHT\Xml\Team\Senior
     */
    protected function getSecondarySeniorTeam($userId = null)
    {
        return $this->getSpecificSeniorTeam(Xml\Team\Senior::SECONDARY, $userId);
    }

    /**
     * @param integer $userId
     * @return \PHT\Xml\Team\Senior
     */
    protected function getInternationalSeniorTeam($userId = null)
    {
        return $this->getSpecificSeniorTeam(Xml\Team\Senior::INTERNATIONAL, $userId);
    }

    /**
     * @param integer $type
     * @param integer $userId
     * @return \PHT\Xml\Team\Senior
     */
    protected function getSpecificSeniorTeam($type, $userId = null)
    {
		$params = array('file' => 'teamdetails', 'version' => Config\Version::TEAMDETAILS);
		if ($userId !== null) {
			$params['userID'] = $userId;
		}
		$url = Network\Request::buildUrl($params);
		$xml = Network\Request::fetchUrl($url);
        $doc = new \DOMDocument('1.0', 'UTF-8');
		$doc->loadXml($xml);
        $teams = $doc->getElementsByTagName('Team');
        for ($t = 0; $t < $teams->length; $t++) {
            $txml = new \DOMDocument('1.0', 'UTF-8');
            $txml->appendChild($txml->importNode($teams->item($t), true));
            $isHti = $txml->getElementsByTagName('LeagueID')->item(0)->nodeValue == 1000;
            $isPrimary = strtolower($txml->getElementsByTagName('IsPrimaryClub')->item(0)->nodeValue) == 'true';
            if ($type == Xml\Team\Senior::PRIMARY && $isPrimary) {
                continue;
            }
            if ($type == Xml\Team\Senior::SECONDARY && !$isPrimary && !$isHti) {
                continue;
            }
            if ($type == Xml\Team\Senior::INTERNATIONAL && $isHti) {
                continue;
            }
            $doc->getElementsByTagName('Teams')->item(0)->removeChild($teams->item($t));
        }
        if ($doc->getElementsByTagName('Team')->length) {
            return new Xml\Team\Senior($doc->saveXML());
        }
        return null;
    }

    /**
     * @param integer $userId
     * @return \PHT\Xml\Team\Youth
     */
    protected function getPrimaryYouthTeam($userId = null)
    {
        return $this->findYouthTeam($this->getPrimarySeniorTeam($userId)->getYouthTeamId());
    }

    /**
     * @param integer $userId
     * @return \PHT\Xml\Team\Youth
     */
    protected function getSecondaryYouthTeam($userId = null)
    {
        return $this->findYouthTeam($this->getSecondarySeniorTeam($userId)->getYouthTeamId());
    }

    /**
     * Get economy data of user's club, converted in country currency if specfied
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @param integer $teamId
     * @return \PHT\Xml\Team\Economy
     */
    public function getEconomy($countryCurrency = null, $teamId = null)
    {
        return Wrapper\Team\Senior::economy($countryCurrency, $teamId);
    }

    /**
     * Get world region details
     *
     * @param integer $id
     * @return \PHT\Xml\World\Region
     */
    public function getRegion($id = null)
    {
        return Wrapper\World::region($id);
    }

    /**
     * Get senior league details
     *
     * @param integer $id
     * @return \PHT\Xml\World\League\Senior
     */
    public function getSeniorLeague($id = null)
    {
        return Wrapper\World\League::senior($id);
    }

    /**
     * Get youth league details
     *
     * @param integer $id
     * @return \PHT\Xml\World\League\Youth
     */
    public function getYouthLeague($id = null)
    {
        return Wrapper\World\League::youth($id);
    }

    /**
     * Get senior league season details
     *
     * @param integer $leagueLevelId
     * @param integer $season
     * @return \PHT\Xml\World\League\Season\Senior
     */
    public function getSeniorLeagueSeason($leagueLevelId = null, $season = null)
    {
        return Wrapper\World\Season::senior($leagueLevelId, $season);
    }

    /**
     * Get youth league season details
     *
     * @param integer $leagueId
     * @param integer $season
     * @return \PHT\Xml\World\League\Season\Youth
     */
    public function getYouthLeagueSeason($leagueId = null, $season = null)
    {
        return Wrapper\World\Season::youth($leagueId, $season);
    }

    /**
     * Get arena details
     *
     * @param integer $arenaId
     * @param integer $teamId
     * @return \PHT\Xml\Team\Arena
     */
    public function getArena($arenaId = null, $teamId = null)
    {
        return Wrapper\Team\Senior::arena($arenaId, $teamId);
    }

    /**
     * Get world details
     *
     * @param boolean $includeRegions
     * @return \PHT\Xml\World\World
     */
    public function getWorld($includeRegions = false)
    {
        return Wrapper\World::world($includeRegions);
    }

    /**
     * Get country details
     *
     * @param integer $leagueId
     * @param integer $countryId
     * @param boolean $includeRegions
     * @return \PHT\Xml\World\Country
     */
    public function getCountry($leagueId = null, $countryId = null, $includeRegions = false)
    {
        return Wrapper\World::country($leagueId, $countryId, $includeRegions);
    }

    /**
     * Get i18n object to get languages
     *
     * @return \PHT\Xml\I18n
     */
    public function getInternationalization()
    {
        $url = Network\Request::buildUrl(array('file' => 'worldlanguages', 'version' => Config\Version::WORLDLANGUAGES));
        return new Xml\I18n(Network\Request::fetchUrl($url));
    }

    /**
     * Return translation object
     *
     * @param integer $languageId
     * @return \PHT\Xml\I18n\Translation
     */
    public function getTranslation($languageId = null)
    {
        $params = array('file' => 'translations', 'version' => Config\Version::TRANSLATIONS);
        if ($languageId !== null) {
            $params['languageId'] = $languageId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\I18n\Translation(Network\Request::fetchUrl($url));
    }

    /**
     * Get senior team players
     *
     * @param integer $teamId
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Team\Senior\Players
     */
    public function getSeniorPlayers($teamId = null, $includeMatchInfo = true)
    {
        return Wrapper\Team\Senior::players($teamId, $includeMatchInfo);
    }

    /**
     * Get senior team grown players
     *
     * @param integer $teamId
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Team\Senior\Players
     */
    public function getSeniorGrownPlayers($teamId = null, $includeMatchInfo = true)
    {
        return Wrapper\Team\Senior::grownplayers($teamId, $includeMatchInfo);
    }

    /**
     * Get senior team trained coaches
     *
     * @param integer $teamId
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Team\Senior\Players
     */
    public function getSeniorTrainedCoaches($teamId = null, $includeMatchInfo = true)
    {
        return Wrapper\Team\Senior::trainedcoaches($teamId, $includeMatchInfo);
    }

    /**
     * Get senior player details
     *
     * @param integer $playerId
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Player\Senior
     */
    public function getSeniorPlayer($playerId, $includeMatchInfo = true)
    {
        return Wrapper\Player\Senior::player($playerId, $includeMatchInfo);
    }

    /**
     * Return player training events object
     *
     * @param integer $playerId
     * @return \PHT\Xml\Player\Training
     */
    public function getSeniorPlayerTraining($playerId)
    {
        return Wrapper\Player\Senior::training($playerId);
    }

    /**
     * Return player training history object
     *
     * @param integer $playerId
     * @return \PHT\Xml\Player\History
     */
    public function getSeniorPlayerHistory($playerId)
    {
        return Wrapper\Player\Senior::history($playerId);
    }

    /**
     * Get youth team players
     *
     * @param integer $teamId
     * @param boolean $unlockSkills
     * @param boolean $showScoutCall
     * @param boolean $showLastMatch
     * @param string $orderBy
     * @return \PHT\Xml\Team\Youth\Players
     */
    public function getYouthPlayers($teamId = null, $unlockSkills = false, $showScoutCall = true, $showLastMatch = true, $orderBy = null)
    {
        if ($teamId === null) {
            $teamId = $this->getYouthTeam()->getId();
        }
        if ($teamId === null) {
            return null;
        }
        return Wrapper\Team\Youth::players($teamId, $unlockSkills, $showScoutCall, $showLastMatch, $orderBy);
    }

    /**
     * Get youth player details
     *
     * @param integer $playerId
     * @param boolean $unlockSkills
     * @param boolean $showScoutCall
     * @param boolean $showLastMatch
     * @return \PHT\Xml\Player\Youth
     */
    public function getYouthPlayer($playerId, $unlockSkills = false, $showScoutCall = true, $showLastMatch = true)
    {
        return Wrapper\Player\Youth::player($playerId, $unlockSkills, $showScoutCall, $showLastMatch);
    }

    /**
     * Get youth scouts object
     *
     * @param integer $teamId
     * @return \PHT\Xml\Team\Youth\Scouts
     */
    public function getYouthScouts($teamId = null)
    {
        if ($teamId === null) {
            $teamId = $this->getYouthTeam()->getId();
        }
        if ($teamId === null) {
            return null;
        }
        return Wrapper\Team\Youth::scouts($teamId);
    }

    /**
     * Return senior team hall of fame player object
     *
     * @param integer $teamId
     * @return \PHT\Xml\Team\Senior\Hof
     */
    public function getHof($teamId = null)
    {
        return Wrapper\Team\Senior::hofplayers($teamId);
    }

    /**
     * Return training object
     *
     * @param integer $teamId
     * @return \PHT\Xml\Team\Senior\Training
     */
    public function getTraining($teamId = null)
    {
        return Wrapper\Team\Senior::training($teamId);
    }

    /**
     * Set team training
     *
     * @param integer $teamId
     * @param integer $type (see \PHT\Config\Config TRAINING_* constants)
     * @param integer $intensity
     * @param integer $stamina
     * @return boolean
     */
    public function setTraining($teamId, $type = null, $intensity = null, $stamina = null)
    {
        return Wrapper\Team\Senior::train($teamId, $type, $intensity, $stamina);
    }

    /**
     * Search within hattrick
     *
     * @param \PHT\Config\Search $search
     * @return \PHT\Xml\Search\Response
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public function search(Config\Search $search)
    {
        if (!$search instanceof Config\Search) {
            throw new Exception\InvalidArgumentException('Parameter $search should be instanceof \PHT\Config\Search');
        }
        if (isset($search->seniorTeamId)) {
            $params = array('searchType' => '4', 'searchID' => $search->seniorTeamId);
        } elseif (isset($search->seniorTeamName)) {
            $params = array('searchType' => '4', 'searchString' => urlencode($search->seniorTeamName), 'pageIndex' => $search->page);
        } elseif (isset($search->userName)) {
            $params = array('searchType' => '2', 'searchString' => urlencode($search->userName), 'pageIndex' => $search->page);
        } elseif (isset($search->seniorPlayerId)) {
            $params = array('searchType' => '0', 'searchID' => $search->seniorPlayerId);
        } elseif (isset($search->seniorPlayerFirstName) && isset($search->seniorPlayerLastName)) {
            $params = array('searchType' => '0', 'searchString' => $search->seniorPlayerFirstName, 'searchString2' => $search->seniorPlayerLastName, 'pageIndex' => $search->page);
            if ($search->countryLeagueId !== null) {
                $params['searchLeagueID'] = $search->countryLeagueId;
            }
        } elseif (isset($search->regionName)) {
            $params = array('searchType' => '5', 'searchString' => $search->regionName, 'pageIndex' => $search->page);
        } elseif (isset($search->arenaName)) {
            $params = array('searchType' => '1', 'searchString' => $search->arenaName, 'pageIndex' => $search->page);
        } elseif (isset($search->seniorMatchId)) {
            $params = array('searchType' => '6', 'searchID' => $search->seniorMatchId);
        } elseif (isset($search->seniorLeagueName)) {
            $params = array('searchType' => '3', 'searchString' => $search->seniorLeagueName);
            if ($search->countryLeagueId !== null) {
                $params['searchLeagueID'] = $search->countryLeagueId;
            }
        } else {
            throw new Exception\InvalidArgumentException('Parameter $search should have at least one property defined to perform a search');
        }

        return Wrapper\Search::search($params);
    }

    /**
     * Get nt/u20 teams list
     *
     * @param boolean $u20
     * @return \PHT\Xml\National\Teams
     */
    public function getNationalTeams($u20 = false)
    {
        return Wrapper\National::teams($u20);
    }

    /**
     * Get nt/u20 team details
     *
     * @param integer $teamId
     * @return \PHT\Xml\Team\National
     */
    public function getNationalTeam($teamId)
    {
        return Wrapper\National::team($teamId);
    }

    /**
     * Get nt/u20 players
     *
     * @param integer $teamId
     * @return \PHT\Xml\National\Players
     */
    public function getNationalPlayers($teamId)
    {
        return Wrapper\National::players($teamId);
    }

    /**
     * Get nt/u20 matches list
     *
     * @param boolean $u20
     * @return \PHT\Xml\National\Matches
     */
    public function getNationalMatches($u20 = false)
    {
        return Wrapper\National::matches($u20);
    }

    /**
     * Get senior team matches
     *
     * @param integer $teamId
     * @param string $showBeforeDate (format should be : yyyy-mm-dd  - If no specify : returned matches are from now + 28 days)
     * @return \PHT\Xml\Team\Match\Listing
     */
    public function getSeniorMatches($teamId = null, $showBeforeDate = null)
    {
        return Wrapper\Team\Senior::matches($teamId, $showBeforeDate);
    }

    /**
     * Get youth team matches
     *
     * @param integer $teamId
     * @param string $showBeforeDate (format should be : yyyy-mm-dd  - If no specify : returned matches are from now + 28 days)
     * @return \PHT\Xml\Team\Match\Listing
     */
    public function getYouthMatches($teamId = null, $showBeforeDate = null)
    {
        if ($teamId === null) {
            $teamId = $this->findSeniorTeam()->getYouthTeamId();
        }
        return Wrapper\Team\Senior::matches($teamId, $showBeforeDate);
    }

    /**
     * Get senior team archive matches
     *
     * @param integer $teamId
     * @param string $startDate (format should be : yyyy-mm-dd)
     * @param string $endDate (format should be : yyyy-mm-dd)
     * @param integer $season
     * @return \PHT\Xml\Team\Match\Archive
     */
    public function getSeniorMatchesArchive($teamId = null, $startDate = null, $endDate = null, $season = null)
    {
        return Wrapper\Team\Senior::archives($teamId, $startDate, $endDate, $season);
    }

    /**
     * Get youth team archive matches
     *
     * @param integer $teamId
     * @param string $startDate (format should be : yyyy-mm-dd)
     * @param string $endDate (format should be : yyyy-mm-dd)
     * @return \PHT\Xml\Team\Match\Archive
     */
    public function getYouthMatchesArchive($teamId = null, $startDate = null, $endDate = null)
    {
        if ($teamId === null) {
            $teamId = $this->findSeniorTeam()->getYouthTeamId();
        }
        return Wrapper\Team\Youth::archives($teamId, $startDate, $endDate);
    }

    /**
     * Get senior match details
     *
     * @param integer $matchId
     * @param boolean $matchEvents
     * @return \PHT\Xml\Match
     */
    public function getSeniorMatch($matchId, $matchEvents = true)
    {
        return Wrapper\Match::senior($matchId, $matchEvents);
    }

    /**
     * Get youth match details
     *
     * @param integer $matchId
     * @param boolean $matchEvents
     * @return \PHT\Xml\Match
     */
    public function getYouthMatch($matchId, $matchEvents = true)
    {
        return Wrapper\Match::youth($matchId, $matchEvents);
    }

    /**
     * Get tournament match details
     *
     * @param integer $matchId
     * @param boolean $matchEvents
     * @return \PHT\Xml\Match
     */
    public function getTournamentMatch($matchId, $matchEvents = true)
    {
        return Wrapper\Match::tournament($matchId, $matchEvents);
    }

    /**
     * Return lineup for senior match
     *
     * @param integer $matchId
     * @param integer $teamId
     * @return \PHT\Xml\Match\Lineup
     */
    public function getSeniorMatchLineup($matchId = null, $teamId = null)
    {
        return Wrapper\Match::seniorlineup($matchId, $teamId);
    }

    /**
     * Return lineup for youth match
     *
     * @param integer $matchId
     * @param integer $teamId
     * @return \PHT\Xml\Match\Lineup
     */
    public function getYouthMatchLineup($matchId = null, $teamId = null)
    {
        return Wrapper\Match::youthlineup($matchId, $teamId);
    }

    /**
     * Return lineup for tournament match
     *
     * @param integer $matchId
     * @param integer $teamId
     * @return \PHT\Xml\Match\Lineup
     */
    public function getTournamentMatchLineup($matchId, $teamId = null)
    {
        return Wrapper\Match::tournamentlineup($matchId, $teamId);
    }

    /**
     * Return orders for senior match
     *
     * @param integer $matchId
     * @param integer $teamId
     * @return \PHT\Xml\Match\Orders
     */
    public function getSeniorMatchOrders($matchId, $teamId = null)
    {
        return Wrapper\Match::seniororders($matchId, $teamId);
    }

    /**
     * Return orders for youth match
     *
     * @param integer $matchId
     * @param integer $teamId
     * @return \PHT\Xml\Match\Orders
     */
    public function getYouthMatchOrders($matchId, $teamId = null)
    {
        return Wrapper\Match::youthorders($matchId, $teamId);
    }

    /**
     * Return orders for tournament match
     *
     * @param integer $matchId
     * @param integer $teamId
     * @return \PHT\Xml\Match\Orders
     */
    public function getTournamentMatchOrders($matchId, $teamId = null)
    {
        return Wrapper\Match::tournamentorders($matchId, $teamId);
    }

    /**
     * Return match orders sent object
     *
     * @param \PHT\Config\Orders $orders
     * @param integer $teamId
     * @return \PHT\Xml\Match\Orders\Sent
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public function setMatchOrders(Config\Orders $orders, $teamId = null)
    {
        return Wrapper\Match::setorders($orders, $teamId);
    }

    /**
     * Return match orders prediction object
     *
     * @param \PHT\Config\Orders $orders
     * @param integer $teamId
     * @return \PHT\Xml\Match\Orders\Prediction
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public function predictRatings($orders = null, $teamId = null)
    {
        $params = array('file' => 'matchorders', 'actionType' => 'predictratings', 'version' => Config\Version::MATCHORDERS);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        if ($orders !== null && $orders instanceof Config\Orders) {
            if ($orders->hasError()) {
                throw new Exception\InvalidArgumentException('Parameter $orders has ' . $orders->getErrorNumber() . ' errors, please fix before sending');
            }
            $json = $orders->getJson();
            $params['matchID'] = $orders->getMatchId();
            $params['sourceSystem'] = $orders->getSourceSystem();
            $url = Network\Request::buildUrl($params, array('lineup' => $json));
            return new Xml\Match\Orders\Prediction(Network\Request::fetchUrl($url, true, array('lineup' => $json)));
        } else {
            $url = Network\Request::buildUrl($params);
            return new Xml\Match\Orders\Prediction(Network\Request::fetchUrl($url));
        }
    }

    /**
     * Return senior team avatars
     *
     * @param integer $teamId
     * @return \PHT\Xml\Team\Avatars
     */
    public function getSeniorAvatars($teamId = null)
    {
        return Wrapper\Team\Senior::avatars($teamId);
    }

    /**
     * Return youth team avatars
     *
     * @param integer $teamId
     * @return \PHT\Xml\Team\Avatars
     */
    public function getYouthAvatars($teamId = null)
    {
        return Wrapper\Team\Youth::avatars($teamId);
    }

    /**
     * Return senior team hof avatars
     *
     * @param integer $teamId
     * @return \PHT\Xml\Team\Avatars
     */
    public function getHofAvatars($teamId = null)
    {
        return Wrapper\Team\Senior::hofavatars($teamId);
    }

    /**
     * Return senior team staff avatars
     *
     * @param integer $teamId
     * @return \PHT\Xml\Team\Avatars
     */
    public function getStaffAvatars($teamId = null)
    {
        return Wrapper\Team\Senior::staffavatars($teamId);
    }

    /**
     * Return senior team transfers history object
     *
     * @param integer $teamId
     * @return \PHT\Xml\Team\Transfer\History
     */
    public function getTeamTransfersHistory($teamId = null)
    {
        return Wrapper\Team\Senior::transfershistory($teamId);
    }

    /**
     * Return senior player transfers history object
     *
     * @param integer $playerId
     * @return \PHT\Xml\Player\Transfer\History
     */
    public function getPlayerTransfersHistory($playerId)
    {
        return Wrapper\Player\Senior::transfershistory($playerId);
    }

    /**
     * Return senior team bids object
     *
     * @param integer $teamId
     * @param integer $onlyType (see \PHT\Config\Config BID_* constants)
     * @return \PHT\Xml\Team\Transfer\Bids
     */
    public function getBids($teamId = null, $onlyType = null)
    {
        return Wrapper\Team\Senior::bids($teamId, $onlyType);
    }

    /**
     * Bid on a senior player
     *
     * @param integer $teamId the teamId who bid on the player
     * @param integer $playerId the playerId to buy
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @param integer $amount bid amount
     * @param integer $maxAmount max bid amount (for automatic bid)
     * @return \PHT\Xml\Player\Senior
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public function setBid($teamId, $playerId, $countryCurrency, $amount = null, $maxAmount = null)
    {
        return Wrapper\Player\Senior::bid($teamId, $playerId, $countryCurrency, $amount, $maxAmount);
    }

    /**
     * Ignore a transfer or a category of transfers
     *
     * @param integer $transferId
     * @param integer $category Only values 5 (hotlisted), 8 (losing bids) and 9 (finished) are allowed.
     */
    public function ignoreTransfer($transferId = null, $category = null)
    {
        Wrapper\Player\Senior::ignoretransfer($transferId, $category);
    }

    /**
     * Return senior team fans object
     *
     * @param integer $teamId
     * @return \PHT\Xml\Team\Fans
     */
    public function getFans($teamId = null)
    {
        return Wrapper\Team\Senior::fans($teamId);
    }

    /**
     * Return senior team staff object
     *
     * @param integer $teamId
     * @return \PHT\Xml\Team\Staff
     */
    public function getStaff($teamId = null)
    {
        return Wrapper\Team\Senior::staff($teamId);
    }

    /**
     * Return senior team flags object
     *
     * @param integer $teamId
     * @param integer $userId
     * @return \PHT\Xml\Team\Flags
     */
    public function getFlags($teamId = null, $userId = null)
    {
        return Wrapper\Team\Senior::flags($teamId, $userId);
    }

    /**
     * Return user achievements object
     *
     * @param integer $userId
     * @return \PHT\Xml\User\Achievements
     */
    public function getAchievements($userId = null)
    {
        return Wrapper\User::achievements($userId);
    }

    /**
     * Return user bookmarks object
     *
     * @param integer $type (see \PHT\Config\Config BOOKMARK_* constants)
     * @return \PHT\Xml\User\Bookmarks
     */
    public function getBookmarks($type = Config\Config::BOOKMARK_ALL)
    {
        return Wrapper\User::bookmarks($type);
    }

    /**
     * Return federations listing object
     *
     * @param \PHT\Config\Federation $federation
     * @return \PHT\Xml\Federations\Listing
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public function getFederations(Config\Federation $federation)
    {
        if (!$federation instanceof Config\Federation) {
            throw new Exception\InvalidArgumentException('Parameter $federation should be type of \PHT\Config\Federation');
        }
        if (isset($federation->name)) {
            $params = array('searchFor' => $federation->name, 'searchType' => 1, 'searchLanguageID' => $federation->language, 'pageIndex' => $federation->page);
        } elseif (isset($federation->abbreviation)) {
            $params = array('searchFor' => $federation->abbreviation, 'searchType' => 2, 'searchLanguageID' => $federation->language, 'pageIndex' => $federation->page);
        } elseif (isset($federation->description)) {
            $params = array('searchFor' => $federation->description, 'searchType' => 3, 'searchLanguageID' => $federation->language, 'pageIndex' => $federation->page);
        } elseif (isset($federation->user)) {
            $params = array('searchType' => 5);
        } else {
            throw new Exception\InvalidArgumentException('You must define a least one search criteria (name, description, abbreviation, user) into $federation parameter');
        }
        return Wrapper\Federation::search($params);
    }

    /**
     * Return federation details
     *
     * @param integer $federationId
     * @return \PHT\Xml\Federations\Federation
     */
    public function getFederation($federationId)
    {
        return Wrapper\Federation::detail($federationId);
    }

    /**
     * Return federation members list
     *
     * @param integer $federationId
     * @param string $onlyLetter
     * @return \PHT\Xml\Federations\Members
     */
    public function getFederationMembers($federationId, $onlyLetter = null)
    {
        return Wrapper\Federation::members($federationId, $onlyLetter);
    }

    /**
     * Return federation roles list
     *
     * @param integer $federationId
     * @return \PHT\Xml\Federations\Roles
     */
    public function getFederationRoles($federationId)
    {
        return Wrapper\Federation::roles($federationId);
    }

    /**
     * Return senior team tournaments listing
     *
     * @param integer $teamId
     * @return \PHT\Xml\Tournaments\Listing
     */
    public function getTournaments($teamId = null)
    {
        return Wrapper\Tournament::listing($teamId);
    }

    /**
     * Return tournament object
     *
     * @param integer $tournamentId
     * @return \PHT\Xml\Tournaments\Tournament
     */
    public function getTournament($tournamentId)
    {
        return Wrapper\Tournament::tournament($tournamentId);
    }

    /**
     * Return tournament league object
     *
     * @param integer $tournamentId
     * @return \PHT\Xml\Tournaments\League
     */
    public function getTournamentLeague($tournamentId)
    {
        return Wrapper\Tournament::league($tournamentId);
    }

    /**
     * Return tournament matches object
     *
     * @param integer $tournamentId
     * @return \PHT\Xml\Tournaments\Matches
     */
    public function getTournamentMatches($tournamentId)
    {
        return Wrapper\Tournament::matches($tournamentId);
    }

    /**
     * Return senior team ladders listing
     *
     * @param integer $teamId
     * @return \PHT\Xml\Tournaments\Ladders\Listing
     */
    public function getLadders($teamId = null)
    {
        return Wrapper\Tournament::ladders($teamId);
    }

    /**
     * Return ladder object
     *
     * @param integer $ladderId
     * @param integer $teamId
     * @param integer $page set -1 and $teamId to get page of the team, set 0 for first page
     * @param integer $size
     * @return \PHT\Xml\Tournaments\Ladders\Ladder
     */
    public function getLadder($ladderId, $teamId = null, $page = -1, $size = 25)
    {
        return Wrapper\Tournament::ladder($ladderId, $teamId, $page, $size);
    }

    /**
     * Return cup object
     *
     * @param integer $cupId
     * @param integer $round
     * @param integer $season
     * @param integer $afterMatchId
     * @return \PHT\Xml\World\Cup
     */
    public function getCup($cupId, $round = null, $season = null, $afterMatchId = null)
    {
        return Wrapper\World::cup($cupId, $round, $season, $afterMatchId);
    }

    /**
     * Return worldcup object
     *
     * @param integer $season
     * @param integer $cupId
     * @return \PHT\Xml\World\Worldcup
     */
    public function getWorldcup($season, $cupId = null)
    {
        $params = array('file' => 'worldcup', 'actionType' => 'viewGroups', 'season' => $season, 'version' => Config\Version::WORLDCUP);
        if ($cupId !== null) {
            $params['cupID'] = $cupId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\World\Worldcup(Network\Request::fetchUrl($url));
    }

    /**
     * Return worldcup matches object
     *
     * @param integer $season
     * @param integer $groupId
     * @param integer $cupId
     * @param integer $matchRound
     * @return \PHT\Xml\World\Worldcup\Matches
     */
    public function getWorldcupMatches($season, $groupId, $cupId = null, $matchRound = null)
    {
        $params = array('file' => 'worldcup', 'actionType' => 'viewMatches', 'season' => $season, 'cupSeriesUnitID' => $groupId, 'version' => Config\Version::WORLDCUP);
        if ($cupId !== null) {
            $params['cupID'] = $cupId;
        }
        if ($matchRound !== null) {
            $params['matchRound'] = $matchRound;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\World\Worldcup\Matches(Network\Request::fetchUrl($url));
    }

    /**
     * Get supporters users or supported teams
     *
     * @param integer $teamId
     * @param integer $userId
     * @param integer $page
     * @param integer $size
     * @return \PHT\Xml\Supporters
     */
    public function getSupporters($teamId = null, $userId = null, $page = 0, $size = 50)
    {
        return Wrapper\Supporters::listing($teamId, $userId, $page, $size);
    }

    /**
     * Return live object
     *
     * @param boolean $includeLineup
     * @return \PHT\Xml\Live
     */
    public function getLive($includeLineup = true)
    {
        $params = array('file' => 'live', 'actionType' => 'viewAll', 'version' => Config\Version::LIVE);
        if ($includeLineup === true) {
            $params['includeStartingLineup'] = 'true';
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Live(Network\Request::fetchUrl($url));
    }

    /**
     * Search players on transfer market
     *
     * @param \PHT\Config\TransferMarket $criteria
     * @return \PHT\Xml\Search\Market\Response
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public function searchTransferMarket(Config\TransferMarket $criteria)
    {
        if (!$criteria instanceof Config\TransferMarket) {
            throw new Exception\InvalidArgumentException('Parameter $criteria should be instance of \PHT\Config\TransferMarket');
        }
        $skill1 = $criteria->getFirstSkill();
        if ($skill1[0] === null || $skill1[1] === null || $skill1[2] === null || $criteria->getMinAge() === null || $criteria->getMaxAge() === null) {
            throw new Exception\InvalidArgumentException('Parameter $criteria should define at least first skill with minimum and maximum, as well as minimum and maximum age');
        }
        $params = array('ageMin' => $criteria->getMinAge(), 'ageMax' => $criteria->getMaxAge());
        if ($criteria->getMinDays() !== null) {
            $params['ageDaysMin'] = $criteria->getMinDays();
        }
        if ($criteria->getMaxDays() !== null) {
            $params['ageDaysMax'] = $criteria->getMaxDays();
        }
        $params['skillType1'] = $skill1[0];
        $params['minSkillValue1'] = $skill1[1];
        $params['maxSkillValue1'] = $skill1[2];
        $skill2 = $criteria->getSecondSkill();
        if ($skill2[0] !== null) {
            $params['skillType2'] = $skill2[0];
        }
        if ($skill2[1] !== null) {
            $params['minSkillValue2'] = $skill2[1];
        }
        if ($skill2[2] !== null) {
            $params['maxSkillValue2'] = $skill2[2];
        }
        $skill3 = $criteria->getThirdSkill();
        if ($skill3[0] !== null) {
            $params['skillType3'] = $skill3[0];
        }
        if ($skill3[1] !== null) {
            $params['minSkillValue3'] = $skill3[1];
        }
        if ($skill3[2] !== null) {
            $params['maxSkillValue3'] = $skill3[2];
        }
        $skill4 = $criteria->getFourthSkill();
        if ($skill4[0] !== null) {
            $params['skillType4'] = $skill4[0];
        }
        if ($skill4[1] !== null) {
            $params['minSkillValue4'] = $skill4[1];
        }
        if ($skill4[2] !== null) {
            $params['maxSkillValue4'] = $skill4[2];
        }
        if ($criteria->getSpecialty() !== null) {
            $params['specialty'] = $criteria->getSpecialty();
        }
        if ($criteria->getCountryId() !== null) {
            $params['nativeCountryId'] = $criteria->getCountryId();
        }
        if ($criteria->getMinTSI() !== null) {
            $params['tsiMin'] = $criteria->getMinTSI();
        }
        if ($criteria->getMaxTSI() !== null) {
            $params['tsiMax'] = $criteria->getMaxTSI();
        }
        if ($criteria->getMinPrice() !== null) {
            $params['priceMin'] = $criteria->getMinPrice();
        }
        if ($criteria->getMaxPrice() !== null) {
            $params['priceMax'] = $criteria->getMaxPrice();
        }
        if ($criteria->getSize() !== null) {
            $params['pageSize'] = $criteria->getSize();
        }
        if ($criteria->getPage() !== null) {
            $params['pageIndex'] = $criteria->getPage();
        }

        return Wrapper\Search::market($params);
    }

    /**
     * Return team challenges object
     *
     * @param integer $teamId
     * @param boolean $weekendFriendly
     * @return \PHT\Xml\Team\Challengeable\Listing
     */
    public function getChallenges($teamId = null, $weekendFriendly = false)
    {
        return Wrapper\Team\Senior::challenges($teamId, $weekendFriendly);
    }

    /**
     * Challenge a team
     *
     * @param integer $oppenentTeamId
     * @param integer $matchType (see \PHT\Config\Config CHALLENGE_* constants)
     * @param integer $matchPlace (see \PHT\Config\Config CHALLENGE_* constants)
     * @param integer $arenaId (only for neutral arena)
     * @param integer $teamId
     * @param boolean $weekendFriendly
     */
    public function challengeTeam($oppenentTeamId, $matchType, $matchPlace, $arenaId = null, $teamId = null, $weekendFriendly = false)
    {
        Wrapper\Team\Senior::challenge($oppenentTeamId, $matchType, $matchPlace, $arenaId, $teamId, $weekendFriendly);
    }

    /**
     * Return challengeable team object
     *
     * @param array $teamIds
     * @param integer $teamId
     * @param boolean $weekendFriendly
     * @return \PHT\Xml\Team\Challengeable\Listing
     */
    public function getChallengeableTeams($teamIds, $teamId = null, $weekendFriendly = false)
    {
        $ids = implode(',', $teamIds);
        $params = array('file' => 'challenges', 'version' => Config\Version::CHALLENGES, 'actionType' => 'challengeable', 'suggestedTeamIds' => $ids, 'isWeekendFriendly' => (int)$weekendFriendly);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Team\Challengeable\Listing(Network\Request::fetchUrl($url));
    }

    /**
     * Return training stats object
     *
     * @param integer $leagueId
     * @return \PHT\Xml\Stats\Training\Listing
     */
    public function getTrainingStats($leagueId = null)
    {
        return Wrapper\Stats::training($leagueId);
    }

    /**
     * Return national players stats object
     *
     * @param integer $teamId
     * @param string $type (see \PHT\Config\Config STATS_NATIONAL_* constants)
     * @param boolean $showAll
     * @return \PHT\Xml\Stats\National\Players
     */
    public function getNationalPlayersStats($teamId, $type = Config\Config::STATS_NATIONAL_NT, $showAll = true)
    {
        return Wrapper\Stats::nationalplayers($teamId, $type, $showAll);
    }

    /**
     * Return arena stats object
     * /!\ Only valid for arenas of connected user
     *
     * @param integer $arenaId
     * @param string $matchType (see \PHT\Config\Config STATS_ARENA_* constants)
     * @param string $startDate (format should be : yyyy-mm-dd)
     * @param string $endDate (format should be : yyyy-mm-dd)
     * @return \PHT\Xml\Stats\Arena\User
     */
    public function getArenaStats($arenaId = null, $matchType = Config\Config::STATS_ARENA_ALL, $startDate = null, $endDate = null)
    {
        return Wrapper\Stats::arena($arenaId, $matchType, $startDate, $endDate);
    }

    /**
     * Return global stats for arenas
     *
     * @param integer $leagueId (by default 0 = global statistics)
     * @return \PHT\Xml\Stats\Arena\Arenas
     */
    public function getArenasStats($leagueId = 0)
    {
        return Wrapper\Stats::arenas($leagueId);
    }

}
