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

class Matches extends Xml\File
{
    /**
     * @param string $xml
     */
    public function __construct($xml)
    {
        parent::__construct($xml);

        $nodes = $this->getXml()->getElementsByTagName('Match');
        for ($i = 0; $i < $nodes->length; $i++) {
            $nodes->item($i)->appendChild(new \DOMNode('SourceSystem', Config\Config::MATCH_TOURNAMENT));
        }
    }

    /**
     * Return number of matches
     *
     * @return integer
     */
    public function getMatchNumber()
    {
        return $this->getXml()->getElementsByTagName('Match')->length;
    }

    /**
     * Return tournament match object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Chunk
     */
    public function getMatch($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getMatchNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Match');
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Xml\Match\Chunk($node);
        }
        return null;
    }

    /**
     * Return iterator of tournament match objects
     *
     * @return \PHT\Xml\Match\Chunk[]
     */
    public function getMatches()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Match');
        /** @var \PHT\Xml\Match\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Chunk');
        return $data;
    }
}
