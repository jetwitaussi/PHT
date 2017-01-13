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
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Ladder extends Xml\File
{
    private $teamId;

    /**
     * @param string $xml
     * @param integer $teamId
     */
    public function __construct($xml, $teamId)
    {
        parent::__construct($xml);
        $this->teamId = $teamId;
    }

    /**
     * Return ladder id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('LadderId')->item(0)->nodeValue;
    }

    /**
     * Return ladder name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('Name')->item(0)->nodeValue;
    }

    /**
     * Return number of teams in ladder
     *
     * @return integer
     */
    public function getTotalTeams()
    {
        return $this->getXml()->getElementsByTagName('NumOfTeams')->item(0)->nodeValue;
    }

    /**
     * Return page size
     *
     * @return integer
     */
    public function getPageSize()
    {
        return $this->getXml()->getElementsByTagName('PageSize')->item(0)->nodeValue;
    }

    /**
     * Return page index
     *
     * @return integer
     */
    public function getCurrentPage()
    {
        return $this->getXml()->getElementsByTagName('PageIndex')->item(0)->nodeValue;
    }

    /**
     * Return next page for this ladder
     *
     * @return \PHT\Xml\Tournaments\Ladders\Ladder
     */
    public function getNextPage()
    {
        return Wrapper\Tournament::ladder($this->getId(), $this->teamId, $this->getCurrentPage() + 1, $this->getPageSize());
    }

    /**
     * Return previous page for this ladder
     *
     * @return \PHT\Xml\Tournaments\Ladders\Ladder
     */
    public function getPreviousPage()
    {
        if ($this->getCurrentPage() <= 0) {
            return null;
        }
        return Wrapper\Tournament::ladder($this->getId(), $this->teamId, $this->getCurrentPage() - 1, $this->getPageSize());
    }

    /**
     * Return king team id
     *
     * @return integer
     */
    public function getKingTeamId()
    {
        return $this->getXml()->getElementsByTagName('KingTeamId')->item(0)->nodeValue;
    }

    /**
     * Return king team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getKingTeam()
    {
        return Wrapper\Team\Senior::team($this->getKingTeamId());
    }

    /**
     * Return king team name
     *
     * @return string
     */
    public function getKingTeamName()
    {
        return $this->getXml()->getElementsByTagName('KingTeamName')->item(0)->nodeValue;
    }

    /**
     * Return date since team is king
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getKingDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('KingSince')->item(0)->nodeValue, $format);
    }

    /**
     * Return team number
     *
     * @return integer
     */
    public function getTeamNumber()
    {
        return $this->getXml()->getElementsByTagName('Team')->length;
    }

    /**
     * Return ladder team object
     *
     * @param integer $index
     * @return \PHT\Xml\Tournaments\Ladders\Team
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
     * Return iterator of ladder team objects
     *
     * @return \PHT\Xml\Tournaments\Ladders\Team[]
     */
    public function getTeams()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Team');
        /** @var \PHT\Xml\Tournaments\Ladders\Team[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Tournaments\Ladders\Team');
        return $data;
    }
}
