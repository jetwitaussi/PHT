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

namespace PHT\Xml\Team\Economy;

use PHT\Xml;
use PHT\Utils;

class Income extends Xml\Base
{
    private $state;
    private $money;

    /**
     * New income object
     *
     * @param \DOMDocument $xml
     * @param integer $money
     * @param boolean $last
     */
    public function __construct($xml, $money = null, $last = false)
    {
        $this->state = $last ? 'Last' : '';
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->money = $money;
    }

    /**
     * Return spectators income
     *
     * @return integer
     */
    public function getSpectators()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'IncomeSpectators')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return sponsors income
     *
     * @return integer
     */
    public function getSponsors()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'IncomeSponsors')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return financial income
     *
     * @return integer
     */
    public function getFinancial()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'IncomeFinancial')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return sold players income
     *
     * @return integer
     */
    public function getSoldPlayers()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'IncomeSoldPlayers')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return sold players commission income
     *
     * @return integer
     */
    public function getSoldPlayersCommission()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'IncomeSoldPlayersCommission')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return temporary income
     *
     * @return integer
     */
    public function getTemporary()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'IncomeTemporary')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return total income
     *
     * @return integer
     */
    public function getTotal()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'IncomeSum')->item(0)->nodeValue, $this->money);
    }
}
