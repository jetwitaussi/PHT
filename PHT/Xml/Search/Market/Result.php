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

namespace PHT\Xml\Search\Market;

use PHT\Xml;
use PHT\Utils;
use PHT\Wrapper;

class Result extends Xml\Base
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
     * Returns player id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('PlayerId')->item(0)->nodeValue;
    }

    /**
     * Return player
     *
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Player\Senior
     */
    public function getPlayer($includeMatchInfo = true)
    {
        return Wrapper\Player\Senior::player($this->getId(), $includeMatchInfo);
    }

    /**
     * Returns player firstname
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
    }

    /**
     * Returns player lastname
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
    }

    /**
     * Returns player nickname
     *
     * @return string
     */
    public function getNickName()
    {
        return $this->getXml()->getElementsByTagName('NickName')->item(0)->nodeValue;
    }

    /**
     * Return player full name
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->getFirstName() . ' ';
        if ($this->getNickName() !== null && $this->getNickName() !== '') {
            $name .= $this->getNickName() . ' ';
        }
        $name .= $this->getLastName();
        return $name;
    }

    /**
     * Returns player native country id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->getXml()->getElementsByTagName('NativeCountryID')->item(0)->nodeValue;
    }

    /**
     * Returns player native country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country(null, $this->getCountryId());
    }

    /**
     * Returns asking price
     *
     * @return integer
     */
    public function getAskingPrice()
    {
        return $this->getXml()->getElementsByTagName('AskingPrice')->item(0)->nodeValue;
    }

    /**
     * Return deadline date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDeadline($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('Deadline')->item(0)->nodeValue, $format);
    }

    /**
     * Return highest bid
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getHighestBid($countryCurrency = null)
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('HighestBid')->item(0)->nodeValue, $countryCurrency);
    }

    /**
     * Return bidder team id
     *
     * @return integer
     */
    public function getBidderTeamId()
    {
        if ($this->getHighestBid() > 0) {
            $xpath = new \DOMXPath($this->getXml());
            return $xpath->query('//BidderTeam/TeamID')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return bidder team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getBidderTeam()
    {
        if ($this->getHighestBid() > 0) {
            return Wrapper\Team\Senior::team($this->getBidderTeamId());
        }
        return null;
    }

    /**
     * Return bidder team name
     *
     * @return string
     */
    public function getBidderTeamName()
    {
        if ($this->getHighestBid() > 0) {
            $xpath = new \DOMXPath($this->getXml());
            return $xpath->query('//BidderTeam/TeamName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Returns player age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
    }

    /**
     * Returns player days
     *
     * @return integer
     */
    public function getDays()
    {
        return $this->getXml()->getElementsByTagName('AgeDays')->item(0)->nodeValue;
    }

    /**
     * Returns player TSI
     *
     * @return integer
     */
    public function getTSI()
    {
        return $this->getXml()->getElementsByTagName('TSI')->item(0)->nodeValue;
    }

    /**
     * Returns player form
     *
     * @return integer
     */
    public function getForm()
    {
        return $this->getXml()->getElementsByTagName('PlayerForm')->item(0)->nodeValue;
    }

    /**
     * Returns player experience
     *
     * @return integer
     */
    public function getExperience()
    {
        return $this->getXml()->getElementsByTagName('Experience')->item(0)->nodeValue;
    }

    /**
     * Returns player leadership
     *
     * @return integer
     */
    public function getLeadership()
    {
        return $this->getXml()->getElementsByTagName('Leadership')->item(0)->nodeValue;
    }

    /**
     * Returns player specialty id
     *
     * @return integer
     */
    public function getSpecialty()
    {
        return $this->getXml()->getElementsByTagName('Specialty')->item(0)->nodeValue;
    }

    /**
     * Returns number of cards, 3 means red card
     *
     * @return integer
     */
    public function getCards()
    {
        return $this->getXml()->getElementsByTagName('Cards')->item(0)->nodeValue;
    }

    /**
     * Returns player injury level
     *
     * @return integer
     */
    public function getInjury()
    {
        return $this->getXml()->getElementsByTagName('InjuryLevel')->item(0)->nodeValue;
    }

    /**
     * Returns player stamina level
     *
     * @return integer
     */
    public function getStamina()
    {
        return $this->getXml()->getElementsByTagName('StaminaSkill')->item(0)->nodeValue;
    }

    /**
     * Returns player keeper level
     *
     * @return integer
     */
    public function getKeeper()
    {
        return $this->getXml()->getElementsByTagName('KeeperSkill')->item(0)->nodeValue;
    }

    /**
     * Returns player playmaker level
     *
     * @return integer
     */
    public function getPlaymaker()
    {
        return $this->getXml()->getElementsByTagName('PlaymakerSkill')->item(0)->nodeValue;
    }

    /**
     * Returns player scorer level
     *
     * @return integer
     */
    public function getScorer()
    {
        return $this->getXml()->getElementsByTagName('ScorerSkill')->item(0)->nodeValue;
    }

    /**
     * Returns player passing level
     *
     * @return integer
     */
    public function getPassing()
    {
        return $this->getXml()->getElementsByTagName('PassingSkill')->item(0)->nodeValue;
    }

    /**
     * Returns player winger level
     *
     * @return integer
     */
    public function getWinger()
    {
        return $this->getXml()->getElementsByTagName('WingerSkill')->item(0)->nodeValue;
    }

    /**
     * Returns player defender level
     *
     * @return integer
     */
    public function getDefender()
    {
        return $this->getXml()->getElementsByTagName('DefenderSkill')->item(0)->nodeValue;
    }

    /**
     * Returns player set pieces level
     *
     * @return integer
     */
    public function getSetPieces()
    {
        return $this->getXml()->getElementsByTagName('SetPiecesSkill')->item(0)->nodeValue;
    }

    /**
     * Return seller team id
     *
     * @return integer
     */
    public function getSellerTeamId()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//SellerTeam/TeamID')->item(0)->nodeValue;
    }

    /**
     * Return seller team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getSellerTeam()
    {
        return Wrapper\Team\Senior::team($this->getSellerTeamId());
    }

    /**
     * Return seller team name
     *
     * @return string
     */
    public function getSellerTeamName()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//SellerTeam/TeamName')->item(0)->nodeValue;
    }

    /**
     * Return seller team league id
     *
     * @return integer
     */
    public function getSellerLeagueId()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//SellerTeam/LeagueID')->item(0)->nodeValue;
    }

    /**
     * Return seller team country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getSellerCountry()
    {
        return Wrapper\World::country($this->getSellerLeagueId());
    }

    /**
     * Bid on the player
     *
     * @param integer $teamId the teamId who bid on the player
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @param integer $amount bid amount
     * @param integer $maxAmount max bid amount (for automatic bid)
     * @return \PHT\Xml\Player\Senior
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public function setBid($teamId, $countryCurrency, $amount = null, $maxAmount = null)
    {
        return Wrapper\Player\Senior::bid($teamId, $this->getId(), $countryCurrency, $amount, $maxAmount);
    }
}
