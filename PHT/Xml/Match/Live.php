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

namespace PHT\Xml\Match;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Live extends Xml\Base
{
    private $type;

    /**
     * @param \DOMDocument $xml
     */
    public function __construct($xml)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;

        $this->type = Config\Config::MATCH_SENIOR;
        if ($this->isYouth()) {
            $this->type = Config\Config::MATCH_YOUTH;
        } elseif ($this->isTournament()) {
            $this->type = Config\Config::MATCH_TOURNAMENT;
        }
    }

    /**
     * Return if it's a youth match
     *
     * @return boolean
     */
    public function isYouth()
    {
        return strtolower($this->getXml()->getElementsByTagName('SourceSystem')->item(0)->nodeValue) == Config\Config::MATCH_YOUTH;
    }

    /**
     * Return if it's a tournament match
     *
     * @return boolean
     */
    public function isTournament()
    {
        return strtolower($this->getXml()->getElementsByTagName('SourceSystem')->item(0)->nodeValue) == Config\Config::MATCH_TOURNAMENT;
    }

    /**
     * Return match source system
     *
     * @return string
     */
    public function getSourceSystem()
    {
        return $this->getXml()->getElementsByTagName('SourceSystem')->item(0)->nodeValue;
    }

    /**
     * Return match id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
    }

    /**
     * Return match details
     *
     * @param boolean $events
     * @return \PHT\Xml\Match
     */
    public function getMatch($events = true)
    {
        if ($this->isYouth()) {
            return Wrapper\Match::youth($this->getId(), $events);
        }
        return Wrapper\Match::senior($this->getId(), $events);
    }

    /**
     * Return home team id
     *
     * @return integer
     */
    public function getHomeTeamId()
    {
        return $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
    }

    /**
     * Return home team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth
     */
    public function getHomeTeam()
    {
        if ($this->isYouth()) {
            return Wrapper\Team\Youth::team($this->getHomeTeamId());
        }
        return Wrapper\Team\Senior::team($this->getHomeTeamId());
    }

    /**
     * Return home team name
     *
     * @return string
     */
    public function getHomeTeamName()
    {
        return $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
    }

    /**
     * Return away team id
     *
     * @return integer
     */
    public function getAwayTeamId()
    {
        return $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
    }

    /**
     * Return away team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth
     */
    public function getAwayTeam()
    {
        if ($this->isYouth()) {
            return Wrapper\Team\Youth::team($this->getAwayTeamId());
        }
        return Wrapper\Team\Senior::team($this->getAwayTeamId());
    }

    /**
     * Return away team name
     *
     * @return string
     */
    public function getAwayTeamName()
    {
        return $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
    }

    /**
     * Return match date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return home goals
     *
     * @return integer
     */
    public function getHomeGoals()
    {
        return $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
    }

    /**
     * Return away goals
     *
     * @return integer
     */
    public function getAwayGoals()
    {
        return $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
    }

    /**
     * Return number event
     *
     * @return integer
     */
    public function getEventNumber()
    {
        return $this->getXml()->getElementsByTagName('Event')->length;
    }

    /**
     * Return event object
     * Object class depends on event key id and always extends \PHT\Xml\Match\Event
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Event
     */
    public function getEvent($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getEventNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Event');
            $event = new \DOMDocument('1.0', 'UTF-8');
            $event->appendChild($event->importNode($nodeList->item($index), true));
            $e = new Event($event, $this->type);
            $id = $e->getKeyId();
            if (class_exists('\PHT\Xml\Match\Event\E' . $id)) {
                $ev = '\PHT\Xml\Match\Event\E' . $id;
                $e = new $ev($event, $this->type);
            }
            return $e;
        }
        return null;
    }

    /**
     * Return iterator of event objects
     * Object class depends on event key id and always extends \PHT\Xml\Match\Event
     *
     * @return \PHT\Xml\Match\Event[]
     */
    public function getEvents()
    {
        $list = array();
        for ($i = Config\Config::$forIndex; $i < $this->getEventNumber() + Config\Config::$forIndex; $i++) {
            $list[] = $this->getEvent($i);
        }
        return $list;
    }

    /**
     * Return number of substitutions
     *
     * @return integer
     */
    public function getSubstitutionNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//Substitution')->length;
    }

    /**
     * Return lineup substitution object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Lineup\Substitution
     */
    public function getSubstitution($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getSubstitutionNumber() + Config\Config::$forIndex) {
            $type = Config\Config::MATCH_SENIOR;
            if ($this->isYouth()) {
                $type = Config\Config::MATCH_YOUTH;
            }
            $index -= Config\Config::$forIndex;
            $nodeList = $this->getXml()->getElementsByTagName('Substitution');
            $sub = new \DOMDocument('1.0', 'UTF-8');
            $sub->appendChild($sub->importNode($nodeList->item($index), true));
            return new Lineup\Substitution($sub, $type);
        }
        return null;
    }

    /**
     * Return iterator of lineup substitution objects
     *
     * @return \PHT\Xml\Match\Lineup\Substitution[]
     */
    public function getSubstitutions()
    {
        $type = Config\Config::MATCH_SENIOR;
        if ($this->isYouth()) {
            $type = Config\Config::MATCH_YOUTH;
        }
        $nodeList = $this->getXml()->getElementsByTagName('Substitution');
        /** @var \PHT\Xml\Match\Lineup\Substitution[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Lineup\Substitution', $type);
        return $data;
    }

    /**
     * Return home lineup player number
     *
     * @return integer
     */
    public function getHomePlayerNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//HomeTeam/StartingLineup/Player')->length;
    }

    /**
     * Return home lineup player
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Lineup\Player
     */
    public function getHomePlayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getHomePlayerNumber() + Config\Config::$forIndex) {
            $type = Config\Config::MATCH_SENIOR;
            if ($this->isYouth()) {
                $type = Config\Config::MATCH_YOUTH;
            }
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//HomeTeam/StartingLineup/Player');
            $player = new \DOMDocument('1.0', 'UTF-8');
            $player->appendChild($player->importNode($nodeList->item($index), true));
            return new Lineup\Player($player, $type);
        }
        return null;
    }

    /**
     * Return iterator of home lineup players
     *
     * @return \PHT\Xml\Match\Lineup\Player[]
     */
    public function getHomePlayers()
    {
        $type = Config\Config::MATCH_SENIOR;
        if ($this->isYouth()) {
            $type = Config\Config::MATCH_YOUTH;
        }
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//HomeTeam/StartingLineup/Player');
        /** @var \PHT\Xml\Match\Lineup\Player[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Lineup\Player', $type);
        return $data;
    }

    /**
     * Return away lineup player number
     *
     * @return integer
     */
    public function getAwayPlayerNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//AwayTeam/StartingLineup/Player')->length;
    }

    /**
     * Return away lineup player
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Lineup\Player
     */
    public function getAwayPlayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getHomePlayerNumber() + Config\Config::$forIndex) {
            $type = Config\Config::MATCH_SENIOR;
            if ($this->isYouth()) {
                $type = Config\Config::MATCH_YOUTH;
            }
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//AwayTeam/StartingLineup/Player');
            $player = new \DOMDocument('1.0', 'UTF-8');
            $player->appendChild($player->importNode($nodeList->item($index), true));
            return new Lineup\Player($player, $type);
        }
        return null;
    }

    /**
     * Return iterator of away lineup players
     *
     * @return \PHT\Xml\Match\Lineup\Player[]
     */
    public function getAwayPlayers()
    {
        $type = Config\Config::MATCH_SENIOR;
        if ($this->isYouth()) {
            $type = Config\Config::MATCH_YOUTH;
        }
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//AwayTeam/StartingLineup/Player');
        /** @var \PHT\Xml\Match\Lineup\Player[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Lineup\Player', $type);
        return $data;
    }

    /**
     * Return last match event index shown
     *
     * @return integer
     */
    public function getLastEventIndexShown()
    {
        if ($this->getXml()->getElementsByTagName('LastShownEventIndex')->length) {
            return $this->getXml()->getElementsByTagName('LastShownEventIndex')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return next event minute
     *
     * @return integer
     */
    public function getNextEventMinute()
    {
        if ($this->getXml()->getElementsByTagName('NextEventMinute')->length) {
            return $this->getXml()->getElementsByTagName('NextEventMinute')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return next event match part
     *
     * @return integer
     */
    public function getNextEventMatchPart()
    {
        if ($this->getXml()->getElementsByTagName('NextEventMatchPart')->length) {
            return $this->getXml()->getElementsByTagName('NextEventMatchPart')->item(0)->nodeValue;
        }
        return null;
    }
}
