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

namespace PHT\Xml\Tournaments;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class League extends Xml\File
{
    /**
     * Return tournament id
     *
     * @return integer
     */
    public function getTournamentId()
    {
        return $this->getXml()->getElementsByTagName('TournamentId')->item(0)->nodeValue;
    }

    /**
     * Return tournament
     *
     * @return \PHT\Xml\Tournaments\Tournament
     */
    public function getTournament()
    {
        return Wrapper\Tournament::tournament($this->getTournamentId());
    }

    /**
     * Return number of groups
     *
     * @return integer
     */
    public function getGroupNumber()
    {
        return $this->getXml()->getElementsByTagName('TournamentLeagueTable')->length;
    }

    /**
     * Return tournament group object
     *
     * @param integer $index
     * @return \PHT\Xml\Tournaments\Group
     */
    public function getGroup($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getGroupNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//TournamentLeagueTable');
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Group($node);
        }
        return null;
    }

    /**
     * Return iterator of tournament group objects
     *
     * @return \PHT\Xml\Tournaments\Group[]
     */
    public function getGroups()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//TournamentLeagueTable');
        /** @var \PHT\Xml\Tournaments\Group[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Tournaments\Group');
        return $data;
    }
}
