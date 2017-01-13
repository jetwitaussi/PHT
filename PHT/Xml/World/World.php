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
use PHT\Config;
use PHT\Utils;

class World extends Xml\File
{
    /**
     * Return number of country
     *
     * @return integer
     */
    public function getCountryNumber()
    {
        return $this->getXml()->getElementsByTagName('League')->length;
    }

    /**
     * Return country details
     *
     * @param integer $index
     * @return \PHT\Xml\World\Country
     */
    public function getCountryByIndex($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getCountryNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//League');
            $league = new \DOMDocument('1.0', 'UTF-8');
            $league->appendChild($league->importNode($nodeList->item($index), true));
            return new Xml\World\Country($league);
        }
        return null;
    }

    /**
     * Return country details
     *
     * @param integer $leagueId
     * @return \PHT\Xml\World\Country
     */
    public function getCountryByLeagueId($leagueId)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//LeagueID[.="' . $leagueId . '"]');
        $league = new \DOMDocument('1.0', 'UTF-8');
        $league->appendChild($league->importNode($nodeList->item(0)->parentNode, true));
        return new Xml\World\Country($league);
    }

    /**
     * Return country details
     *
     * @param integer $countryId
     * @return \PHT\Xml\World\Country
     */
    public function getCountryByCountryId($countryId)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//CountryID[.="' . $countryId . '"]');
        $league = new \DOMDocument('1.0', 'UTF-8');
        $league->appendChild($league->importNode($nodeList->item(0)->parentNode->parentNode, true));
        return new Xml\World\Country($league);
    }

    /**
     * Return country details
     *
     * @param string $name
     * @return \PHT\Xml\World\Country
     */
    public function getCountryByName($name)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//LeagueName[.="' . $name . '"]');
        $league = new \DOMDocument('1.0', 'UTF-8');
        $league->appendChild($league->importNode($nodeList->item(0)->parentNode, true));
        return new Xml\World\Country($league);
    }

    /**
     * Return iterator of countries objects
     *
     * @return \PHT\Xml\World\Country[]
     */
    public function getCountries()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//League');
        /** @var \PHT\Xml\World\Country[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\World\Country');
        return $data;
    }
}
