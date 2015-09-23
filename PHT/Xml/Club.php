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

use PHT\Wrapper;

class Club extends File
{
    /**
     * Return senior team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return senior team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return senior team name
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return assistant trainer levels
     *
     * @return integer
     */
    public function getAssistantTrainerLevels()
    {
        return $this->getXml()->getElementsByTagName('AssistantTrainerLevels')->item(0)->nodeValue;
    }

    /**
     * Return financial director levels
     *
     * @return integer
     */
    public function getFinancialDirectorLevels()
    {
        return $this->getXml()->getElementsByTagName('FinancialDirectorLevels')->item(0)->nodeValue;
    }

    /**
     * Return form coach levels
     *
     * @return integer
     */
    public function getFormCoachLevels()
    {
        return $this->getXml()->getElementsByTagName('FormCoachLevels')->item(0)->nodeValue;
    }

    /**
     * Return medic levels
     *
     * @return integer
     */
    public function getMedicLevels()
    {
        return $this->getXml()->getElementsByTagName('MedicLevels')->item(0)->nodeValue;
    }

    /**
     * Return spokesperson levels
     *
     * @return integer
     */
    public function getSpokespersonLevels()
    {
        return $this->getXml()->getElementsByTagName('SpokespersonLevels')->item(0)->nodeValue;
    }

    /**
     * Return sport psychologist levels
     *
     * @return integer
     */
    public function getSportPsychologistLevels()
    {
        return $this->getXml()->getElementsByTagName('SportPsychologistLevels')->item(0)->nodeValue;
    }

    /**
     * Return tactical assistant levels
     *
     * @return integer
     */
    public function getTacticalAssistantLevels()
    {
        return $this->getXml()->getElementsByTagName('TacticalAssistantLevels')->item(0)->nodeValue;
    }

    /**
     * Get old youth pull details
     *
     * @return \PHT\Xml\OldYouthPull
     */
    public function getOldYouthPull()
    {
        return new OldYouthPull($this->getXml());
    }

    /**
     * Get team staff details
     *
     * @return \PHT\Xml\Team\Staff
     */
    public function getStaff()
    {
        return Wrapper\Team\Senior::staff($this->getTeamId());
    }
}
