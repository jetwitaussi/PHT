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

namespace PHT\Xml\Stats\Arena;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Arenas extends Xml\File
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
     * Return country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country($this->getLeagueId());
    }

    /**
     * Return league name
     *
     * @return string
     */
    public function getLeagueName()
    {
        if ($this->getXml()->getElementsByTagName('LeagueName')->length) {
            return $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return stats created date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getStatsDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('CreatedDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return number of arenas
     *
     * @return integer
     */
    public function getArenaNumber()
    {
        return $this->getXml()->getElementsByTagName('ArenaStat')->length;
    }

    /**
     * Return arena stats object
     *
     * @param integer $index
     * @return \PHT\Xml\Stats\Arena\Arena
     */
    public function getArena($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getArenaNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//ArenaStat');
            $arena = new \DOMDocument('1.0', 'UTF-8');
            $arena->appendChild($arena->importNode($nodeList->item($index), true));
            return new Arena($arena);
        }
        return null;
    }

    /**
     * Return iterator of arena stats objects
     *
     * @return \PHT\Xml\Stats\Arena\Arena[]
     */
    public function getArenas()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//ArenaStat');
        /** @var \PHT\Xml\Stats\Arena\Arena[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Stats\Arena\Arena');
        return $data;
    }
}
