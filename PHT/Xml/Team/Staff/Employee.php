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

use PHT\Xml;
use PHT\Utils;
use PHT\Wrapper;

class Employee extends Xml\Base
{
    private $teamId;

    /**
     * @param \DOMDocument $xml
     * @param integer $teamId
     */
    public function __construct($xml, $teamId)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->teamId = $teamId;
    }

    /**
     * Return employee name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('Name')->item(0)->nodeValue;
    }

    /**
     * Return employee id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('StaffId')->item(0)->nodeValue;
    }

    /**
     * Return employee type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('StaffType')->item(0)->nodeValue;
    }

    /**
     * Return employee level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->getXml()->getElementsByTagName('StaffLevel')->item(0)->nodeValue;
    }

    /**
     * Return employee hired date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getHiredDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('HiredDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return employee cost
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getCost($countryCurrency = null)
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('Cost')->item(0)->nodeValue, $countryCurrency);
    }

    /**
     * Return hof player id
     *
     * @return integer
     */
    public function getHofPlayerId()
    {
        $id = $this->getXml()->getElementsByTagName('HofPlayerId')->item(0)->nodeValue;
        if ($id > 0) {
            return $id;
        }
        return null;
    }

    /**
     * Return hof player if any
     *
     * @return \PHT\Xml\Player\Hof
     */
    public function getHofPlayer()
    {
        if ($this->getHofPlayerId()) {
            $hof = Wrapper\Team\Senior::hofplayers($this->teamId);
            foreach ($hof->getPlayers() as $player) {
                if ($player->getId() == $this->getHofPlayerId()) {
                    return $player;
                }
            }
        }
        return null;
    }
}
