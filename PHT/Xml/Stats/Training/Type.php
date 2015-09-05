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

namespace PHT\Xml\Stats\Training;

use PHT\Xml;

class Type extends Xml\Base
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
     * Return type of training
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('TrainingType')->item(0)->nodeValue;
    }

    /**
     * Return number of teams training this type of training
     *
     * @return integer
     */
    public function getTeamNumber()
    {
        return $this->getXml()->getElementsByTagName('NumberOfTeams')->item(0)->nodeValue;
    }

    /**
     * Return percentage of teams training this type of training
     *
     * @return integer
     */
    public function getPercentage()
    {
        return $this->getXml()->getElementsByTagName('FractionOfTeams')->item(0)->nodeValue;
    }
}
