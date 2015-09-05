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
use PHT\Wrapper;

class Player extends Xml\Base
{
    private $teamId;

    /**
     * @param \DOMDocument $xml
     * @param integer $teamId
     */
    public function __construct($xml, $teamId)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->teamId = $teamId;
    }

    /**
     * Return player id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player
     *
     * @return \PHT\Xml\Player\National
     */
    public function getPlayer()
    {
        $players = Wrapper\National::players($this->teamId);
        foreach ($players->getPlayers() as $player) {
            if ($player->getId() == $this->getId()) {
                return $player;
            }
        }
        return null;
    }

    /**
     * Return player name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('PlayerName')->item(0)->nodeValue;
    }

    /**
     * Return number of matches
     *
     * @return integer
     */
    public function getMatchNumber()
    {
        return $this->getXml()->getElementsByTagName('NrOfMatches')->item(0)->nodeValue;
    }
}
