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

class Youth extends Xml\File
{
    /**
     * Return youth player id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('YouthPlayerID')->item(0)->nodeValue;
    }

    /**
     * Return youth player firstname
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
    }

    /**
     * Return youth player lastname
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
    }

    /**
     * Return youth player nickname
     *
     * @return string
     */
    public function getNickName()
    {
        return $this->getXml()->getElementsByTagName('NickName')->item(0)->nodeValue;
    }

    /**
     * Return youth player age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
    }

    /**
     * Return youth player age days
     *
     * @return integer
     */
    public function getDays()
    {
        return $this->getXml()->getElementsByTagName('AgeDays')->item(0)->nodeValue;
    }

    /**
     * Return arrival date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getArrivalDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('ArrivalDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return number of days when player can be promoted
     *
     * @return integer
     */
    public function getCanBePromotedIn()
    {
        return $this->getXml()->getElementsByTagName('CanBePromotedIn')->item(0)->nodeValue;
    }

    /**
     * Return youth player number
     *
     * @return integer
     */
    public function getNumber()
    {
        $node = $this->getXml()->getElementsByTagName('PlayerNumber');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return youth player statement
     *
     * @return string
     */
    public function getStatement()
    {
        $node = $this->getXml()->getElementsByTagName('Statement');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return notes
     *
     * @return string
     */
    public function getNotes()
    {
        $node = $this->getXml()->getElementsByTagName('OwnerNotes');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return category id
     *
     * @return integer
     */
    public function getCategory()
    {
        $node = $this->getXml()->getElementsByTagName('PlayerCategoryID');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return cards number
     *
     * @return integer
     */
    public function getCardsNumber()
    {
        return $this->getXml()->getElementsByTagName('Cards')->item(0)->nodeValue;
    }

    /**
     * Return injury level
     *
     * @return integer
     */
    public function getInjuryLevel()
    {
        return $this->getXml()->getElementsByTagName('InjuryLevel')->item(0)->nodeValue;
    }

    /**
     * Return specialty id
     *
     * @return integer
     */
    public function getSpecialty()
    {
        return $this->getXml()->getElementsByTagName('Specialty')->item(0)->nodeValue;
    }

    /**
     * Return career goals number
     *
     * @return integer
     */
    public function getCareerGoals()
    {
        return $this->getXml()->getElementsByTagName('CareerGoals')->item(0)->nodeValue;
    }

    /**
     * Return career hattricks number
     *
     * @return integer
     */
    public function getCareerHattricks()
    {
        return $this->getXml()->getElementsByTagName('CareerHattricks')->item(0)->nodeValue;
    }

    /**
     * Return league goals number
     *
     * @return integer
     */
    public function getLeagueGoals()
    {
        return $this->getXml()->getElementsByTagName('LeagueGoals')->item(0)->nodeValue;
    }

    /**
     * Return friends goals number
     *
     * @return integer
     */
    public function getFriendlyGoals()
    {
        return $this->getXml()->getElementsByTagName('FriendlyGoals')->item(0)->nodeValue;
    }

    /**
     * Return youth team id
     *
     * @return integer
     */
    public function getYouthTeamId()
    {
        return $this->getXml()->getElementsByTagName('YouthTeamID')->item(0)->nodeValue;
    }

    /**
     * Return youth team
     *
     * @return \PHT\Xml\Team\Youth
     */
    public function getYouthTeam()
    {
        return Wrapper\Team\Youth::team($this->getYouthTeamId());
    }

    /**
     * Return youth team name
     *
     * @return string
     */
    public function getYouthTeamName()
    {
        return $this->getXml()->getElementsByTagName('YouthTeamName')->item(0)->nodeValue;
    }

    /**
     * Return youth team league id
     *
     * @return integer
     */
    public function getYouthTeamLeagueId()
    {
        return $this->getXml()->getElementsByTagName('YouthTeamLeagueID')->item(0)->nodeValue;
    }

    /**
     * Return youth team league
     *
     * @return \PHT\Xml\World\League\Youth
     */
    public function getYouthTeamLeague()
    {
        return Wrapper\World\League::youth($this->getYouthTeamLeagueId());
    }

    /**
     * Return player avatar
     *
     * @return \PHT\Xml\Avatar
     */
    public function getAvatar()
    {
        $avatars = Wrapper\Team\Youth::avatars($this->getYouthTeamId());
        foreach ($avatars->getAvatars() as $avatar) {
            if ($avatar->getPlayerId() == $this->getId()) {
                return $avatar;
            }
        }
        return null;
    }

    /**
     * Return senior team id
     *
     * @return integer
     */
    public function getSeniorTeamId()
    {
        return $this->getXml()->getElementsByTagName('SeniorTeamID')->item(0)->nodeValue;
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
     * Return senior team name
     *
     * @return string
     */
    public function getSeniorTeamName()
    {
        return $this->getXml()->getElementsByTagName('SeniorTeamName')->item(0)->nodeValue;
    }

    /**
     * Return is skills are available
     *
     * @return boolean
     */
    public function hasSkills()
    {
        return $this->getXml()->getElementsByTagName('PlayerSkills')->length;
    }

    /**
     * Get keeper skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getKeeperSkill()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('KeeperSkill')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get max keeper skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getKeeperSkillMax()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('KeeperSkillMax')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get defender skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getDefenderSkill()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('DefenderSkill')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get max defender skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getDefenderSkillMax()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('DefenderSkillMax')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get playmaker skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getPlaymakerSkill()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('PlaymakerSkill')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get max playmaker skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getPlaymakerSkillMax()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('PlaymakerSkillMax')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get winger skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getWingerSkill()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('WingerSkill')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get max winger skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getWingerSkillMax()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('WingerSkillMax')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get scorer skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getScorerSkill()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('ScorerSkill')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get max scorer skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getScorerSkillMax()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('ScorerSkillMax')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get passing skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getPassingSkill()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('PassingSkill')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get max passing skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getPassingSkillMax()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('PassingSkillMax')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get set pieces skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getSetPiecesSkill()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('SetPiecesSkill')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get max set pieces skill
     *
     * @return \PHT\Xml\Player\Youth\Skill
     */
    public function getSetPiecesSkillMax()
    {
        if ($this->hasSkills()) {
            $skill = new \DOMDocument('1.0', 'UTF-8');
            $skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('SetPiecesSkillMax')->item(0), true));
            return new Xml\Player\Youth\Skill($skill);
        }
        return null;
    }

    /**
     * Get scout call
     *
     * @return \PHT\Xml\Team\Youth\Scout\Call
     */
    public function getScoutCall()
    {
        $node = $this->getXml()->getElementsByTagName('ScoutCall');
        if ($node !== null && $node->length) {
            $call = new \DOMDocument('1.0', 'UTF-8');
            $call->appendChild($call->importNode($node->item(0), true));
            return new Xml\Team\Youth\Scout\Call($call, $this->getYouthTeamId());
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
        if ($this->getXml()->getElementsByTagName('LastMatch')->length) {
            $match = new \DOMDocument('1.0', 'UTF-8');
            $match->appendChild($match->importNode($this->getXml()->getElementsByTagName('LastMatch')->item(0), true));
            return new LastMatch($match, Config\Config::MATCH_YOUTH);
        }
        return null;
    }
}
