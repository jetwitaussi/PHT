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

namespace PHT\Xml\World\League;

use PHT\Xml;
use PHT\Wrapper;

class Level extends Xml\Base
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
     * Return group id
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevel')->item(0)->nodeValue;
    }

    /**
     * Return number of series in the league level
     *
     * @return integer
     */
    public function getSeniorLeagueNumber()
    {
        return $this->getXml()->getElementsByTagName('NrOfLeagueLevelUnits')->item(0)->nodeValue;
    }

    /**
     * Return array of league level unit id (senior league id)
     *
     * @return array
     */
    public function getSeniorLeagueIds()
    {
        return explode(',', $this->getXml()->getElementsByTagName('LeagueLevelUnitIdList')->item(0)->nodeValue);
    }

    /**
     * Return the league level unit id (senior league id) from the number of the serie
     *
     * @param integer $number
     * @return integer
     */
    public function getSeniorLeagueId($number)
    {
        $ids = $this->getSeniorLeagueIds();
        return $ids[--$number];
    }

    /**
     * Return senior league object from serie number
     *
     * @param integer $number
     * @return \PHT\Xml\World\League\Senior
     */
    public function getSeniorLeague($number)
    {
        return Wrapper\World\League::senior($this->getSeniorLeagueId($number));
    }

    /**
     * Return number of teams in the league level
     *
     * @return integer
     */
    public function getTeamsNumber()
    {
        return $this->getXml()->getElementsByTagName('NrOfTeams')->item(0)->nodeValue;
    }

    /**
     * Return number of shared promotion slots
     *
     * @return integer
     */
    public function getSharedPromotionSlotsNumber()
    {
        return $this->getXml()->getElementsByTagName('NrOfSharedPromotionSlotsPerSeries')->item(0)->nodeValue;
    }

    /**
     * Return number of direct promotion slots
     *
     * @return integer
     */
    public function getDirectPromotionSlotsNumber()
    {
        return $this->getXml()->getElementsByTagName('NrOfDirectPromotionSlotsPerSeries')->item(0)->nodeValue;
    }

    /**
     * Return number of qualification promotion slots
     *
     * @return integer
     */
    public function getQualificationPromotionSlotsNumber()
    {
        return $this->getXml()->getElementsByTagName('NrOfQualificationPromotionSlotsPerSeries')->item(0)->nodeValue;
    }

    /**
     * Return number of qualification demotion slots
     *
     * @return integer
     */
    public function getQualificationDemotionSlotsNumber()
    {
        return $this->getXml()->getElementsByTagName('NrOfQualificationDemotionSlotsPerSeries')->item(0)->nodeValue;
    }

    /**
     * Return number of direct deomotion slots
     *
     * @return integer
     */
    public function getDirectDemotionSlotsNumber()
    {
        return $this->getXml()->getElementsByTagName('NrOfDirectDemotionSlotsPerSeries')->item(0)->nodeValue;
    }
}
