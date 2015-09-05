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

namespace PHT\Xml\User;

use PHT\Xml;
use PHT\Wrapper;

class Supporter extends Xml\Base
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
     * Return user id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('UserId')->item(0)->nodeValue;
    }

    /**
     * Return user
     *
     * @return \PHT\Xml\User
     */
    public function getUser()
    {
        return Wrapper\User::user($this->getId());
    }

    /**
     * Return user name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('LoginName')->item(0)->nodeValue;
    }

    /**
     * Return team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
    }

    /**
     * Return team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return team name
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

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
     * Return country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country($this->getLeagueId());
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
     * Return senior league id
     *
     * @return integer
     */
    public function getSeniorLeagueId()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevelUnitID')->item(0)->nodeValue;
    }

    /**
     * Return senior league
     *
     * @return \PHT\Xml\World\League\Senior
     */
    public function getSeniorLeague()
    {
        return Wrapper\World\League::senior($this->getSeniorLeagueId());
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
}
