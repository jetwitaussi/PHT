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

namespace PHT\Xml\Stats\Arena;

use PHT\Xml;
use PHT\Wrapper;

class Arena extends Xml\Base
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
     * Return arena id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
    }

    /**
     * Return arena details
     *
     * @return \PHT\Xml\Team\Arena
     */
    public function getArena()
    {
        return Wrapper\Team\Senior::arena($this->getId());
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
     * Return arena size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->getXml()->getElementsByTagName('ArenaSize')->item(0)->nodeValue;
    }

    /**
     * Return arena region id
     *
     * @return integer
     */
    public function getRegionId()
    {
        return $this->getXml()->getElementsByTagName('ArenaRegionId')->item(0)->nodeValue;
    }

    /**
     * Return arena region
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
        return $this->getXml()->getElementsByTagName('ArenaRegionName')->item(0)->nodeValue;
    }

    /**
     * Return arena league id
     *
     * @return integer
     */
    public function getLeagueId()
    {
        return $this->getXml()->getElementsByTagName('ArenaLeagueID')->item(0)->nodeValue;
    }

    /**
     * Return arena country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country($this->getLeagueId());
    }

    /**
     * Return arena league name
     *
     * @return string
     */
    public function getLeagueName()
    {
        return $this->getXml()->getElementsByTagName('ArenaLeagueName')->item(0)->nodeValue;
    }
}
