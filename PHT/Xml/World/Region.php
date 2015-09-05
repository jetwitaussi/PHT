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

namespace PHT\Xml\World;

use PHT\Xml;
use PHT\Wrapper;

class Region extends Xml\File
{
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
     * Return country details
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country($this->getLeagueId());
    }

    /**
     * Return region id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
    }

    /**
     * Return region name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
    }

    /**
     * Return current weather id
     *
     * @return integer
     */
    public function getWeatherId()
    {
        return $this->getXml()->getElementsByTagName('WeatherID')->item(0)->nodeValue;
    }

    /**
     * Return tomorrow weather id
     *
     * @return integer
     */
    public function getTomorrowWeatherId()
    {
        return $this->getXml()->getElementsByTagName('TomorrowWeatherID')->item(0)->nodeValue;
    }

    /**
     * Return number of users
     *
     * @return integer
     */
    public function getUserNumber()
    {
        return $this->getXml()->getElementsByTagName('NumberOfUsers')->item(0)->nodeValue;
    }

    /**
     * Return number of online users
     *
     * @return integer
     */
    public function getOnlineUserNumber()
    {
        return $this->getXml()->getElementsByTagName('NumberOfOnline')->item(0)->nodeValue;
    }
}
