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

namespace PHT\Xml\Stats\National;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Players extends Xml\File
{
    /**
     * Return team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
    }

    /**
     * Return team
     *
     * @return \PHT\Xml\Team\National
     */
    public function getTeam()
    {
        return Wrapper\National::team($this->getTeamId());
    }

    /**
     * Return team name
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return match type (NT for national team match, WC for worldcup match)
     *
     * @return string
     */
    public function getMatchType()
    {
        return $this->getXml()->getElementsByTagName('MatchTypeCategory')->item(0)->nodeValue;
    }

    /**
     * Return if more records available
     *
     * @return integer
     */
    public function hasMoreRecords()
    {
        return strtolower($this->getXml()->getElementsByTagName('MoreRecordsAvailable')->item(0)->nodeValue) == "true";
    }

    /**
     * Return players number
     *
     * @return integer
     */
    public function getPlayerNumber()
    {
        return $this->getXml()->getElementsByTagName('Player')->length;
    }

    /**
     * Return national player object
     *
     * @param integer $index
     * @return \PHT\Xml\Stats\National\Player
     */
    public function getPlayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getPlayerNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Achievement');
            $player = new \DOMDocument('1.0', 'UTF-8');
            $player->appendChild($player->importNode($nodeList->item($index), true));
            return new Player($player, $this->getTeamId());
        }
        return null;
    }

    /**
     * Return iterator of national player objects
     *
     * @return \PHT\Xml\Stats\National\Player[]
     */
    public function getPlayers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Achievement');
        /** @var \PHT\Xml\Stats\National\Player[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Stats\National\Player', $this->getTeamId());
        return $data;
    }
}
