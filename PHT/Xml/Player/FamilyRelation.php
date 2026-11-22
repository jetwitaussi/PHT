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

class FamilyRelation extends Xml\Base
{
    const SOURCE_SENIOR = 1;
    const SOURCE_HOF = 2;
    const SOURCE_YOUTH = 3;
    const SOURCE_STAFF = 4;

    const BROTHER = 1;
    const FATHER = 2;
    const GRANDFATHER = 3;
    const SON = 4;
    const GRANDSON = 5;

    /**
     * @param \DOMDocument $xml
     */
    public function __construct($xml)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
    }

    /**
     * Return related player id, use getSourceId() to know which kind of player it is
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->getXml()->getElementsByTagName('RelatedPlayerId')->item(0)->nodeValue;
    }

    /**
     * Return related player full name
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->getXml()->getElementsByTagName('RelatedPlayerName')->item(0)->nodeValue;
    }

    /**
     * Return kind of related player (1: senior player, 2: hall of fame player, 3: youth player, 4: staff member)
     *
     * @return integer
     */
    public function getSourceId()
    {
        return $this->getXml()->getElementsByTagName('RelatedPlayerSourceId')->item(0)->nodeValue;
    }

    /**
     * Return if related player is a senior player
     *
     * @return boolean
     */
    public function isSeniorPlayer()
    {
        return $this->getSourceId() == self::SOURCE_SENIOR;
    }

    /**
     * Return if related player is a hall of fame player
     *
     * @return boolean
     */
    public function isHofPlayer()
    {
        return $this->getSourceId() == self::SOURCE_HOF;
    }

    /**
     * Return if related player is a youth player
     *
     * @return boolean
     */
    public function isYouthPlayer()
    {
        return $this->getSourceId() == self::SOURCE_YOUTH;
    }

    /**
     * Return if related player is a staff member
     *
     * @return boolean
     */
    public function isStaffMember()
    {
        return $this->getSourceId() == self::SOURCE_STAFF;
    }

    /**
     * Return relation type (1: brother, 2: father, 3: grandfather, 4: son, 5: grandson)
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->getXml()->getElementsByTagName('RelationshipTypeId')->item(0)->nodeValue;
    }

    /**
     * Return if related player is the brother
     *
     * @return boolean
     */
    public function isBrother()
    {
        return $this->getTypeId() == self::BROTHER;
    }

    /**
     * Return if related player is the father
     *
     * @return boolean
     */
    public function isFather()
    {
        return $this->getTypeId() == self::FATHER;
    }

    /**
     * Return if related player is the grandfather
     *
     * @return boolean
     */
    public function isGrandfather()
    {
        return $this->getTypeId() == self::GRANDFATHER;
    }

    /**
     * Return if related player is the son
     *
     * @return boolean
     */
    public function isSon()
    {
        return $this->getTypeId() == self::SON;
    }

    /**
     * Return if related player is the grandson
     *
     * @return boolean
     */
    public function isGrandson()
    {
        return $this->getTypeId() == self::GRANDSON;
    }

    /**
     * Return related player object, only for senior and youth players
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getPlayer()
    {
        if ($this->isSeniorPlayer()) {
            return Wrapper\Player\Senior::player($this->getPlayerId());
        } elseif ($this->isYouthPlayer()) {
            return Wrapper\Player\Youth::player($this->getPlayerId());
        }
        return null;
    }
}
