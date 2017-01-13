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

class Flags extends Senior
{
    /**
     * Return number of home flags
     *
     * @return integer
     */
    public function getHomeFlagNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query("//HomeFlags/Flag")->length;
    }

    /**
     * Return number of away flags
     *
     * @return integer
     */
    public function getAwayFlagNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query("//AwayFlags/Flag")->length;
    }

    /**
     * Return flag object
     *
     * @param integer $index
     * @return \PHT\Xml\Flag
     */
    public function getHomeFlag($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getHomeFlagNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query("//HomeFlags/Flag");
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Xml\Flag($node);
        }
        return null;
    }

    /**
     * Return iterator of flag objects
     *
     * @return \PHT\Xml\Flag[]
     */
    public function getHomeFlags()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//HomeFlags/Flag");
        /** @var \PHT\Xml\Flag[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Flag');
        return $data;
    }

    /**
     * Return flag object
     *
     * @param integer $index
     * @return \PHT\Xml\Flag
     */
    public function getAwayFlag($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getAwayFlagNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query("//AwayFlags/Flag");
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Xml\Flag($node);
        }
        return null;
    }

    /**
     * Return iterator of flag objects
     *
     * @return \PHT\Xml\Flag[]
     */
    public function getAwayFlags()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//AwayFlags/Flag");
        /** @var \PHT\Xml\Flag[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Flag');
        return $data;
    }
}
