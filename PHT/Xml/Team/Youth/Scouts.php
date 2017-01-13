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

namespace PHT\Xml\Team\Youth;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Scouts extends Xml\File
{
    private $teamId;

    /**
     * @param string $xml
     */
    public function __construct($xml)
    {
        parent::__construct($xml);
        if ($this->getXml()->getElementsByTagName('MotherTeamID')->length) {
            $this->teamId = $this->getXml()->getElementsByTagName('MotherTeamID')->item(0)->nodeValue;
        }
    }

    /**
     * Return youth team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('YouthTeamID')->item(0)->nodeValue;
    }

    /**
     * Return youth team
     *
     * @return \PHT\Xml\Team\Youth
     */
    public function getTeam()
    {
        return new Xml\Team\Youth($this->getXml(false));
    }

    /**
     * Return number of scout
     *
     * @return integer
     */
    public function getScoutNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//Scout");
        return $nodeList->length;
    }

    /**
     * Get scout object
     *
     * @param integer $index
     * @return \PHT\Xml\Team\Youth\Scout
     */
    public function getScout($index)
    {
        $index = round($index);
        if ($index > Config\Config::$forIndex && $index < $this->getScoutNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Scout');
            $scout = new \DOMDocument('1.0', 'UTF-8');
            $scout->appendChild($scout->importNode($nodeList->item($index), true));
            return new Scout($scout, $this->teamId);
        }
        return null;
    }

    /**
     * Get iterator of scout objects
     *
     * @return \PHT\Xml\Team\Youth\Scout[]
     */
    public function getScouts()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Scout');
        /** @var \PHT\Xml\Team\Youth\Scout[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Team\Youth\Scout', $this->teamId);
        return $data;
    }
}
