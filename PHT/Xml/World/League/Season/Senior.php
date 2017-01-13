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

namespace PHT\Xml\World\League\Season;

use PHT\Xml;
use PHT\Config;
use PHT\Wrapper;

class Senior extends Xml\File
{
    /**
     * Return senior league id
     *
     * @return integer
     */
    public function getSeniorLeagueId()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevelUnitID')->item(0)->nodeValue;
    }

    /**
     * Get senior league

     * @return \PHT\Xml\World\League\Senior
     */
    public function getSeniorLeague()
    {
        return Wrapper\World\League::senior($this->getSeniorLeagueId());
    }

    /**
     * Return senior league name
     *
     * @return integer
     */
    public function getSeniorLeagueName()
    {
        return $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
    }

    /**
     * Return season number
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
    }

    /**
     * Get round details
     *
     * @param integer $id
     * @return \PHT\Xml\World\League\Season\Round
     */
    public function getRound($id)
    {
        $id = round($id);
        if ($id >= Config\Config::$forIndex && $id < 14 + Config\Config::$forIndex) {
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query("//MatchRound[.='" . $id . "']");
            $matches = new \DOMDocument('1.0', 'UTF-8');
            foreach ($nodeList as $node) {
                $matches->appendChild($matches->importNode($node->parentNode, true));
            }
            return new Xml\World\League\Season\Round($matches, Config\Config::SENIOR);
        }
        return null;
    }

    /**
     * Get last played round details
     *
     * @return \PHT\Xml\World\League\Season\Round
     */
    public function getLastRound()
    {
        foreach (array_reverse($this->getRounds()) as $round) {
            /** @var \PHT\Xml\World\League\Season\Round $round */
            if ($round->getMatch(Config\Config::$forIndex)->getHomeGoals() !== null) {
                return $round;
            }
        }
        return null;
    }

    /**
     * Get next round details
     *
     * @return \PHT\Xml\World\League\Season\Round
     */
    public function getNextRound()
    {
        foreach ($this->getRounds() as $round) {
            if ($round->getMatch(Config\Config::$forIndex)->getHomeGoals() === null) {
                return $round;
            }
        }
        return null;
    }

    /**
     * Get season rounds
     *
     * @return \PHT\Xml\World\League\Season\Round[]
     */
    public function getRounds()
    {
        $rounds = array();
        for ($i = Config\Config::$forIndex; $i < 14 + Config\Config::$forIndex; $i++) {
            $rounds[] = $this->getRound($i);
        }
        return $rounds;
    }

    /**
     * Get standings
     *
     * @return \PHT\Xml\World\League\Season\Team[]
     */
    public function getStandings()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//Match");
        $tmp = array();
        foreach ($nodeList as $node) {
            $matches = new \DOMDocument('1.0', 'UTF-8');
            $matches->appendChild($matches->importNode($node, true));
            $homeId = $matches->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
            $awayId = $matches->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;

            if (!isset($tmp[$homeId])) {
                $tmp[$homeId] = array('name' => '', 'goalsfor' => 0, 'goalsagainst' => 0, 'won' => 0, 'lost' => 0, 'draw' => 0, 'points' => 0, 'played' => 0);
            }
            if (!isset($tmp[$awayId])) {
                $tmp[$awayId] = array('name' => '', 'goalsfor' => 0, 'goalsagainst' => 0, 'won' => 0, 'lost' => 0, 'draw' => 0, 'points' => 0, 'played' => 0);
            }

            $tmp[$homeId]['name'] = $matches->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
            $tmp[$awayId]['name'] = $matches->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
            if ($matches->getElementsByTagName('HomeGoals')->length) {
                $homeGoals = $matches->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
                $awayGoals = $matches->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
                $tmp[$homeId]['goalsfor'] += (int)$homeGoals;
                $tmp[$homeId]['goalsagainst'] += (int)$awayGoals;
                $tmp[$awayId]['goalsfor'] += (int)$awayGoals;
                $tmp[$awayId]['goalsagainst'] += (int)$homeGoals;
                $tmp[$homeId]['played'] ++;
                $tmp[$awayId]['played'] ++;
                if ($homeGoals > $awayGoals) {
                    $tmp[$homeId]['won'] ++;
                    $tmp[$homeId]['points'] += 3;
                    $tmp[$awayId]['lost'] ++;
                } elseif ($homeGoals < $awayGoals) {
                    $tmp[$awayId]['won'] ++;
                    $tmp[$awayId]['points'] += 3;
                    $tmp[$homeId]['lost'] ++;
                } elseif ($homeGoals == $awayGoals) {
                    $tmp[$awayId]['points'] ++;
                    $tmp[$homeId]['points'] ++;
                    $tmp[$homeId]['draw'] ++;
                    $tmp[$awayId]['draw'] ++;
                }
            }
        }
        $list = array();
        foreach ($tmp as $id => $data) {
            $value = str_pad($data['points'], 2, 0, STR_PAD_LEFT) . substr(str_pad((((500 + ($data['goalsfor'] - $data['goalsagainst'])) + ($data['goalsfor'] / 100)) / 1000), 10, 0, STR_PAD_RIGHT), 1);
            $list[$value . '-' . $data['name']] = new Xml\World\League\Season\Team($id, $data, Config\Config::SENIOR);
        }
        krsort($list);
        $teams = array();
        foreach ($list as $team) {
            $teams[] = $team;
        }
        unset($tmp, $list);
        return $teams;
    }
}
