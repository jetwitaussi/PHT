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

namespace PHT\Xml\Team\Match;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Archive extends Xml\File
{
    /**
     * Create an instance
     *
     * @param string $xml
     */
    public function __construct($xml)
    {
        parent::__construct($xml);

        if ($this->getXml()->getElementsByTagName('SourceSystem')->length) {
            return;
        }

        if ($this->isYouth()) {
            $ss = Config\Config::MATCH_YOUTH;
        } else {
            $ss = Config\Config::MATCH_SENIOR;
        }

        $nodeList = $this->getXml()->getElementsByTagName('Match');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $n = $nodeList->item($i);
            $n->appendChild(new \DOMElement('SourceSystem', $ss));
        }
    }

    /**
     * Return team id
     *
     * @return integer
     */
    public function getId()
    {
        $node = $this->getXml()->getElementsByTagName('TeamID');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return tzam name
     *
     * @return string
     */
    public function getName()
    {
        $node = $this->getXml()->getElementsByTagName('TeamName');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return if it's youth matches
     *
     * @return boolean
     */
    public function isYouth()
    {
        return strtolower($this->getXml()->getElementsByTagName('IsYouth')->item(0)->nodeValue) == 'true';
    }

    /**
     * Return number of match in list
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
     * Get chunk of match
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Chunk
     */
    public function getMatch($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getMatchNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $nodeList = $this->getXml()->getElementsByTagName('Match');
            $xmlMatch = new \DOMDocument('1.0', 'UTF-8');
            $xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($index), true));
            return new Xml\Match\Chunk($xmlMatch);
        }
        return null;
    }

    /**
     * Get iterator of chunk match objects
     *
     * @return \PHT\Xml\Match\Chunk[]
     */
    public function getMatches()
    {
        $nodeList = $this->getXml()->getElementsByTagName('Match');
        /** @var \PHT\Xml\Match\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Chunk');
        return $data;
    }
}
