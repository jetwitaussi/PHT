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
use PHT\Utils;

class Round extends Xml\Base
{
    private $type;

    /**
     * @param \DOMDocument $xml
     */
    public function __construct($xml, $type)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->type = $type;
    }

    /**
     * Return number of matches
     *
     * @return integer
     */
    public function getMatchNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Match');
        return $nodeList->length;
    }

    /**
     * Get round match object
     *
     * @param integer $index
     * @return \PHT\Xml\World\League\Season\Match
     */
    public function getMatch($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getMatchNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $match = new \DOMDocument('1.0', 'UTF-8');
            $match->appendChild($match->importNode($this->getXml()->getElementsByTagName('Match')->item($index), true));
            return new Xml\World\League\Season\Match($match, $this->type);
        }
        return null;
    }

    /**
     * Return iterator of round match objects
     *
     * @return \PHT\Xml\World\League\Season\Match[]
     */
    public function getMatches()
    {
        $nodes = $this->getXml()->getElementsByTagName('Match');
        /** @var \PHT\Xml\World\League\Season\Match[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\World\League\Season\Match', $this->type);
        return $data;
    }
}
