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
use PHT\Wrapper;

class Challenges extends Xml\File
{
    /**
     * Return team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return team
     * /!\ If no challenge found, team returned will always be a senior team even if national team is requested
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\National
     */
    public function getTeam()
    {
        $xpath = new \DOMXPath($this->getXml());
        if ($xpath->query('//FriendlyType[.="12"]')->length) {
            return Wrapper\National::team($this->getTeamId());
        }
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return team name
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return my challenges number
     *
     * @return integer
     */
    public function getSentOfferNumber()
    {
        return $this->getXml()->getElementsByTagName('Challenge')->length;
    }

    /**
     * Return match challenge object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Challenge
     */
    public function getSentOffer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getSentOfferNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Challenge');
            $challenge = new \DOMDocument('1.0', 'UTF-8');
            $challenge->appendChild($challenge->importNode($nodeList->item($index), true));
            return new Xml\Match\Challenge($challenge);
        }
        return null;
    }

    /**
     * Return iterator of match challenge objects
     *
     * @return \PHT\Xml\Match\Challenge
     */
    public function getSentOffers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Challenge');
        /** @var \PHT\Xml\Match\Challenge $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Challenge');
        return $data;
    }

    /**
     * Return offers challenges number
     *
     * @return integer
     */
    public function getReceivedOfferNumber()
    {
        return $this->getXml()->getElementsByTagName('Offer')->length;
    }

    /**
     * Return match challenge object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Challenge
     */
    public function getReceivedOffer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getReceivedOfferNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Offer');
            $challenge = new \DOMDocument('1.0', 'UTF-8');
            $challenge->appendChild($challenge->importNode($nodeList->item($index), true));
            return new Xml\Match\Challenge($challenge);
        }
        return null;
    }

    /**
     * Return iterator of match challenge objects
     *
     * @return \PHT\Xml\Match\Challenge[]
     */
    public function getReceivedOffers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Offer');
        /** @var \PHT\Xml\Match\Challenge[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Challenge');
        return $data;
    }
}
