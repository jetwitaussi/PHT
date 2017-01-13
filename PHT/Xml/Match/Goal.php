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
use PHT\Wrapper;

class Goal extends Xml\Base
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
     * Return scorer player id
     *
     * @return integer
     */
    public function getScorerId()
    {
        return $this->getXml()->getElementsByTagName('ScorerPlayerID')->item(0)->nodeValue;
    }

    /**
     * Return scorer player name
     *
     * @return string
     */
    public function getScorerName()
    {
        return $this->getXml()->getElementsByTagName('ScorerPlayerName')->item(0)->nodeValue;
    }

    /**
     * Return player
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getScorer()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Player\Youth::player($this->getScorerId());
        }
        return Wrapper\Player\Senior::player($this->getScorerId());
    }

    /**
     * Return scorer team id
     *
     * @return integer
     */
    public function getScorerTeamId()
    {
        return $this->getXml()->getElementsByTagName('ScorerTeamID')->item(0)->nodeValue;
    }

    /**
     * Return scorer team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth|\PHT\Xml\Team\National
     */
    public function getScorerTeam()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Team\Youth::team($this->getScorerTeamId());
        } elseif ($this->type == Config\Config::MATCH_NATIONAL) {
            return Wrapper\Team\Senior::team($this->getScorerTeamId());
        }
        return Wrapper\Team\Senior::team($this->getScorerTeamId());
    }

    /**
     * Return home team score
     *
     * @return integer
     */
    public function getHomeTeamScore()
    {
        return $this->getXml()->getElementsByTagName('ScorerHomeGoals')->item(0)->nodeValue;
    }

    /**
     * Return away team score
     *
     * @return integer
     */
    public function getAwayTeamScore()
    {
        return $this->getXml()->getElementsByTagName('ScorerAwayGoals')->item(0)->nodeValue;
    }

    /**
     * Return goal minute
     *
     * @return integer
     */
    public function getMinute()
    {
        return $this->getXml()->getElementsByTagName('ScorerMinute')->item(0)->nodeValue;
    }

    /**
     * Return match part when goal happened
     *
     * @return integer
     */
    public function getMatchPart()
    {
        return $this->getXml()->getElementsByTagName('MatchPart')->item(0)->nodeValue;
    }
}
