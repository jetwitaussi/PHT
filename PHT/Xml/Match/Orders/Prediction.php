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

namespace PHT\Xml\Match\Orders;

use PHT\Xml;

class Prediction extends Xml\File
{
    /**
     * Return match id
     *
     * @return integer
     */
    public function getMatchId()
    {
        return $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
    }

    /**
     * Return if match is youth
     *
     * @return boolean
     */
    public function isYouth()
    {
        return strtolower($this->getXml()->getElementsByTagName('IsYouth')->item(0)->nodeValue) == 'true';
    }

    /**
     * Return tactic type
     *
     * @return integer
     */
    public function getTacticType()
    {
        return $this->getXml()->getElementsByTagName('TacticType')->item(0)->nodeValue;
    }

    /**
     * Return tactic skill
     *
     * @return integer
     */
    public function getTacticSkill()
    {
        return $this->getXml()->getElementsByTagName('TacticSkill')->item(0)->nodeValue;
    }

    /**
     * Return midfield rating
     *
     * @return integer
     */
    public function getMidfield()
    {
        return $this->getXml()->getElementsByTagName('RatingMidfield')->item(0)->nodeValue;
    }

    /**
     * Return right defense rating
     *
     * @return integer
     */
    public function getRightDefense()
    {
        return $this->getXml()->getElementsByTagName('RatingRightDef')->item(0)->nodeValue;
    }

    /**
     * Return central defense rating
     *
     * @return integer
     */
    public function getCentralDefense()
    {
        return $this->getXml()->getElementsByTagName('RatingMidDef')->item(0)->nodeValue;
    }

    /**
     * Return left defense rating
     *
     * @return integer
     */
    public function getLeftDefense()
    {
        return $this->getXml()->getElementsByTagName('RatingLeftDef')->item(0)->nodeValue;
    }

    /**
     * Return right attack rating
     *
     * @return integer
     */
    public function getRightAttack()
    {
        return $this->getXml()->getElementsByTagName('RatingRightAtt')->item(0)->nodeValue;
    }

    /**
     * Return central attack rating
     *
     * @return integer
     */
    public function getCentralAttack()
    {
        return $this->getXml()->getElementsByTagName('RatingMidAtt')->item(0)->nodeValue;
    }

    /**
     * Return left attack rating
     *
     * @return integer
     */
    public function getLeftAttack()
    {
        return $this->getXml()->getElementsByTagName('RatingLeftAtt')->item(0)->nodeValue;
    }
}
