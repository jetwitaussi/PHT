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

namespace PHT\Xml\Team;

use PHT\Xml;
use PHT\Wrapper;
use PHT\Utils;

class Youth extends Xml\File
{
    /**
     * Returns if youth team exist
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->getId() === null;
    }

    /**
     * Return youth team id
     *
     * @return integer
     */
    public function getId()
    {
        $id = $this->getXml()->getElementsByTagName('YouthTeamID')->item(0)->nodeValue;
        if ((int)$id == 0) {
            return null;
        }
        return $id;
    }

    /**
     * Return youth team name
     *
     * @return string
     */
    public function getName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('YouthTeamName')->item(0)->nodeValue;
    }

    /**
     * Return youth team short name
     *
     * @return string
     */
    public function getShortName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('ShortTeamName')->item(0)->nodeValue;
    }

    /**
     * Return creation date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getCreationDate($format = null)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Utils\Date::convert($this->getXml()->getElementsByTagName('CreatedDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return country id
     *
     * @return integer
     */
    public function getCountryId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('CountryID')->item(0)->nodeValue;
    }

    /**
     * Return country name
     *
     * @return string
     */
    public function getCountryName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('CountryName')->item(0)->nodeValue;
    }

    /**
     * Return region id
     *
     * @return integer
     */
    public function getRegionId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
    }

    /**
     * Return region name
     *
     * @return string
     */
    public function getRegionName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
    }

    /**
     * Get world region details
     *
     * @return \PHT\Xml\World\Region
     */
    public function getRegion()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\World::region($this->getRegionId());
    }

    /**
     * Return arena id
     *
     * @return integer
     */
    public function getArenaId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('YouthArenaID')->item(0)->nodeValue;
    }

    /**
     * Return arena
     *
     * @return \PHT\Xml\Team\Arena
     */
    public function getArena()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\Team\Senior::arena($this->getArenaId());
    }

    /**
     * Return arena name
     *
     * @return string
     */
    public function getArenaName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('YouthArenaName')->item(0)->nodeValue;
    }

    /**
     * Return league id
     *
     * @return integer
     */
    public function getLeagueId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('YouthLeagueID')->item(0)->nodeValue;
    }

    /**
     * Return league name
     *
     * @return string
     */
    public function getLeagueName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('YouthLeagueName')->item(0)->nodeValue;
    }

    /**
     * Return league
     *
     * @return \PHT\Xml\World\League\Youth
     */
    public function getLeague()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Wrapper\World\League::youth($this->getLeagueId());
    }

    /**
     * Return league status
     *
     * @return integer
     */
    public function getLeagueStatus()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('YouthLeagueStatus')->item(0)->nodeValue;
    }

    /**
     * Return senior team id
     *
     * @return integer
     */
    public function getSeniorTeamId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('MotherTeamID')->item(0)->nodeValue;
    }

    /**
     * Return senior team name
     *
     * @return string
     */
    public function getSeniorTeamName()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('MotherTeamName')->item(0)->nodeValue;
    }

    /**
     * Return senior team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getSeniorTeam()
    {
        return Wrapper\Team\Senior::team($this->getSeniorTeamId());
    }

    /**
     * Return trainer id
     *
     * @return integer
     */
    public function getTrainerId()
    {
        if ($this->isDeleted()) {
            return null;
        }
        return $this->getXml()->getElementsByTagName('YouthPlayerID')->item(0)->nodeValue;
    }

    /**
     * Return next training match date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getNextTrainingMatchDate($format = null)
    {
        if ($this->isDeleted()) {
            return null;
        }
        return Utils\Date::convert($this->getXml()->getElementsByTagName('NextTrainingMatchDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return team's players
     *
     * @param boolean $unlockSkills
     * @param boolean $showScoutCall
     * @param boolean $showLastMatch
     * @param string $orderBy
     * @return \PHT\Xml\Team\Youth\Players
     */
    public function getPlayers($unlockSkills = false, $showScoutCall = true, $showLastMatch = true, $orderBy = null)
    {
        return Wrapper\Team\Youth::players($this->getId(), $unlockSkills, $showScoutCall, $showLastMatch, $orderBy);
    }

    /**
     * Return team's scouts
     *
     * @return \PHT\Xml\Team\Youth\Scouts
     */
    public function getScouts()
    {
        return Wrapper\Team\Youth::scouts($this->getId());
    }

    /**
     * Get team matches
     *
     * @param string $showBeforeDate (format should be : yyyy-mm-dd  - If no specify : returned matches are from now + 28 days)
     * @return \PHT\Xml\Team\Match\Listing
     */
    public function getMatches($showBeforeDate = null)
    {
        return Wrapper\Team\Youth::matches($this->getId(), $showBeforeDate);
    }

    /**
     * Get team archive matches
     *
     * @param string $startDate (format should be : yyyy-mm-dd)
     * @param string $endDate (format should be : yyyy-mm-dd)
     * @return \PHT\Xml\Team\Match\Archive
     */
    public function getMatchesArchive($startDate = null, $endDate = null)
    {
        return Wrapper\Team\Youth::archives($this->getId(), $startDate, $endDate);
    }

}
