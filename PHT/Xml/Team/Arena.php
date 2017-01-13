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
use PHT\Config;

class Arena extends Xml\File
{
    /**
     * Return senior team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return senior team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return senior team name
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return arena id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
    }

    /**
     * Return arena name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
    }

    /**
     * Return arena league id
     *
     * @return integer
     */
    public function getLeagueId()
    {
        return $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
    }

    /**
     * Return arena league name
     *
     * @return string
     */
    public function getLeagueName()
    {
        return $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
    }

    /**
     * Return arena region id
     *
     * @return integer
     */
    public function getRegionId()
    {
        return $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
    }

    /**
     * Return region detail
     *
     * @return \PHT\Xml\World\Region
     */
    public function getRegion()
    {
        return Wrapper\World::region($this->getRegionId());
    }

    /**
     * Return arena region name
     *
     * @return string
     */
    public function getRegionName()
    {
        return $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
    }

    /**
     * Return arena capacity object (current capacity)
     *
     * @return \PHT\Xml\Team\Arena\Capacity
     */
    public function getCurrentCapacity()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//CurrentCapacity');
        $capacity = new \DOMDocument('1.0', 'UTF-8');
        $capacity->appendChild($capacity->importNode($nodeList->item(0), true));
        return new Xml\Team\Arena\Capacity($capacity);
    }

    /**
     * Is future capacity available ?
     *
     * @return boolean
     */
    public function isFutureCapacityAvailable()
    {
        return strtolower($this->getXml()->getElementsByTagName('ExpandedCapacity')->item(0)->getAttribute('Available')) == 'true';
    }

    /**
     * Return arena capacity object (expanded capacity)
     *
     * @return \PHT\Xml\Team\Arena\Capacity
     */
    public function getExpandedCapacity()
    {
        if ($this->isFutureCapacityAvailable()) {
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//ExpandedCapacity');
            $capacity = new \DOMDocument('1.0', 'UTF-8');
            $capacity->appendChild($capacity->importNode($nodeList->item(0), true));
            return new Xml\Team\Arena\Capacity($capacity);
        }
        return null;
    }

    /**
     * Return future arena capacity
     *
     * @return integer
     */
    public function getFutureCapacity()
    {
        if ($this->isFutureCapacityAvailable()) {
            return $this->getCurrentCapacity()->getTotal() + $this->getExpandedCapacity()->getTotal();
        }
        return $this->getCurrentCapacity()->getTotal();
    }

    /**
     * Return arena stats object
     * /!\ Only valid for arena of connected user
     *
     * @param string $matchType (see \PHT\Config\Config STATS_ARENA_* constants)
     * @param string $startDate (format should be : yyyy-mm-dd)
     * @param string $endDate (format should be : yyyy-mm-dd)
     * @return \PHT\Xml\Stats\Arena\User
     */
    public function getStats($matchType = Config\Config::STATS_ARENA_ALL, $startDate = null, $endDate = null)
    {
        return Wrapper\Stats::arena($this->getId(), $matchType, $startDate, $endDate);
    }
}
