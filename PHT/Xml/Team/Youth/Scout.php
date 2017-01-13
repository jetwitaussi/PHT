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

namespace PHT\Xml\Team\Youth;

use PHT\Xml;
use PHT\Wrapper;
use PHT\Utils;

class Scout extends Xml\Base
{
    private $teamId;

    /**
     * @param \DOMDocument $xml
     * @param integer $teamId
     */
    public function __construct($xml, $teamId)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->teamId = $teamId;
    }

    /**
     * Return scout id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('YouthScoutID')->item(0)->nodeValue;
    }

    /**
     * Return scout name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('ScoutName')->item(0)->nodeValue;
    }

    /**
     * Return scout age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
    }

    /**
     * Return country id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->getXml()->getElementsByTagName('CountryID')->item(0)->nodeValue;
    }

    /**
     * Return country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country(null, $this->getCountryId());
    }

    /**
     * Return country name
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->getXml()->getElementsByTagName('CountryName')->item(0)->nodeValue;
    }

    /**
     * Return region id
     *
     * @return integer
     */
    public function getRegionId()
    {
        return $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
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
     * Return in country id
     *
     * @return integer
     */
    public function getInCountryId()
    {
        return $this->getXml()->getElementsByTagName('CountryID')->item(1)->nodeValue;
    }

    /**
     * Return in country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getInCountry()
    {
        return Wrapper\World::country(null, $this->getInCountryId());
    }

    /**
     * Return in country name
     *
     * @return string
     */
    public function getInCountryName()
    {
        return $this->getXml()->getElementsByTagName('CountryName')->item(1)->nodeValue;
    }

    /**
     * Return in region id
     *
     * @return integer
     */
    public function getInRegionId()
    {
        return $this->getXml()->getElementsByTagName('RegionID')->item(1)->nodeValue;
    }

    /**
     * Return in region
     *
     * @return \PHT\Xml\World\Region
     */
    public function getInRegion()
    {
        return Wrapper\World::region($this->getInRegionId());
    }

    /**
     * Return in region name
     *
     * @return string
     */
    public function getInRegionName()
    {
        return $this->getXml()->getElementsByTagName('RegionName')->item(1)->nodeValue;
    }

    /**
     * Return hired date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getHiredDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('HiredDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return last call date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getLastCallDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('LastCalled')->item(0)->nodeValue, $format);
    }

    /**
     * Return if scout is in travel
     *
     * @return boolean
     */
    public function inTravel()
    {
        return $this->getXml()->getElementsByTagName('Travel')->item(0)->hasChildNodes();
    }

    /**
     * Return travel start date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getTravelStartDate($format = null)
    {
        if ($this->inTravel()) {
            return Utils\Date::convert($this->getXml()->getElementsByTagName('TravelStartDate')->item(0)->nodeValue, $format);
        }
        return null;
    }

    /**
     * Return travel length
     *
     * @return integer
     */
    public function getTravelLength()
    {
        return $this->getXml()->getElementsByTagName('TravelLength')->item(0)->nodeValue;
    }

    /**
     * Return travel type
     *
     * @return integer
     */
    public function getTravelType()
    {
        return $this->getXml()->getElementsByTagName('TravelType')->item(0)->nodeValue;
    }

    /**
     * Return player type search
     *
     * @return integer
     */
    public function getPlayerTypeSearch()
    {
        return $this->getXml()->getElementsByTagName('PlayerTypeSearch')->item(0)->nodeValue;
    }

    /**
     * Return hof player id
     *
     * @return integer
     */
    public function getHofPlayerId()
    {
        $id = $this->getXml()->getElementsByTagName('HofPlayerId')->item(0)->nodeValue;
        if ($id > 0) {
            return $id;
        }
        return null;
    }

    /**
     * Return hof player if any
     *
     * @return \PHT\Xml\Player\Hof
     */
    public function getHofPlayer()
    {
        if ($this->getHofPlayerId()) {
            $hof = Wrapper\Team\Senior::hofplayers($this->teamId);
            foreach ($hof->getPlayers() as $player) {
                if ($player->getId() == $this->getHofPlayerId()) {
                    return $player;
                }
            }
        }
        return null;
    }
}
