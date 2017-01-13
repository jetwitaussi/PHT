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

class Lineup extends Xml\File
{
    /**
     * Return match id
     *
     * @return integer
     */
    public function getMatchId()
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
        if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
            return Wrapper\Match::youth($this->getMatchId(), $events);
        } elseif (in_array($this->getMatchType(), array(50, 51, 60, 61, 80))) {
            return Wrapper\Match::tournament($this->getMatchId(), $events);
        }
        return Wrapper\Match::senior($this->getMatchId(), $events);
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
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth|\PHT\Xml\Team\National
     */
    public function getHomeTeam()
    {
        if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
            return Wrapper\Team\Youth::team($this->getHomeTeamId());
        } elseif (in_array($this->getMatchType(), array(10, 11, 12))) {
            return Wrapper\National::team($this->getHomeTeamId());
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
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth|\PHT\Xml\Team\National
     */
    public function getAwayTeam()
    {
        if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
            return Wrapper\Team\Youth::team($this->getAwayTeamId());
        } elseif (in_array($this->getMatchType(), array(10, 11, 12))) {
            return Wrapper\National::team($this->getAwayTeamId());
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
    public function getMatchDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return match type
     *
     * @return integer
     */
    public function getMatchType()
    {
        return $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
    }

    /**
     * Return arena id
     *
     * @return integer
     */
    public function getArenaId()
    {
        return $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
    }

    /**
     * Return arena id
     *
     * @return \PHT\Xml\Team\Arena
     */
    public function getArena()
    {
        $id = $this->getArenaId();
        if ($id > 0) {
            return Wrapper\Team\Senior::arena($id);
        }
        return null;
    }

    /**
     * Return arena name
     *
     * @return string
     */
    public function getArenaName()
    {
        return $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
    }

    /**
     * Return team experience level
     *
     * @return integer
     */
    public function getTeamExperience()
    {
        return $this->getXml()->getElementsByTagName('ExperienceLevel')->item(0)->nodeValue;
    }

    /**
     * Return team coach modifier used
     *
     * @return integer
     */
    public function getCoachModifier()
    {
        if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('StyleOfPlay')->item(0)->nodeValue;
    }

    /**
     * Return player number in starting lineup
     *
     * @return integer
     */
    public function getStartingPlayerNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//StartingLineup/Player')->length;
    }

    /**
     * Return player in starting lineup
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Lineup\Player
     */
    public function getStartingPlayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getStartingPlayerNumber() + Config\Config::$forIndex) {
            $type = Config\Config::MATCH_SENIOR;
            if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                $type = Config\Config::MATCH_YOUTH;
            }
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//StartingLineup/Player');
            $player = new \DOMDocument('1.0', 'UTF-8');
            $player->appendChild($player->importNode($nodeList->item($index), true));
            return new Lineup\Player($player, $type);
        }
        return null;
    }

    /**
     * Return iterator of players in starting lineup
     *
     * @return \PHT\Xml\Match\Lineup\Player[]
     */
    public function getStartingPlayers()
    {
        $type = Config\Config::MATCH_SENIOR;
        if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
            $type = Config\Config::MATCH_YOUTH;
        }
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//StartingLineup/Player');
        /** @var \PHT\Xml\Match\Lineup\Player[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Lineup\Player', $type);
        return $data;
    }

    /**
     * Return player number in final lineup
     *
     * @return integer
     */
    public function getFinalPlayerNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//Lineup/Player')->length;
    }

    /**
     * Return player in final lineup
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Lineup\Player
     */
    public function getFinalPlayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getStartingPlayerNumber() + Config\Config::$forIndex) {
            $type = Config\Config::MATCH_SENIOR;
            if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                $type = Config\Config::MATCH_YOUTH;
            }
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Lineup/Player');
            $player = new \DOMDocument('1.0', 'UTF-8');
            $player->appendChild($player->importNode($nodeList->item($index), true));
            return new Lineup\Player($player, $type);
        }
        return null;
    }

    /**
     * Return iterator of players in final lineup
     *
     * @return \PHT\Xml\Match\Lineup\Player[]
     */
    public function getFinalPlayers()
    {
        $type = Config\Config::MATCH_SENIOR;
        if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
            $type = Config\Config::MATCH_YOUTH;
        }
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Lineup/Player');
        /** @var \PHT\Xml\Match\Lineup\Player[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Lineup\Player', $type);
        return $data;
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
            if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                $type = Config\Config::MATCH_YOUTH;
            } elseif (in_array($this->getMatchType(), array(10, 11, 12))) {
                $type = Config\Config::MATCH_NATIONAL;
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
        if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
            $type = Config\Config::MATCH_YOUTH;
        } elseif (in_array($this->getMatchType(), array(10, 11, 12))) {
            $type = Config\Config::MATCH_NATIONAL;
        }
        $nodeList = $this->getXml()->getElementsByTagName('Substitution');
        /** @var \PHT\Xml\Match\Lineup\Substitution[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Lineup\Substitution', $type);
        return $data;
    }

    /**
     * Return team total stars
     *
     * @param boolean $includeSubstitutes
     * @return float
     */
    public function getTotalStars($includeSubstitutes = true)
    {
        $total = 0;
        foreach ($this->getFinalPlayers() as $player) {
            if ($includeSubstitutes === true || ($includeSubstitutes === false && $player->getRole() >= 100 && $player->getRole() <= 113)) {
                $total += $player->getRatingStars();
            }
        }
        return $total;
    }

    /**
     * Return team total stars at end of match
     *
     * @param boolean $includeSubstitutes
     * @return float
     */
    public function getTotalStarsAtEndOfMatch($includeSubstitutes = true)
    {
        if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
            return null;
        }
        $total = 0;
        foreach ($this->getFinalPlayers() as $player) {
            if ($includeSubstitutes === true || ($includeSubstitutes === false && $player->getRole() >= 100 && $player->getRole() <= 113)) {
                $total += $player->getRatingStarsAtEndOfMatch();
            }
        }
        return $total;
    }
}
