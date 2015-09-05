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

namespace PHT\Xml\Player\Youth;

use PHT\Xml;

class Skill extends Xml\Base
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
     * Return skill value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->getXml()->documentElement->nodeValue;
    }

    /**
     * Return if skill is available
     *
     * @return boolean
     */
    public function isAvailable()
    {
        return strtolower($this->getXml()->documentElement->getAttribute('IsAvailable')) == 'true';
    }

    /**
     * Return if skill can be trained anymore
     *
     * @return boolean
     */
    public function isMaxReached()
    {
        return strtolower($this->getXml()->documentElement->getAttribute('IsMaxReached')) == 'true';
    }

    /**
     * Return if skill can be unlocked
     *
     * @return boolean
     */
    public function mayUnlock()
    {
        return strtolower($this->getXml()->documentElement->getAttribute('MayUnlock')) == 'true';
    }
}
