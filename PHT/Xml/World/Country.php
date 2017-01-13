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
use PHT\Utils;
use PHT\Config;
use PHT\Wrapper;

class Country extends Xml\Base
{
    /**
     * @param \DOMDocument $xml
     */
    public function __construct($xml)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
    }

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
     * Return league name
     *
     * @return string
     */
    public function getLeagueName()
    {
        return $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
    }

    /**
     * Return current season number
     *
     * @return integer
     */
    public function getSeasonNumber()
    {
        return $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
    }

    /**
     * Return season offset to swedish's season
     *
     * @return integer
     */
    public function getSeasonOffset()
    {
        return $this->getXml()->getElementsByTagName('SeasonOffset')->item(0)->nodeValue;
    }

    /**
     * Return match round number
     *
     * @return integer
     */
    public function getMatchRound()
    {
        return $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
    }

    /**
     * Return league short name
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->getXml()->getElementsByTagName('ShortName')->item(0)->nodeValue;
    }

    /**
     * Return continent
     *
     * @return string
     */
    public function getContinent()
    {
        return $this->getXml()->getElementsByTagName('Continent')->item(0)->nodeValue;
    }

    /**
     * Return world zone
     *
     * @return string
     */
    public function getZoneName()
    {
        return $this->getXml()->getElementsByTagName('ZoneName')->item(0)->nodeValue;
    }

    /**
     * Return english name
     *
     * @return string
     */
    public function getEnglishName()
    {
        return $this->getXml()->getElementsByTagName('EnglishName')->item(0)->nodeValue;
    }

    /**
     * @return boolean
     */
    public function isCountry()
    {
        return strtolower($this->getXml()->getElementsByTagName('Country')->item(0)->getAttribute('Available')) == 'true';
    }

    /**
     * Return country id
     *
     * @return integer
     */
    public function getId()
    {
        if ($this->isCountry()) {
            return $this->getXml()->getElementsByTagName('CountryID')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return country name
     *
     * @return string
     */
    public function getName()
    {
        if ($this->isCountry()) {
            return $this->getXml()->getElementsByTagName('CountryName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return country code
     *
     * @return string
     */
    public function getCode()
    {
        if ($this->isCountry()) {
            return $this->getXml()->getElementsByTagName('CountryCode')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return country date format
     *
     * @return string
     */
    public function getDateFormat()
    {
        if ($this->isCountry()) {
            return $this->getXml()->getElementsByTagName('DateFormat')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return country time format
     *
     * @return string
     */
    public function getTimeFormat()
    {
        if ($this->isCountry()) {
            return $this->getXml()->getElementsByTagName('TimeFormat')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return currency name
     *
     * @return string
     */
    public function getCurrencyName()
    {
        if ($this->isCountry()) {
            return $this->getXml()->getElementsByTagName('CurrencyName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return currency rate
     *
     * @return integer
     */
    public function getCurrencyRate()
    {
        if ($this->isCountry()) {
            return $this->getXml()->getElementsByTagName('CurrencyRate')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return number of active teams
     *
     * @return integer
     */
    public function getNumberActiveTeams()
    {
        return $this->getXml()->getElementsByTagName('ActiveTeams')->item(0)->nodeValue;
    }

    /**
     * Return number of active users
     *
     * @return integer
     */
    public function getNumberActiveUsers()
    {
        return $this->getXml()->getElementsByTagName('ActiveUsers')->item(0)->nodeValue;
    }

    /**
     * Return number of waiting users
     *
     * @return integer
     */
    public function getNumberWaitingUsers()
    {
        return $this->getXml()->getElementsByTagName('WaitingUsers')->item(0)->nodeValue;
    }

    /**
     * Return training update date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getTrainingDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('TrainingDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return economy update date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getEconomyDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('EconomyDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return cup match date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getCupMatchDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('CupMatchDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return series match date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getSeriesMatchDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('SeriesMatchDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return max league level
     *
     * @return integer
     */
    public function getNumberLevel()
    {
        return $this->getXml()->getElementsByTagName('NumberOfLevels')->item(0)->nodeValue;
    }

    /**
     * Return national team id
     *
     * @return integer
     */
    public function getNationalTeamId()
    {
        return $this->getXml()->getElementsByTagName('NationalTeamId')->item(0)->nodeValue;
    }

    /**
     * Return national team
     *
     * @return \PHT\Xml\Team\National
     */
    public function getNationalTeam()
    {
        return Wrapper\National::team($this->getNationalTeamId());
    }

    /**
     * Return U20 team id
     *
     * @return integer
     */
    public function getU20TeamId()
    {
        return $this->getXml()->getElementsByTagName('U20TeamId')->item(0)->nodeValue;
    }

    /**
     * Return U20 team
     *
     * @return \PHT\Xml\Team\National
     */
    public function getU20Team()
    {
        return Wrapper\National::team($this->getU20TeamId());
    }

    /**
     * Return number of regions
     *
     * @return integer
     */
    public function getRegionsNumber()
    {
        return $this->getXml()->getElementsByTagName('Region')->length;
    }

    /**
     * Return region object
     *
     * @param integer $index
     * @return \PHT\Xml\World\Region
     */
    public function getRegion($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getRegionsNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $nodeList = $this->getXml()->getElementsByTagName('Region');
            $region = new \DOMDocument('1.0', 'UTF-8');
            $region->appendChild($region->importNode($nodeList->item($index), true));
            return new Xml\World\Region($region);
        }
        return null;
    }

    /**
     * Return iterator of regions objects
     *
     * @return \PHT\Xml\World\Region[]
     */
    public function getRegions()
    {
        $nodeList = $this->getXml()->getElementsByTagName('Region');
        /** @var \PHT\Xml\World\Region[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\World\Region');
        return $data;
    }

    /**
     * Return number of cups
     *
     * @return integer
     */
    public function getCupsNumber()
    {
        return $this->getXml()->getElementsByTagName('Cup')->length;
    }

    /**
     * Get cup details
     *
     * @param integer $index
     * @return \PHT\Xml\World\League\Cup
     */
    public function getCup($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getCupsNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $nodeList = $this->getXml()->getElementsByTagName('Cup');
            $cup = new \DOMDocument('1.0', 'UTF-8');
            $cup->appendChild($cup->importNode($nodeList->item($index), true));
            return new Xml\World\League\Cup($cup);
        }
        return null;
    }

    /**
     * Return iterator of cups objects
     *
     * @return \PHT\Xml\World\League\Cup[]
     */
    public function getCups()
    {
        $nodeList = $this->getXml()->getElementsByTagName('Cup');
        /** @var \PHT\Xml\World\League\Cup[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\World\League\Cup');
        return $data;
    }

    /**
     * Return training stats object
     *
     * @return \PHT\Xml\Stats\Training\Listing
     */
    public function getTrainingStats()
    {
        return Wrapper\Stats::training($this->getLeagueId());
    }

    /**
     * Return arenas stats object
     *
     * @return \PHT\Xml\Stats\Arena\Arenas
     */
    public function getArenasStats()
    {
        return Wrapper\Stats::arenas($this->getLeagueId());
    }
}
