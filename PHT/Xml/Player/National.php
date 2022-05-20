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

namespace PHT\Xml\Player;

use PHT\Xml;
use PHT\Wrapper;

class National extends Xml\Base
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
     * Return player id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('PlayerName')->item(0)->nodeValue;
    }

    /**
     * Return player cards number
     *
     * @return string
     */
    public function getCards()
    {
        return $this->getXml()->getElementsByTagName('Cards')->item(0)->nodeValue;
    }

    /**
     * Return player specialty
     *
     * @return integer
     */
    public function getSpecialty()
    {
        return $this->getXml()->getElementsByTagName('Specialty')->item(0)->nodeValue;
    }

    /**
     * Get full player details
     *
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Player\Senior
     */
    public function getPlayer($includeMatchInfo = true)
    {
        return Wrapper\Player\Senior::player($this->getId(), $includeMatchInfo);
    }

    /**
     * Return user avatar
     *
     * @return \PHT\Xml\Avatar
     */
    public function getAvatar()
    {
        $node = new \DOMDocument('1.0', 'UTF-8');
        $node->appendChild($node->importNode($this->getXml()->getElementsByTagName('Avatar')->item(0), true));
        return new Xml\Avatar($node);
    }
}
