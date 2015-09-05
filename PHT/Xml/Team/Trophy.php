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
use PHT\Utils;

class Trophy extends Xml\Base
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
     * Return type id
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('TrophyTypeId')->item(0)->nodeValue;
    }

    /**
     * Return season number
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->getXml()->getElementsByTagName('TrophySeason')->item(0)->nodeValue;
    }

    /**
     * Return senior league level
     *
     * @return integer
     */
    public function getSeniorLeagueLevel()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevel')->item(0)->nodeValue;
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
     * Return gained date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('GainedDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return image url
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->getXml()->getElementsByTagName('ImageUrl')->item(0)->nodeValue;
    }
}
