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

namespace PHT\Xml\Federations;

use PHT\Xml;

class Role extends Xml\Base
{
    /**
     * Create an instance
     *
     * @param \DOMDocument $xml
     */
    public function __construct($xml)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
    }

    /**
     * Return role id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('RoleID')->item(0)->nodeValue;
    }

    /**
     * Return role name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('RoleName')->item(0)->nodeValue;
    }

    /**
     * Return role rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->getXml()->getElementsByTagName('RoleRank')->item(0)->nodeValue;
    }

    /**
     * Return number of member
     *
     * @return integer
     */
    public function getTotalMember()
    {
        return $this->getXml()->getElementsByTagName('RoleMemberCount')->item(0)->nodeValue;
    }

    /**
     * Return maximum number of member
     *
     * @return integer
     */
    public function getMaximumMember()
    {
        return $this->getXml()->getElementsByTagName('RoleMaxMembers')->item(0)->nodeValue;
    }

    /**
     * Return the way to become member
     *
     * @return integer
     */
    public function getRequestType()
    {
        return $this->getXml()->getElementsByTagName('RoleRequestType')->item(0)->nodeValue;
    }

    /**
     * Return role description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getXml()->getElementsByTagName('RoleDescription')->item(0)->nodeValue;
    }
}
