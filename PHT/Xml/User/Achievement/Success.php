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

namespace PHT\Xml\User\Achievement;

use PHT\Xml;
use PHT\Utils;

class Success extends Xml\Base
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
     * Return achievement type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('AchievementTypeID')->item(0)->nodeValue;
    }

    /**
     * Return achievement text
     *
     * @return string
     */
    public function getText()
    {
        return $this->getXml()->getElementsByTagName('AchievementText')->item(0)->nodeValue;
    }

    /**
     * Return achievement category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->getXml()->getElementsByTagName('CategoryID')->item(0)->nodeValue;
    }

    /**
     * Return achievement date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('EventDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return achievement points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->getXml()->getElementsByTagName('Points')->item(0)->nodeValue;
    }

    /**
     * Return if achievement is multi level
     *
     * @return boolean
     */
    public function isMultiLevel()
    {
        return strtolower($this->getXml()->getElementsByTagName('MultiLevel')->item(0)->nodeValue) == "true";
    }

    /**
     * Return achievement number of events
     *
     * @return integer
     */
    public function getNumberOfEvents()
    {
        return $this->getXml()->getElementsByTagName('NumberOfEvents')->item(0)->nodeValue;
    }

    /**
     * Return user's current rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->getXml()->getElementsByTagName('Rank')->item(0)->nodeValue;
    }
}
