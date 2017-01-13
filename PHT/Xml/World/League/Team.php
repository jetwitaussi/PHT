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

namespace PHT\Xml\World\League;

use PHT\Xml;
use PHT\Network;
use PHT\Config;
use PHT\Wrapper;

class Team extends Xml\Base
{
    public $type = '';

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
     * Return team id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return user id
     *
     * @return integer
     */
    public function getUserId()
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
        return Wrapper\User::user($this->getUserId());
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
     * Return position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->getXml()->getElementsByTagName('Position')->item(0)->nodeValue;
    }

    /**
     * Return if position change is available
     *
     * @return boolean
     */
    public function isPositionChangeAvailable()
    {
        return $this->getXml()->getElementsByTagName('PositionChange')->length > 0;
    }

    /**
     * Return position change
     *
     * @return integer
     */
    public function getPositionChange()
    {
        if ($this->isPositionChangeAvailable()) {
            return $this->getXml()->getElementsByTagName('PositionChange')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return number of played matches
     *
     * @return integer
     */
    public function getNumberOfPlayedMatches()
    {
        return $this->getXml()->getElementsByTagName('Matches')->item(0)->nodeValue;
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
     * Return total points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->getXml()->getElementsByTagName('Points')->item(0)->nodeValue;
    }

    /**
     * Return goal average
     *
     * @return integer
     */
    public function getGoalAverage()
    {
        return $this->getGoalsFor() - $this->getGoalsAgainst();
    }

    /**
     * Return total won matches
     *
     * @return integer
     */
    public function getWonNumber()
    {
        return $this->getXml()->getElementsByTagName('Won')->item(0)->nodeValue;
    }

    /**
     * Return total draws matches
     *
     * @return integer
     */
    public function getDrawsNumber()
    {
        return $this->getXml()->getElementsByTagName('Draws')->item(0)->nodeValue;
    }

    /**
     * Return total lost matches
     *
     * @return integer
     */
    public function getLostNumber()
    {
        return $this->getXml()->getElementsByTagName('Lost')->item(0)->nodeValue;
    }

    /**
     * Return full team data
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth
     */
    public function getTeam()
    {
        if ($this->type == Config\Config::SENIOR) {
            $url = Network\Request::buildUrl(array('file' => 'teamdetails', 'teamID' => $this->getId(), 'version' => Config\Version::TEAMDETAILS));
            return new Xml\Team\Senior(Network\Request::fetchUrl($url), $this->getId());
        } elseif ($this->type == Config\Config::YOUTH) {
            $url = Network\Request::buildUrl(array('file' => 'youthteamdetails', 'youthTeamId' => $this->getId(), 'version' => Config\Version::YOUTHTEAMDETAILS));
            return new Xml\Team\Youth(Network\Request::fetchUrl($url));
        }
        return null;
    }
}
