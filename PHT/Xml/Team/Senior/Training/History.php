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
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class History extends Xml\File
{
    /**
     * Return team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return team object
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return date history starts from, history covers week of this date and every week after it
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getFromDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('FromDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return number of training changes
     *
     * @return integer
     */
    public function getChangeNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//TrainingHistory/Training')->length;
    }

    /**
     * Return training change object, oldest first, first one is training of the week of from date
     *
     * @param integer $index
     * @return \PHT\Xml\Team\Senior\Training\Change
     */
    public function getChange($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getChangeNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//TrainingHistory/Training');
            $change = new \DOMDocument('1.0', 'UTF-8');
            $change->appendChild($change->importNode($nodeList->item($index), true));
            return new Change($change);
        }
        return null;
    }

    /**
     * Return iterator of training change objects, oldest first
     *
     * @return \PHT\Xml\Team\Senior\Training\Change[]
     */
    public function getChanges()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//TrainingHistory/Training');
        /** @var \PHT\Xml\Team\Senior\Training\Change[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Team\Senior\Training\Change');
        return $data;
    }
}
