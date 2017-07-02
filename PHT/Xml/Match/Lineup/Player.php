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

namespace PHT\Xml\Match\Lineup;

use PHT\Xml;
use PHT\Config;
use PHT\Wrapper;

class Player extends Xml\Base
{
    private $type;

    /**
     * @param \DOMDocument $xml
     * @param string $type
     */
    public function __construct($xml, $type)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->type = $type;
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
     * Return player details
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getPlayer()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Player\Youth::player($this->getId());
        }
        return Wrapper\Player\Senior::player($this->getId());
    }

    /**
     * Return player role id
     *
     * @return integer
     */
    public function getRole()
    {
		$tmp = $this->getXml()->getElementsByTagName('RoleID');
		if ($tmp->length) {
			return $tmp->item(0)->nodeValue;
		}
		return null;
    }

    /**
     * Return player name
     *
     * @return string
     */
    public function getName()
    {
		$tmp = $this->getXml()->getElementsByTagName('PlayerName');
		if ($tmp->length) {
			return $tmp->item(0)->nodeValue;
        }
        $name = $this->getFirstName();
        if ($this->getNickName() !== null && $this->getNickName() !== '') {
            $name .= ' ' . $this->getNickName();
        }
        $name .= ' ' . $this->getLastName();
		return strlen(trim($name)) ? $name : null;
    }

    /**
     * Return player first name
     *
     * @return string
     */
    public function getFirstName()
    {
		$tmp = $this->getXml()->getElementsByTagName('FirstName');
		if ($tmp->length) {
			return $tmp->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player last name
     *
     * @return string
     */
    public function getLastName()
    {
		$tmp = $this->getXml()->getElementsByTagName('LastName');
		if ($tmp->length) {
			return $tmp->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player nick name
     *
     * @return string
     */
    public function getNickName()
    {
		$tmp = $this->getXml()->getElementsByTagName('NickName');
		if ($tmp->length) {
			return $tmp->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player rating stars
     *
     * @return float
     */
    public function getRatingStars()
    {
        $tmp = $this->getXml()->getElementsByTagName('RatingStars');
        if ($tmp->length) {
            return $tmp->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player rating stars at end of game
     *
     * @return float
     */
    public function getRatingStarsAtEndOfMatch()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return null;
        }
        $tmp = $this->getXml()->getElementsByTagName('RatingStarsEndOfMatch');
        if ($tmp->length) {
            return $tmp->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player individual order code
     *
     * @return integer
     */
    public function getIndividualOrder()
    {
        $tmp = $this->getXml()->getElementsByTagName('Behaviour');
        if ($tmp->length) {
            return $tmp->item(0)->nodeValue;
        }
        return null;
    }
}
