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

class Cup extends Xml\File
{
    /**
     * Return cup id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
    }

    /**
     * Return cup season
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->getXml()->getElementsByTagName('CupSeason')->item(0)->nodeValue;
    }

    /**
     * Return cup round
     *
     * @return integer
     */
    public function getRound()
    {
        return $this->getXml()->getElementsByTagName('CupRound')->item(0)->nodeValue;
    }

    /**
     * Return cup name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('CupName')->item(0)->nodeValue;
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
     * Return match chunk object
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
            $match = new \DOMDocument('1.0', 'UTF-8');
            $match->appendChild($match->importNode($nodeList->item($index), true));
            return new Xml\Match\Chunk($match);
        }
        return null;
    }

    /**
     * Return iterator of match chunk objects
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
