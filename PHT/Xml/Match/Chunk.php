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

namespace PHT\Xml\Match;

use PHT\Xml;
use PHT\Wrapper;
use PHT\Config;
use PHT\Utils;

class Chunk extends Xml\Base
{
    /**
     * @param \DOMDocument $xml
     */
    public function __construct($xml)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
    }

    /**
     * Return if it's youth match
     *
     * @return boolean
     */
    public function isYouth()
    {
        if ($this->getXml()->getElementsByTagName('SourceSystem')->length) {
            return strtolower($this->getXml()->getElementsByTagName('SourceSystem')->item(0)->nodeValue) == Config\Config::MATCH_YOUTH;
        }
        return false;
    }

    /**
     * Return if it's tournament match
     *
     * @return boolean
     */
    public function isTournament()
    {
        if ($this->getXml()->getElementsByTagName('SourceSystem')->length) {
            return strtolower($this->getXml()->getElementsByTagName('SourceSystem')->item(0)->nodeValue) == Config\Config::MATCH_SENIOR;
        }
        return false;
    }

    /**
     * Return match id
     *
     * @return integer
     */
    public function getId()
    {
        if ($this->getXml()->getElementsByTagName('MatchId')->length) {
            return $this->getXml()->getElementsByTagName('MatchId')->item(0)->nodeValue;
        }
        return $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
    }

    /**
     * Return match details
     *
     * @param boolean $events
     * @return \PHT\Xml\Match
     */
    public function getMatch($events = true)
    {
        if ($this->isYouth()) {
            return Wrapper\Match::youth($this->getId(), $events);
        } elseif ($this->isTournament()) {
            return Wrapper\Match::tournament($this->getId(), $events);
        }
        return Wrapper\Match::senior($this->getId(), $events);
    }

    /**
     * Return home team id
     *
     * @return integer
     */
    public function getHomeTeamId()
    {
        $xpath = new \DOMXPath($this->getXml());
        if ($xpath->query('//HomeTeam/TeamId')->length) {
            return $xpath->query('//HomeTeam/TeamId')->item(0)->nodeValue;
        } elseif ($xpath->query('//HomeTeam/TeamID')->length) {
            return $xpath->query('//HomeTeam/TeamID')->item(0)->nodeValue;
        } elseif ($this->getXml()->getElementsByTagName('HomeTeamId')->length) {
            return $this->getXml()->getElementsByTagName('HomeTeamId')->item(0)->nodeValue;
        }
        return $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
    }

    /**
     * Return home team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth
     */
    public function getHomeTeam()
    {
        if ($this->isYouth()) {
            return Wrapper\Team\Youth::team($this->getHomeTeamId());
        }
        return Wrapper\Team\Senior::team($this->getHomeTeamId());
    }

    /**
     * Return home team name
     *
     * @return string
     */
    public function getHomeTeamName()
    {
        $xpath = new \DOMXPath($this->getXml());
        if ($xpath->query('//HomeTeam/TeamName')->length) {
            return $xpath->query('//HomeTeam/TeamName')->item(0)->nodeValue;
        }
        return $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
    }

    /**
     * Return home team short name
     *
     * @return string
     */
    public function getHomeTeamShortName()
    {
        if ($this->getXml()->getElementsByTagName('HomeShortTeamName')->length) {
            return $this->getXml()->getElementsByTagName('HomeShortTeamName')->item(0)->nodeValue;
        }
        if ($this->getXml()->getElementsByTagName('HomeTeamShortName')->length) {
            return $this->getXml()->getElementsByTagName('HomeTeamShortName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return home team goals
     *
     * @return integer
     */
    public function getHomeGoals()
    {
        if ($this->getXml()->getElementsByTagName('HomeGoals')->length) {
            return $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return away team id
     *
     * @return integer
     */
    public function getAwayTeamId()
    {
        $xpath = new \DOMXPath($this->getXml());
        if ($xpath->query('//AwayTeam/TeamId')->length) {
            return $xpath->query('//AwayTeam/TeamId')->item(0)->nodeValue;
        } elseif ($xpath->query('//AwayTeam/TeamID')->length) {
            return $xpath->query('//AwayTeam/TeamID')->item(0)->nodeValue;
        } elseif ($this->getXml()->getElementsByTagName('AwayTeamId')->length) {
            return $this->getXml()->getElementsByTagName('AwayTeamId')->item(0)->nodeValue;
        }
        return $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
    }

    /**
     * Return away team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth
     */
    public function getAwayTeam()
    {
        if ($this->isYouth()) {
            return Wrapper\Team\Youth::team($this->getAwayTeamId());
        }
        return Wrapper\Team\Senior::team($this->getAwayTeamId());
    }

    /**
     * Return away team name
     *
     * @return string
     */
    public function getAwayTeamName()
    {
        $xpath = new \DOMXPath($this->getXml());
        if ($xpath->query('//AwayTeam/TeamName')->length) {
            return $xpath->query('//AwayTeam/TeamName')->item(0)->nodeValue;
        }
        return $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
    }

    /**
     * Return away team short name
     *
     * @return string
     */
    public function getAwayTeamShortName()
    {
        if ($this->getXml()->getElementsByTagName('AwayShortTeamName')->length) {
            return $this->getXml()->getElementsByTagName('AwayShortTeamName')->item(0)->nodeValue;
        }
        if ($this->getXml()->getElementsByTagName('AwayTeamShortName')->length) {
            return $this->getXml()->getElementsByTagName('AwayTeamShortName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return away team goals
     *
     * @return integer
     */
    public function getAwayGoals()
    {
        if ($this->getXml()->getElementsByTagName('AwayGoals')->length) {
            return $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return home league id
     *
     * @return integer
     */
    public function getHomeLeagueId()
    {
        if ($this->getXml()->getElementsByTagName('HomeLeagueID')->length) {
            return $this->getXml()->getElementsByTagName('HomeLeagueID')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return home country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getHomeCountry()
    {
        if ($this->getHomeLeagueId()) {
            return Wrapper\World::country($this->getHomeLeagueId());
        }
        return null;
    }

    /**
     * Return away league id
     *
     * @return integer
     */
    public function getAwayLeagueId()
    {
        if ($this->getXml()->getElementsByTagName('AwayLeagueID')->length) {
            return $this->getXml()->getElementsByTagName('AwayLeagueID')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return home country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getAwayCountry()
    {
        if ($this->getAwayLeagueId()) {
            return Wrapper\World::country($this->getAwayLeagueId());
        }
        return null;
    }

    /**
     * Return home league name
     *
     * @return string
     */
    public function getHomeLeagueName()
    {
        if ($this->getXml()->getElementsByTagName('HomeLeagueName')->length) {
            return $this->getXml()->getElementsByTagName('HomeLeagueName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return away league name
     *
     * @return string
     */
    public function getAwayLeagueName()
    {
        if ($this->getXml()->getElementsByTagName('AwayLeagueName')->length) {
            return $this->getXml()->getElementsByTagName('AwayLeagueName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return match date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return match finished date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getFinishedDate($format = null)
    {
        if ($this->getXml()->getElementsByTagName('FinishedDate')->length) {
            return Utils\Date::convert($this->getXml()->getElementsByTagName('FinishedDate')->item(0)->nodeValue, $format);
        }
        return null;
    }

    /**
     * Return match type code
     *
     * @return integer
     */
    public function getType()
    {
        if ($this->getXml()->getElementsByTagName('MatchType')->length) {
            return $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return cup level if match type is cup, 0 otherwise
     *
     * @return integer
     */
    public function getCupLevel()
    {
        if ($this->getXml()->getElementsByTagName('CupLevel')->length) {
            return $this->getXml()->getElementsByTagName('CupLevel')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return cup level index if match type is cup, 0 otherwise
     *
     * @return integer
     */
    public function getCupLevelIndex()
    {
        if ($this->getXml()->getElementsByTagName('CupLevelIndex')->length) {
            return $this->getXml()->getElementsByTagName('CupLevelIndex')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return match context id
     *
     * @return integer
     */
    public function getContextId()
    {
        if ($this->getXml()->getElementsByTagName('MatchContextId')->length) {
            return $this->getXml()->getElementsByTagName('MatchContextId')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Is orders already given for this match ?
     *
     * @return boolean
     */
    public function isOrdersGiven()
    {
        if ($this->getXml()->getElementsByTagName('OrdersGiven')->length) {
            return strtolower($this->getXml()->getElementsByTagName('OrdersGiven')->item(0)->nodeValue) == "true";
        }
        return null;
    }

    /**
     * Return match status : UPCOMING, ONGOING, FINISHED
     *
     * @return string
     */
    public function getStatus()
    {
        if ($this->getXml()->getElementsByTagName('Status')->length) {
            return $this->getXml()->getElementsByTagName('Status')->item(0)->nodeValue;
        }
        return Config\Config::MATCH_PLAYED;
    }

    /**
     * Return score
     *
     * @return string
     */
    public function getScore()
    {
        if ($this->getXml()->getElementsByTagName('HomeGoals')->length) {
            return $this->getHomeGoals() . '-' . $this->getAwayGoals();
        }
        return null;
    }

    /**
     * Return fan expectation
     *
     * @return integer
     */
    public function getFanExpectation()
    {
        if ($this->getXml()->getElementsByTagName('FanMatchExpectation')->length) {
            return $this->getXml()->getElementsByTagName('FanMatchExpectation')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return fan expectation
     *
     * @return integer
     */
    public function getFanMoodAfter()
    {
        if ($this->getXml()->getElementsByTagName('FanMoodAfterMatch')->length) {
            return $this->getXml()->getElementsByTagName('FanMoodAfterMatch')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return match weather
     *
     * @return integer
     */
    public function getWeather()
    {
        if ($this->getXml()->getElementsByTagName('Weather')->length) {
            return $this->getXml()->getElementsByTagName('Weather')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return match affluence
     *
     * @return integer
     */
    public function getAffluence()
    {
        if ($this->getXml()->getElementsByTagName('SoldSeats')->length) {
            return $this->getXml()->getElementsByTagName('SoldSeats')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return match round
     *
     * @return integer
     */
    public function getRound()
    {
        if ($this->getXml()->getElementsByTagName('MatchRound')->length) {
            return $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return match group
     *
     * @return integer
     */
    public function getGroup()
    {
        if ($this->getXml()->getElementsByTagName('Group')->length) {
            return $this->getXml()->getElementsByTagName('Group')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return home statement
     *
     * @return string
     */
    public function getHomeStatement()
    {
        if ($this->getXml()->getElementsByTagName('HomeStatement')->length) {
            return $this->getXml()->getElementsByTagName('HomeStatement')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return away statement
     *
     * @return string
     */
    public function getAwayStatement()
    {
        if ($this->getXml()->getElementsByTagName('AwayStatement')->length) {
            return $this->getXml()->getElementsByTagName('AwayStatement')->item(0)->nodeValue;
        }
        return null;
    }
}
