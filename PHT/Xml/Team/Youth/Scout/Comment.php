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

namespace PHT\Xml\Team\Youth\Scout;

use PHT\Xml;

class Comment extends Xml\Base
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
     * Return text
     *
     * @return string
     */
    public function getText()
    {
        return $this->getXml()->getElementsByTagName('CommentText')->item(0)->nodeValue;
    }

    /**
     * Return type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('CommentType')->item(0)->nodeValue;
    }

    /**
     * Return variation
     *
     * @return integer
     */
    public function getVariation()
    {
        return $this->getXml()->getElementsByTagName('CommentVariation')->item(0)->nodeValue;
    }

    /**
     * Return skill type
     *
     * @return integer
     */
    public function getSkillType()
    {
        return $this->getXml()->getElementsByTagName('CommentSkillType')->item(0)->nodeValue;
    }

    /**
     * Return skill level
     *
     * @return integer
     */
    public function getSkillLevel()
    {
        return $this->getXml()->getElementsByTagName('CommentSkillLevel')->item(0)->nodeValue;
    }
}
