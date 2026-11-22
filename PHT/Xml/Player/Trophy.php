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

namespace PHT\Xml\Player;

use PHT\Xml;
use PHT\Utils;
use PHT\Wrapper;

class Trophy extends Xml\Base
{
    const CUP_WIN = 16;
    const SERIES_WIN = 17;
    const SERIES_TOP_SCORER = 20;

    /**
     * @param \DOMDocument $xml
     */
    public function __construct($xml)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
    }

    /**
     * Return trophy type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('TrophyTypeId')->item(0)->nodeValue;
    }

    /**
     * Return id of club the trophy was won with, 0 for national team and top scorer trophies
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
    }

    /**
     * Return name of team the trophy was won with, empty for top scorer trophies
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return senior team the trophy was won with
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        if ($this->getTeamId() > 0) {
            return Wrapper\Team\Senior::team($this->getTeamId());
        }
        return null;
    }

    /**
     * Return trophy season
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->getXml()->getElementsByTagName('TrophySeason')->item(0)->nodeValue;
    }

    /**
     * Return league level, depends on trophy type: division level for series titles,
     * medal for national team trophies (1: gold, 2: silver, 3: bronze), always 1 for top scorer trophies
     *
     * @return integer
     */
    public function getLeagueLevel()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevel')->item(0)->nodeValue;
    }

    /**
     * Return competition id: series id for series titles and series top scorer, cup id for cup titles,
     * tournament id for national team trophies
     *
     * @return integer
     */
    public function getCompetitionId()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevelUnitId')->item(0)->nodeValue;
    }

    /**
     * Return competition name (series, cup or tournament), when trophy stores one
     *
     * @return string
     */
    public function getCompetitionName()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
    }

    /**
     * Return senior league (series), only for series titles and series top scorer trophies
     *
     * @return \PHT\Xml\World\League\Senior
     */
    public function getSeniorLeague()
    {
        if (in_array($this->getType(), array(self::SERIES_WIN, self::SERIES_TOP_SCORER)) && $this->getCompetitionId() > 0) {
            return Wrapper\World\League::senior($this->getCompetitionId());
        }
        return null;
    }

    /**
     * Return senior league season of trophy, only for series titles and series top scorer trophies
     *
     * @return \PHT\Xml\World\League\Season\Senior
     */
    public function getSeniorLeagueSeason()
    {
        if (in_array($this->getType(), array(self::SERIES_WIN, self::SERIES_TOP_SCORER)) && $this->getCompetitionId() > 0) {
            return Wrapper\World\Season::senior($this->getCompetitionId(), $this->getSeason());
        }
        return null;
    }

    /**
     * Return cup, only for cup titles
     *
     * @return \PHT\Xml\World\Cup
     */
    public function getCup()
    {
        if ($this->getType() == self::CUP_WIN && $this->getCompetitionId() > 0) {
            return Wrapper\World::cup($this->getCompetitionId(), null, $this->getSeason());
        }
        return null;
    }

    /**
     * Return date when trophy was gained
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('GainedDate')->item(0)->nodeValue, $format);
    }
}
