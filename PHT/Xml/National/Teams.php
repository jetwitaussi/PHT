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

class Teams extends Xml\HTSupporter
{
    /**
     * Create an instance
     *
     * @param string $xml
     */
    public function __construct($xml)
    {
        parent::__construct($xml);

        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $this->getXml()->getElementsByTagName('NationalTeam');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $n = $nodeList->item($i);
            $id = $n->childNodes->item(1)->nodeValue;
            $incup = 'false';
            if ($xpath->query('//CupNationalTeamID[.=' . $id . ']')->length) {
                $incup = 'true';
            }
            $n->appendChild(new \DOMElement('StillInCup', $incup));
        }
    }

    /**
     * Return if listing contains u20 teams, if no it's NT teams
     *
     * @return boolean
     */
    public function isU20Teams()
    {
        return $this->getXml()->getElementsByTagName('LeagueOfficeTypeID')->item(0)->nodeValue == 4;
    }

    /**
     * Return current worldcup id
     *
     * @return integer
     */
    public function getCurrentWorldcupId()
    {
        return $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
    }

    /**
     * Return number of national teams still in worldcup
     *
     * @return integer
     */
    public function getNumberTeamsStillInWorldcup()
    {
        return $this->getXml()->getElementsByTagName('CupTeam')->length;
    }

    /**
     * Return number of national teams
     *
     * @return integer
     */
    public function getTeamNumber()
    {
        return $this->getXml()->getElementsByTagName('NationalTeam')->length;
    }

    /**
     * Return national team object
     *
     * @param integer $index
     * @return \PHT\Xml\Team\National\Chunk
     */
    public function getTeam($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getTeamNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//NationalTeam');
            $team = new \DOMDocument('1.0', 'UTF-8');
            $team->appendChild($team->importNode($nodeList->item($index), true));
            return new Xml\Team\National\Chunk($team);
        }
        return null;
    }

    /**
     * Return iterator of national team objects
     *
     * @return \PHT\Xml\Team\National\Chunk[]
     */
    public function getTeams()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//NationalTeam');
        /** @var \PHT\Xml\Team\National\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Team\National\Chunk');
        return $data;
    }
}
