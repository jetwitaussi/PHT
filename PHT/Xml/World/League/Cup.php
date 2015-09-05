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

namespace PHT\Xml\World\League;

use PHT\Xml;

class Cup extends Xml\Base
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
     * Return cup id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
    }

    /**
     * Return cup name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('CupName')->item(0)->nodeValue;
    }

    /**
     * Return cup league level
     *
     * @return integer
     */
    public function getLeagueLevel()
    {
        return $this->getXml()->getElementsByTagName('CupLeagueLevel')->item(0)->nodeValue;
    }

    /**
     * Return cup level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->getXml()->getElementsByTagName('CupLevel')->item(0)->nodeValue;
    }

    /**
     * Return cup level index
     *
     * @return integer
     */
    public function getLevelIndex()
    {
        return $this->getXml()->getElementsByTagName('CupLevelIndex')->item(0)->nodeValue;
    }

    /**
     * Return cup match round
     *
     * @return integer
     */
    public function getMatchRound()
    {
        return $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
    }

    /**
     * Return cup match round left
     *
     * @return integer
     */
    public function getMatchRoundLeft()
    {
        return $this->getXml()->getElementsByTagName('MatchRoundsLeft')->item(0)->nodeValue;
    }
}
