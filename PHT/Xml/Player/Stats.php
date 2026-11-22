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

namespace PHT\Xml\Player;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Stats extends Xml\File
{
    /**
     * Return player id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player object
     *
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Player\Senior
     */
    public function getPlayer($includeMatchInfo = true)
    {
        return Wrapper\Player\Senior::player($this->getId(), $includeMatchInfo);
    }

    /**
     * Return player first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
    }

    /**
     * Return player nickname
     *
     * @return string
     */
    public function getNickName()
    {
        return $this->getXml()->getElementsByTagName('NickName')->item(0)->nodeValue;
    }

    /**
     * Return player last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
    }

    /**
     * Return player full name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * Return player shirt number, null if player has no number
     *
     * @return integer
     */
    public function getShirtNumber()
    {
        $node = $this->getXml()->getElementsByTagName('PlayerNumber')->item(0);
        if ($node !== null && $node->nodeValue !== '') {
            return $node->nodeValue;
        }
        return null;
    }

    /**
     * Return owning team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//OwningTeam/TeamID')->item(0)->nodeValue;
    }

    /**
     * Return owning team name
     *
     * @return string
     */
    public function getTeamName()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//OwningTeam/TeamName')->item(0)->nodeValue;
    }

    /**
     * Return owning team object
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return owning team league id
     *
     * @return integer
     */
    public function getTeamLeagueId()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//OwningTeam/LeagueID')->item(0)->nodeValue;
    }

    /**
     * Return owning team country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getTeamCountry()
    {
        return Wrapper\World::country($this->getTeamLeagueId());
    }

    /**
     * Return number of matches
     *
     * @return integer
     */
    public function getMatchNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//MatchStats/Match')->length;
    }

    /**
     * Return match statistics object
     *
     * @param integer $index
     * @return \PHT\Xml\Player\Stats\HTMatch
     */
    public function getMatch($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getMatchNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//MatchStats/Match');
            $match = new \DOMDocument('1.0', 'UTF-8');
            $match->appendChild($match->importNode($nodeList->item($index), true));
            return new Stats\HTMatch($match);
        }
        return null;
    }

    /**
     * Return iterator of match statistics objects
     *
     * @return \PHT\Xml\Player\Stats\HTMatch[]
     */
    public function getMatches()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//MatchStats/Match');
        /** @var \PHT\Xml\Player\Stats\HTMatch[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Player\Stats\HTMatch');
        return $data;
    }
}
