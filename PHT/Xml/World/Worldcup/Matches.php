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

namespace PHT\Xml\World\Worldcup;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Matches extends Xml\File
{
    /**
     * Return cup id
     *
     * @return integer
     */
    public function getCupId()
    {
        return $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
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
     * Return match round
     *
     * @return integer
     */
    public function getMatchRound()
    {
        return $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
    }

    /**
     * Return cup series unit id
     *
     * @return integer
     */
    public function getGroupId()
    {
        return $this->getXml()->getElementsByTagName('CupSeriesUnitID')->item(0)->nodeValue;
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

    /**
     * Return round number
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
            return new Round($round);
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
}
