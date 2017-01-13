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
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;
use PHT\Network;

class Challenge extends Xml\Base
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
     * Return challenge id
     *
     * @return integer
     */
    public function getChallengeId()
    {
        return $this->getXml()->getElementsByTagName('TrainingMatchID')->item(0)->nodeValue;
    }

    /**
     * Return match id
     *
     * @return integer
     */
    public function getId()
    {
        if ($this->getXml()->getElementsByTagName('MatchId')->length) {
            return $this->getXml()->getElementsByTagName('MatchId')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return match details
     *
     * @param boolean $events
     * @return \PHT\Xml\Match
     */
    public function getMatch($events = true)
    {
        if ($this->getId()) {
            return Wrapper\Match::senior($this->getId(), $events);
        }
        return null;
    }

    /**
     * Return match date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('MatchTime')->item(0)->nodeValue, $format);
    }

    /**
     * Return match type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('FriendlyType')->item(0)->nodeValue;
    }

    /**
     * Return opponent team id
     *
     * @return integer
     */
    public function getOpponentTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return opponent team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\National
     */
    public function getOpponentTeam()
    {
        if ($this->getType() == Config\Config::CHALLENGE_NATIONAL) {
            return Wrapper\National::team($this->getOpponentTeamId());
        }
        return Wrapper\Team\Senior::team($this->getOpponentTeamId());
    }

    /**
     * Return opponent team name
     *
     * @return integer
     */
    public function getOpponentTeamName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return arena id
     *
     * @return integer
     */
    public function getArenaId()
    {
        return $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
    }

    /**
     * Return arena
     *
     * @return \PHT\Xml\Team\Arena
     */
    public function getArena()
    {
        return Wrapper\Team\Senior::arena($this->getArenaId());
    }

    /**
     * Return arena name
     *
     * @return string
     */
    public function getArenaName()
    {
        return $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
    }

    /**
     * Return counry id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->getXml()->getElementsByTagName('CountryID')->item(0)->nodeValue;
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
     * Return country name
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->getXml()->getElementsByTagName('CountryName')->item(0)->nodeValue;
    }

    /**
     * Is this challenge accepted ?
     *
     * @return boolean
     */
    public function isAccepted()
    {
        return strtolower($this->getXml()->getElementsByTagName('IsAgreed')->item(0)->nodeValue) == "true";
    }

    /**
     * Accept challenge
     */
    public function accept()
    {
        $url = Network\Request::buildUrl(array('file' => 'challenges', 'version' => Config\Version::CHALLENGES, 'actionType' => 'accept', 'trainingMatchId' => $this->getChallengeId()));
        Network\Request::fetchUrl($url);
    }

    /**
     * Decline challenge
     */
    public function decline()
    {
        $url = Network\Request::buildUrl(array('file' => 'challenges', 'version' => Config\Version::CHALLENGES, 'actionType' => 'decline', 'trainingMatchId' => $this->getChallengeId()));
        Network\Request::fetchUrl($url);
    }

    /**
     * Withdraw challenge
     */
    public function withdraw()
    {
        $url = Network\Request::buildUrl(array('file' => 'challenges', 'version' => Config\Version::CHALLENGES, 'actionType' => 'withdraw', 'trainingMatchId' => $this->getChallengeId()));
        Network\Request::fetchUrl($url);
    }
}
