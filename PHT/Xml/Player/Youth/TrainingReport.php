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

namespace PHT\Xml\Player\Youth;

use PHT\Xml;
use PHT\Utils;

class TrainingReport extends Xml\Base
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
     * Return report text, in user's language
     *
     * @return string
     */
    public function getText()
    {
        return $this->getXml()->getElementsByTagName('ReportText')->item(0)->nodeValue;
    }

    /**
     * Return report date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('ReportDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return report type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('ReportType')->item(0)->nodeValue;
    }

    /**
     * Return skill named in report, 0 if report does not name a skill
     *
     * @return integer
     */
    public function getSkillType()
    {
        return $this->getXml()->getElementsByTagName('ReportSkillType')->item(0)->nodeValue;
    }

    /**
     * Return skill level named in report, only set when report type is 18, 19, 28, 29 or 30, otherwise 0
     *
     * @return integer
     */
    public function getSkillLevel()
    {
        return $this->getXml()->getElementsByTagName('ReportSkillLevel')->item(0)->nodeValue;
    }
}
