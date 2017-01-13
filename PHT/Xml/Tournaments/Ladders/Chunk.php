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
use PHT\Utils;
use PHT\Wrapper;

class Chunk extends Xml\Base
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
     * Return senior team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * Return senior team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        if ($this->teamId !== null) {
            return Wrapper\Team\Senior::team($this->teamId);
        }
        return null;
    }

    /**
     * Return ladder id
     *
     * @return integer
     */
    public function getLadderId()
    {
        return $this->getXml()->getElementsByTagName('LadderId')->item(0)->nodeValue;
    }

    /**
     * Return ladder details
     *
     * @param integer $page -1 for team page, 0 for first page
     * @param integer $size
     * @return \PHT\Xml\Tournaments\Ladders\Ladder
     */
    public function getLadder($page = -1, $size = 25)
    {
        return Wrapper\Tournament::ladder($this->getLadderId(), $this->teamId, $page, $size);
    }

    /**
     * Return ladder name
     *
     * @return string
     */
    public function getLadderName()
    {
        return $this->getXml()->getElementsByTagName('Name')->item(0)->nodeValue;
    }

    /**
     * Return team position
     *
     * @return integer
     */
    public function getTeamPosition()
    {
        return $this->getXml()->getElementsByTagName('Position')->item(0)->nodeValue;
    }

    /**
     * Return next match date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getNextMatchDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('NextMatchDate')->item(0)->nodeValue, $format);
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
}
