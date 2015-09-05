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

namespace PHT\Xml\Tournaments\Ladders;

use PHT\Xml;
use PHT\Wrapper;

class Team extends Xml\Base
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
     * Return team id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
    }

    /**
     * Return team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getId());
    }

    /**
     * Return team name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return team position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->getXml()->getElementsByTagName('Position')->item(0)->nodeValue;
    }

    /**
     * Return total won matches
     *
     * @return integer
     */
    public function getWonNumber()
    {
        return $this->getXml()->getElementsByTagName('Wins')->item(0)->nodeValue;
    }

    /**
     * Return total lost matches
     *
     * @return integer
     */
    public function getLostNumber()
    {
        return $this->getXml()->getElementsByTagName('Lost')->item(0)->nodeValue;
    }

    /**
     * Return total won matches in a row
     *
     * @return integer
     */
    public function getWonInARowNumber()
    {
        return $this->getXml()->getElementsByTagName('WinsInARow')->item(0)->nodeValue;
    }

    /**
     * Return total lost matches
     *
     * @return integer
     */
    public function getLostInARowNumber()
    {
        return $this->getXml()->getElementsByTagName('LostInARow')->item(0)->nodeValue;
    }
}
