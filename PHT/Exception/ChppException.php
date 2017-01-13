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

namespace PHT\Exception;

class ChppException extends \Exception
{
    /**
     * @var \DOMDocument
     */
    private $xml;

    /**
     * @param string $message
     * @param integer $code
     * @param \Exception $previous
     */
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->xml = new \DOMDocument('1.0', 'UTF-8');
        $this->xml->loadXML($message);
    }

    /**
     * Return error code
     *
     * @return integer
     */
    public function getErrorCode()
    {
        return $this->xml->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
    }

    /**
     * Return error
     *
     * @return string
     */
    public function getError()
    {
        return $this->xml->getElementsByTagName('Error')->item(0)->nodeValue;
    }

    /**
     * Return error guid
     *
     * @return string
     */
    public function getErrorGuid()
    {
        return $this->xml->getElementsByTagName('ErrorGUID')->item(0)->nodeValue;
    }

    /**
     * Return server
     *
     * @return string
     */
    public function getServer()
    {
        return $this->xml->getElementsByTagName('Server')->item(0)->nodeValue;
    }

    /**
     * Return request
     *
     * @return string
     */
    public function getRequest()
    {
        return $this->xml->getElementsByTagName('Request')->item(0)->nodeValue;
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
        if ($asObject === true) {
            return $this->xml;
        }
        return $this->xml->saveXML($onlyNode);
    }

    /**
     * Return xml string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getXml(false);
    }

}
