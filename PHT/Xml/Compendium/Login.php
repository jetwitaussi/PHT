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

namespace PHT\Xml\Compendium;

use PHT\Xml;
use PHT\Utils;

class Login extends Xml\Base
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
     * Return login date
     *
     * @param string $format (php date() function format)
     * @return integer
     */
    public function getDate($format = null)
    {
        $login = explode(' : ', $this->getXml()->getElementsByTagName('LoginTime')->item(0)->nodeValue);
        return Utils\Date::convert($login[0], $format);
    }

    /**
     * Return login ip
     *
     * @return integer
     */
    public function getIp()
    {
        $login = explode(' : ', $this->getXml()->getElementsByTagName('LoginTime')->item(0)->nodeValue);
        return $login[1];
    }
}