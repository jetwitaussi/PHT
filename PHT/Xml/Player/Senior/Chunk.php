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

namespace PHT\Xml\Player\Senior;

use PHT\Xml;
use PHT\Wrapper;
use PHT\Config;
use PHT\Utils;

class Chunk extends Xml\Base
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
     * Get player's full details
     *
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Player\Senior
     */
    public function getPlayer($includeMatchInfo = true)
    {
        return Wrapper\Player\Senior::player($this->getId(), $includeMatchInfo);
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
     * Return player shirt number if team is hattrick supporter
     *
     * @return integer
     */
    public function getShirtNumber()
    {
        $node = $this->getXml()->getElementsByTagName('PlayerNumber');
        if ($node->length) {
            $num = $node->item(0)->nodeValue;
            if ($num != 100) {
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
     * Return player form level
     *
     * @return integer
     */
    public function getForm()
    {
        return $this->getXml()->getElementsByTagName('PlayerForm')->item(0)->nodeValue;
    }

    /**
     * Return player statement
     *
     * @return integer
     */
    public function getStatement()
    {
        $node = $this->getXml()->getElementsByTagName('Statement');
        if ($node->length) {
            return $node->item(0)->nodeValue;
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
        return $this->getXml()->getElementsByTagName('IsAbroad')->item(0)->nodeValue == "1";
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
     * Return player number goals in league
     *
     * @return integer
     */
    public function getGoalsInLeague()
    {
        return $this->getXml()->getElementsByTagName('LeagueGoals')->item(0)->nodeValue;
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
     * Return player number goals in friendly
     *
     * @return integer
     */
    public function getGoalsInFriendly()
    {
        return $this->getXml()->getElementsByTagName('FriendliesGoals')->item(0)->nodeValue;
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
     * Return player number hattricks in his career
     *
     * @return integer
     */
    public function getHattricksInCareer()
    {
        return $this->getXml()->getElementsByTagName('CareerHattricks')->item(0)->nodeValue;
    }

    /**
     * Return player specialty code
     *
     * @return integer
     */
    public function getSpecialty()
    {
        return $this->getXml()->getElementsByTagName('Specialty')->item(0)->nodeValue;
    }

    /**
     * Is player transfer listed ?
     *
     * @return boolean
     */
    public function isTransferListed()
    {
        return $this->getXml()->getElementsByTagName('TransferListed')->item(0)->nodeValue == "1";
    }

    /**
     * Return national team id if player selected
     *
     * @return integer
     */
    public function getNationalTeamId()
    {
        $id = $this->getXml()->getElementsByTagName('NationalTeamID')->item(0)->nodeValue;
        if ($id != 0) {
            return $id;
        }
        return null;
    }

    /**
     * Return player country id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->getXml()->getElementsByTagName('CountryID')->item(0)->nodeValue;
    }

    /**
     * Return player number caps in NT country team
     * @deprecated use getNTCaps function instead
     *
     * @return integer
     */
    public function getACaps()
    {
        trigger_error(__FUNCTION__.'() function in '.__CLASS__.' is deprecated, use getNTCaps() instead', E_USER_DEPRECATED);
        return $this->getNTCaps();
    }

    /**
     * Return player number caps in NT country team
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
     * Is player a trainer ?
     *
     * @return boolean
     */
    public function isTrainer()
    {
        return $this->getXml()->getElementsByTagName('TrainerData')->length > 0;
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
        return Utils\Date::convert(date('Y-m-d', time() + ((Config\Config::DAYS_IN_YEAR - $this->getDays()) * 24 * 3600)), $format);
    }

    /**
     * Return player stamina level if available
     *
     * @return integer
     */
    public function getStamina()
    {
        if ($this->getXml()->getElementsByTagName('StaminaSkill')->length) {
            return $this->getXml()->getElementsByTagName('StaminaSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player keeper level if available
     *
     * @return integer
     */
    public function getKeeper()
    {
        if ($this->getXml()->getElementsByTagName('KeeperSkill')->length) {
            return $this->getXml()->getElementsByTagName('KeeperSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player playmaker level if available
     *
     * @return integer
     */
    public function getPlaymaker()
    {
        if ($this->getXml()->getElementsByTagName('PlaymakerSkill')->length) {
            return $this->getXml()->getElementsByTagName('PlaymakerSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player scorer level if available
     *
     * @return integer
     */
    public function getScorer()
    {
        if ($this->getXml()->getElementsByTagName('ScorerSkill')->length) {
            return $this->getXml()->getElementsByTagName('ScorerSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player passing level if available
     *
     * @return integer
     */
    public function getPassing()
    {
        if ($this->getXml()->getElementsByTagName('PassingSkill')->length) {
            return $this->getXml()->getElementsByTagName('PassingSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player winger level if available
     *
     * @return integer
     */
    public function getWinger()
    {
        if ($this->getXml()->getElementsByTagName('WingerSkill')->length) {
            return $this->getXml()->getElementsByTagName('WingerSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player defender level if available
     *
     * @return integer
     */
    public function getDefender()
    {
        if ($this->getXml()->getElementsByTagName('DefenderSkill')->length) {
            return $this->getXml()->getElementsByTagName('DefenderSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player set pieces level if available
     *
     * @return integer
     */
    public function getSetPieces()
    {
        if ($this->getXml()->getElementsByTagName('SetPiecesSkill')->length) {
            return $this->getXml()->getElementsByTagName('SetPiecesSkill')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return player category id
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->getXml()->getElementsByTagName('PlayerCategoryId')->item(0)->nodeValue;
    }

    /**
     * Return player last match object
     *
     * @return \PHT\Xml\Player\LastMatch
     */
    public function getLastMatch()
    {
        $node = $this->getXml()->getElementsByTagName('LastMatch')->item(0);
        if ($node !== null && $node->hasChildNodes()) {
            $lastmatch = new \DOMDocument('1.0', 'UTF-8');
            $lastmatch->appendChild($lastmatch->importNode($node, true));
            return new Xml\Player\LastMatch($lastmatch, Config\Config::MATCH_SENIOR);
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
     * Return current team id of grown player
     *
     * @return integer
     */
    public function getNewTeamId()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//OwningTeam/TeamID');
        if ($nodeList->length) {
            return $nodeList->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return current team of grown player
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getNewTeam()
    {
        $id = $this->getNewTeamId();
        if ($id !== null) {
            return Wrapper\Team\Senior::team($id);
        }
        return null;
    }

    /**
     * Return current team name of grown player
     *
     * @return string
     */
    public function getNewTeamName()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//OwningTeam/TeamName');
        if ($nodeList->length) {
            return $nodeList->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return current country name of grown player's team
     *
     * @return string
     */
    public function getNewTeamCountryName()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//OwningTeam/LeagueName');
        if ($nodeList->length) {
            return $nodeList->item(0)->nodeValue;
        }
        return null;
    }
}
