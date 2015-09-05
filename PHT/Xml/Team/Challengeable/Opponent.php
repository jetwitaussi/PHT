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

namespace PHT\Xml\Team\Challengeable;

use PHT\Xml;
use PHT\Wrapper;

class Opponent extends Xml\Base
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
     * Return if team is challengeable
     *
     * @return boolean
     */
    public function isChallengeable()
    {
        return strtolower($this->getXml()->getElementsByTagName('IsChallengeable')->item(0)->nodeValue) == 'true';
    }

    /**
     * Return user id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->getXml()->getElementsByTagName('UserId')->item(0)->nodeValue;
    }

    /**
     * Return user
     *
     * @return \PHT\Xml\User
     */
    public function getUser()
    {
        return Wrapper\User::user($this->getUserId());
    }

    /**
     * Return team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
    }

    /**
     * Return team
     * /!\ May be wrong as it always return senior team even if national team is requested
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
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
     * Return team logo url
     *
     * @return string
     */
    public function getLogoUrl()
    {
        return $this->getXml()->getElementsByTagName('LogoURL')->item(0)->nodeValue;
    }
}
