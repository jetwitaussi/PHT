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

namespace PHT\Xml\Avatar;

use PHT\Xml;

class Layer extends Xml\Base
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
     * Return X coord
     *
     * @return integer
     */
    public function getX()
    {
        return $this->getXml()->getElementsByTagName('Layer')->item(0)->getAttribute('x');
    }

    /**
     * Return Y coord
     *
     * @return integer
     */
    public function getY()
    {
        return $this->getXml()->getElementsByTagName('Layer')->item(0)->getAttribute('y');
    }

    /**
     * Return layer image url
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getXml()->getElementsByTagName('Image')->item(0)->nodeValue;
    }
}
