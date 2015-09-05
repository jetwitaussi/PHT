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

namespace PHT\Xml\Player\Event;

use PHT\Xml;
use PHT\Utils;

class History extends Xml\Base
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
     * Return event date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('EventDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return event type id
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('PlayerEventTypeID')->item(0)->nodeValue;
    }

    /**
     * Return event text
     *
     * @return string
     */
    public function getText()
    {
        return $this->getXml()->getElementsByTagName('EventText')->item(0)->nodeValue;
    }
}
