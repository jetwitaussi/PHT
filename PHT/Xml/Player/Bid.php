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

namespace PHT\Xml\Player;

use PHT\Xml;
use PHT\Utils;
use PHT\Wrapper;

class Bid extends Xml\Base
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
     * Return type of bid
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('Type')->item(0)->nodeValue;
    }

    /**
     * Return transfer id
     *
     * @return integer
     */
    public function getTransferId()
    {
        return $this->getXml()->getElementsByTagName('TransferId')->item(0)->nodeValue;
    }

    /**
     * Ignore transfer
     */
    public function ignoreTransfer()
    {
        Wrapper\Player\Senior::ignoretransfer($this->getTransferId());
    }

    /**
     * Return player id
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->getXml()->getElementsByTagName('PlayerId')->item(0)->nodeValue;
    }

    /**
     * Return player
     *
     * @return \PHT\Xml\Player\Senior
     */
    public function getPlayer()
    {
        return Wrapper\Player\Senior::player($this->getPlayerId());
    }

    /**
     * Return player name
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->getXml()->getElementsByTagName('PlayerName')->item(0)->nodeValue;
    }

    /**
     * Return bid deadline
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDeadline($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('Deadline')->item(0)->nodeValue, $format);
    }

    /**
     * Return if a bid is placed
     *
     * @return boolean
     */
    public function hasBidPlaced()
    {
        return $this->getXml()->getElementsByTagName('Amount')->length;
    }

    /**
     * Return  bid amount
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getBidAmount($countryCurrency = null)
    {
        if ($this->hasBidPlaced()) {
            return Utils\Money::convert($this->getXml()->getElementsByTagName('Amount')->item(0)->nodeValue, $countryCurrency);
        }
        return 0;
    }

    /**
     * Return bid team id
     *
     * @return integer
     */
    public function getBidTeamId()
    {
        if ($this->hasBidPlaced()) {
            return $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return bid team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getBidTeam()
    {
        if ($this->hasBidPlaced()) {
            return Wrapper\Team\Senior::team($this->getBidTeamId());
        }
        return null;
    }

    /**
     * Return bid team name
     *
     * @return string
     */
    public function getBidTeamName()
    {
        if ($this->hasBidPlaced()) {
            return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
        }
        return null;
    }
}
