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

class Listing extends Xml\File
{
    /**
     * Return number of tournaments
     *
     * @return integer
     */
    public function getTournamentNumber()
    {
        return $this->getXml()->getElementsByTagName('Tournament')->length;
    }

    /**
     * Return tournament object
     *
     * @param integer $index
     * @return \PHT\Xml\Tournaments\Tournament
     */
    public function getTournament($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getTournamentNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Tournament');
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Tournament($node);
        }
        return null;
    }

    /**
     * Return iterator of tournament objects
     *
     * @return \PHT\Xml\Tournaments\Tournament[]
     */
    public function getTournaments()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Tournament');
        /** @var \PHT\Xml\Tournaments\Tournament[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Tournaments\Tournament');
        return $data;
    }
}
