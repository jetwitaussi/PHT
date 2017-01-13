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
use PHT\Config;
use PHT\Utils;

class Worldcup extends Xml\File
{
    /**
     * @param string $xml
     */
    public function __construct($xml)
    {
        parent::__construct($xml);

        $xpath = new \DOMXPath($this->getXml());
        $this->getXml()->getElementsByTagName('HattrickData')->item(0)->appendChild($this->getXml()->createElement('Groups'));
        $groups = $this->getXml()->getElementsByTagName('Groups')->item(0);
        foreach ($this->getTeams() as $team) {
            if (!$xpath->query('//Group[@Id="' . $team->getGroupId() . '"]')->length) {
                $group = $this->getXml()->createElement('Group');
                $group->setAttributeNode(new \DOMAttr('Id', $team->getGroupId()));
                $group->setAttributeNode(new \DOMAttr('Name', $team->getGroupName()));
                $groups->appendChild($group);
            }
            $xpath->query('//Group[@Id="' . $team->getGroupId() . '"]')->item(0)->appendChild($this->getXml()->importNode($team->getXml()->documentElement, true));
        }
    }

    /**
     * Return worldcup id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
    }

    /**
     * Return worldcup season
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
    }

    /**
     * Return worldcup current round
     *
     * @return integer
     */
    public function getCurrentRound()
    {
        return $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
    }

    /**
     * Return number of teams
     *
     * @return integer
     */
    public function getTeamNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//WorldCupScores/Team')->length;
    }

    /**
     * Return worldcup team object
     *
     * @param integer $index
     * @return \PHT\Xml\World\Worldcup\Team
     */
    public function getTeam($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getTeamNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//WorldCupScores/Team');
            $team = new \DOMDocument('1.0', 'UTF-8');
            $team->appendChild($team->importNode($nodeList->item($index), true));
            return new Worldcup\Team($team);
        }
        return null;
    }

    /**
     * Return iterator of worldcup team objects
     *
     * @return \PHT\Xml\World\Worldcup\Team[]
     */
    public function getTeams()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//WorldCupScores/Team');
        /** @var \PHT\Xml\World\Worldcup\Team[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\World\Worldcup\Team');
        return $data;
    }

    /**
     * Return number of rounds
     *
     * @return integer
     */
    public function getRoundNumber()
    {
        return $this->getXml()->getElementsByTagName('Round')->length;
    }

    /**
     * Return worldcup round object
     *
     * @param integer $index
     * @return \PHT\Xml\World\Worldcup\Round
     */
    public function getRound($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getRoundNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Round');
            $round = new \DOMDocument('1.0', 'UTF-8');
            $round->appendChild($round->importNode($nodeList->item($index), true));
            return new WorldCup\Round($round);
        }
        return null;
    }

    /**
     * Return iterator of worldcup round objects
     *
     * @return \PHT\Xml\World\Worldcup\Round[]
     */
    public function getRounds()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Round');
        /** @var \PHT\Xml\World\Worldcup\Round[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\World\Worldcup\Round');
        return $data;
    }

    /**
     * Return number of teams
     *
     * @return integer
     */
    public function getGroupNumber()
    {
        return $this->getXml()->getElementsByTagName('Group')->length;
    }

    /**
     * Return worldcup group object
     *
     * @param integer $index
     * @return \PHT\Xml\World\Worldcup\Group
     */
    public function getGroup($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getGroupNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Group');
            $group = new \DOMDocument('1.0', 'UTF-8');
            $group->appendChild($group->importNode($nodeList->item($index), true));
            return new Worldcup\Group($group);
        }
        return null;
    }

    /**
     * Return iterator of worldcup team objects
     *
     * @return \PHT\Xml\World\Worldcup\Group[]
     */
    public function getGroups()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Group');
        /** @var \PHT\Xml\World\Worldcup\Group[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\World\Worldcup\Group');
        return $data;
    }
}
