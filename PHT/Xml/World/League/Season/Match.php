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

namespace PHT\Xml\World\League\Season;

use PHT\Xml;
use PHT\Wrapper;
use PHT\Utils;
use PHT\Config;

class Match extends Xml\Base
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
        return $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
    }

    /**
     * Return match round
     *
     * @return integer
     */
    public function getRound()
    {
        return $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
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
     * Return home team id
     *
     * @return integer
     */
    public function getHomeTeamId()
    {
        return $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
    }

    /**
     * Get home team details
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth
     */
    public function getHomeTeam()
    {
        if ($this->type == Config\Config::SENIOR) {
            return Wrapper\Team\Senior::team($this->getHomeTeamId());
        } elseif ($this->type == Config\Config::YOUTH) {
            return Wrapper\Team\Youth::team($this->getHomeTeamId());
        }
        return null;
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
     * Return away team id
     *
     * @return integer
     */
    public function getAwayTeamId()
    {
        return $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
    }

    /**
     * Get away team details
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth
     */
    public function getAwayTeam()
    {
        if ($this->type == Config\Config::SENIOR) {
            return Wrapper\Team\Senior::team($this->getAwayTeamId());
        } elseif ($this->type == Config\Config::YOUTH) {
            return Wrapper\Team\Youth::team($this->getAwayTeamId());
        }
        return null;
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
     * Return number of home goals
     *
     * @return integer
     */
    public function getHomeGoals()
    {
        $node = $this->getXml()->getElementsByTagName('HomeGoals');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return number of away goals
     *
     * @return integer
     */
    public function getAwayGoals()
    {
        $node = $this->getXml()->getElementsByTagName('AwayGoals');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Only for youth match, return match status : UPCOMING, ONGOING, FINISHED
     *
     * @return string
     */
    public function getStatus()
    {
        $node = $this->getXml()->getElementsByTagName('Status');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }
}
