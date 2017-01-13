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

namespace PHT\Xml\Team\National;

use PHT\Xml;
use PHT\Wrapper;

class Chunk extends Xml\Base
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
     * Return national team id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('NationalTeamID')->item(0)->nodeValue;
    }

    /**
     * Get national team details
     *
     * @return \PHT\Xml\Team\National
     */
    public function getTeam()
    {
        return Wrapper\National::team($this->getId());
    }

    /**
     * Return national team name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('NationalTeamName')->item(0)->nodeValue;
    }

    /**
     * Return national team score rating
     *
     * @return integer
     */
    public function getRatingScore()
    {
        if ($this->getXml()->getElementsByTagName('RatingScore')->length) {
            return $this->getXml()->getElementsByTagName('RatingScore')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return league id
     *
     * @return integer
     */
    public function getLeagueId()
    {
        if ($this->getXml()->getElementsByTagName('LeagueId')->length) {
            return $this->getXml()->getElementsByTagName('LeagueId')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Get country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        if ($this->getLeagueId()) {
            return Wrapper\World::country($this->getLeagueId());
        }
        return null;
    }

    /**
     * Return if team is still in current worldcup
     *
     * @return boolean
     */
    public function stillInWorldcup()
    {
        if ($this->getXml()->getElementsByTagName('StillInCup')->length) {
            return strtolower($this->getXml()->getElementsByTagName('StillInCup')->item(0)->nodeValue) == 'true';
        }
        return null;
    }

    /**
     * Get team players
     *
     * @return \PHT\Xml\National\Players
     */
    public function getPlayers()
    {
        return Wrapper\National::players($this->getId());
    }

    /**
     * Get array of team matches
     *
     * @return \PHT\Xml\Match\National[]
     */
    public function getMatches()
    {
        $u20 = strpos($this->getName(), 'U-20') !== false;
        $matches = Wrapper\National::matches($u20);
        $ok = array();
        foreach ($matches->getMatches() as $match) {
            if ($match->getHomeTeamName() == $this->getName() || $match->getAwayTeamName() == $this->getName()) {
                $ok[] = $match;
            }
        }
        return $ok;
    }
}
