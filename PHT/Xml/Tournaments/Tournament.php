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

namespace PHT\Xml\Tournaments;

use PHT\Xml;
use PHT\Utils;
use PHT\Wrapper;

class Tournament extends Xml\File
{
    /**
     * Create an instance
     *
     * @param string|\DOMDocument $xml
     */
    public function __construct($xml)
    {
        if ($xml instanceof \DOMDocument) {
            parent::__construct($xml->saveXML());
        } else {
            parent::__construct($xml);
        }
    }

    /**
     * Return tournament id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('TournamentId')->item(0)->nodeValue;
    }

    /**
     * Return tournament name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('Name')->item(0)->nodeValue;
    }

    /**
     * Return tournament type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('TournamentType')->item(0)->nodeValue;
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
     * Return logo url
     *
     * @return string
     */
    public function getLogoUrl()
    {
        return $this->getXml()->getElementsByTagName('LogoUrl')->item(0)->nodeValue;
    }

    /**
     * Return trophy
     *
     * @return integer
     */
    public function getTrophy()
    {
        return $this->getXml()->getElementsByTagName('TrophyType')->item(0)->nodeValue;
    }

    /**
     * Return number of teams
     *
     * @return integer
     */
    public function getTeamNumber()
    {
        return $this->getXml()->getElementsByTagName('NumberOfTeams')->item(0)->nodeValue;
    }

    /**
     * Return number of groups
     *
     * @return integer
     */
    public function getGroupNumber()
    {
        return $this->getXml()->getElementsByTagName('NumberOfGroups')->item(0)->nodeValue;
    }

    /**
     * Return last finished round number
     *
     * @return integer
     */
    public function getLastRound()
    {
        return $this->getXml()->getElementsByTagName('LastMatchRound')->item(0)->nodeValue;
    }

    /**
     * Return first match round date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getFirstRoundDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('FirstMatchRoundDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return next match round date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getNextRoundDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('NextMatchRoundDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return if matches are ongoing
     *
     * @return boolean
     */
    public function isMatchOnGoing()
    {
        return (bool)$this->getXml()->getElementsByTagName('IsMatchesOngoing')->item(0)->nodeValue;
    }

    /**
     * Return creator id
     *
     * @return integer
     */
    public function getCreatorId()
    {
        return $this->getXml()->getElementsByTagName('UserId')->item(0)->nodeValue;
    }

    /**
     * Return creator
     *
     * @return \PHT\Xml\User
     */
    public function getCreator()
    {
        return Wrapper\User::user($this->getCreatorId());
    }

    /**
     * Return creator name
     *
     * @return integer
     */
    public function getCreatorName()
    {
        return $this->getXml()->getElementsByTagName('Loginname')->item(0)->nodeValue;
    }

    /**
     * Return tournament league
     *
     * @return \PHT\Xml\Tournaments\League
     */
    public function getLeague()
    {
        return Wrapper\Tournament::league($this->getId());
    }

    /**
     * Return tournament matches
     *
     * @return \PHT\Xml\Tournaments\Matches
     */
    public function getMatches()
    {
        return Wrapper\Tournament::matches($this->getId());
    }
}
