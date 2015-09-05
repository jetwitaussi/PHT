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

class OldYouthPull extends Base
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
     * Return invesment sum
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getInvestment($countryCurrency = null)
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('Investment')->item(0)->nodeValue, $countryCurrency);
    }

    /**
     * Youth promoted this week ?
     *
     * @return boolean
     */
    public function hasPromoted()
    {
        return strtolower($this->getXml()->getElementsByTagName('HasPromoted')->item(0)->nodeValue) == "true";
    }

    /**
     * Return youth squad level
     *
     * @return integer
     */
    public function getYouthLevel()
    {
        return $this->getXml()->getElementsByTagName('YouthLevel')->item(0)->nodeValue;
    }
}
