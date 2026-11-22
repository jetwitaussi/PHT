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

namespace PHT\Xml\Team;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class History extends Xml\File
{
    /**
     * Return team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return team name
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return team object
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getTeamId());
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
     * Return country object
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country($this->getLeagueId());
    }

    /**
     * Return date team was founded for its current owner
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getFoundedDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('FoundedDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return number of seasons since current owner took over the team
     *
     * @return integer
     */
    public function getSeasonNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//TeamHistory/Season')->length;
    }

    /**
     * Return season result object, oldest season first
     *
     * @param integer $index
     * @return \PHT\Xml\Team\History\Season
     */
    public function getSeason($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getSeasonNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//TeamHistory/Season');
            $season = new \DOMDocument('1.0', 'UTF-8');
            $season->appendChild($season->importNode($nodeList->item($index), true));
            return new History\Season($season, $this->getTeamId());
        }
        return null;
    }

    /**
     * Return season result object of a specific season number
     *
     * @param integer $seasonNumber
     * @return \PHT\Xml\Team\History\Season
     */
    public function getSeasonByNumber($seasonNumber)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//TeamHistory/Season[SeasonNumber="' . (int)$seasonNumber . '"]');
        if (!$nodeList->length) {
            return null;
        }
        $season = new \DOMDocument('1.0', 'UTF-8');
        $season->appendChild($season->importNode($nodeList->item(0), true));
        return new History\Season($season, $this->getTeamId());
    }

    /**
     * Return iterator of season result objects, oldest season first
     *
     * @return \PHT\Xml\Team\History\Season[]
     */
    public function getSeasons()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//TeamHistory/Season');
        /** @var \PHT\Xml\Team\History\Season[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Team\History\Season', $this->getTeamId());
        return $data;
    }

    /**
     * Return number of trophies won since current owner took over the team
     *
     * @return integer
     */
    public function getTrophyNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//TrophyList/Trophy')->length;
    }

    /**
     * Return trophy object
     *
     * @param integer $index
     * @return \PHT\Xml\Team\Trophy
     */
    public function getTrophy($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getTrophyNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//TrophyList/Trophy');
            $trophy = new \DOMDocument('1.0', 'UTF-8');
            $trophy->appendChild($trophy->importNode($nodeList->item($index), true));
            return new Trophy($trophy);
        }
        return null;
    }

    /**
     * Return iterator of trophy objects
     *
     * @return \PHT\Xml\Team\Trophy[]
     */
    public function getTrophies()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//TrophyList/Trophy');
        /** @var \PHT\Xml\Team\Trophy[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Team\Trophy');
        return $data;
    }
}
