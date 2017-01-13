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

namespace PHT\Xml\Tournaments\Ladders;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Listing extends Xml\File
{
    private $teamId;

    /**
     * @param string $xml
     * @param integer $teamId
     */
    public function __construct($xml, $teamId)
    {
        parent::__construct($xml);
        $this->teamId = $teamId;
    }

    /**
     * Return senior team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * Return senior team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        if ($this->teamId !== null) {
            return Wrapper\Team\Senior::team($this->teamId);
        }
        return null;
    }

    /**
     * Return number of tournaments
     *
     * @return integer
     */
    public function getLadderNumber()
    {
        return $this->getXml()->getElementsByTagName('Ladder')->length;
    }

    /**
     * Return ladder chunk object
     *
     * @param integer $index
     * @return \PHT\Xml\Tournaments\Ladders\Chunk
     */
    public function getLadder($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getLadderNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Ladder');
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Chunk($node, $this->teamId);
        }
        return null;
    }

    /**
     * Return iterator of ladder chunk objects
     *
     * @return \PHT\Xml\Tournaments\Ladders\Chunk[]
     */
    public function getLadders()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Ladder');
        /** @var \PHT\Xml\Tournaments\Ladders\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Tournaments\Ladders\Chunk', $this->teamId);
        return $data;
    }
}
