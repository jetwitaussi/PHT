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

namespace PHT\Xml\Team;

use PHT\Xml;
use PHT\Wrapper;
use PHT\Utils;
use PHT\Config;

class Senior extends Xml\HTSupporter
{
    const PRIMARY = 1;
    const SECONDARY = 2;
    const INTERNATIONAL = 3;

    /**
     *
     * @param string $xml
     * @param integer $id
     */
    public function __construct($xml, $id = null)
    {
        parent::__construct($xml);
        if ($this->xml->getElementsByTagName('Team')->length >= 2) {
            $teams = $this->xml->getElementsByTagName('Team');
            if ($id === null) {
                for ($t = $teams->length - 1; $t >= 0; $t--) {
                    $txml = new \DOMDocument('1.0', 'UTF-8');
                    $txml->appendChild($txml->importNode($teams->item($t), true));
                    if (strtolower($txml->getElementsByTagName('IsPrimaryClub')->item(0)->nodeValue) == 'false') {
                        $this->xml->getElementsByTagName('Teams')->item(0)->removeChild($teams->item($t));
                    }
                }
            } else {
                for ($t = $teams->length - 1; $t >= 0; $t--) {
                    $txml = new \DOMDocument('1.0', 'UTF-8');
                    $txml->appendChild($txml->importNode($teams->item($t), true));
                    if ($txml->getElementsByTagName('TeamID')->item(0)->nodeValue != $id) {
                        $this->xml->getElementsByTagName('Teams')->item(0)->removeChild($teams->item($t));
                    }
                }
            }
            $this->xmlText = $this->xml->saveXML();
        }
    }

    /**
     * Return Team Id
     *
     * @return integer
     */
    public function getId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return Team name of connected user
     *
     * @return string
     */
    public function getName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return teams's userid
     *
     * @return integer
     */
    public function getUserId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('UserID')->item(1)->nodeValue;
    }

    /**
     * Return team user's language id
     *
     * @return integer
     */
    public function getLanguageId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('LanguageID')->item(0)->nodeValue;
    }

    /**
     * Return team user's language name
     *
     * @return string
     */
    public function getLanguageName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('LanguageName')->item(0)->nodeValue;
    }

    /**
     * Return user public loginname
     *
     * @return string
     */
    public function getLoginName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('Loginname')->item(0)->nodeValue;
    }

    /**
     * Return irl user name if he made it public
     *
     * @return string
     */
    public function getUserName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        $name = $this->getXml()->getElementsByTagName('Name')->item(0)->nodeValue;
        if ($name == 'HIDDEN') {
            return null;
        }
        return $name;
    }

    /**
     * Return user icq number
     *
     * @return string
     */
    public function getIcq()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('ICQ')->item(0)->nodeValue;
    }

    /**
     * Return signup date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getSignupDate($format = null)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Utils\Date::convert($this->getXml()->getElementsByTagName('SignupDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return activation date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getActivationDate($format = null)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Utils\Date::convert($this->getXml()->getElementsByTagName('ActivationDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return team founded date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getFoundedDate($format = null)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Utils\Date::convert($this->getXml()->getElementsByTagName('FoundedDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return last login date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getLastLoginDate($format = null)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Utils\Date::convert($this->getXml()->getElementsByTagName('LastLoginDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return short team nam
     *
     * @return string
     */
    public function getShortName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('ShortTeamName')->item(0)->nodeValue;
    }

    /**
     * Return arena id
     *
     * @return integer
     */
    public function getArenaId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
    }

    /**
     * Return arena
     *
     * @return \PHT\Xml\Team\Arena
     */
    public function getArena()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::arena($this->getArenaId());
    }

    /**
     * Return arena name
     *
     * @return string
     */
    public function getArenaName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
    }

    /**
     * Return league id
     *
     * @return integer
     */
    public function getLeagueId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
    }

    /**
     * Return league name
     *
     * @return string
     */
    public function getLeagueName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
    }

    /**
     * Return country id
     *
     * @return integer
     */
    public function getCountryId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('CountryID')->item(0)->nodeValue;
    }

    /**
     * Return country name
     *
     * @return string
     */
    public function getCountryName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('CountryName')->item(0)->nodeValue;
    }

    /**
     * Return country details
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country($this->getLeagueId());
    }

    /**
     * Return region id
     *
     * @return integer
     */
    public function getRegionId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
    }

    /**
     * Return region
     *
     * @return \PHT\Xml\World\Region
     */
    public function getRegion()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\World::region($this->getRegionId());
    }

    /**
     * Return region name
     *
     * @return string
     */
    public function getRegionName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
    }

    /**
     * Return trainer id
     *
     * @return integer
     */
    public function getTrainerId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
    }

    /**
     * Return home page url
     *
     * @return string
     */
    public function getHomePageUrl()
    {
        if ($this->isDeleted()) {
            return null;
        }
        $homePageUrl = $this->getXml()->getElementsByTagName('HomePage')->item(0)->nodeValue;
        if (substr($homePageUrl, 0, 7) !== 'http://' && substr($homePageUrl, 0, 8) !== 'https://') {
            $homePageUrl = 'http://' . $homePageUrl;
        }
        return $homePageUrl;
    }

    /**
     * Return url of main dress
     *
     * @return string
     */
    public function getDressURI()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('DressURI')->item(0)->nodeValue;
    }

    /**
     * Return url of alternate dress
     *
     * @return string
     */
    public function getDressAlternateURI()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('DressAlternateURI')->item(0)->nodeValue;
    }

    /**
     * Is the team a bot ?
     *
     * @return boolean
     */
    public function isBot()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return strtolower($this->getXml()->getElementsByTagName('IsBot')->item(0)->nodeValue) == "true";
    }

    /**
     * Return date when team became bot, if it's a bot
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getBotDate($format = null)
    {
        if ($this->isDeleted() || !$this->isBot()) {
            return null;
        }
        return Utils\Date::convert($this->getXml()->getElementsByTagName('BotSince')->item(0)->nodeValue, $format);
    }

    /**
     * Is the team still in cup ?
     *
     * @return boolean
     */
    public function isInCup()
    {
        if ($this->isDeleted()) {
            return false;
        }
        if ($this->getXml()->getElementsByTagName('StillInCup')->length) {
            return strtolower($this->getXml()->getElementsByTagName('StillInCup')->item(0)->nodeValue) == "true";
        }
        return false;
    }

    /**
     * Return cup id
     *
     * @return integer
     */
    public function getCupId()
    {
        if ($this->isDeleted() || !$this->isInCup()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
    }

    /**
     * Return cup
     *
     * @param integer $round
     * @param integer $season
     * @param integer $afterMatchId
     * @return \PHT\Xml\World\Cup
     */
    public function getCup($round = null, $season = null, $afterMatchId = null)
    {
        if ($this->isDeleted() || !$this->isInCup()) {
            return null;
        }
        return Wrapper\World::cup($this->getCupId(), $round, $season, $afterMatchId);
    }

    /**
     * Return cup name
     *
     * @return string
     */
    public function getCupName()
    {
        if ($this->isDeleted() || !$this->isInCup()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('CupName')->item(0)->nodeValue;
    }

    /**
     * Return cup league level
     *
     * @return integer
     */
    public function getCupLeagueLevel()
    {
        if ($this->isDeleted() || !$this->isInCup()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('CupLeagueLevel')->item(0)->nodeValue;
    }

    /**
     * Return cup level
     *
     * @return integer
     */
    public function getCupLevel()
    {
        if ($this->isDeleted() || !$this->isInCup()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('CupLevel')->item(0)->nodeValue;
    }

    /**
     * Return cup level index
     *
     * @return integer
     */
    public function getCupLevelIndex()
    {
        if ($this->isDeleted() || !$this->isInCup()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('CupLevelIndex')->item(0)->nodeValue;
    }

    /**
     * Return cup match round
     *
     * @return integer
     */
    public function getCupMatchRound()
    {
        if ($this->isDeleted() || !$this->isInCup()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
    }

    /**
     * Return cup match round left
     *
     * @return integer
     */
    public function getCupMatchRoundLeft()
    {
        if ($this->isDeleted() || !$this->isInCup()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('MatchRoundsLeft')->item(0)->nodeValue;
    }

    /**
     * Is League data available ?
     *
     * @return boolean
     */
    public function isSeniorLeagueAvailable()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('LeagueLevelUnit')->item(0)->hasChildNodes();
    }

    /**
     * Return senior league level
     *
     * @return integer
     */
    public function getSeniorLeagueLevel()
    {
        if ($this->isDeleted() || !$this->isSeniorLeagueAvailable()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('LeagueLevel')->item(0)->nodeValue;
    }

    /**
     * Return senior league id
     *
     * @return integer
     */
    public function getSeniorLeagueId()
    {
        if ($this->isDeleted() || !$this->isSeniorLeagueAvailable()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('LeagueLevelUnitID')->item(0)->nodeValue;
    }

    /**
     * Get senior league
     *
     * @return \PHT\Xml\World\League\Senior
     */
    public function getSeniorLeague()
    {
        if ($this->isDeleted() || !$this->isSeniorLeagueAvailable()) {
            return null;
        }
        return Wrapper\World\League::senior($this->getSeniorLeagueId());
    }

    /**
     * Return senior league name
     *
     * @return integer
     */
    public function getSeniorLeagueName()
    {
        if ($this->isDeleted() || !$this->isSeniorLeagueAvailable()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
    }

    /**
     * Return opposite team id for friendly match
     *
     * @return integer
     */
    public function getFriendlyOppositeTeamId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        $friendlyTeamId = $this->getXml()->getElementsByTagName('FriendlyTeamID')->item(0)->nodeValue;
        if ($friendlyTeamId == 0) {
            return null;
        }
        return $friendlyTeamId;
    }

    /**
     * Return number of consecutive victories
     *
     * @return integer
     */
    public function getNumberOfVictories()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('NumberOfVictories')->item(0)->nodeValue;
    }

    /**
     * Return number of consecutive undefeated match
     *
     * @return integer
     */
    public function getNumberOfUndefeat()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('NumberOfUndefeated')->item(0)->nodeValue;
    }

    /**
     * Return team rank
     *
     * @return integer
     */
    public function getRank()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('TeamRank')->item(0)->nodeValue;
    }

    /**
     * Return logo url
     *
     * @return string
     */
    public function getLogoUrl()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return str_replace("\\", "/", $this->getXml()->getElementsByTagName('LogoURL')->item(0)->nodeValue);
    }

    /**
     * Return fan club id
     *
     * @return integer
     */
    public function getFanClubId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('FanclubID')->item(0)->nodeValue;
    }

    /**
     * Return fan club name
     *
     * @return string
     */
    public function getFanClubName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('FanclubName')->item(0)->nodeValue;
    }

    /**
     * Return fan club size
     *
     * @return integer
     */
    public function getFanClubSize()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('FanclubSize')->item(0)->nodeValue;
    }

    /**
     * Return number of messages in user guestbook
     *
     * @return integer
     */
    public function getNumberMessageInGuestbook()
    {
        if ($this->isDeleted() || !$this->isHtSupporter()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('NumberOfGuestbookItems')->item(0)->nodeValue;
    }

    /**
     * Return title of last press announcement
     *
     * @return string
     */
    public function getPressAnnouncementTitle()
    {
        if ($this->isDeleted() || !$this->isHtSupporter()) {
            return null;
        }
        $node = $this->getXml()->getElementsByTagName('Subject');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return content of last press announcement
     *
     * @return string
     */
    public function getPressAnnouncementText()
    {
        if ($this->isDeleted() || !$this->isHtSupporter()) {
            return null;
        }
        $node = $this->getXml()->getElementsByTagName('Body');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return content of last press announcement
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getPressAnnouncementDate($format = null)
    {
        if ($this->isDeleted() || !$this->isHtSupporter()) {
            return null;
        }
        $node = $this->getXml()->getElementsByTagName('SendDate');
        if ($node !== null && $node->length) {
            return Utils\Date::convert($node->item(0)->nodeValue, $format);
        }
        return null;
    }

    /**
     * Return youth team id
     *
     * @return integer
     */
    public function getYouthTeamId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        $youthTeamId = $this->getXml()->getElementsByTagName('YouthTeamID')->item(0)->nodeValue;
        if ($youthTeamId == 0) {
            return null;
        }
        return $youthTeamId;
    }

    /**
     * Return youth team
     *
     * @return \PHT\Xml\Team\Youth
     */
    public function getYouthTeam()
    {
        if ($this->isDeleted() || !$this->getYouthTeamId()) {
            return null;
        }
        return Wrapper\Team\Youth::team($this->getYouthTeamId());
    }

    /**
     * Return youth team name
     *
     * @return string
     */
    public function getYouthTeamName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('YouthTeamName')->item(0)->nodeValue;
    }

    /**
     * Return if team does not exist anymore and is not a bot
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->getXml()->getElementsByTagName('Team')->length == 0;
    }

    /**
     * Return if team is primary team
     *
     * @return boolean
     */
    public function isPrimaryTeam()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return strtolower($this->getXml()->getElementsByTagName('IsPrimaryClub')->item(0)->nodeValue) == 'true';
    }

    /**
     * Return if team is secondary team
     *
     * @return boolean
     */
    public function isSecondaryTeam()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return strtolower($this->getXml()->getElementsByTagName('IsPrimaryClub')->item(0)->nodeValue) == 'false';
    }

    /**
     * Return supporter color
     *
     * @return string
     */
    public function getHtSupporterColor()
    {
        if ($this->isDeleted() || !$this->isHtSupporter()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('Color')->item(0)->nodeValue;
    }

    /**
     * Return supporter background color
     *
     * @return string
     */
    public function getHtSupporterBackgroundColor()
    {
        if ($this->isDeleted() || !$this->isHtSupporter()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('BackgroundColor')->item(0)->nodeValue;
    }

    /**
     * Get economy data of user's club, converted in country currency if specfied
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return \PHT\Xml\Team\Economy
     */
    public function getEconomy($countryCurrency = null)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::economy($countryCurrency, $this->getId());
    }

    /**
     * Get club details
     *
     * @return \PHT\Xml\Club
     */
    public function getClub()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\User::club($this->getId());
    }

    /**
     * Get team players
     *
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Team\Senior\Players
     */
    public function getPlayers($includeMatchInfo = true)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::players($this->getId(), $includeMatchInfo);
    }

    /**
     * Get team players avatars
     *
     * @return \PHT\Xml\Team\Avatars
     */
    public function getPlayersAvatars()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::avatars($this->getId());
    }

    /**
     * Get team grown players
     *
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Team\Senior\Players
     */
    public function getGrownPlayers($includeMatchInfo = true)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::grownplayers($this->getId(), $includeMatchInfo);
    }

    /**
     * Get team trained coaches
     *
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Team\Senior\Players
     */
    public function getTrainedCoaches($includeMatchInfo = true)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::trainedcoaches($this->getId(), $includeMatchInfo);
    }

    /**
     * Get team hof players
     *
     * @return \PHT\Xml\Team\Senior\Hof
     */
    public function getHof()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::hofplayers($this->getId());
    }

    /**
     * Get team hof avatars
     *
     * @return \PHT\Xml\Team\Avatars
     */
    public function getHofAvatars()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::hofavatars($this->getId());
    }

    /**
     * Get team training
     *
     * @return \PHT\Xml\Team\Senior\Training
     */
    public function getTraining()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::training($this->getId());
    }

    /**
     * Set team training
     *
     * @param integer $type (see \PHT\Config\Config TRAINING_* constants)
     * @param integer $intensity
     * @param integer $stamina
     * @return boolean
     */
    public function setTraining($type = null, $intensity = null, $stamina = null)
    {
        if ($this->isDeleted()) {
            return false;
        }
        return Wrapper\Team\Senior::train($this->getId(), $type, $intensity, $stamina);
    }

    /**
     * Get team matches
     *
     * @param string $showBeforeDate (format should be : yyyy-mm-dd  - If no specify : returned matches are from now + 28 days)
     * @return \PHT\Xml\Team\Match\Listing
     */
    public function getMatches($showBeforeDate = null)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::matches($this->getId(), $showBeforeDate);
    }

    /**
     * Get team archive matches
     *
     * @param string $startDate (format should be : yyyy-mm-dd)
     * @param string $endDate (format should be : yyyy-mm-dd)
     * @param integer $season
     * @return \PHT\Xml\Team\Match\Archive
     */
    public function getMatchesArchive($startDate = null, $endDate = null, $season = null)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::archives($this->getId(), $startDate, $endDate, $season);
    }

    /**
     * Return team transfers history object
     *
     * @return \PHT\Xml\Team\Transfer\History
     */
    public function getTransfersHistory()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::transfershistory($this->getId());
    }

    /**
     * Return team bids object
     *
     * @param integer $onlyType (see \PHT\Config\Config BID_* constants)
     * @return \PHT\Xml\Team\Transfer\Bids
     */
    public function getBids($onlyType = null)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::bids($this->getId(), $onlyType);
    }

    /**
     * Delete finished transfers
     */
    public function deleteFinishedTransfers()
    {
        if ($this->isDeleted()) {
            return;
        }
        Wrapper\Team\Senior::deletetransfers($this->getId());
    }

    /**
     * Return team fans object
     *
     * @return \PHT\Xml\Team\Fans
     */
    public function getFans()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::fans($this->getId());
    }

    /**
     * Return team staff object
     *
     * @return \PHT\Xml\Team\Staff
     */
    public function getStaff()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::staff($this->getId());
    }

    /**
     * Return team staff avatars
     *
     * @return \PHT\Xml\Team\Avatars
     */
    public function getStaffAvatars()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::staffavatars($this->getId());
    }

    /**
     * Return team flags
     *
     * @return \PHT\Xml\Team\Flags
     */
    public function getFlags()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::flags($this->getId());
    }

    /**
     * Return team tournaments listing
     *
     * @return \PHT\Xml\Tournaments\Listing
     */
    public function getTournaments()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Tournament::listing($this->getId());
    }

    /**
     * Return team ladders listing
     *
     * @return \PHT\Xml\Tournaments\Ladders\Listing
     */
    public function getLadders()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Tournament::ladders($this->getId());
    }

    /**
     * Return supporters
     *
     * @param integer $page
     * @param integer $size
     * @return \PHT\Xml\Supporters
     */
    public function getSupporters($page = 0, $size = 50)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Supporters::listing($this->getId(), null, $page, $size);
    }

    /**
     * Return challenges, only team belongs to connected user
     *
     * @param boolean $weekendFriendly
     * @return \PHT\Xml\Team\Challengeable\Listing
     */
    public function getChallenges($weekendFriendly = false)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::challenges($this->getId(), $weekendFriendly);
    }

    /**
     * Challenge team
     *
     * @param integer $matchType (see \PHT\Config\Config CHALLENGE_* constants)
     * @param integer $matchPlace (see \PHT\Config\Config CHALLENGE_* constants)
     * @param integer $arenaId (only for neutral arena)
     * @param integer $challengerTeamId
     * @param boolean $weekendFriendly
     */
    public function challenge($matchType, $matchPlace, $arenaId = null, $challengerTeamId = null, $weekendFriendly = false)
    {
        if ($this->isDeleted()) {
            return;
        }
        Wrapper\Team\Senior::challenge($this->getId(), $matchType, $matchPlace, $arenaId, $challengerTeamId, $weekendFriendly);
    }

    /**
     * Return number of national team coached
     *
     * @return integer
     */
    public function getNationalTeamNumber()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('NationalTeam')->length;
    }

    /**
     * Return national team chunk object
     *
     * @param integer $index
     * @return \PHT\Xml\Team\National\Chunk
     */
    public function getNationalTeam($index)
    {
        if ($this->isDeleted()) {
            return null;
        }
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getNationalTeamNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query("//NationalTeam");
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new National\Chunk($node);
        }
        return null;
    }

    /**
     * Return iterator of national team chunk objects
     *
     * @return \PHT\Xml\Team\National\Chunk[]
     */
    public function getNationalTeams()
    {
        if ($this->isDeleted()) {
            return null;
        }
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//NationalTeam");
        /** @var \PHT\Xml\Team\National\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Team\National\Chunk');
        return $data;
    }

    /**
     * Return trophy number
     *
     * @return integer
     */
    public function getTrophyNumber()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('Trophy')->length;
    }

    /**
     * Return trophy object
     *
     * @param integer $index
     * @return \PHT\Xml\Team\Trophy
     */
    public function getTrophy($index)
    {
        if ($this->isDeleted()) {
            return null;
        }
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getTrophyNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query("//Trophy");
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Trophy($node);
        }
        return null;
    }

    /**
     * Return iterator of trophy objects
     *
     * @return \PHT\Xml\Team\Trophy[]
     */
    public function getTrophies()
    {
        if ($this->isDeleted()) {
            return null;
        }
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//Trophy");
        /** @var \PHT\Xml\Team\Trophy[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Team\Trophy');
        return $data;
    }

}
