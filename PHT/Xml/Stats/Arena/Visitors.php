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

namespace PHT\Xml\Stats\Arena;

use PHT\Xml;

class Visitors extends Xml\Base
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
     * Return terraces seats number
     *
     * @return integer
     */
    public function getTerraces()
    {
        return $this->getXml()->getElementsByTagName('Terraces')->item(0)->nodeValue;
    }

    /**
     * Return basic seats number
     *
     * @return integer
     */
    public function getBasic()
    {
        return $this->getXml()->getElementsByTagName('Basic')->item(0)->nodeValue;
    }

    /**
     * Return roof seats number
     *
     * @return integer
     */
    public function getRoof()
    {
        return $this->getXml()->getElementsByTagName('Roof')->item(0)->nodeValue;
    }

    /**
     * Return vip seats number
     *
     * @return integer
     */
    public function getVip()
    {
        return $this->getXml()->getElementsByTagName('VIP')->item(0)->nodeValue;
    }

    /**
     * Return total seats number
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->getXml()->getElementsByTagName('Total')->item(0)->nodeValue;
    }
}
