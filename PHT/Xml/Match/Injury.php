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

class Injury extends Xml\Base
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
     * Return player id
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->getXml()->getElementsByTagName('InjuryPlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getPlayer()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Player\Youth::player($this->getPlayerId());
        }
        return Wrapper\Player\Senior::player($this->getPlayerId());
    }

    /**
     * Return player name
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->getXml()->getElementsByTagName('InjuryPlayerName')->item(0)->nodeValue;
    }

    /**
     * Return player id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('InjuryTeamID')->item(0)->nodeValue;
    }

    /**
     * Return team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth|\PHT\Xml\Team\National
     */
    public function getTeam()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Team\Youth::team($this->getTeamId());
        } elseif ($this->type == Config\Config::MATCH_NATIONAL) {
            return Wrapper\National::team($this->getTeamId());
        }
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return injury type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('InjuryType')->item(0)->nodeValue;
    }

    /**
     * Return injury minute
     *
     * @return integer
     */
    public function getMinute()
    {
        return $this->getXml()->getElementsByTagName('InjuryMinute')->item(0)->nodeValue;
    }

    /**
     * Return match part when injury happened
     *
     * @return integer
     */
    public function getMatchPart()
    {
        return $this->getXml()->getElementsByTagName('MatchPart')->item(0)->nodeValue;
    }
}
