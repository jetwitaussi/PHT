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

namespace PHT\Xml\Match;

use PHT\Xml;
use PHT\Utils;
use PHT\Wrapper;

class National extends Xml\Base
{
    private $u;

    /**
     * @param \DOMDocument $xml
     * @param boolean $u20
     */
    public function __construct($xml, $u20)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->u = $u20;
    }

    /**
     * Return match id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
    }

    /**
     * Return match details
     *
     * @param boolean $events
     * @return \PHT\Xml\HTMatch
     */
    public function getMatch($events = true)
    {
        return Wrapper\HTMatch::senior($this->getId(), $events);
    }

    /**
     * Return match date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return match type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
    }

    /**
     * Return home team name
     *
     * @return string
     */
    public function getHomeTeamName()
    {
        return $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
    }

    /**
     * Return home team id
     *
     * @return string
     */
    public function getHomeTeamId()
    {
        return $this->getXml()->getElementsByTagName('HomeTeamId')->item(0)->nodeValue;
    }

    /**
     * Return home national team
     *
     * @return \PHT\Xml\Team\National
     */
    public function getHomeTeam()
    {
        return Wrapper\National::team($this->getHomeTeamId());
    }

    /**
     * Return away team name
     *
     * @return string
     */
    public function getAwayTeamName()
    {
        return $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
    }

    /**
     * Return away team id
     *
     * @return string
     */
    public function getAwayTeamId()
    {
        return $this->getXml()->getElementsByTagName('AwayTeamId')->item(0)->nodeValue;
    }

    /**
     * Return away national team
     *
     * @return \PHT\Xml\Team\National
     */
    public function getAwayTeam()
    {
        return Wrapper\National::team($this->getAwayTeamId());
    }

    /**
     * Return if match is finished
     *
     * @return boolean
     */
    public function isResultAvailable()
    {
        if ($this->getXml()->getElementsByTagName('HomeGoals')->length && $this->getXml()->getElementsByTagName('AwayGoals')->length) {
            return true;
        }
        return false;
    }

    /**
     * Return home goals number
     *
     * @return integer
     */
    public function getHomeGoals()
    {
        if ($this->isResultAvailable()) {
            return $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return away goals number
     *
     * @return integer
     */
    public function getAwayGoals()
    {
        if ($this->isResultAvailable()) {
            return $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
        }
        return null;
    }
}
