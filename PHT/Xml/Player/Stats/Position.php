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

namespace PHT\Xml\Player\Stats;

use PHT\Xml;

class Position extends Xml\Base
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
     * Return position code, central defenders, inner midfielders and forwards are grouped
     * and shown with code of right side position
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->getXml()->getElementsByTagName('PositionCode')->item(0)->nodeValue;
    }

    /**
     * Return minutes spent in this position
     *
     * @return integer
     */
    public function getMinutes()
    {
        return $this->getXml()->getElementsByTagName('Minutes')->item(0)->nodeValue;
    }
}
