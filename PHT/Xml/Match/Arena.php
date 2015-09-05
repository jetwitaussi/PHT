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

class Arena extends Xml\Base
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
     * Return arena id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
    }

    /**
     * Return arena details
     *
     * @return \PHT\Xml\Team\Arena
     */
    public function getArena()
    {
        if ($this->type == Config\Config::MATCH_SENIOR || $this->type == Config\Config::MATCH_NATIONAL) {
            return Wrapper\Team\Senior::arena($this->getId());
        }
        return null;
    }

    /**
     * Return arena name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
    }

    /**
     * Return weather id
     *
     * @return integer
     */
    public function getWeatherId()
    {
        return $this->getXml()->getElementsByTagName('WeatherID')->item(0)->nodeValue;
    }

    /**
     * Return spectators number
     *
     * @return integer
     */
    public function getSpectators()
    {
        return $this->getXml()->getElementsByTagName('SoldTotal')->item(0)->nodeValue;
    }

    /**
     * Return number of sold terraces
     *
     * @return integer
     */
    public function getSoldTerraces()
    {
        $node = $this->getXml()->getElementsByTagName('SoldTerraces');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return number of sold terraces
     *
     * @return integer
     */
    public function getSoldBasic()
    {
        $node = $this->getXml()->getElementsByTagName('SoldBasic');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return number of sold roof
     *
     * @return integer
     */
    public function getSoldRoof()
    {
        $node = $this->getXml()->getElementsByTagName('SoldRoof');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return number of sold VIP
     *
     * @return integer
     */
    public function getSoldVip()
    {
        $node = $this->getXml()->getElementsByTagName('SoldVIP');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }
}
