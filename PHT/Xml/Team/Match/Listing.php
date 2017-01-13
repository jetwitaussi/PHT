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

namespace PHT\Xml\Team\Match;

use PHT\Xml;
use PHT\Wrapper;
use PHT\Config;
use PHT\Utils;

class Listing extends Xml\File
{
    /**
     * Return team id
     *
     * @return integer
     */
    public function getId()
    {
        $node = $this->getXml()->getElementsByTagName('TeamID');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return team name
     *
     * @return string
     */
    public function getName()
    {
        $node = $this->getXml()->getElementsByTagName('TeamName');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return team short name
     *
     * @return string
     */
    public function getShortName()
    {
        $node = $this->getXml()->getElementsByTagName('ShortTeamName');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return if it's youth matches
     *
     * @return boolean
     */
    public function isYouth()
    {
        return strtolower($this->getXml()->getElementsByTagName('IsYouth')->item(0)->nodeValue) == 'true';
    }

    /**
     * Return league id
     *
     * @return integer
     */
    public function getLeagueId()
    {
        if (!$this->isYouth()) {
            return $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        if (!$this->isYouth()) {
            return Wrapper\World::country($this->getLeagueId());
        }
        return null;
    }

    /**
     * Return league name
     *
     * @return string
     */
    public function getLeagueName()
    {
        if (!$this->isYouth()) {
            return $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return senior league id
     *
     * @return integer
     */
    public function getSeniorLeagueId()
    {
        if (!$this->isYouth()) {
            return $this->getXml()->getElementsByTagName('LeagueLevelUnitID')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return senior league
     *
     * @return \PHT\Xml\World\League\Senior
     */
    public function getSeniorLeague()
    {
        if (!$this->isYouth()) {
            return Wrapper\World\League::senior($this->getSeniorLeagueId());
        }
        return null;
    }

    /**
     * Return senior league name
     *
     * @return string
     */
    public function getSeniorLeagueName()
    {
        if (!$this->isYouth()) {
            return $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return senior league level
     *
     * @return integer
     */
    public function getSeniorLeagueLevel()
    {
        if (!$this->isYouth()) {
            return $this->getXml()->getElementsByTagName('LeagueLevel')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return number of matches in list
     *
     * @return integer
     */
    public function getMatchNumber()
    {
        return $this->getXml()->getElementsByTagName('Match')->length;
    }

    /**
     * Get chunk of match
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Chunk
     */
    public function getMatch($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getMatchNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $nodeList = $this->getXml()->getElementsByTagName('Match');
            $xmlMatch = new \DOMDocument('1.0', 'UTF-8');
            $xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($index), true));
            return new Xml\Match\Chunk($xmlMatch);
        }
        return null;
    }

    /**
     * Get iterator of chunk match objects
     *
     * @return \PHT\Xml\Match\Chunk[]
     */
    public function getMatches()
    {
        $nodeList = $this->getXml()->getElementsByTagName('Match');
        /** @var \PHT\Xml\Match\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Chunk');
        return $data;
    }

    /**
     * Return next match to come (match chunk)
     *
     * @return \PHT\Xml\Match\Chunk
     */
    public function getNextMatch()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Status[.="' . Config\Config::MATCH_TOCOME . '"]');
        $xmlMatch = new \DOMDocument('1.0', 'UTF-8');
        $xmlMatch->appendChild($xmlMatch->importNode($nodeList->item(0)->parentNode, true));
        return new Xml\Match\Chunk($xmlMatch);
    }

    /**
     * Return iterator of match chunk objects (with only matches to come)
     *
     * @return \PHT\Xml\Match\Chunk[]
     */
    public function getNextMatches()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Status[.="' . Config\Config::MATCH_TOCOME . '"]');
        $xmlMatch = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodeList = $xmlMatch->getElementsByTagName('Match');
        /** @var \PHT\Xml\Match\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Chunk');
        return $data;
    }

    /**
     * Return last played match (match chunk)
     *
     * @return \PHT\Xml\Match\Chunk
     */
    public function getLastMatch()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Status[.="' . Config\Config::MATCH_PLAYED . '"]');
        $xmlMatch = new \DOMDocument('1.0', 'UTF-8');
        $xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($nodeList->length - 1)->parentNode, true));
        return new Xml\Match\Chunk($xmlMatch);
    }

    /**
     * Return iterator of match chunk objects (with only played matches)
     *
     * @return \PHT\Xml\Match\Chunk[]
     */
    public function getLastMatches()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Status[.="' . Config\Config::MATCH_PLAYED . '"]');
        $xmlMatch = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 1; $i <= $nodeList->length; $i++) {
            $xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($nodeList->length - $i)->parentNode, true));
        }
        $nodeList = $xmlMatch->getElementsByTagName('Match');
        /** @var \PHT\Xml\Match\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Chunk');
        return $data;
    }

    /**
     * Return iterator of match chunk objects (with only ongoing matches)
     *
     * @return \PHT\Xml\Match\Chunk[]
     */
    public function getPlayingMatches()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Status[.="' . Config\Config::MATCH_LIVE . '"]');
        $xmlMatch = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodeList = $xmlMatch->getElementsByTagName('Match');
        /** @var \PHT\Xml\Match\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Chunk');
        return $data;
    }
}
