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

namespace PHT\Xml\Team\Senior;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Hof extends Xml\File
{
    private $teamId;

    /**
     * @param string $xml
     * @param integer $teamId
     */
    public function __construct($xml, $teamId = null)
    {
        parent::__construct($xml);
        $this->teamId = $teamId;
    }

    /**
     * Return player number
     *
     * @return integer
     */
    public function getPlayerNumber()
    {
        return $this->getXml()->getElementsByTagName('Player')->length;
    }

    /**
     * Return hof player object
     *
     * @return \PHT\Xml\Player\Hof
     */
    public function getPlayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getPlayerNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query("//Player");
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Xml\Player\Hof($node, $this->teamId);
        }
        return null;
    }

    /**
     * Return iterator of hof player objects
     *
     * @return \PHT\Xml\Player\Hof[]
     */
    public function getPlayers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//Player");
        /** @var \PHT\Xml\Player\Hof[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Player\Hof', $this->teamId);
        return $data;
    }
}
