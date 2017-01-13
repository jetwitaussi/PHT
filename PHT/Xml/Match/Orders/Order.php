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

namespace PHT\Xml\Match\Orders;

use PHT\Xml;
use PHT\Wrapper;
use PHT\Config;

class Order extends Xml\Base
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
     * Return player order id
     *
     * @return integer
     */
    public function getOrderId()
    {
        return $this->getXml()->getElementsByTagName('PlayerOrderID')->item(0)->nodeValue;
    }

    /**
     * Return player order id
     *
     * @return integer
     */
    public function getOrderType()
    {
        return $this->getXml()->getElementsByTagName('OrderType')->item(0)->nodeValue;
    }

    /**
     * Return minute criteria
     *
     * @return integer
     */
    public function getMinuteCriteria()
    {
        return $this->getXml()->getElementsByTagName('MatchMinuteCriteria')->item(0)->nodeValue;
    }

    /**
     * Return goal difference criteria
     *
     * @return integer
     */
    public function getGoalDifferenceCriteria()
    {
        return $this->getXml()->getElementsByTagName('GoalDiffCriteria')->item(0)->nodeValue;
    }

    /**
     * Return red card criteria
     *
     * @return integer
     */
    public function getRedCardCriteria()
    {
        return $this->getXml()->getElementsByTagName('RedCardCriteria')->item(0)->nodeValue;
    }

    /**
     * Return player id out
     *
     * @return integer
     */
    public function getPlayerOutId()
    {
        return $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player out
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
     * Return player id in
     *
     * @return integer
     */
    public function getPlayerInId()
    {
        return $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player in
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
     * Return new player behaviour
     *
     * @return integer
     */
    public function getNewBehaviour()
    {
        return $this->getXml()->getElementsByTagName('NewPositionBehaviour')->item(0)->nodeValue;
    }

    /**
     * Return new player position
     *
     * @return integer
     */
    public function getNewPosition()
    {
        return $this->getXml()->getElementsByTagName('NewPositionId')->item(0)->nodeValue;
    }
}
