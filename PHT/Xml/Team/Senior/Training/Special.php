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

namespace PHT\Xml\Team\Senior\Training;

use PHT\Xml;
use PHT\Wrapper;

class Special extends Xml\Base
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
     * Return player id
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player
     *
     * @return \PHT\Xml\Player\Senior
     */
    public function getPlayer()
    {
        return Wrapper\Player\Senior::player($this->getPlayerId());
    }

    /**
     * Return training id
     *
     * @return integer
     */
    public function getTrainingType()
    {
        return $this->getXml()->getElementsByTagName('SpecialTrainingTypeID')->item(0)->nodeValue;
    }
}
