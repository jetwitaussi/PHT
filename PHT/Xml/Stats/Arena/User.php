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

namespace PHT\Xml\Stats\Arena;

use PHT\Xml;
use PHT\Utils;
use PHT\Wrapper;

class User extends Xml\File
{
    /**
     * Return arena id
     *
     * @return integer
     */
    public function getArenaId()
    {
        return $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
    }

    /**
     * Return arena
     *
     * @return \PHT\Xml\Team\Arena
     */
    public function getArena()
    {
        return Wrapper\Team\Senior::arena($this->getArenaId());
    }

    /**
     * Return arena name
     *
     * @return string
     */
    public function getArenaName()
    {
        return $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
    }

    /**
     * Return match type
     *
     * @return string
     */
    public function getMatchType()
    {
        return $this->getXml()->getElementsByTagName('MatchTypes')->item(0)->nodeValue;
    }

    /**
     * Return start date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getStartDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('FirstDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return end date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getEndDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('LastDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return matches number
     *
     * @return string
     */
    public function getMatchNumber()
    {
        return $this->getXml()->getElementsByTagName('NumberOfMatches')->item(0)->nodeValue;
    }

    /**
     * Return arena visitor stats object
     *
     * @return \PHT\Xml\Stats\Arena\Visitors
     */
    public function getAverageVisitors()
    {
        return $this->getVisitors('VisitorsAverage');
    }

    /**
     * Return arena visitor stats object
     *
     * @return \PHT\Xml\Stats\Arena\Visitors
     */
    public function getMostVisitors()
    {
        return $this->getVisitors('VisitorsMost');
    }

    /**
     * Return arena visitor stats object
     *
     * @return \PHT\Xml\Stats\Arena\Visitors
     */
    public function getLeastVisitors()
    {
        return $this->getVisitors('VisitorsLeast');
    }

    /**
     * Return arena visitor stats object
     *
     * @param string $type
     * @return \PHT\Xml\Stats\Arena\Visitors
     */
    private function getVisitors($type)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//' . $type);
        $visitor = new \DOMDocument('1.0', 'UTF-8');
        $visitor->appendChild($visitor->importNode($nodeList->item(0), true));
        return new Visitors($visitor);
    }
}
