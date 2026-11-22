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

namespace PHT\Xml\Player\Stats;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class HTMatch extends Xml\Base
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
     * Return match id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
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
     * Return match source system
     *
     * @return string
     */
    public function getSourceSystem()
    {
        return $this->getXml()->getElementsByTagName('SourceSystem')->item(0)->nodeValue;
    }

    /**
     * Return if it's a youth match
     *
     * @return boolean
     */
    public function isYouth()
    {
        return strtolower($this->getSourceSystem()) == Config\Config::MATCH_YOUTH;
    }

    /**
     * Return if it's a tournament match
     *
     * @return boolean
     */
    public function isTournament()
    {
        return strtolower($this->getSourceSystem()) == Config\Config::MATCH_TOURNAMENT;
    }

    /**
     * Return match details object
     *
     * @param boolean $events
     * @return \PHT\Xml\HTMatch
     */
    public function getMatch($events = true)
    {
        if ($this->isYouth()) {
            return Wrapper\HTMatch::youth($this->getId(), $events);
        } elseif ($this->isTournament()) {
            return Wrapper\HTMatch::tournament($this->getId(), $events);
        }
        return Wrapper\HTMatch::senior($this->getId(), $events);
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
     * Return home team name
     *
     * @return string
     */
    public function getHomeTeamName()
    {
        return $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
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
     * Return if match was a walkover
     *
     * @return boolean
     */
    public function isWalkover()
    {
        return strtolower($this->getXml()->getElementsByTagName('IsWalkover')->item(0)->nodeValue) == "true";
    }

    /**
     * Return player age at match time
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
    }

    /**
     * Return player age days at match time
     *
     * @return integer
     */
    public function getDays()
    {
        return $this->getXml()->getElementsByTagName('AgeDays')->item(0)->nodeValue;
    }

    /**
     * Return player tsi at match time
     *
     * @return integer
     */
    public function getTsi()
    {
        return $this->getXml()->getElementsByTagName('TSI')->item(0)->nodeValue;
    }

    /**
     * Return player form at match time
     *
     * @return integer
     */
    public function getForm()
    {
        return $this->getXml()->getElementsByTagName('PlayerForm')->item(0)->nodeValue;
    }

    /**
     * Return player stamina at match start, null if no value (player not in lineup for example)
     *
     * @return integer
     */
    public function getStamina()
    {
        return $this->getOptionalValue('Stamina');
    }

    /**
     * Return player loyalty at match time, null if no value
     *
     * @return integer
     */
    public function getLoyalty()
    {
        return $this->getOptionalValue('Loyalty');
    }

    /**
     * Return player experience at match time
     *
     * @return integer
     */
    public function getExperience()
    {
        return $this->getXml()->getElementsByTagName('Experience')->item(0)->nodeValue;
    }

    /**
     * Return number of goals scored by player in match
     *
     * @return integer
     */
    public function getGoals()
    {
        return $this->getXml()->getElementsByTagName('GoalsInMatch')->item(0)->nodeValue;
    }

    /**
     * Return number of yellow cards received by player in match
     *
     * @return integer
     */
    public function getYellowCards()
    {
        return $this->getXml()->getElementsByTagName('YellowCardsInMatch')->item(0)->nodeValue;
    }

    /**
     * Return number of red cards received by player in match
     *
     * @return integer
     */
    public function getRedCards()
    {
        return $this->getXml()->getElementsByTagName('RedCardsInMatch')->item(0)->nodeValue;
    }

    /**
     * Return player injury level after match: -1 = healthy, 0 = bruised, >0 = number of weeks injured
     *
     * @return integer
     */
    public function getInjury()
    {
        return $this->getXml()->getElementsByTagName('InjuryLevel')->item(0)->nodeValue;
    }

    /**
     * Return total number of minutes player was on the pitch
     *
     * @return integer
     */
    public function getPlayedMinutes()
    {
        return $this->getXml()->getElementsByTagName('PlayedMinutes')->item(0)->nodeValue;
    }

    /**
     * Return player average star rating in match, 0 if player did not get a rating
     *
     * @return float
     */
    public function getRating()
    {
        return $this->getXml()->getElementsByTagName('Rating')->item(0)->nodeValue;
    }

    /**
     * Return player star rating at end of match, 0 if player did not get a rating
     *
     * @return float
     */
    public function getRatingEndOfGame()
    {
        return $this->getXml()->getElementsByTagName('RatingEndOfGame')->item(0)->nodeValue;
    }

    /**
     * Return number of positions occupied by player during match
     *
     * @return integer
     */
    public function getPositionNumber()
    {
        return $this->getXml()->getElementsByTagName('Position')->length;
    }

    /**
     * Return position object
     *
     * @param integer $index
     * @return \PHT\Xml\Player\Stats\Position
     */
    public function getPosition($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getPositionNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $position = new \DOMDocument('1.0', 'UTF-8');
            $position->appendChild($position->importNode($this->getXml()->getElementsByTagName('Position')->item($index), true));
            return new Position($position);
        }
        return null;
    }

    /**
     * Return iterator of position objects
     *
     * @return \PHT\Xml\Player\Stats\Position[]
     */
    public function getPositions()
    {
        $nodeList = $this->getXml()->getElementsByTagName('Position');
        /** @var \PHT\Xml\Player\Stats\Position[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Player\Stats\Position');
        return $data;
    }

    /**
     * @param string $tag
     * @return string
     */
    private function getOptionalValue($tag)
    {
        $node = $this->getXml()->getElementsByTagName($tag)->item(0);
        if ($node !== null && $node->nodeValue !== '') {
            return $node->nodeValue;
        }
        return null;
    }
}
