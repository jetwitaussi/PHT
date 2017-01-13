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
use PHT\Wrapper;
use PHT\Config;
use PHT\Utils;

class Youth extends Xml\File
{
    /**
     * Return league id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('YouthLeagueID')->item(0)->nodeValue;
    }

    /**
     * Return league name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('YouthLeagueName')->item(0)->nodeValue;
    }

    /**
     * Return league type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('YouthLeagueType')->item(0)->nodeValue;
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
     * Return league team object, as small part of a team
     *
     * @param integer $index
     * @return \PHT\Xml\World\League\Team
     */
    public function getTeam($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getTeamNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query("//Team");
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Xml\World\League\Team($node, Config\Config::YOUTH);
        }
        return null;
    }

    /**
     * Return iterator of league team objects
     *
     * @return \PHT\Xml\World\League\Team[]
     */
    public function getTeams()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//Team");
        /** @var \PHT\Xml\World\League\Team[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\World\League\Team', Config\Config::YOUTH);
        return $data;
    }

    /**
     * Return season number
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
    }

    /**
     * Get current season details
     *
     * @return \PHT\Xml\World\League\Season\Youth
     */
    public function getCurrentSeason()
    {
        return Wrapper\World\Season::youth($this->getId(), $this->getSeason());
    }
}
