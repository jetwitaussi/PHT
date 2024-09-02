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

namespace PHT\Xml\World;

use PHT\Xml;
use PHT\Wrapper;
use PHT\Utils;

class LeagueStructure extends Xml\File
{
    /**
     * Return league id
     *
     * @return integer
     */
    public function getLeagueId()
    {
        return $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
    }

    /**
     * Return country object
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country($this->getLeagueId());
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
     * Return number of levels in the league
     *
     * @return integer
     */
    public function getLevelNumber()
    {
        return $this->getXml()->getElementsByTagName('NrOfLeagueLevels')->item(0)->nodeValue;
    }

    /**
     * Return league level object
     *
     * @param integer $number
     * @return \PHT\Xml\World\League\Level
     */
    public function getLevel($number)
    {
        $number = round($number);
        if ($number >=1 && $number <= $this->getLevelNumber()) {
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//LeagueLevel[.="'.$number.'"]');
            $level = new \DOMDocument('1.0', 'UTF-8');
            $level->appendChild($level->importNode($nodeList->item(0)->parentNode, true));
            return new League\Level($level);
        }
        return null;
    }

    /**
     * Return iterator of league level objects
     *
     * @return \PHT\Xml\World\League\Level[]
     */
    public function getLevels()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Staff');
        /** @var \PHT\Xml\World\League\Level[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\World\League\Level');
        return $data;
    }
}