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

namespace PHT\Xml\User\Achievement;

use PHT\Xml;

class Variation extends Xml\Base
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
     * Return variation rank, 1 is the best rank possible
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->getXml()->getElementsByTagName('Rank')->item(0)->nodeValue;
    }

    /**
     * Return value required to reach this variation
     *
     * @return integer
     */
    public function getValueToAchieve()
    {
        return $this->getXml()->getElementsByTagName('ValueToAchieve')->item(0)->nodeValue;
    }

    /**
     * Return points awarded for this variation
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->getXml()->getElementsByTagName('Points')->item(0)->nodeValue;
    }
}
