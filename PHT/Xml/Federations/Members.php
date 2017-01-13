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

namespace PHT\Xml\Federations;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Members extends Xml\File
{
    /**
     * Return federation id
     *
     * @return integer
     */
    public function getFederationId()
    {
        return $this->getXml()->getElementsByTagName('AllianceID')->item(0)->nodeValue;
    }

    /**
     * Return federation details
     *
     * @return \PHT\Xml\Federations\Federation
     */
    public function getFederation()
    {
        return Wrapper\Federation::detail($this->getFederationId());
    }

    /**
     * Return federation name
     *
     * @return string
     */
    public function getFederationName()
    {
        return $this->getXml()->getElementsByTagName('AllianceName')->item(0)->nodeValue;
    }

    /**
     * Return number of members
     *
     * @return integer
     */
    public function getMemberNumber()
    {
        return $this->getXml()->getElementsByTagName('Member')->length;
    }

    /**
     * Return member object
     *
     * @param integer $index
     * @return \PHT\Xml\Federations\Member
     */
    public function getMember($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getMemberNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Member');
            $member = new \DOMDocument('1.0', 'UTF-8');
            $member->appendChild($member->importNode($nodeList->item($index), true));
            return new Member($member, $this->getFederationId());
        }
        return null;
    }

    /**
     * Return iterator of member objects
     *
     * @return \PHT\Xml\Federations\Member[]
     */
    public function getMembers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Member');
        /** @var \PHT\Xml\Federations\Member[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Federations\Member', $this->getFederationId());
        return $data;
    }
}
