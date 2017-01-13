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

namespace PHT\Xml\Team\Senior;

use PHT\Xml;
use PHT\Utils;
use PHT\Config;

class Training extends Xml\HTSupporter
{
    /**
     * Return training level
     *
     * @return integer
     */
    public function getTrainingLevel()
    {
        return $this->getXml()->getElementsByTagName('TrainingLevel')->item(0)->nodeValue;
    }

    /**
     * Return new training level if available
     *
     * @return integer
     */
    public function getNewTrainingLevel()
    {
        if ($this->getXml()->getElementsByTagName('NewTrainingLevel')->item(0)->getAttribute('Available')) {
            return $this->getXml()->getElementsByTagName('NewTrainingLevel')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return training type
     *
     * @return integer
     */
    public function getTrainingType()
    {
        return $this->getXml()->getElementsByTagName('TrainingType')->item(0)->nodeValue;
    }

    /**
     * Return training stamina part
     *
     * @return integer
     */
    public function getStaminaTrainingPart()
    {
        return $this->getXml()->getElementsByTagName('StaminaTrainingPart')->item(0)->nodeValue;
    }

    /**
     * Return trainer id
     *
     * @return integer
     */
    public function getTrainerId()
    {
        return $this->getXml()->getElementsByTagName('TrainerID')->item(0)->nodeValue;
    }

    /**
     * Return trainer name
     *
     * @return string
     */
    public function getTrainerName()
    {
        return $this->getXml()->getElementsByTagName('TrainerName')->item(0)->nodeValue;
    }

    /**
     * Return trainer arrival date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getTrainerArrivalDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('ArrivalDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return team spirit level
     *
     * @return integer
     */
    public function getTeamSpirit()
    {
        if (strtolower($this->getXml()->getElementsByTagName('Morale')->item(0)->getAttribute('Available')) == "true") {
            return $this->getXml()->getElementsByTagName('Morale')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return self confidence level
     *
     * @return integer
     */
    public function getSelfConfidence()
    {
        if (strtolower($this->getXml()->getElementsByTagName('SelfConfidence')->item(0)->getAttribute('Available')) == "true") {
            return $this->getXml()->getElementsByTagName('SelfConfidence')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return 442 experience level
     *
     * @return integer
     */
    public function get442Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience442')->item(0)->nodeValue;
    }

    /**
     * Return 343 experience level
     *
     * @return integer
     */
    public function get433Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience433')->item(0)->nodeValue;
    }

    /**
     * Return 451 experience level
     *
     * @return integer
     */
    public function get451Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience451')->item(0)->nodeValue;
    }

    /**
     * Return 352 experience level
     *
     * @return integer
     */
    public function get352Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience352')->item(0)->nodeValue;
    }

    /**
     * Return 532 experience level
     *
     * @return integer
     */
    public function get532Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience532')->item(0)->nodeValue;
    }

    /**
     * Return 343 experience level
     *
     * @return integer
     */
    public function get343Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience343')->item(0)->nodeValue;
    }

    /**
     * Return 541 experience level
     *
     * @return integer
     */
    public function get541Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience541')->item(0)->nodeValue;
    }

    /**
     * Return 523 experience level
     *
     * @return integer
     */
    public function get523Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience523')->item(0)->nodeValue;
    }

    /**
     * Return 550 experience level
     *
     * @return integer
     */
    public function get550Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience550')->item(0)->nodeValue;
    }

    /**
     * Return 253 experience level
     *
     * @return integer
     */
    public function get253Experience()
    {
        return $this->getXml()->getElementsByTagName('Experience253')->item(0)->nodeValue;
    }

    /**
     * Return number of player with special training
     *
     * @return integer
     */
    public function getSpecialTrainingNumber()
    {
        return $this->getXml()->getElementsByTagName('Player')->length;
    }

    /**
     * Return special training object
     *
     * @param integer $index
     * @return \PHT\Xml\Team\Senior\Training\Special
     */
    public function getSpecialTraining($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getSpecialTrainingNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Player');
            $player = new \DOMDocument('1.0', 'UTF-8');
            $player->appendChild($player->importNode($nodeList->item($index)->parentNode, true));
            return new Training\Special($player);
        }
        return null;
    }

    /**
     * Return iterator of special training objects
     *
     * @return \PHT\Xml\Team\Senior\Training\Special
     */
    public function getSpecialTrainings()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Player');
        /** @var \PHT\Xml\Team\Senior\Training\Special $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Team\Senior\Training\Special');
        return $data;
    }

    /**
     * Return last training type
     *
     * @return integer
     */
    public function getLastTrainingTrainingType()
    {
        return $this->getXml()->getElementsByTagName('LastTrainingTrainingType')->item(0)->nodeValue;
    }

    /**
     * Return last training level
     *
     * @return integer
     */
    public function getLastTrainingTrainingLevel()
    {
        return $this->getXml()->getElementsByTagName('LastTrainingTrainingLevel')->item(0)->nodeValue;
    }

    /**
     * Return last training stamina part level
     *
     * @return integer
     */
    public function getLastTrainingStaminaTrainingPart()
    {
        return $this->getXml()->getElementsByTagName('LastTrainingStaminaTrainingPart')->item(0)->nodeValue;
    }
}
