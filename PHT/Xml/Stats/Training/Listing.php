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

namespace PHT\Xml\Stats\Training;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Listing extends Xml\File
{
    /**
     * Return league id
     *
     * @return integer
     */
    public function getLeagueId()
    {
        return $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
    }

    /**
     * Return country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country($this->getLeagueId());
    }

    /**
     * Return number of training types
     *
     * @return integer
     */
    public function getTrainingTypeNumber()
    {
        return $this->getXml()->getElementsByTagName('TrainingStat')->length;
    }

    /**
     * Return training type object
     *
     * @param integer $index
     * @return \PHT\Xml\Stats\Training\Type
     */
    public function getTrainingType($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getTrainingTypeNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//TrainingType');
            $training = new \DOMDocument('1.0', 'UTF-8');
            $training->appendChild($training->importNode($nodeList->item($index)->parentNode, true));
            return new Type($training);
        }
        return null;
    }

    /**
     * Return iterator of training type objects
     *
     * @return \PHT\Xml\Stats\Training\Type[]
     */
    public function getTrainingTypes()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//TrainingType');
        /** @var \PHT\Xml\Stats\Training\Type[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Stats\Training\Type');
        return $data;
    }
}
