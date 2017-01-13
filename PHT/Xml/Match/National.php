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
use PHT\Utils;
use PHT\Wrapper;

class National extends Xml\Base
{
    private $u;

    /**
     * @param \DOMDocument $xml
     * @param boolean $u20
     */
    public function __construct($xml, $u20)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->u = $u20;
    }

    /**
     * Return match id
     *
     * @return integer
     */
    public function getId()
    {
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
        return Wrapper\Match::senior($this->getId(), $events);
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
     * Return match type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
    }

    /**
     * Return home type name
     *
     * @return string
     */
    public function getHomeTeamName()
    {
        return $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
    }

    /**
     * Return home national team
     *
     * @return \PHT\Xml\Team\National\Chunk
     */
    public function getHomeTeam()
    {
        $teams = Wrapper\National::teams($this->u);
        foreach ($teams->getTeams() as $team) {
            if ($team->getName() == $this->getHomeTeamName()) {
                return $team;
            }
        }
        return null;
    }

    /**
     * Return away type name
     *
     * @return string
     */
    public function getAwayTeamName()
    {
        return $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
    }

    /**
     * Return away national team
     *
     * @return \PHT\Xml\Team\National\Chunk
     */
    public function getAwayTeam()
    {
        $teams = Wrapper\National::teams($this->u);
        foreach ($teams->getTeams() as $team) {
            if ($team->getName() == $this->getAwayTeamName()) {
                return $team;
            }
        }
        return null;
    }

    /**
     * Return if match is finished
     *
     * @return boolean
     */
    public function isResultAvailable()
    {
        if ($this->getXml()->getElementsByTagName('HomeGoals')->length && $this->getXml()->getElementsByTagName('AwayGoals')->length) {
            return true;
        }
        return false;
    }

    /**
     * Return home goals number
     *
     * @return integer
     */
    public function getHomeGoals()
    {
        if ($this->isResultAvailable()) {
            return $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return away goals number
     *
     * @return integer
     */
    public function getAwayGoals()
    {
        if ($this->isResultAvailable()) {
            return $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
        }
        return null;
    }
}
