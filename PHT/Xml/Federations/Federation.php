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

class Federation extends Xml\File
{
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
     * Return federation abbreviation
     *
     * @return string
     */
    public function getAbbreviation()
    {
        if ($this->getXml()->getElementsByTagName('Abbreviation')->length) {
            return $this->getXml()->getElementsByTagName('Abbreviation')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return federation description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getXml()->getElementsByTagName('Description')->item(0)->nodeValue;
    }

    /**
     * Return federation top role name
     *
     * @return string
     */
    public function getTopRole()
    {
        return $this->getXml()->getElementsByTagName('TopRole')->item(0)->nodeValue;
    }

    /**
     * Return federation chief officer user id
     *
     * @return integer
     */
    public function getChiefUserId()
    {
        return $this->getXml()->getElementsByTagName('TopUserID')->item(0)->nodeValue;
    }

    /**
     * Return federation chief officer user
     *
     * @return \PHT\Xml\User
     */
    public function getChiefUser()
    {
        return Wrapper\User::user($this->getChiefUserId());
    }

    /**
     * Return federation chief officer name
     *
     * @return string
     */
    public function getChiefUserName()
    {
        return $this->getXml()->getElementsByTagName('TopLoginname')->item(0)->nodeValue;
    }

    /**
     * Return federation creation date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getCreationDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('CreationDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return federation homepage url
     *
     * @return string
     */
    public function getHomepageUrl()
    {
        if ($this->getXml()->getElementsByTagName('HomePageURL')->length) {
            return $this->getXml()->getElementsByTagName('HomePageURL')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return federation message
     *
     * @return string
     */
    public function getMessage()
    {
        if ($this->getXml()->getElementsByTagName('Message')->length) {
            return $this->getXml()->getElementsByTagName('Message')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return federation logo url
     *
     * @return string
     */
    public function getLogoUrl()
    {
        if ($this->getXml()->getElementsByTagName('LogoURL')->length) {
            return $this->getXml()->getElementsByTagName('LogoURL')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return federation members number
     *
     * @return integer
     */
    public function getTotalMembers()
    {
        return $this->getXml()->getElementsByTagName('NumberOfMembers')->item(0)->nodeValue;
    }

    /**
     * Return alliance languages number
     *
     * @return integer
     */
    public function getLanguageNumber()
    {
        return $this->getXml()->getElementsByTagName('Language')->length;
    }

    /**
     * Return i18n language object
     *
     * @param integer $index
     * @return \PHT\Xml\I18n\Language
     */
    public function getLanguage($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getLanguageNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Language');
            $lang = new \DOMDocument('1.0', 'UTF-8');
            $lang->appendChild($lang->importNode($nodeList->item($index), true));
            return new Xml\I18n\Language($lang);
        }
        return null;
    }

    /**
     * Return iterator of i18n language objects
     *
     * @return \PHT\Xml\I18n\Language[]
     */
    public function getLanguages()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Language');
        /** @var \PHT\Xml\I18n\Language[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\I18n\Language');
        return $data;
    }

    /**
     * Return number of logged users
     *
     * @return integer
     */
    public function getLoggedUserNumber()
    {
        return $this->getXml()->getElementsByTagName('LoggedInUsers')->item(0)->getAttribute('Count');
    }

    /**
     * Return user chunk object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Chunk
     */
    public function getLoggedUser($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getLoggedUserNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//LoggedInUser');
            $user = new \DOMDocument('1.0', 'UTF-8');
            $user->appendChild($user->importNode($nodeList->item($index), true));
            return new Xml\User\Chunk($user);
        }
        return null;
    }

    /**
     * Return iterator of user chunk objects
     *
     * @return \PHT\Xml\User\Chunk[]
     */
    public function getLoggedUsers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//LoggedInUser');
        /** @var \PHT\Xml\User\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\User\Chunk');
        return $data;
    }

    /**
     * Return federation rules
     *
     * @return string
     */
    public function getRules()
    {
        return $this->getXml()->getElementsByTagName('Rules')->item(0)->nodeValue;
    }

    /**
     * Return user role id
     *
     * @return integer
     */
    public function getUserRoleId()
    {
        return $this->getXml()->getElementsByTagName('RoleID')->item(0)->nodeValue;
    }

    /**
     * Return user role name
     *
     * @return string
     */
    public function getUserRoleName()
    {
        return $this->getXml()->getElementsByTagName('RoleName')->item(0)->nodeValue;
    }

    /**
     * Return number of awaiting requests
     *
     * @return integer
     */
    public function getTotalAwaitingRequests()
    {
        if ($this->getXml()->getElementsByTagName('AwaitingRequests')->length) {
            return $this->getXml()->getElementsByTagName('AwaitingRequests')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Does user have right to accept requests ?
     *
     * @return boolean
     */
    public function userHasRightToAcceptRequests()
    {
        if ($this->getXml()->getElementsByTagName('HasRightToAcceptRequests')->length) {
            return strtolower($this->getXml()->getElementsByTagName('HasRightToAcceptRequests')->item(0)->nodeValue) == "true";
        }
        return false;
    }

    /**
     * Does user have right to edit public properties ?
     *
     * @return boolean
     */
    public function userHasRightToEditPublicProperties()
    {
        if ($this->getXml()->getElementsByTagName('HasRightToEditPublicProperties')->length) {
            return strtolower($this->getXml()->getElementsByTagName('HasRightToEditPublicProperties')->item(0)->nodeValue) == "true";
        }
        return false;
    }

    /**
     * Does user have right to exclude members ?
     *
     * @return boolean
     */
    public function userHasRightToExcludeMembers()
    {
        if ($this->getXml()->getElementsByTagName('HasRightToExcludeMembers')->length) {
            return strtolower($this->getXml()->getElementsByTagName('HasRightToExcludeMembers')->item(0)->nodeValue) == "true";
        }
        return false;
    }

    /**
     * Does user have right to create roles ?
     *
     * @return boolean
     */
    public function userHasRightToCreateRoles()
    {
        if ($this->getXml()->getElementsByTagName('HasRightToCreateRoles')->length) {
            return strtolower($this->getXml()->getElementsByTagName('HasRightToCreateRoles')->item(0)->nodeValue) == "true";
        }
        return false;
    }

    /**
     * Does user have right to edit roles ?
     *
     * @return boolean
     */
    public function userHasRightToEditRoles()
    {
        if ($this->getXml()->getElementsByTagName('HasRightToEditRoles')->length) {
            return strtolower($this->getXml()->getElementsByTagName('HasRightToEditRoles')->item(0)->nodeValue) == "true";
        }
        return false;
    }

    /**
     * Does user have right to moderate ?
     *
     * @return boolean
     */
    public function userHasRightToModerate()
    {
        if ($this->getXml()->getElementsByTagName('HasRightToModerate')->length) {
            return strtolower($this->getXml()->getElementsByTagName('HasRightToModerate')->item(0)->nodeValue) == "true";
        }
        return false;
    }

    /**
     * Does user have right to send newsletter ?
     *
     * @return boolean
     */
    public function userHasRightToSendNewsLetter()
    {
        if ($this->getXml()->getElementsByTagName('HasRightToSendNewsLetter')->length) {
            return strtolower($this->getXml()->getElementsByTagName('HasRightToSendNewsLetter')->item(0)->nodeValue) == "true";
        }
        return false;
    }

    /**
     * Does user have right to create polls ?
     *
     * @return boolean
     */
    public function userHasRightToCreatePolls()
    {
        if ($this->getXml()->getElementsByTagName('HasRightToCreatePolls')->length) {
            return strtolower($this->getXml()->getElementsByTagName('HasRightToCreatePolls')->item(0)->nodeValue) == "true";
        }
        return false;
    }

    /**
     * Does user have right to edit rules ?
     *
     * @return boolean
     */
    public function userHasRightToEditRules()
    {
        if ($this->getXml()->getElementsByTagName('HasRightToEditRules')->length) {
            return strtolower($this->getXml()->getElementsByTagName('HasRightToEditRules')->item(0)->nodeValue) == "true";
        }
        return false;
    }

    /**
     * Return dissolution date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDissolutionDate($format = null)
    {
        if ($this->getXml()->getElementsByTagName('DissolutionEndDate')->length) {
            return Utils\Date::convert($this->getXml()->getElementsByTagName('DissolutionEndDate'), $format);
        }
        return null;
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
