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

namespace PHT\Xml\Team\Youth\Scout;

use PHT\Xml;
use PHT\Wrapper;
use PHT\Config;
use PHT\Utils;

class Call extends Xml\Base
{
    private $teamId;

    /**
     * @param \DOMDocument $xml
     * @param integer $teamId
     */
    public function __construct($xml, $teamId)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->teamId = $teamId;
    }

    /**
     * Return scout id
     *
     * @return integer
     */
    public function getScoutId()
    {
        return $this->getXml()->getElementsByTagName('ScoutId')->item(0)->nodeValue;
    }

    /**
     * Return scout
     *
     * @return \PHT\Xml\Team\Youth\Scout
     */
    public function getScout()
    {
        $scouts = Wrapper\Team\Youth::scouts($this->teamId);
        foreach ($scouts->getScouts() as $scout) {
            if ($scout->getId() == $this->getScoutId()) {
                return $scout;
            }
        }
        return null;
    }

    /**
     * Return scout name
     *
     * @return string
     */
    public function getScoutName()
    {
        return $this->getXml()->getElementsByTagName('ScoutName')->item(0)->nodeValue;
    }

    /**
     * Return scouting region id
     *
     * @return integer
     */
    public function getRegionId()
    {
        return $this->getXml()->getElementsByTagName('ScoutingRegionID')->item(0)->nodeValue;
    }

    /**
     * Return scouting region
     *
     * @return \PHT\Xml\World\Region
     */
    public function getRegion()
    {
        return Wrapper\World::region($this->getRegionId());
    }

    /**
     * Return number of comment
     *
     * @return integer
     */
    public function getCommentNumber()
    {
        return $this->getXml()->getElementsByTagName('ScoutComment')->length;
    }

    /**
     * Get scout comment
     *
     * @return \PHT\Xml\Team\Youth\Scout\Comment
     */
    public function getComment($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getCommentNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query("//ScoutComment");
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Comment($node);
        }
        return null;
    }

    /**
     * Get iterator of scout comment objects
     *
     * @return \PHT\Xml\Team\Youth\Scout\Comment[]
     */
    public function getComments()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//ScoutComment");
        /** @var \PHT\Xml\Team\Youth\Scout\Comment[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Team\Youth\Scout\Comment');
        return $data;
    }
}
