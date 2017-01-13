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

namespace PHT\Xml;

use PHT\Utils;
use PHT\Config;

class Match extends File
{
    private $type;

    /**
     * @param string $xml
     */
    public function __construct($xml)
    {
        parent::__construct($xml);

        $this->type = Config\Config::MATCH_SENIOR;
        if ($this->isYouth()) {
            $this->type = Config\Config::MATCH_YOUTH;
        } elseif (in_array($this->getType(), array(10, 11, 12))) {
            $this->type = Config\Config::MATCH_NATIONAL;
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
     * Return match context id
     *
     * @return integer
     */
    public function getContextId()
    {
        return $this->getXml()->getElementsByTagName('MatchContextId')->item(0)->nodeValue;
    }

    /**
     * Return match type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
    }

    /**
     * Return cup level if match type is cup, 0 otherwise
     *
     * @return integer
     */
    public function getCupLevel()
    {
        return $this->getXml()->getElementsByTagName('CupLevel')->item(0)->nodeValue;
    }

    /**
     * Return cup level index if match type is cup, 0 otherwise
     *
     * @return integer
     */
    public function getCupLevelIndex()
    {
        return $this->getXml()->getElementsByTagName('CupLevelIndex')->item(0)->nodeValue;
    }

    /**
     * Return match start datetime
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getStartDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return match end datetime
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getEndDate($format = null)
    {
        $node = $this->getXml()->getElementsByTagName('FinishedDate');
        if ($node->length) {
            return Utils\Date::convert($node->item(0)->nodeValue, $format);
        }
        return null;
    }

    /**
     * Return added minutes number
     *
     * @return integer
     */
    public function getAddedMinutes()
    {
        return $this->getXml()->getElementsByTagName('AddedMinutes')->item(0)->nodeValue;
    }

    /**
     * Return home team object
     *
     * @return \PHT\Xml\Match\Team
     */
    public function getHomeTeam()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//HomeTeam');
        if ($this->getXml()->getElementsByTagName('PossessionFirstHalfHome')->length) {
            $fh = $this->getXml()->getElementsByTagName('PossessionFirstHalfHome')->item(0)->nodeValue;
            $nodeList->item(0)->appendChild(new \DOMElement('PossessionFirstHalf', $fh));
        }
        if ($this->getXml()->getElementsByTagName('PossessionSecondHalfHome')->length) {
            $sh = $this->getXml()->getElementsByTagName('PossessionSecondHalfHome')->item(0)->nodeValue;
            $nodeList->item(0)->appendChild(new \DOMElement('PossessionSecondHalf', $sh));
        }
        $team = new \DOMDocument('1.0', 'UTF-8');
        $team->appendChild($team->importNode($nodeList->item(0), true));
        return new Match\Team($team, 'Home', $this->type, $this->getId());
    }

    /**
     * Return away team object
     *
     * @return \PHT\Xml\Match\Team
     */
    public function getAwayTeam()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//AwayTeam');
        if ($this->getXml()->getElementsByTagName('PossessionFirstHalfAway')->length) {
            $fh = $this->getXml()->getElementsByTagName('PossessionFirstHalfAway')->item(0)->nodeValue;
            $nodeList->item(0)->appendChild(new \DOMElement('PossessionFirstHalf', $fh));
        }
        if ($this->getXml()->getElementsByTagName('PossessionSecondHalfAway')->length) {
            $sh = $this->getXml()->getElementsByTagName('PossessionSecondHalfAway')->item(0)->nodeValue;
            $nodeList->item(0)->appendChild(new \DOMElement('PossessionSecondHalf', $sh));
        }
        $team = new \DOMDocument('1.0', 'UTF-8');
        $team->appendChild($team->importNode($nodeList->item(0), true));
        return new Match\Team($team, 'Away', $this->type, $this->getId());
    }

    /**
     * Return match arena object (null for tournament match)
     *
     * @return \PHT\Xml\Match\Arena
     */
    public function getArena()
    {
        if ($this->isTournament()) {
            return null;
        }
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Arena');
        $arena = new \DOMDocument('1.0', 'UTF-8');
        $arena->appendChild($arena->importNode($nodeList->item(0), true));
        return new Match\Arena($arena, $this->type);
    }

    /**
     * Return match score
     *
     * @return string
     */
    public function getScore()
    {
        $home = $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
        $away = $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
        return $home . '-' . $away;
    }

    /**
     * Return number of goals in the match
     *
     * @return integer
     */
    public function getGoalNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//Goal')->length;
    }

    /**
     * Return match goal object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Goal
     */
    public function getGoal($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getGoalNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Goal');
            $goal = new \DOMDocument('1.0', 'UTF-8');
            $goal->appendChild($goal->importNode($nodeList->item($index), true));
            return new Match\Goal($goal, $this->type);
        }
        return null;
    }

    /**
     * Return iterator of match goal objects
     *
     * @return \PHT\Xml\Match\Goal[]
     */
    public function getGoals()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Goal');
        /** @var \PHT\Xml\Match\Goal[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Goal', $this->type);
        return $data;
    }

    /**
     * Return total of yellow cards
     *
     * @return integer
     */
    public function getYellowCardNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookingType[.="1"]')->length;
    }

    /**
     * Return total of red cards
     *
     * @return integer
     */
    public function getRedCardNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookingType[.="2"]')->length;
    }

    /**
     * Return total of cards
     *
     * @return integer
     */
    public function getCardNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//Booking')->length;
    }

    /**
     * Return match card object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Card
     */
    public function getCard($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getCardNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Booking');
            $card = new \DOMDocument('1.0', 'UTF-8');
            $card->appendChild($card->importNode($nodeList->item($index), true));
            return new Match\Card($card, $this->type);
        }
        return null;
    }

    /**
     * Return iterator of match card objects
     *
     * @return \PHT\Xml\Match\Card[]
     */
    public function getCards()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Booking');
        /** @var \PHT\Xml\Match\Card[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Card', $this->type);
        return $data;
    }

    /**
     * Return yellow card object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Card
     */
    public function getYellowCard($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getYellowCardNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookingType[.="1"]');
            $card = new \DOMDocument('1.0', 'UTF-8');
            $card->appendChild($card->importNode($nodeList->item($index)->parentNode, true));
            return new Match\Card($card, $this->type);
        }
        return null;
    }

    /**
     * Return iterator of yellow card objects
     *
     * @return \PHT\Xml\Match\Card[]
     */
    public function getYellowCards()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookingType[.="1"]');
        $card = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $card->appendChild($card->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $card->getElementsByTagName('Booking');
        /** @var \PHT\Xml\Match\Card[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\Match\Card', $this->type);
        return $data;
    }

    /**
     * Return red card object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Card
     */
    public function getRedCard($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getYellowCardNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookingType[.="2"]');
            $card = new \DOMDocument('1.0', 'UTF-8');
            $card->appendChild($card->importNode($nodeList->item($index)->parentNode, true));
            return new Match\Card($card, $this->type);
        }
        return null;
    }

    /**
     * Return iterator of red card objects
     *
     * @return \PHT\Xml\Match\Card[]
     */
    public function getRedCards()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookingType[.="2"]');
        $card = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $card->appendChild($card->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $card->getElementsByTagName('Booking');
        /** @var \PHT\Xml\Match\Card[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\Match\Card', $this->type);
        return $data;
    }

    /**
     * Return whole text match
     *
     * @param string $playerUrlReplacement (given url is concat with : PlayerID=xxxxxxx )
     * @return string
     */
    public function getFullText($playerUrlReplacement = null)
    {
        return $this->getFirstHalfTime($playerUrlReplacement) . $this->getSecondHalfTime($playerUrlReplacement);
    }

    /**
     * Return text of first half time
     *
     * @param string $playerUrlReplacement (given url is concat with : PlayerID=xxxxxxx )
     * @return string
     */
    public function getFirstHalfTime($playerUrlReplacement = null)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Minute[.<46]');
        if ($nodeList->length) {
            $text = '';
            foreach ($nodeList as $event) {
                /** @var \DOMElement $event */
                $event = $event->parentNode;
                $text .= $event->getElementsByTagName('EventText')->item(0)->nodeValue;
            }
            return Utils\Player::updateUrl($text, $playerUrlReplacement);
        }
        return null;
    }

    /**
     * Return text of second half time
     *
     * @param string $playerUrlReplacement (given url is concat with : PlayerID=xxxxxxx )
     * @return string
     */
    public function getSecondHalfTime($playerUrlReplacement = null)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Minute[.>45]');
        if ($nodeList->length) {
            $text = '';
            foreach ($nodeList as $event) {
                /** @var \DOMElement $event */
                $event = $event->parentNode;
                $text .= $event->getElementsByTagName('EventText')->item(0)->nodeValue;
            }
            return Utils\Player::updateUrl($text, $playerUrlReplacement);
        }
        return null;
    }

    /**
     * Return events number
     *
     * @return integer
     */
    public function getEventNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//Event')->length;
    }

    /**
     * Return match event object
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
            $e = new Match\Event($event, $this->type);
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
     * Return iterator of match event objects
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
     * Return number of injuries in the match
     *
     * @return integer
     */
    public function getInjuryNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//Injury')->length;
    }

    /**
     * Return match injury object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Injury
     */
    public function getInjury($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getInjuryNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Injury');
            $injury = new \DOMDocument('1.0', 'UTF-8');
            $injury->appendChild($injury->importNode($nodeList->item($index), true));
            return new Match\Injury($injury, $this->type);
        }
        return null;
    }

    /**
     * Return iterator of match injury objects
     *
     * @return \PHT\Xml\Match\Injury[]
     */
    public function getInjuries()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Injury');
        /** @var \PHT\Xml\Match\Injury[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Injury', $this->type);
        return $data;
    }

    /**
     * Return referee object
     *
     * @return \PHT\Xml\Match\Referee
     */
    public function getReferee()
    {
        $node = $this->getXml()->getElementsByTagName('Referee');
        if ($node->length) {
            $ref = new \DOMDocument('1.0', 'UTF-8');
            $ref->appendChild($ref->importNode($node->item(0), true));
            return new Match\Referee($ref);
        }
        return null;
    }

    /**
     * Return referee object for first assistant
     *
     * @return \PHT\Xml\Match\Referee
     */
    public function getRefereeFirstAssistant()
    {
        $node = $this->xml->getElementsByTagName('RefereeAssistant1');
        if ($node->length) {
            $ref = new \DOMDocument('1.0', 'UTF-8');
            $ref->appendChild($ref->importNode($node->item(0), true));
            return new Match\Referee($ref);
        }
        return null;
    }

    /**
     * Return referee object for second assistant
     *
     * @return \PHT\Xml\Match\Referee
     */
    public function getRefereeSecondAssistant()
    {
        $node = $this->xml->getElementsByTagName('RefereeAssistant2');
        if ($node->length) {
            $ref = new \DOMDocument('1.0', 'UTF-8');
            $ref->appendChild($ref->importNode($node->item(0), true));
            return new Match\Referee($ref);
        }
        return null;
    }
}
