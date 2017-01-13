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

use PHT\Utils;

class File extends Base
{
    protected $userId = null;
    protected $fetchedDate = null;
    protected $xmlFileVersion = null;
    protected $xmlFileName = null;

    /**
     * Create an instance
     *
     * @param string $xml
     */
    public function __construct($xml)
    {
        $this->xmlText = $xml;
        $this->xml = new \DOMDocument('1.0', 'UTF-8');
        $this->xml->loadXml($xml);
    }

    /**
     * Return UserId of connected user
     *
     * @return integer
     */
    public function getConnectedUserId()
    {
        if (!isset($this->userId) || $this->userId === null) {
            $this->userId = $this->getXml()->getElementsByTagName('UserID')->item(0)->nodeValue;
        }
        return $this->userId;
    }

    /**
     * Return fetched date of xml file
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getFetchedDate($format = null)
    {
        if (!isset($this->fetchedDate[$format]) || $this->fetchedDate[$format] === null) {
            $this->fetchedDate[$format] = $this->getXml()->getElementsByTagName('FetchedDate')->item(0)->nodeValue;
            if ($format !== null) {
                $this->fetchedDate[$format] = Utils\Date::convert($this->fetchedDate[$format], $format);
            }
        }
        return $this->fetchedDate[$format];
    }

    /**
     * Return version of xml file
     *
     * @return integer
     */
    public function getXmlFileVersion()
    {
        if (!isset($this->xmlFileVersion) || $this->xmlFileVersion === null) {
            $this->xmlFileVersion = $this->getXml()->getElementsByTagName('Version')->item(0)->nodeValue;
        }
        return $this->xmlFileVersion;
    }

    /**
     * Return name of xml file
     *
     * @return string
     */
    public function getXmlFileName()
    {
        if (!isset($this->xmlFileName) || $this->xmlFileName === null) {
            $this->xmlFileName = $this->getXml()->getElementsByTagName('FileName')->item(0)->nodeValue;
        }
        return $this->xmlFileName;
    }
}
