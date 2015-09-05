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

namespace PHT\Xml;

use PHT\Utils;
use PHT\Wrapper;

class Transfer extends Base
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
     * Return transfer id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('TransferID')->item(0)->nodeValue;
    }

    /**
     * Return deadline date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDeadline($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('Deadline')->item(0)->nodeValue, $format);
    }

    /**
     * Return transfer type : S for sale, B for buy
     *
     * @return string
     */
    public function getType()
    {
        $node = $this->getXml()->getElementsByTagName('TransferType');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return if it's a sale transfer
     *
     * @return boolean
     */
    public function isSale()
    {
        if ($this->getXml()->getElementsByTagName('TransferType')->length) {
            return strtolower($this->getXml()->getElementsByTagName('TransferType')->item(0)->nodeValue) == 's';
        }
        return null;
    }

    /**
     * Return if it's a buy transfer
     *
     * @return boolean
     */
    public function isBuy()
    {
        if ($this->getXml()->getElementsByTagName('TransferType')->length) {
            return strtolower($this->getXml()->getElementsByTagName('TransferType')->item(0)->nodeValue) == 'b';
        }
        return null;
    }

    /**
     * Return player id
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player
     *
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Player\Senior
     */
    public function getPlayer($includeMatchInfo = true)
    {
        return Wrapper\Player\Senior::player($this->getPlayerId(), $includeMatchInfo);
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
     * Return buyer team id
     *
     * @return integer
     */
    public function getBuyerTeamId()
    {
        return $this->getXml()->getElementsByTagName('BuyerTeamID')->item(0)->nodeValue;
    }

    /**
     * Return buyer team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getBuyerTeam()
    {
        return Wrapper\Team\Senior::team($this->getBuyerTeamId());
    }

    /**
     * Return buyer team name
     *
     * @return string
     */
    public function getBuyerTeamName()
    {
        return $this->getXml()->getElementsByTagName('BuyerTeamName')->item(0)->nodeValue;
    }

    /**
     * Return seller team id
     *
     * @return integer
     */
    public function getSellerTeamId()
    {
        return $this->getXml()->getElementsByTagName('SellerTeamID')->item(0)->nodeValue;
    }

    /**
     * Return seller team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getSellerTeam()
    {
        return Wrapper\Team\Senior::team($this->getSellerTeamId());
    }

    /**
     * Return seller team name
     *
     * @return string
     */
    public function getSellerTeamName()
    {
        return $this->getXml()->getElementsByTagName('SellerTeamName')->item(0)->nodeValue;
    }

    /**
     * Return transfer price
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getPrice($countryCurrency = null)
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('Price')->item(0)->nodeValue, $countryCurrency);
    }

    /**
     * Return player tsi at transfer date
     *
     * @return integer
     */
    public function getTsi()
    {
        return $this->getXml()->getElementsByTagName('TSI')->item(0)->nodeValue;
    }
}
