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
use PHT\Utils;
use PHT\Wrapper;

class Member extends Xml\Base
{
    private $federationId;

    /**
     * Create an instance
     *
     * @param \DOMDocument $xml
     * @param integer $federationId
     */
    public function __construct($xml, $federationId)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->federationId = $federationId;
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
     * Return user
     *
     * @return \PHT\Xml\User
     */
    public function getUser()
    {
        return Wrapper\User::user($this->getId());
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
     * Return user role id
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->getXml()->getElementsByTagName('RoleID')->item(0)->nodeValue;
    }

    /**
     * Return user role
     *
     * @return \PHT\Xml\Federations\Role
     */
    public function getRole()
    {
        $roles = Wrapper\Federation::roles($this->federationId);
        foreach ($roles->getRoles() as $role) {
            if ($role->getId() == $this->getRoleId()) {
                return $role;
            }
        }
        return null;
    }

    /**
     * Return user role name
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->getXml()->getElementsByTagName('RoleName')->item(0)->nodeValue;
    }

    /**
     * Return user ship date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getShipDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('MemberShipDate')->item(0)->nodeValue, $format);
    }
}
