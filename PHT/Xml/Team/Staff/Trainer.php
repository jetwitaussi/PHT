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

namespace PHT\Xml\Team\Staff;

use PHT\Config\Config;
use PHT\Xml;
use PHT\Utils;
use PHT\Wrapper;

class Trainer extends Xml\Base
{
    private $teamId;

    /**
     * @param \DOMDocument $xml
     */
    public function __construct($xml, $teamId)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->teamId = $teamId;
    }

    /**
     * Return trainer id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('TrainerId')->item(0)->nodeValue;
    }

    /**
     * Return trainer name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('Name')->item(0)->nodeValue;
    }

    /**
     * Return trainer age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
    }

    /**
     * Return trainer age days
     *
     * @return integer
     */
    public function getDays()
    {
        return $this->getXml()->getElementsByTagName('AgeDays')->item(0)->nodeValue;
    }

    /**
     * Return trainer contract date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getContractDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('ContractDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return trainer type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('TrainerType')->item(0)->nodeValue;
    }

    /**
     * Return trainer leadership
     *
     * @return integer
     */
    public function getLeadership()
    {
        return $this->getXml()->getElementsByTagName('Leadership')->item(0)->nodeValue;
    }

    /**
     * Return trainer skill level
     *
     * @return integer
     */
    public function getSkill()
    {
        return $this->getXml()->getElementsByTagName('TrainerSkillLevel')->item(0)->nodeValue;
    }

    /**
     * Return trainer status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->getXml()->getElementsByTagName('TrainerStatus')->item(0)->nodeValue;
    }

    /**
     * Return trainer avatar
     *
     * @return \PHT\Xml\Team\Staff\TrainerAvatar
     */
    public function getAvatar()
    {
        return Wrapper\Team\Senior::traineravatar($this->teamId);
    }
}
