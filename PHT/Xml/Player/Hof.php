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
use PHT\Utils;
use PHT\Wrapper;

class Hof extends Xml\Base
{
    private $teamId;

    /**
     * @param \DOMDocument $xml
     * @param integer $teamId
     */
    public function __construct($xml, $teamId = null)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->teamId = $teamId;
    }

    /**
     * Return player id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('PlayerId')->item(0)->nodeValue;
    }

    /**
     * Return player fristname
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
    }

    /**
     * Return player nickname
     *
     * @return string
     */
    public function getNickName()
    {
        return $this->getXml()->getElementsByTagName('NickName')->item(0)->nodeValue;
    }

    /**
     * Return player lastname
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
    }

    /**
     * Return player name
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->getFirstName();
        if ($this->getNickName() !== null && $this->getNickName() !== '') {
            $name .= ' ' . $this->getNickName();
        }
        $name .= ' ' . $this->getLastName();
        return $name;
    }

    /**
     * Return player age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
    }

    /**
     * Return player next birthday date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getNextBirthdayDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('NextBirthday')->item(0)->nodeValue, $format);
    }

    /**
     * Return player arrival date in team
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getArrivalDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('ArrivalDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return player expert type
     *
     * @return integer
     */
    public function getExpertType()
    {
        return $this->getXml()->getElementsByTagName('ExpertType')->item(0)->nodeValue;
    }

    /**
     * Return player arrival date in hof
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getHofDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('HofDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return player age when he came in hof
     *
     * @return integer
     */
    public function getHofAge()
    {
        return $this->getXml()->getElementsByTagName('HofAge')->item(0)->nodeValue;
    }

    /**
     * Return country id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->getXml()->getElementsByTagName('CountryID')->item(0)->nodeValue;
    }

    /**
     * Return country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country(null, $this->getCountryId());
    }

    /**
     * Return player avatar
     *
     * @return \PHT\Xml\Avatar
     */
    public function getAvatar()
    {
        $avatars = Wrapper\Team\Senior::hofavatars($this->teamId);
        foreach ($avatars->getAvatars() as $avatar) {
            if ($avatar->getPlayerId() == $this->getId()) {
                return $avatar;
            }
        }
        return null;
    }

}
