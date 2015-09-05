<?php
/**
 * PHT
 *
 * @author Telesphore
 * @link https://github.com/jetwitaussi/PHT
 * @version 3.0
 * @license "THE BEER-WARE LICENSE" (Revision 42):
 * Telesphore wrote this file.  As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return.
 */

namespace PHT\Exception;

class Exception extends \Exception
{
    /**
     * @var \DOMDocument
     */
    private $xml;
    private $isXml;

    /**
     * @param string $message
     * @param boolean $isXml
     */
    public function __construct($message, $isXml = false)
    {
        $this->isXml = $isXml;
        parent::__construct($message);
        if ($this->isXmlError()) {
            $this->xml = new \DOMDocument('1.0', 'UTF-8');
            $this->xml->loadXML($message);
        }
    }

    /**
     * Is error xml ?
     *
     * @return boolean
     */
    public function isXmlError()
    {
        return $this->isXml;
    }

    /**
     * Return error code
     *
     * @return integer
     */
    public function getErrorCode()
    {
        if ($this->isXmlError()) {
            return $this->xml->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
        }
        return parent::getCode();
    }

    /**
     * Return error
     *
     * @return string
     */
    public function getError()
    {
        if ($this->isXmlError()) {
            return $this->xml->getElementsByTagName('Error')->item(0)->nodeValue;
        }
        return parent::getMessage();
    }

    /**
     * Return error guid
     *
     * @return string
     */
    public function getErrorGuid()
    {
        if ($this->isXmlError()) {
            return $this->xml->getElementsByTagName('ErrorGUID')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return server
     *
     * @return string
     */
    public function getServer()
    {
        if ($this->isXmlError()) {
            return $this->xml->getElementsByTagName('Server')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return request
     *
     * @return string
     */
    public function getRequest()
    {
        if ($this->isXmlError()) {
            return $this->xml->getElementsByTagName('Request')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return xml
     *
     * @param boolean $asObject
     * @param string $onlyNode only used if $asObject is false
     * @return \DOMDocument|string
     */
    public function getXml($asObject = true, $onlyNode = null)
    {
        if ($this->isXmlError()) {
            if ($asObject === true) {
                return $this->xml;
            }
            return $this->xml->saveXML($onlyNode);
        }
        return null;
    }

}
