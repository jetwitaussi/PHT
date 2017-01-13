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

namespace PHT\Xml\Team;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Fans extends Xml\File
{
    /**
     * Return fan club ib
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('FanclubId')->item(0)->nodeValue;
    }

    /**
     * Return fan club name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('FanclubName')->item(0)->nodeValue;
    }

    /**
     * Return fan mood
     *
     * @return integer
     */
    public function getMood()
    {
        return $this->getXml()->getElementsByTagName('FanMood')->item(0)->nodeValue;
    }

    /**
     * Return fan seasonExpectation
     *
     * @return integer
     */
    public function getSeasonExpectation()
    {
        return $this->getXml()->getElementsByTagName('FanSeasonExpectation')->item(0)->nodeValue;
    }

    /**
     * Return fans number
     *
     * @return integer
     */
    public function getMembers()
    {
        return $this->getXml()->getElementsByTagName('Members')->item(0)->nodeValue;
    }

    /**
     * Return number of last played matches
     *
     * @return integer
     */
    public function getLastMatchNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//PlayedMatches/Match')->length;
    }

    /**
     * Return chunk match object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Chunk
     */
    public function getLastMatch($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getLastMatchNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//PlayedMatches/Match');
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Xml\Match\Chunk($node);
        }
        return null;
    }

    /**
     * Return iterator of chunk match objects
     *
     * @return \PHT\Xml\Match\Chunk[]
     */
    public function getLastMatches()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//PlayedMatches/Match');
        /** @var \PHT\Xml\Match\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Chunk');
        return $data;
    }

    /**
     * Return number of upcoming matches
     *
     * @return integer
     */
    public function getNextMatchNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//UpcomingMatches/Match')->length;
    }

    /**
     * Return chunk match object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Chunk
     */
    public function getNextMatch($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getNextMatchNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//UpcomingMatches/Match');
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Xml\Match\Chunk($node);
        }
        return null;
    }

    /**
     * Return iterator of chunk match objects
     *
     * @return \PHT\Xml\Match\Chunk[]
     */
    public function getNextMatches()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//UpcomingMatches/Match');
        /** @var \PHT\Xml\Match\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Chunk');
        return $data;
    }
}
