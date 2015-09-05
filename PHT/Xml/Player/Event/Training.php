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

namespace PHT\Xml\Player\Event;

use PHT\Xml;
use PHT\Utils;

class Training extends Xml\Base
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
     * Return event date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('EventDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return skill id
     *
     * @return integer
     */
    public function getSkillId()
    {
        return $this->getXml()->getElementsByTagName('SkillID')->item(0)->nodeValue;
    }

    /**
     * Return old level
     *
     * @return integer
     */
    public function getOldLevel()
    {
        return $this->getXml()->getElementsByTagName('OldLevel')->item(0)->nodeValue;
    }

    /**
     * Return new level
     *
     * @return integer
     */
    public function getNewLevel()
    {
        return $this->getXml()->getElementsByTagName('NewLevel')->item(0)->nodeValue;
    }

    /**
     * Return season number
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
    }

    /**
     * Return week number
     *
     * @return integer
     */
    public function getWeek()
    {
        return $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
    }

    /**
     * Return day number
     *
     * @return integer
     */
    public function getDay()
    {
        return $this->getXml()->getElementsByTagName('DayNumber')->item(0)->nodeValue;
    }
}
