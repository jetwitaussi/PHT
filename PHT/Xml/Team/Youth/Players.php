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

namespace PHT\Xml\Team\Youth;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Players extends Xml\File
{
    /**
     * Return number of youth player
     *
     * @return integer
     */
    public function getPlayerNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//YouthPlayer");
        return $nodeList->length;
    }

    /**
     * Get player details
     *
     * @param integer $index
     * @return \PHT\Xml\Player\Youth
     */
    public function getPlayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getPlayerNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//YouthPlayer');
            $youth = new \DOMDocument('1.0', 'UTF-8');
            $youth->appendChild($youth->importNode($nodeList->item($index), true));
            return new Xml\Player\Youth($youth->saveXML());
        }
        return null;
    }

    /**
     * Get iterator of player objets
     *
     * @return \PHT\Xml\Player\Youth[]
     */
    public function getPlayers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//YouthPlayer');
        /** @var \PHT\Xml\Player\Youth[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Player\Youth');
        return $data;
    }
}
