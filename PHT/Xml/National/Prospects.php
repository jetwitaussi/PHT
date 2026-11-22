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

namespace PHT\Xml\National;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Prospects extends Xml\HTSupporter
{
    /**
     * Return national team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return national team name
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return national team object
     *
     * @return \PHT\Xml\Team\National
     */
    public function getTeam()
    {
        return Wrapper\National::team($this->getTeamId());
    }

    /**
     * Return number of prospect players, list is hidden from 8 hours before every match of national team
     * until match starts, except for national team's coach, assistant coach and scouts
     *
     * @return integer
     */
    public function getPlayerNumber()
    {
        return $this->getXml()->getElementsByTagName('Player')->length;
    }

    /**
     * Get prospect player
     *
     * @param integer $index
     * @return \PHT\Xml\Player\Prospect
     */
    public function getPlayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getPlayerNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Players/Player');
            $player = new \DOMDocument('1.0', 'UTF-8');
            $player->appendChild($player->importNode($nodeList->item($index), true));
            return new Xml\Player\Prospect($player);
        }
        return null;
    }

    /**
     * Get iterator of prospect players objects
     *
     * @return \PHT\Xml\Player\Prospect[]
     */
    public function getPlayers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Players/Player');
        /** @var \PHT\Xml\Player\Prospect[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Player\Prospect');
        return $data;
    }
}
