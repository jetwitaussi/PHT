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
use PHT\Wrapper;

class Chunk extends Xml\Base
{
    /**
     * @param \DOMDocument $xml
     */
    public function __construct($xml)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
    }

    /**
     * Return federation id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('AllianceID')->item(0)->nodeValue;
    }

    /**
     * Return federation name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('AllianceName')->item(0)->nodeValue;
    }

    /**
     * Return federation description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getXml()->getElementsByTagName('AllianceDescription')->item(0)->nodeValue;
    }

    /**
     * Return federation details
     *
     * @return \PHT\Xml\Federations\Federation
     */
    public function getFederation()
    {
        return Wrapper\Federation::detail($this->getId());
    }

    /**
     * Return federation members
     *
     * @param string $onlyLetter
     * @return \PHT\Xml\Federations\Members
     */
    public function getMembers($onlyLetter = null)
    {
        return Wrapper\Federation::members($this->getId(), $onlyLetter);
    }

    /**
     * Return federation roles
     *
     * @return \PHT\Xml\Federations\Roles
     */
    public function getRoles()
    {
        return Wrapper\Federation::roles($this->getId());
    }
}
