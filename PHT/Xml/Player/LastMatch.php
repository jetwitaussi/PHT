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
use PHT\Config;
use PHT\Wrapper;

class LastMatch extends Xml\Base
{
    private $type;

    /**
     * @param \DOMDocument $xml
     * @param string $type
     */
    public function __construct($xml, $type)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->type = $type;
    }

    /**
     * Return match id
     *
     * @return integer
     */
    public function getId()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return $this->getXml()->getElementsByTagName('YouthMatchID')->item(0)->nodeValue;
        }
        return $this->getXml()->getElementsByTagName('MatchId')->item(0)->nodeValue;
    }

    /**
     * Return match
     *
     * @param boolean $events
     * @return \PHT\Xml\Match
     */
    public function getMatch($events = true)
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Match::youth($this->getId(), $events);
        }
        return Wrapper\Match::senior($this->getId(), $events);
    }

    /**
     * Return position code
     *
     * @return integer
     */
    public function getPositionCode()
    {
        return $this->getXml()->getElementsByTagName('PositionCode')->item(0)->nodeValue;
    }

    /**
     * Return match date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('Date')->item(0)->nodeValue, $format);
    }

    /**
     * Return rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->getXml()->getElementsByTagName('Rating')->item(0)->nodeValue;
    }

    /**
     * Return number of played minutes
     *
     * @return integer
     */
    public function getPlayedMinutes()
    {
        return $this->getXml()->getElementsByTagName('PlayedMinutes')->item(0)->nodeValue;
    }

    /**
     * Return rating at end of game
     *
     * @return integer
     */
    public function getRatingEndOfGame()
    {
        if ($this->getXml()->getElementsByTagName('RatingEndOfGame')->length) {
            return $this->getXml()->getElementsByTagName('RatingEndOfGame')->item(0)->nodeValue;
        }
        return null;
    }
}
