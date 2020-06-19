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

namespace PHT\Xml\World\Region;

use PHT\Xml;
use PHT\Wrapper;

class Chunk extends Xml\Base
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
     * Return region id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
    }

    /**
     * Return region id
     *
     * @return integer
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
    }

    /**
     * Get full region details
     *
     * @return Xml\World\Region
     */
    public function getRegion()
    {
        return Wrapper\World::region($this->getId());
    }
}
