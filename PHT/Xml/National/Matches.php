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

namespace PHT\Xml\National;

use PHT\Xml;
use PHT\Utils;
use PHT\Config;

class Matches extends Xml\HTSupporter
{
    /**
     * Return if listing contains u20 matches, if no it's NT matches
     *
     * @return boolean
     */
    public function isU20Matches()
    {
        return $this->getXml()->getElementsByTagName('LeagueOfficeTypeID')->item(0)->nodeValue == 4;
    }

    /**
     * Return match number
     *
     * @return integer
     */
    public function getMatchNumber()
    {
        return $this->getXml()->getElementsByTagName('Match')->length;
    }

    /**
     * Return national match object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\National
     */
    public function getMatch($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getMatchNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Match');
            $match = new \DOMDocument('1.0', 'UTF-8');
            $match->appendChild($match->importNode($nodeList->item($index), true));
            return new Xml\Match\National($match, $this->isU20Matches());
        }
        return null;
    }

    /**
     * Return iterator of national match objects
     *
     * @return \PHT\Xml\Match\National[]
     */
    public function getMatches()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Match');
        /** @var \PHT\Xml\Match\National[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\National', $this->isU20Matches());
        return $data;
    }
}
