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

namespace PHT\Xml\Match\Lineup;

use PHT\Xml;
use PHT\Config;
use PHT\Wrapper;

class Substitution extends Xml\Base
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
     * Return team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
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
     * Return player id, leaving player or player changing behaviour or player swap
     *
     * @return integer
     */
    public function getPlayerOutId()
    {
        return $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player, leaving player or player changing behaviour or player swap
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getPlayerOut()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Player\Youth::player($this->getPlayerOutId());
        }
        return Wrapper\Player\Senior::player($this->getPlayerOutId());
    }

    /**
     * Return player id, entering player or player changing behaviour or player swap
     *
     * @return integer
     */
    public function getPlayerInId()
    {
        return $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player, entering player or player changing behaviour or player swap
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getPlayerIn()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Player\Youth::player($this->getPlayerInId());
        }
        return Wrapper\Player\Senior::player($this->getPlayerInId());
    }

    /**
     * Return order type
     *
     * @return integer
     */
    public function getOrderType()
    {
        return $this->getXml()->getElementsByTagName('OrderType')->item(0)->nodeValue;
    }

    /**
     * Return new position id
     *
     * @return integer
     */
    public function getNewPositionId()
    {
        return $this->getXml()->getElementsByTagName('NewPositionId')->item(0)->nodeValue;
    }

    /**
     * Return new position behaviour
     *
     * @return integer
     */
    public function getNewPositionBehaviour()
    {
        return $this->getXml()->getElementsByTagName('NewPositionBehaviour')->item(0)->nodeValue;
    }

    /**
     * Return minute of substitution
     *
     * @return integer
     */
    public function getMinute()
    {
        return $this->getXml()->getElementsByTagName('MatchMinute')->item(0)->nodeValue;
    }

    /**
     * Return match part when substitution happened
     *
     * @return integer
     */
    public function getMatchPart()
    {
        return $this->getXml()->getElementsByTagName('MatchPart')->item(0)->nodeValue;
    }
}
