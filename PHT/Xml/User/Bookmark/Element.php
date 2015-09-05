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

namespace PHT\Xml\User\Bookmark;

use PHT\Xml;

class Element extends Xml\Base
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
     * Return bookmark id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('BookmarkID')->item(0)->nodeValue;
    }

    /**
     * Return bookmark type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('BookmarkTypeID')->item(0)->nodeValue;
    }

    /**
     * Return bookmark comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->getXml()->getElementsByTagName('Comment')->item(0)->nodeValue;
    }

    /**
     * Return bookmark text
     *
     * @return string
     */
    public function getText()
    {
        return $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
    }

    /**
     * Return bookmark text2
     *
     * @return string
     */
    public function getText2()
    {
        return $this->getXml()->getElementsByTagName('Text2')->item(0)->nodeValue;
    }

    /**
     * Return bookmark objectId
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
    }

    /**
     * Return bookmark objectId2
     *
     * @return integer
     */
    public function getObjectId2()
    {
        return $this->getXml()->getElementsByTagName('ObjectID2')->item(0)->nodeValue;
    }

    /**
     * Return bookmark objectId3
     *
     * @return integer
     */
    public function getObjectId3()
    {
        return $this->getXml()->getElementsByTagName('ObjectID3')->item(0)->nodeValue;
    }
}
