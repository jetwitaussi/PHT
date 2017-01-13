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

namespace PHT\Xml\User;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Achievements extends Xml\File
{
    /**
     * Return max points achievements
     *
     * @return integer
     */
    public function getMaxPoints()
    {
        return $this->getXml()->getElementsByTagName('MaxPoints')->item(0)->nodeValue;
    }

    /**
     * Return achievements number
     *
     * @return integer
     */
    public function getAchievementNumber()
    {
        return $this->getXml()->getElementsByTagName('Achievement')->length;
    }

    /**
     * Return achievement success object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Achievement\Success
     */
    public function getAchievement($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getAchievementNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Achievement');
            $achievement = new \DOMDocument('1.0', 'UTF-8');
            $achievement->appendChild($achievement->importNode($nodeList->item($index), true));
            return new Achievement\Success($achievement);
        }
        return null;
    }

    /**
     * Return iterator of achievement success objects
     *
     * @return \PHT\Xml\User\Achievement\Success[]
     */
    public function getAchievements()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Achievement');
        /** @var \PHT\Xml\User\Achievement\Success[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\User\Achievement\Success');
        return $data;
    }
}
