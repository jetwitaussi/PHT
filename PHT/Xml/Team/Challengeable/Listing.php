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

namespace PHT\Xml\Team\Challengeable;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Listing extends Xml\File
{
    /**
     * @param string $xml
     */
    public function __construct($xml)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($xml);
        $xpath = new \DOMXPath($dom);
        $nodeList = $xpath->query('//ChallengeableResult');
        $teams = new \DOMDocument('1.0', 'UTF-8');
        $teams->appendChild($teams->importNode($nodeList->item(0), true));
        parent::__construct($teams->saveXML());
    }

    /**
     * Return number of opponent teams
     *
     * @return integer
     */
    public function getOpponentNumber()
    {
        return $this->getXml()->getElementsByTagName('Opponent')->length;
    }

    /**
     * Return opponent team object
     *
     * @param integer $index
     * @return \PHT\Xml\Team\Challengeable\Opponent
     */
    public function getOpponent($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getOpponentNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Opponent');
            $team = new \DOMDocument('1.0', 'UTF-8');
            $team->appendChild($team->importNode($nodeList->item($index), true));
            return new Opponent($team);
        }
        return null;
    }

    /**
     * Return iterator of opponent team objects
     *
     * @return \PHT\Xml\Team\Challengeable\Opponent[]
     */
    public function getOpponents()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Opponent');
        /** @var \PHT\Xml\Team\Challengeable\Opponent[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Team\Challengeable\Opponent');
        return $data;
    }
}
