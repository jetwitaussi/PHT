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

namespace PHT\Xml\Team\Senior;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Players extends Xml\HTSupporter
{
    /**
     * Return number players of team
     *
     * @return integer
     */
    public function getPlayerNumber()
    {
        return $this->getXml()->getElementsByTagName('Player')->length;
    }

    /**
     * Get a chunk of player details
     *
     * @param integer $index
     * @return \PHT\Xml\Player\Senior\Chunk
     */
    public function getPlayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getPlayerNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Player');
            $player = new \DOMDocument('1.0', 'UTF-8');
            $player->appendChild($player->importNode($nodeList->item($index), true));
            return new Xml\Player\Senior\Chunk($player);
        }
        return null;
    }

    /**
     * Get iterator of player chunk objets
     *
     * @return \PHT\Xml\Player\Senior\Chunk[]
     */
    public function getPlayers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Player');
        /** @var \PHT\Xml\Player\Senior\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Player\Senior\Chunk');
        return $data;
    }

    /**
     * Is team playing a match?
     *
     * @return boolean
     */
    public function isPlayingMatch()
    {
        return strtolower($this->getXml()->getElementsByTagName('IsPlayingMatch')->item(0)->nodeValue) == "true";
    }
}
