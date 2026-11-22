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

namespace PHT\Xml\Team\Senior\Training;

use PHT\Xml;
use PHT\Utils;

class Change extends Xml\Base
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
     * Return global season of first week with this training
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->getXml()->getElementsByTagName('HattrickSeason')->item(0)->nodeValue;
    }

    /**
     * Return week (1-16) of first week with this training
     *
     * @return integer
     */
    public function getWeek()
    {
        return $this->getXml()->getElementsByTagName('HattrickWeek')->item(0)->nodeValue;
    }

    /**
     * Return training type (see \PHT\Config\Config TRAINING_* constants)
     *
     * @return integer
     */
    public function getTrainingType()
    {
        return $this->getXml()->getElementsByTagName('TrainingType')->item(0)->nodeValue;
    }

    /**
     * Return training intensity, in percent
     *
     * @return integer
     */
    public function getTrainingLevel()
    {
        return $this->getXml()->getElementsByTagName('TrainingLevel')->item(0)->nodeValue;
    }

    /**
     * Return share of training spent on stamina, in percent
     *
     * @return integer
     */
    public function getStaminaTrainingPart()
    {
        return $this->getXml()->getElementsByTagName('TrainingLevelStamina')->item(0)->nodeValue;
    }

    /**
     * Return date of first training with these settings
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('TrainingDate')->item(0)->nodeValue, $format);
    }
}
