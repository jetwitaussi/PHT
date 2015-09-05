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

namespace PHT\Xml\World\Worldcup;

use PHT\Xml;
use PHT\Utils;

class Round extends Xml\Base
{
    /**
     * Create an instance
     *
     * @param \DOMDocument $xml
     */
    public function __construct($xml)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
    }

    /**
     * Return start match number
     *
     * @return integer
     */
    public function getStartMatchNumber()
    {
        return $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
    }

    /**
     * Return round start date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getStartDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('StartDate')->item(0)->nodeValue, $format);
    }
}
