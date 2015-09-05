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
use PHT\Wrapper;

class Referee extends Xml\Base
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
     * Return referee id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('RefereeId')->item(0)->nodeValue;
    }

    /**
     * Return referee name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('RefereeName')->item(0)->nodeValue;
    }

    /**
     * Return referee country id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->getXml()->getElementsByTagName('RefereeCountryId')->item(0)->nodeValue;
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
     * Return referee country name
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->getXml()->getElementsByTagName('RefereeCountryName')->item(0)->nodeValue;
    }

    /**
     * Return referee team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('RefereeTeamId')->item(0)->nodeValue;
    }

    /**
     * Return senior team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return referee team name
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getXml()->getElementsByTagName('RefereeTeamname')->item(0)->nodeValue;
    }

    /**
     * Return hof player details
     *
     * @return \PHT\Xml\Player\Hof
     */
    public function getHofPlayer()
    {
        $hof = Wrapper\Team\Senior::hofplayers($this->getTeamId());
        foreach ($hof->getPlayers() as $player) {
            if ($player->getId() == $this->getId()) {
                return $player;
            }
        }
        return null;
    }
}
