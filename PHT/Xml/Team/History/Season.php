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

namespace PHT\Xml\Team\History;

use PHT\Xml;
use PHT\Wrapper;

class Season extends Xml\Base
{
    private $teamId;

    /**
     * @param \DOMDocument $xml
     * @param integer $teamId
     */
    public function __construct($xml, $teamId = null)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->teamId = $teamId;
    }

    /**
     * Return zero-based index of season entry in list
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->getXml()->getElementsByTagName('Season')->item(0)->getAttribute('Index');
    }

    /**
     * Return season number, in local season numbering of team's league
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->getXml()->getElementsByTagName('SeasonNumber')->item(0)->nodeValue;
    }

    /**
     * Return senior league (series) id the team played in that season
     *
     * @return integer
     */
    public function getSeniorLeagueId()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevelUnitID')->item(0)->nodeValue;
    }

    /**
     * Return senior league (series) name the team played in that season
     *
     * @return string
     */
    public function getSeniorLeagueName()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
    }

    /**
     * Return senior league (series) level (division) that season
     *
     * @return integer
     */
    public function getSeniorLeagueLevel()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevel')->item(0)->nodeValue;
    }

    /**
     * Return senior league (series) object, with current data
     *
     * @return \PHT\Xml\World\League\Senior
     */
    public function getSeniorLeague()
    {
        return Wrapper\World\League::senior($this->getSeniorLeagueId());
    }

    /**
     * Return senior league (series) fixtures of that season
     *
     * @return \PHT\Xml\World\League\Season\Senior
     */
    public function getSeniorLeagueSeason()
    {
        return Wrapper\World\Season::senior($this->getSeniorLeagueId(), $this->getSeason());
    }

    /**
     * Return team final position in series
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->getXml()->getElementsByTagName('Position')->item(0)->nodeValue;
    }

    /**
     * Return team league points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->getXml()->getElementsByTagName('Points')->item(0)->nodeValue;
    }

    /**
     * Return number of goals scored by team
     *
     * @return integer
     */
    public function getGoalsFor()
    {
        return $this->getXml()->getElementsByTagName('GoalsFor')->item(0)->nodeValue;
    }

    /**
     * Return number of goals conceded by team
     *
     * @return integer
     */
    public function getGoalsAgainst()
    {
        return $this->getXml()->getElementsByTagName('GoalsAgainst')->item(0)->nodeValue;
    }

    /**
     * Return goal difference
     *
     * @return integer
     */
    public function getGoalsDifference()
    {
        return $this->getGoalsFor() - $this->getGoalsAgainst();
    }

    /**
     * Return if team has a national cup result that season
     *
     * @return boolean
     */
    public function hasCupResult()
    {
        return $this->getXml()->getElementsByTagName('CupRound')->item(0)->nodeValue != -1;
    }

    /**
     * Return national cup round reached by team, last round if team won the cup, -1 if no cup result
     *
     * @return integer
     */
    public function getCupRound()
    {
        return $this->getXml()->getElementsByTagName('CupRound')->item(0)->nodeValue;
    }

    /**
     * Return number of national cup rounds left when team was knocked out, counting round it lost,
     * 0 if team won the cup, -1 if no cup result
     *
     * @return integer
     */
    public function getCupRoundsLeft()
    {
        return $this->getXml()->getElementsByTagName('CupRoundsLeft')->item(0)->nodeValue;
    }

    /**
     * Return if team won the national cup that season
     *
     * @return boolean
     */
    public function isCupWinner()
    {
        return $this->hasCupResult() && $this->getCupRoundsLeft() == 0;
    }

    /**
     * Return team object
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        if ($this->teamId === null) {
            return null;
        }
        return Wrapper\Team\Senior::team($this->teamId);
    }
}
