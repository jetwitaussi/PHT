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

namespace PHT\Xml\Team\Arena;

use PHT\Xml;
use PHT\Utils;

class Capacity extends Xml\Base
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
     * Is last rebuild arena date available ?
     *
     * @return boolean
     */
    public function isLastRebuildDateAvailable()
    {
        $node = $this->getXml()->getElementsByTagName('RebuiltDate');
        if ($node !== null && $node->length) {
            return strtolower($this->getXml()->getElementsByTagName('RebuiltDate')->item(0)->getAttribute('Available')) == 'true';
        }
        return false;
    }

    /**
     * Return last rebuild arena date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getLastRebuildDate($format = null)
    {
        if ($this->isLastRebuildDateAvailable()) {
            return Utils\Date::convert($this->getXml()->getElementsByTagName('RebuiltDate')->item(0)->nodeValue, $format);
        }
        return null;
    }

    /**
     * Return arena expansion date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getExpansionDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('ExpansionDate')->item(0)->nodeValue, $format);
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
        $node = $this->getXml()->getElementsByTagName('Total');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        } else {
            return $this->getTerraces() + $this->getBasic() + $this->getRoof() + $this->getVip();
        }
    }
}
