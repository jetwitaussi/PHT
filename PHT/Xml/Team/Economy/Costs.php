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

class Costs extends Xml\Base
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
     * Return arena cost
     *
     * @return integer
     */
    public function getArena()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'CostsArena')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return players salaries cost
     *
     * @return integer
     */
    public function getSalaries()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'CostsPlayers')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return financial cost
     *
     * @return integer
     */
    public function getFinancial()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'CostsFinancial')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return bought players cost
     *
     * @return integer
     */
    public function getBoughtPlayers()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'CostsBoughtPlayers')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return arena building cost
     *
     * @return integer
     */
    public function getArenaBuilding()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'CostsArenaBuilding')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return temporary cost
     *
     * @return integer
     */
    public function getTemporary()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'CostsTemporary')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return staff cost
     *
     * @return integer
     */
    public function getStaff()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'CostsStaff')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return youth cost
     *
     * @return integer
     */
    public function getYouth()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'CostsYouth')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return total cost
     *
     * @return integer
     */
    public function getTotal()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName($this->state . 'CostsSum')->item(0)->nodeValue, $this->money);
    }
}
