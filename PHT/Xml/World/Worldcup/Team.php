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

namespace PHT\Xml\World\Worldcup;

use PHT\Xml;
use PHT\Wrapper;

class Team extends Xml\Base
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
     * Return team id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return team details
     *
     * @return \PHT\Xml\Team\National
     */
    public function getTeam()
    {
        return Wrapper\National::team($this->getId());
    }

    /**
     * Return team name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return team place
     *
     * @return integer
     */
    public function getPlace()
    {
        return $this->getXml()->getElementsByTagName('Place')->item(0)->nodeValue;
    }

    /**
     * Return group id
     *
     * @return integer
     */
    public function getGroupId()
    {
        return $this->getXml()->getElementsByTagName('CupSeriesUnitID')->item(0)->nodeValue;
    }

    /**
     * Return group name
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->getXml()->getElementsByTagName('CupSeriesUnitName')->item(0)->nodeValue;
    }

    /**
     * Return number of played matches
     *
     * @return integer
     */
    public function getPlayedMatchesNumber()
    {
        return $this->getXml()->getElementsByTagName('MatchesPlayed')->item(0)->nodeValue;
    }

    /**
     * Return number of goal for team
     *
     * @return integer
     */
    public function getGoalsFor()
    {
        return $this->getXml()->getElementsByTagName('GoalsFor')->item(0)->nodeValue;
    }

    /**
     * Return number of goal against team
     *
     * @return integer
     */
    public function getGoalsAgainst()
    {
        return $this->getXml()->getElementsByTagName('GoalsAgainst')->item(0)->nodeValue;
    }

    /**
     * Return points number
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->getXml()->getElementsByTagName('Points')->item(0)->nodeValue;
    }
}
