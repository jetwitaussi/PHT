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

namespace PHT\Xml\Compendium;

use PHT\Xml;
use PHT\Wrapper;

class Team extends Xml\Base
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
     * Return team id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
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
     * Return senior team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getSeniorTeam()
    {
        return Wrapper\Team\Senior::team($this->getId());
    }

    /**
     * Return arena id
     *
     * @return integer
     */
    public function getArenaId()
    {
        return $this->getXml()->getElementsByTagName('ArenaId')->item(0)->nodeValue;
    }

    /**
     * Return arena
     *
     * @return \PHT\Xml\Team\Arena
     */
    public function getArena()
    {
        return Wrapper\Team\Senior::arena($this->getArenaId());
    }

    /**
     * Return arena name
     *
     * @return string
     */
    public function getArenaName()
    {
        return $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
    }

    /**
     * Return league id
     *
     * @return integer
     */
    public function getLeagueId()
    {
        return $this->getXml()->getElementsByTagName('LeagueId')->item(0)->nodeValue;
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
     * Return league name
     *
     * @return string
     */
    public function getLeagueName()
    {
        return $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
    }

    /**
     * Return senior league id
     *
     * @return integer
     */
    public function getSeniorLeagueId()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevelUnitId')->item(0)->nodeValue;
    }

    /**
     * Return senior league
     *
     * @return \PHT\Xml\World\League\Senior
     */
    public function getSeniorLeague()
    {
        return Wrapper\World\League::senior($this->getSeniorLeagueId());
    }

    /**
     * Return senior league name
     *
     * @return string
     */
    public function getSeniorLeagueName()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
    }

    /**
     * Return region id
     *
     * @return integer
     */
    public function getRegionId()
    {
        return $this->getXml()->getElementsByTagName('RegionId')->item(0)->nodeValue;
    }

    /**
     * Return region
     *
     * @return \PHT\Xml\World\Region
     */
    public function getRegion()
    {
        return Wrapper\World::region($this->getRegionId());
    }

    /**
     * Return region name
     *
     * @return string
     */
    public function getRegionName()
    {
        return $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
    }

    /**
     * Is youth team exist ?
     *
     * @return boolean
     */
    public function hasYouthTeam()
    {
        return $this->getXml()->getElementsByTagName('YouthTeam')->item(0)->hasChildNodes();
    }

    /**
     * Return youth team id
     *
     * @return integer
     */
    public function getYouthTeamId()
    {
        if ($this->hasYouthTeam()) {
            return $this->getXml()->getElementsByTagName('YouthTeamId')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return youth team
     *
     * @return \PHT\Xml\Team\Youth
     */
    public function getYouthTeam()
    {
        if ($this->hasYouthTeam()) {
            return Wrapper\Team\Youth::team($this->getYouthTeamId());
        }
        return null;
    }

    /**
     * Return youth team name
     *
     * @return string
     */
    public function getYouthTeamName()
    {
        if ($this->hasYouthTeam()) {
            return $this->getXml()->getElementsByTagName('YouthTeamName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return youth team league id
     *
     * @return integer
     */
    public function getYouthLeagueId()
    {
        if ($this->hasYouthTeam()) {
            return $this->getXml()->getElementsByTagName('YouthLeagueId')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return youth league
     *
     * @return \PHT\Xml\World\League\Youth
     */
    public function getYouthLeague()
    {
        if ($this->hasYouthTeam()) {
            return Wrapper\World\League::youth($this->getYouthLeagueId());
        }
        return null;
    }

    /**
     * Return youth team league name
     *
     * @return string
     */
    public function getYouthLeagueName()
    {
        if ($this->hasYouthTeam()) {
            return $this->getXml()->getElementsByTagName('YouthLeagueName')->item(0)->nodeValue;
        }
        return null;
    }
}
