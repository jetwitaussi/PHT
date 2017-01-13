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

namespace PHT\Xml\I18n;

use PHT\Xml;

class Translation extends Xml\File
{
    /**
     * Returns language id
     *
     * @return integer
     */
    public function getLanguageId()
    {
        return $this->getXml()->getElementsByTagName('Language')->item(0)->getAttribute('Id');
    }

    /**
     * Returns language name
     *
     * @return string
     */
    public function getLanguageName()
    {
        return $this->getXml()->getElementsByTagName('Language')->item(0)->nodeValue;
    }

    /**
     * Return keeper skill name
     *
     * @return string
     */
    public function getSkillNameKeeper()
    {
        return $this->getSkillName('Keeper');
    }

    /**
     * Return stamina skill name
     *
     * @return string
     */
    public function getSkillNameStamina()
    {
        return $this->getSkillName('Stamina');
    }

    /**
     * Return defender skill name
     *
     * @return string
     */
    public function getSkillNameDefender()
    {
        return $this->getSkillName('Defender');
    }

    /**
     * Return playmaker skill name
     *
     * @return string
     */
    public function getSkillNamePlaymaker()
    {
        return $this->getSkillName('Playmaker');
    }

    /**
     * Return winger skill name
     *
     * @return string
     */
    public function getSkillNameWinger()
    {
        return $this->getSkillName('Winger');
    }

    /**
     * Return scorer skill name
     *
     * @return string
     */
    public function getSkillNameScorer()
    {
        return $this->getSkillName('Scorer');
    }

    /**
     * Return kicker skill name
     *
     * @return string
     */
    public function getSkillNameKicker()
    {
        return $this->getSkillName('Kicker');
    }

    /**
     * Return passer skill name
     *
     * @return string
     */
    public function getSkillNamePasser()
    {
        return $this->getSkillName('Passer');
    }

    /**
     * Return experience skill name
     *
     * @return string
     */
    public function getSkillNameExperience()
    {
        return $this->getSkillName('Experience');
    }

    /**
     * Return leadership skill name
     *
     * @return string
     */
    public function getSkillNameLeaderShip()
    {
        return $this->getSkillName('LeaderShip');
    }

    /**
     * Return skill name
     *
     * @param string $type
     * @return string
     */
    private function getSkillName($type)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//SkillNames/Skill[@Type='" . $type . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Return skill level
     *
     * @param integer $level
     * @return string
     */
    public function getSkillLevel($level)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//SkillLevels/Level[@Value='" . $level . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Return skill sub level
     *
     * @param float $sublevel
     * @return string
     */
    public function getSkillSubLevel($sublevel)
    {
        $sublevel = str_replace('.', ',', $sublevel);
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//SkillSubLevels/SubLevel[@Value='" . $sublevel . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns player specialties label
     *
     * @return string
     */
    public function getPlayerSpecialitiesLabel()
    {
        return trim($this->getXml()->getElementsByTagName('PlayerSpecialties')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns player speciality name
     *
     * @param integer $id
     * @return string
     */
    public function getPlayerSpeciality($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//PlayerSpecialties/Item[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns player agreeability label
     *
     * @return string
     */
    public function getPlayerAgreeabilityLabel()
    {
        return trim($this->getXml()->getElementsByTagName('PlayerAgreeability')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns player agreeability name
     *
     * @param integer $id
     * @return string
     */
    public function getPlayerAgreeability($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//PlayerAgreeability/Level[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns player agressiveness label
     *
     * @return string
     */
    public function getPlayerAgressivenessLabel()
    {
        return trim($this->getXml()->getElementsByTagName('PlayerAgressiveness')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns player agressiveness name
     *
     * @param integer $id
     * @return string
     */
    public function getPlayerAgressiveness($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//PlayerAgressiveness/Level[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns player honesty label
     *
     * @return string
     */
    public function getPlayerHonestyLabel()
    {
        return trim($this->getXml()->getElementsByTagName('PlayerHonesty')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns player honesty name
     *
     * @param integer $id
     * @return string
     */
    public function getPlayerHonesty($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//PlayerHonesty/Level[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns tactics type label
     *
     * @return string
     */
    public function getTacticsLabel()
    {
        return trim($this->getXml()->getElementsByTagName('TacticTypes')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns tactic name
     *
     * @param integer $id
     * @return string
     */
    public function getTactic($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//TacticTypes/Item[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns match position label
     *
     * @return string
     */
    public function getMatchPositionLabel()
    {
        return trim($this->getXml()->getElementsByTagName('MatchPositions')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns match position name
     *
     * @param integer $id
     * @return string
     */
    public function getMatchPositions($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//MatchPositions/Item[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns match position label
     *
     * @return string
     */
    public function getRatingSectorsLabel()
    {
        return trim($this->getXml()->getElementsByTagName('RatingSectors')->item(0)->getAttribute('Label'));
    }

    /**
     * Return midfield rating sector name
     *
     * @return string
     */
    public function getRatingSectorsMidfield()
    {
        return $this->getRatingSectors('Midfield');
    }

    /**
     * Return right defense rating sector name
     *
     * @return string
     */
    public function getRatingSectorsRightDefense()
    {
        return $this->getRatingSectors('RightDefense');
    }

    /**
     * Return central defense rating sector name
     *
     * @return string
     */
    public function getRatingSectorsCentralDefense()
    {
        return $this->getRatingSectors('CentralDefense');
    }

    /**
     * Return left defense rating sector name
     *
     * @return string
     */
    public function getRatingSectorsLeftDefense()
    {
        return $this->getRatingSectors('LeftDefense');
    }

    /**
     * Return right attack rating sector name
     *
     * @return string
     */
    public function getRatingSectorsRightAttack()
    {
        return $this->getRatingSectors('RightAttack');
    }

    /**
     * Return central attack rating sector name
     *
     * @return string
     */
    public function getRatingSectorsCentralAttack()
    {
        return $this->getRatingSectors('CentralAttack');
    }

    /**
     * Return left attack rating sector name
     *
     * @return string
     */
    public function getRatingSectorsLeftAttack()
    {
        return $this->getRatingSectors('LeftAttack');
    }

    /**
     * Returns rating sector name
     *
     * @param integer $type
     * @return string
     */
    private function getRatingSectors($type)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//RatingSectors/Item[@Type='" . $type . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns team attitude label
     *
     * @return string
     */
    public function getTeamAttitudeLabel()
    {
        return trim($this->getXml()->getElementsByTagName('TeamAttitude')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns team attitude name
     *
     * @param integer $id
     * @return string
     */
    public function getTeamAttitude($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//TeamAttitude/Level[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns team spirit label
     *
     * @return string
     */
    public function getTeamSpiritLabel()
    {
        return trim($this->getXml()->getElementsByTagName('TeamSpirit')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns team spirit name
     *
     * @param integer $id
     * @return string
     */
    public function getTeamSpirit($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//TeamSpirit/Level[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns team confidence label
     *
     * @return string
     */
    public function getTeamConfidenceLabel()
    {
        return trim($this->getXml()->getElementsByTagName('Confidence')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns team confidence name
     *
     * @param integer $id
     * @return string
     */
    public function getTeamConfidence($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//Confidence/Level[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns training types label
     *
     * @return string
     */
    public function getTrainingTypesLabel()
    {
        return trim($this->getXml()->getElementsByTagName('TrainingTypes')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns training type name
     *
     * @param integer $id
     * @return string
     */
    public function getTrainingType($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//TrainingTypes/Item[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns sponsors label
     *
     * @return string
     */
    public function getSponsorsLabel()
    {
        return trim($this->getXml()->getElementsByTagName('Sponsors')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns sponsor name
     *
     * @param integer $id
     * @return string
     */
    public function getSponsor($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//Sponsors/Level[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns fan mood label
     *
     * @return string
     */
    public function getFanMoodLabel()
    {
        return trim($this->getXml()->getElementsByTagName('FanMood')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns fan mood name
     *
     * @param integer $id
     * @return string
     */
    public function getFanMood($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//FanMood/Level[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns fan match expectations label
     *
     * @return string
     */
    public function getFanMatchExpectationsLabel()
    {
        return trim($this->getXml()->getElementsByTagName('FanMatchExpectations')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns fan match expectation name
     *
     * @param integer $id
     * @return string
     */
    public function getFanMatchExpectation($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//FanMatchExpectations/Level[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns fan season expectations label
     *
     * @return string
     */
    public function getFanSeasonExpectationsLabel()
    {
        return trim($this->getXml()->getElementsByTagName('FanSeasonExpectations')->item(0)->getAttribute('Label'));
    }

    /**
     * Returns fan season expectation name
     *
     * @param integer $id
     * @return string
     */
    public function getFanSeasonExpectation($id)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//FanSeasonExpectations/Level[@Value='" . $id . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns league name
     *
     * @param integer $leagueId
     * @return string
     */
    public function getLeagueName($leagueId)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//League/LeagueId[.='" . $leagueId . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->parentNode->getElementsByTagName("LanguageLeagueName")->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * Returns league local name
     *
     * @param integer $leagueId
     * @return string
     */
    public function getLeagueLocalName($leagueId)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//League/LeagueId[.='" . $leagueId . "']");
        if ($nodeList->length) {
            return trim($nodeList->item(0)->parentNode->getElementsByTagName("LocalLeagueName")->item(0)->nodeValue);
        }
        return '';
    }
}
