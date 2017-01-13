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

namespace PHT\Xml\World\Worldcup;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Group extends Xml\Base
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
     * Return group id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->documentElement->getAttribute('Id');
    }

    /**
     * Return group name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->documentElement->getAttribute('Name');
    }

    /**
     * Return number of teams
     *
     * @return integer
     */
    public function getTeamNumber()
    {
        return $this->getXml()->getElementsByTagName('Team')->length;
    }

    /**
     * Return worldcup team object
     *
     * @param integer $index
     * @return \PHT\Xml\World\Worldcup\Team
     */
    public function getTeam($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getTeamNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Team');
            $team = new \DOMDocument('1.0', 'UTF-8');
            $team->appendChild($team->importNode($nodeList->item($index), true));
            return new Team($team);
        }
        return null;
    }

    /**
     * Return iterator of worldcup team objects
     *
     * @return \PHT\Xml\World\Worldcup\Team[]
     */
    public function getTeams()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Team');
        /** @var \PHT\Xml\World\Worldcup\Team[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\World\Worldcup\Team');
        return $data;
    }
}
