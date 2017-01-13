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
use PHT\Wrapper;

class Token extends File
{
    /**
     * Return token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->getXml()->getElementsByTagName('Token')->item(0)->nodeValue;
    }

    /**
     * Return creation date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getCreationDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('Created')->item(0)->nodeValue, $format);
    }

    /**
     * Return user id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->getXml()->getElementsByTagName('User')->item(0)->nodeValue;
    }

    /**
     * Return user
     *
     * @return \PHT\Xml\User
     */
    public function getUser()
    {
        return Wrapper\User::user($this->getUserId());
    }

    /**
     * Return expiration date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getExpirationDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('Expires')->item(0)->nodeValue, $format);
    }

    /**
     * Return granted permissions
     *
     * @return string
     */
    public function getExtendedPermissions()
    {
        return $this->getXml()->getElementsByTagName('ExtendedPermissions')->item(0)->nodeValue;
    }

    /**
     * Return if token is valid or not
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->getXml()->getElementsByTagName('Expires')->length != 0;
    }
}
