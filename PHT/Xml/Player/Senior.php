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
use PHT\Utils;
use PHT\Config;

class Senior extends Xml\HTSupporter
{
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
     * Return player first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
    }

    /**
     * Return player last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
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
     * Return player's team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//OwningTeam/TeamID')->item(0)->nodeValue;
    }

    /**
     * Return player's team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return player's team name
     *
     * @return integer
     */
    public function getTeamName()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//OwningTeam/TeamName')->item(0)->nodeValue;
    }

    /**
     * Return player avatar
     *
     * @return \PHT\Xml\Avatar
     */
    public function getAvatar()
    {
        $avatars = Wrapper\Team\Senior::avatars($this->getTeamId());
        foreach ($avatars->getAvatars() as $avatar) {
            if ($avatar->getPlayerId() == $this->getId()) {
                return $avatar;
            }
        }
        return null;
    }

    /**
     * Return player shirt number if team is hattrick supporter
     *
     * @return integer
     */
    public function getShirtNumber()
    {
        $node = $this->getXml()->getElementsByTagName('PlayerNumber');
        if ($node->length) {
            $num = $node->item(0)->nodeValue;
            if ($num < 100) {
                return $num;
            }
        }
        return null;
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
     * Return player age days
     *
     * @return integer
     */
    public function getDays()
    {
        return $this->getXml()->getElementsByTagName('AgeDays')->item(0)->nodeValue;
    }

    /**
     * Return player TSI
     *
     * @return integer
     */
    public function getTsi()
    {
        return $this->getXml()->getElementsByTagName('TSI')->item(0)->nodeValue;
    }

    /**
     * Return player league id
     *
     * @return integer
     */
    public function getTeamLeagueId()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//OwningTeam/LeagueID')->item(0)->nodeValue;
    }

    /**
     * Return country details
     *
     * @return \PHT\Xml\World\Country
     */
    public function getTeamCountry()
    {
        return Wrapper\World::country($this->getTeamLeagueId());
    }

    /**
     * Return player form level
     *
     * @return integer
     */
    public function getForm()
    {
        return $this->getXml()->getElementsByTagName('PlayerForm')->item(0)->nodeValue;
    }

    /**
     * Return player cards number
     *
     * @return integer
     */
    public function getCards()
    {
        return $this->getXml()->getElementsByTagName('Cards')->item(0)->nodeValue;
    }

    /**
     * Return player injury level, -1 if not injured
     *
     * @return integer
     */
    public function getInjury()
    {
        return $this->getXml()->getElementsByTagName('InjuryLevel')->item(0)->nodeValue;
    }

    /**
     * Return player statement
     *
     * @return integer
     */
    public function getStatement()
    {
        if ($this->getXml()->getElementsByTagName('Statement')->length) {
            return $this->getXml()->getElementsByTagName('Statement')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player language
     *
     * @return string
     */
    public function getLanguageName()
    {
        if ($this->getXml()->getElementsByTagName('PlayerLanguage')->length) {
            return $this->getXml()->getElementsByTagName('PlayerLanguage')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player language id
     *
     * @return integer
     */
    public function getLanguageId()
    {
        if ($this->getXml()->getElementsByTagName('PlayerLanguageID')->length) {
            return $this->getXml()->getElementsByTagName('PlayerLanguageID')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player experience level
     *
     * @return integer
     */
    public function getExperience()
    {
        return $this->getXml()->getElementsByTagName('Experience')->item(0)->nodeValue;
    }

    /**
     * Return player leadership level
     *
     * @return integer
     */
    public function getLeadership()
    {
        return $this->getXml()->getElementsByTagName('Leadership')->item(0)->nodeValue;
    }

    /**
     * Return player salary, in currency if specify
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getSalary($countryCurrency = null)
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('Salary')->item(0)->nodeValue, $countryCurrency);
    }

    /**
     * Is player abroad ?
     *
     * @return boolean
     */
    public function isAbroad()
    {
        return strtolower($this->getXml()->getElementsByTagName('IsAbroad')->item(0)->nodeValue) == "true";
    }

    /**
     * Return player speciality code
     *
     * @return integer
     */
    public function getSpecialty()
    {
        return $this->getXml()->getElementsByTagName('Specialty')->item(0)->nodeValue;
    }

    /**
     * Return player native league id
     *
     * @return integer
     */
    public function getNativeLeagueId()
    {
        return $this->getXml()->getElementsByTagName('NativeLeagueID')->item(0)->nodeValue;
    }

    /**
     * Return player native league name
     *
     * @return integer
     */
    public function getNativeLeagueName()
    {
        return $this->getXml()->getElementsByTagName('NativeLeagueName')->item(0)->nodeValue;
    }

    /**
     * Return native country details
     *
     * @return \PHT\Xml\World\Country
     */
    public function getNativeCountry()
    {
        return Wrapper\World::country($this->getNativeLeagueId());
    }

    /**
     * Return player native country id
     *
     * @return integer
     */
    public function getNativeCountryId()
    {
        return $this->getXml()->getElementsByTagName('NativeCountryID')->item(0)->nodeValue;
    }

    /**
     * Is player transfer listed ?
     *
     * @return boolean
     */
    public function isTransferListed()
    {
        return strtolower($this->getXml()->getElementsByTagName('TransferListed')->item(0)->nodeValue) == "true";
    }

    /**
     * Return asking price
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getAskingPrice($countryCurrency = null)
    {
        if ($this->isTransferListed()) {
            return Utils\Money::convert($this->getXml()->getElementsByTagName('AskingPrice')->item(0)->nodeValue, $countryCurrency);
        }
        return null;
    }

    /**
     * Return transfer deadline
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDeadline($format = null)
    {
        if ($this->isTransferListed()) {
            return Utils\Date::convert($this->getXml()->getElementsByTagName('Deadline')->item(0)->nodeValue, $format);
        }
        return null;
    }

    /**
     * Return highest bid
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getHighestBid($countryCurrency = null)
    {
        if ($this->isTransferListed()) {
            return Utils\Money::convert($this->getXml()->getElementsByTagName('HighestBid')->item(0)->nodeValue, $countryCurrency);
        }
        return null;
    }

    /**
     * Return maximum autobid set by user if any
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getMaxAutoBid($countryCurrency = null)
    {
        if ($this->isTransferListed() && $this->getXml()->getElementsByTagName('MaxBid')->length) {
            return Utils\Money::convert($this->getXml()->getElementsByTagName('MaxBid')->item(0)->nodeValue, $countryCurrency);
        }
        return null;
    }

    /**
     * Return bidder team id
     *
     * @return integer
     */
    public function getBidderTeamId()
    {
        if ($this->isTransferListed() && $this->getHighestBid() > 0) {
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
        $id = $this->getBidderTeamId();
        if ($id !== null) {
            return Wrapper\Team\Senior::team($id);
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
        if ($this->isTransferListed() && $this->getHighestBid() > 0) {
            $xpath = new \DOMXPath($this->getXml());
            return $xpath->query('//BidderTeam/TeamName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player number caps in national country team
     *
     * @return integer
     */
    public function getNTCaps()
    {
        return $this->getXml()->getElementsByTagName('Caps')->item(0)->nodeValue;
    }

    /**
     * Return player number caps in U20 country team
     *
     * @return integer
     */
    public function getU20Caps()
    {
        return $this->getXml()->getElementsByTagName('CapsU20')->item(0)->nodeValue;
    }

    /**
     * Does player skills available ?
     *
     * @return boolean
     */
    public function isSkillsAvailable()
    {
        return $this->getXml()->getElementsByTagName('PlayerSkills')->item(0)->childNodes->length > 1;
    }

    /**
     * Return player stamina level
     *
     * @return integer
     */
    public function getStamina()
    {
        return $this->getXml()->getElementsByTagName('StaminaSkill')->item(0)->nodeValue;
    }

    /**
     * Return player keeper level
     *
     * @return integer
     */
    public function getKeeper()
    {
        if ($this->isSkillsAvailable()) {
            return $this->getXml()->getElementsByTagName('KeeperSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player playmaker level
     *
     * @return integer
     */
    public function getPlaymaker()
    {
        if ($this->isSkillsAvailable()) {
            return $this->getXml()->getElementsByTagName('PlaymakerSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player scorer level
     *
     * @return integer
     */
    public function getScorer()
    {
        if ($this->isSkillsAvailable()) {
            return $this->getXml()->getElementsByTagName('ScorerSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player passing level
     *
     * @return integer
     */
    public function getPassing()
    {
        if ($this->isSkillsAvailable()) {
            return $this->getXml()->getElementsByTagName('PassingSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player winger level
     *
     * @return integer
     */
    public function getWinger()
    {
        if ($this->isSkillsAvailable()) {
            return $this->getXml()->getElementsByTagName('WingerSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player defender level
     *
     * @return integer
     */
    public function getDefender()
    {
        if ($this->isSkillsAvailable()) {
            return $this->getXml()->getElementsByTagName('DefenderSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player set pieces level
     *
     * @return integer
     */
    public function getSetPieces()
    {
        if ($this->isSkillsAvailable()) {
            return $this->getXml()->getElementsByTagName('SetPiecesSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player agreeability level
     *
     * @return integer
     */
    public function getAgreeability()
    {
        return $this->getXml()->getElementsByTagName('Agreeability')->item(0)->nodeValue;
    }

    /**
     * Return player aggressiveness level
     *
     * @return integer
     */
    public function getAggressiveness()
    {
        return $this->getXml()->getElementsByTagName('Aggressiveness')->item(0)->nodeValue;
    }

    /**
     * Return player honesty level
     *
     * @return integer
     */
    public function getHonesty()
    {
        return $this->getXml()->getElementsByTagName('Honesty')->item(0)->nodeValue;
    }

    /**
     * Is player a trainer ?
     *
     * @return boolean
     */
    public function isTrainer()
    {
        return $this->getXml()->getElementsByTagName('TrainerData')->item(0)->hasChildNodes();
    }

    /**
     * Return player trainer type
     *
     * @return integer
     */
    public function getTrainerType()
    {
        if ($this->isTrainer()) {
            return $this->getXml()->getElementsByTagName('TrainerType')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player trainer skill
     *
     * @return integer
     */
    public function getTrainerSkill()
    {
        if ($this->isTrainer()) {
            return $this->getXml()->getElementsByTagName('TrainerSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player next birthday
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getNextBirthDay($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('NextBirthDay')->item(0)->nodeValue, $format);
    }

    /**
     * Return owner category id set for the player
     *
     * @return integer
     */
    public function getCategoryId()
    {
        $node = $this->getXml()->getElementsByTagName('PlayerCategoryID');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return owner private notes
     *
     * @return string
     */
    public function getOwnerNotes()
    {
        $node = $this->getXml()->getElementsByTagName('OwnerNotes');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return last match object
     *
     * @return \PHT\Xml\Player\LastMatch
     */
    public function getLastMatch()
    {
        $node = $this->getXml()->getElementsByTagName('LastMatch')->item(0);
        if ($node !== null && $node->hasChildNodes()) {
            $lastmatch = new \DOMDocument('1.0', 'UTF-8');
            $lastmatch->appendChild($lastmatch->importNode($node, true));
            return new LastMatch($lastmatch, Config\Config::MATCH_SENIOR);
        }
        return null;
    }

    /**
     * Return player loyalty level
     *
     * @return integer
     */
    public function getLoyalty()
    {
        $node = $this->getXml()->getElementsByTagName('Loyalty');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return if player has mother club bonus
     *
     * @return boolean
     */
    public function hasMotherClubBonus()
    {
        $node = $this->getXml()->getElementsByTagName('MotherClubBonus');
        if ($node->length) {
            return strtolower($node->item(0)->nodeValue) == 'true';
        }
        return false;
    }

    /**
     * Return player number goals in his career
     *
     * @return integer
     */
    public function getGoalsInCareer()
    {
        return $this->getXml()->getElementsByTagName('CareerGoals')->item(0)->nodeValue;
    }

    /**
     * Return player number goals in cup
     *
     * @return integer
     */
    public function getGoalsInCup()
    {
        return $this->getXml()->getElementsByTagName('CupGoals')->item(0)->nodeValue;
    }

    /**
     * Return player number goals in league
     *
     * @return integer
     */
    public function getGoalsInLeague()
    {
        return $this->getXml()->getElementsByTagName('LeagueGoals')->item(0)->nodeValue;
    }

    /**
     * Return player number hattricks in his career
     *
     * @return integer
     */
    public function getHattricksInCareer()
    {
        return $this->getXml()->getElementsByTagName('CareerHattricks')->item(0)->nodeValue;
    }

    /**
     * Return player number goals in friendly
     *
     * @return integer
     */
    public function getGoalsInFriendly()
    {
        return $this->getXml()->getElementsByTagName('FriendliesGoals')->item(0)->nodeValue;
    }

    /**
     * Return player number goals in his team
     *
     * @return integer
     */
    public function getGoalsInTeam()
    {
        return $this->getXml()->getElementsByTagName('GoalsCurrentTeam')->item(0)->nodeValue;
    }

    /**
     * Return national team id if enrolled
     *
     * @return integer
     */
    public function getNationalTeamId()
    {
        $node = $this->getXml()->getElementsByTagName('NationalTeamID');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return national team name if enrolled
     *
     * @return string
     */
    public function getNationalTeamName()
    {
        $node = $this->getXml()->getElementsByTagName('NationalTeamName');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player transfers history object
     *
     * @return \PHT\Xml\Player\Transfer\History
     */
    public function getTransfers()
    {
        return Wrapper\Player\Senior::transfershistory($this->getId());
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

    /**
     * Return player training history object
     *
     * @return \PHT\Xml\Player\Training
     */
    public function getTraining()
    {
        return Wrapper\Player\Senior::training($this->getId());
    }

    /**
     * Return player history object
     *
     * @return \PHT\Xml\Player\History
     */
    public function getHistory()
    {
        return Wrapper\Player\Senior::history($this->getId());
    }
}
