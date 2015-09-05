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
use PHT\Wrapper;

class Chunk extends Xml\Base
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
     * Return user id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('UserID')->item(0)->nodeValue;
    }

    /**
     * Return user name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('Loginname')->item(0)->nodeValue;
    }

    /**
     * Return user
     *
     * @return \PHT\Xml\User
     */
    public function getUser()
    {
        return Wrapper\User::user($this->getId());
    }
}
