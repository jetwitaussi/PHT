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

namespace PHT\Xml\Player\Transfer;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class History extends Xml\File
{
    /**
     * Return start date of transfers list
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getStartDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('StartDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return end date of transfers list
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getEndDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('EndDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return player id
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player
     *
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Player\Senior
     */
    public function getPlayer($includeMatchInfo = true)
    {
        return Wrapper\Player\Senior::player($this->getPlayerId(), $includeMatchInfo);
    }

    /**
     * Return player name
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->getXml()->getElementsByTagName('PlayerName')->item(0)->nodeValue;
    }

    /**
     * Return transfers number
     *
     * @return integer
     */
    public function getTransferNumber()
    {
        return $this->getXml()->getElementsByTagName('Transfer')->length;
    }

    /**
     * Return transfer object
     *
     * @param integer $index
     * @return \PHT\Xml\Transfer
     */
    public function getTransfer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getTransferNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Transfer');
            $player = $xpath->query('//Player');
            $transfer = new \DOMDocument('1.0', 'UTF-8');
            $transfer->appendChild($transfer->importNode($nodeList->item($index), true));
            $transfer->appendChild($transfer->importNode($player->item(0), true));
            return new Xml\Transfer($transfer);
        }
        return null;
    }

    /**
     * Return iterator of transfer objects
     *
     * @return \PHT\Xml\Transfer[]
     */
    public function getTransfers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Transfer');
        $player = $xpath->query('//Player');
        $transfer = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $nodeList->item($i)->appendChild($player->item(0));
            $transfer->appendChild($transfer->importNode($nodeList->item($i), true));
        }
        $nodes = $transfer->getElementsByTagName('Transfer');
        /** @var \PHT\Xml\Transfer[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\Transfer');
        return $data;
    }
}
