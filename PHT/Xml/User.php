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

namespace PHT\Xml;

use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;
use PHT\Network;

class User extends HTSupporter
{
    /**
     * Return user id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('UserId')->item(0)->nodeValue;
    }

    /**
     * Return user name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('Loginname')->item(0)->nodeValue;
    }

    /**
     * Return language id
     *
     * @return integer
     */
    public function getLanguageId()
    {
        return $this->getXml()->getElementsByTagName('LanguageId')->item(0)->nodeValue;
    }

    /**
     * Return language name
     *
     * @return string
     */
    public function getLanguageName()
    {
        return $this->getXml()->getElementsByTagName('LanguageName')->item(0)->nodeValue;
    }

    /**
     * Return country id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->getXml()->getElementsByTagName('CountryId')->item(0)->nodeValue;
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
     * Return country name
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->getXml()->getElementsByTagName('CountryName')->item(0)->nodeValue;
    }

    /**
     * Return number of teams
     *
     * @param string $type
     * @return integer
     */
    public function getTeamNumber($type = null)
    {
        if ($type !== Team\Senior::INTERNATIONAL && $type !== Team\Senior::SECONDARY) {
            return $this->getXml()->getElementsByTagName('Team')->length;
        }
        $params = array('file' => 'teamdetails', 'version' => Config\Version::TEAMDETAILS, 'userID' => $this->getId());
        $url = Network\Request::buildUrl($params);
        $xml = Network\Request::fetchUrl($url);
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->loadXml($xml);
        $teams = $doc->getElementsByTagName('Team');
        $number = 0;
        for ($t = 0; $t < $teams->length; $t++) {
            $txml = new \DOMDocument('1.0', 'UTF-8');
            $txml->appendChild($txml->importNode($teams->item($t), true));
            $isPrimary = strtolower($txml->getElementsByTagName('IsPrimaryClub')->item(0)->nodeValue) == 'true';
            if ($isPrimary) {
                continue;
            }
            $isHti = $txml->getElementsByTagName('LeagueID')->item(0)->nodeValue == Config\Config::HTI_LEAGUE;
            if (($type == Team\Senior::INTERNATIONAL && $isHti) || ($type == Team\Senior::SECONDARY && !$isHti)) {
                $number++;
            }
        }
        return $number;
    }

    /**
     * Return compendium team object
     *
     * @param integer $index
     * @return \PHT\Xml\Compendium\Team
     */
    public function getTeam($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getTeamNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Team');
            $team = new \DOMDocument('1.0', 'UTF-8');
            $team->appendChild($team->importNode($nodeList->item($index), true));
            return new Compendium\Team($team);
        }
        return null;
    }

    /**
     * Return iterator of compendium team objects
     *
     * @return \PHT\Xml\Compendium\Team[]
     */
    public function getTeams()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Team');
        /** @var \PHT\Xml\Compendium\Team[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Compendium\Team');
        return $data;
    }

    /**
     * Return number of national teams
     *
     * @return integer
     */
    public function getNationalTeamNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query("//NationalTeamCoach/NationalTeam")->length;
    }

    /**
     * Return compendium national team object
     *
     * @param integer $index
     * @return \PHT\Xml\Compendium\National
     */
    public function getNationalTeam($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getNationalTeamNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//NationalTeamCoach/NationalTeam');
            $team = new \DOMDocument('1.0', 'UTF-8');
            $team->appendChild($team->importNode($nodeList->item($index), true));
            return new Compendium\National($team);
        }
        return null;
    }

    /**
     * Return iterator of compendium national team objects
     *
     * @return \PHT\Xml\Compendium\National[]
     */
    public function getNationalTeams()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//NationalTeamCoach/NationalTeam');
        /** @var \PHT\Xml\Compendium\National[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Compendium\National');
        return $data;
    }

    /**
     * Return number of national team assistant
     *
     * @return integer
     */
    public function getNationalTeamsAssistantNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query("//NationalTeamAssistant/NationalTeam")->length;
    }

    /**
     * Return compendium national team assistant object
     *
     * @param integer $index
     * @return \PHT\Xml\Compendium\National
     */
    public function getNationalTeamsAssistant($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getNationalTeamsAssistantNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//NationalTeamAssistant/NationalTeam');
            $team = new \DOMDocument('1.0', 'UTF-8');
            $team->appendChild($team->importNode($nodeList->item($index), true));
            return new Compendium\National($team);
        }
        return null;
    }

    /**
     * Return iterator of compendium national team assistant objects
     *
     * @return \PHT\Xml\Compendium\National[]
     */
    public function getNationalTeamsAssistants()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//NationalTeamAssistant/NationalTeam');
        /** @var \PHT\Xml\Compendium\National[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Compendium\National');
        return $data;
    }

    /**
     * Return user achievements object
     *
     * @return \PHT\Xml\User\Achievements
     */
    public function getAchievements()
    {
        return Wrapper\User::achievements($this->getId());
    }

    /**
     * Return user bookmarks object
     * /!\ Be careful: It does not necessarily return bookmarks for this user, but those of the connected user!
     *
     * @param integer $type (see \PHT\Config\Config BOOKMARK_* constants)
     * @return \PHT\Xml\User\Bookmarks
     */
    public function getBookmarks($type = Config\Config::BOOKMARK_ALL)
    {
        return Wrapper\User::bookmarks($type);
    }

    /**
     * Return connected user federations
     * /!\ Be careful: It does not necessarily return federations for this user, but those of the connected user!
     *
     * @return \PHT\Xml\Federations\Listing
     */
    public function getFederations()
    {
        return Wrapper\Federation::search(array('searchType' => 5));
    }

    /**
     * Return supporters
     *
     * @param integer $page
     * @param integer $size
     * @return \PHT\Xml\Supporters
     */
    public function getSupporters($page = 0, $size = 50)
    {
        return Wrapper\Supporters::listing(null, $this->getId(), $page, $size);
    }

    /**
     * Return number of last logins
     *
     * @return integer
     */
    public function getLastLoginNumber()
    {
        return $this->getXml()->getElementsByTagName('LoginTime')->length;
    }

    /**
     * Return last login object
     *
     * @param integer $index
     * @return \PHT\Xml\Compendium\Login
     */
    public function getLastLogin($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getLastLoginNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//LoginTime');
            $login = new \DOMDocument('1.0', 'UTF-8');
            $login->appendChild($login->importNode($nodeList->item($index), true));
            return new Compendium\Login($login);
        }
        return null;
    }

    /**
     * Return iterator of user last logins
     *
     * @return \PHT\Xml\Compendium\Login[]
     */
    public function getLastLogins()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//LoginTime');
        /** @var \PHT\Xml\Compendium\Login[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Compendium\Login');
        return $data;
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
        return new Avatar($node);
    }
}
