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
use PHT\Config;
use PHT\Wrapper;

class National extends Xml\File
{
    /**
     * Return team id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return team name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return coach user id
     *
     * @return integer
     */
    public function getCoachUserId()
    {
        return $this->getXml()->getElementsByTagName('NationalCoachUserID')->item(0)->nodeValue;
    }

    /**
     * Return coach user name
     *
     * @return string
     */
    public function getCoachUserName()
    {
        return $this->getXml()->getElementsByTagName('NationalCoachLoginname')->item(0)->nodeValue;
    }

    /**
     * Return short team name
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->getXml()->getElementsByTagName('ShortTeamName')->item(0)->nodeValue;
    }

    /**
     * Return league id
     *
     * @return integer
     */
    public function getLeagueId()
    {
        return $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
    }

    /**
     * Return league name
     *
     * @return string
     */
    public function getLeagueName()
    {
        return $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
    }

    /**
     * Return country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country($this->getLeagueId());
    }

    /**
     * Return home page url
     *
     * @return string
     */
    public function getHomepageUrl()
    {
        return $this->getXml()->getElementsByTagName('HomePage')->item(0)->nodeValue;
    }

    /**
     * Return dress uri
     *
     * @return string
     */
    public function getDressURI()
    {
        return $this->getXml()->getElementsByTagName('DressURI')->item(0)->nodeValue;
    }

    /**
     * Return dress alternate uri
     *
     * @return string
     */
    public function getDressAlternateURI()
    {
        return $this->getXml()->getElementsByTagName('DressAlternateURI')->item(0)->nodeValue;
    }

    /**
     * Return 442 experience level
     *
     * @return integer
     */
    public function get442Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience442')->item(0)->nodeValue;
    }

    /**
     * Return 433 experience level
     *
     * @return integer
     */
    public function get433Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience433')->item(0)->nodeValue;
    }

    /**
     * Return 451 experience level
     *
     * @return integer
     */
    public function get451Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience451')->item(0)->nodeValue;
    }

    /**
     * Return 352 experience level
     *
     * @return integer
     */
    public function get352Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience352')->item(0)->nodeValue;
    }

    /**
     * Return 532 experience level
     *
     * @return integer
     */
    public function get532Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience532')->item(0)->nodeValue;
    }

    /**
     * Return 343 experience level
     *
     * @return integer
     */
    public function get343Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience343')->item(0)->nodeValue;
    }

    /**
     * Return 451 experience level
     *
     * @return integer
     */
    public function get541Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience541')->item(0)->nodeValue;
    }

    /**
     * Return 523 experience level
     *
     * @return integer
     */
    public function get523Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience523')->item(0)->nodeValue;
    }

    /**
     * Return 550 experience level
     *
     * @return integer
     */
    public function get550Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience550')->item(0)->nodeValue;
    }

    /**
     * Return 253 experience level
     *
     * @return integer
     */
    public function get253Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience253')->item(0)->nodeValue;
    }

    /**
     * Return team spirit level
     *
     * @return integer
     */
    public function getTeamSpirit()
    {
        return $this->getXml()->getElementsByTagName('Morale')->item(0)->nodeValue;
    }

    /**
     * Return self confidence level
     *
     * @return integer
     */
    public function getSelfConfidence()
    {
        return $this->getXml()->getElementsByTagName('SelfConfidence')->item(0)->nodeValue;
    }

    /**
     * Return self confidence level
     *
     * @return integer
     */
    public function getSupportersPopularity()
    {
        return $this->getXml()->getElementsByTagName('SupportersPopularity')->item(0)->nodeValue;
    }

    /**
     * Return rating score
     *
     * @return integer
     */
    public function getRatingScore()
    {
        return $this->getXml()->getElementsByTagName('RatingScore')->item(0)->nodeValue;
    }

    /**
     * Return fan club size
     *
     * @return integer
     */
    public function getFanClubSize()
    {
        return $this->getXml()->getElementsByTagName('FanClubSize')->item(0)->nodeValue;
    }

    /**
     * Return team rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->getXml()->getElementsByTagName('Rank')->item(0)->nodeValue;
    }

    /**
     * Return if team is playing a match
     *
     * @return boolean
     */
    public function isPlayingMatch()
    {
        return strtolower($this->getXml()->getElementsByTagName('IsPlayingMatch')->item(0)->nodeValue) == "true";
    }

    /**
     * Return team logo url
     *
     * @return string
     */
    public function getLogoUrl()
    {
        return $this->getXml()->getElementsByTagName('Logo')->item(0)->nodeValue;
    }

    /**
     * Get team players
     *
     * @return \PHT\Xml\National\Players
     */
    public function getPlayers()
    {
        return Wrapper\National::players($this->getId());
    }

    /**
     * Get team players stats object
     *
     * @param string $type (see \PHT\Config\Config STATS_NATIONAL_* constants)
     * @param boolean $showAll
     * @return \PHT\Xml\Stats\National\Players
     */
    public function getPlayersStats($type = Config\Config::STATS_NATIONAL_NT, $showAll = true)
    {
        return Wrapper\Stats::nationalplayers($this->getId(), $type, $showAll);
    }

    /**
     * Get array of team matches
     *
     * @return \PHT\Xml\Match\National[]
     */
    public function getMatches()
    {
        $u20 = strpos($this->getName(), 'U-20') !== false;
        $matches = Wrapper\National::matches($u20);
        $ok = array();
        foreach ($matches->getMatches() as $match) {
            if ($match->getHomeTeamName() == $this->getName() || $match->getAwayTeamName() == $this->getName()) {
                $ok[] = $match;
            }
        }
        return $ok;
    }

    /**
     * Return challenges, only team belongs to connected user
     *
     * @param boolean $weekendFriendly
     * @return \PHT\Xml\Team\Challengeable\Listing
     */
    public function getChallenges($weekendFriendly = false)
    {
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
        Wrapper\Team\Senior::challenge($this->getId(), $matchType, $matchPlace, $arenaId, $challengerTeamId, $weekendFriendly);
    }
}
