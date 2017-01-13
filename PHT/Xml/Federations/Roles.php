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
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Roles extends Xml\File
{
    /**
     * Return federation id
     *
     * @return integer
     */
    public function getFederationId()
    {
        return $this->getXml()->getElementsByTagName('AllianceID')->item(0)->nodeValue;
    }

    /**
     * Return federation details
     *
     * @return \PHT\Xml\Federations\Federation
     */
    public function getFederation()
    {
        return Wrapper\Federation::detail($this->getFederationId());
    }

    /**
     * Return federation name
     *
     * @return string
     */
    public function getFederationName()
    {
        return $this->getXml()->getElementsByTagName('AllianceName')->item(0)->nodeValue;
    }

    /**
     * Return number of roles
     *
     * @return integer
     */
    public function getRoleNumber()
    {
        return $this->getXml()->getElementsByTagName('Role')->length;
    }

    /**
     * Return role object
     *
     * @param integer $index
     * @return \PHT\Xml\Federations\Role
     */
    public function getRole($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getRoleNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Role');
            $role = new \DOMDocument('1.0', 'UTF-8');
            $role->appendChild($role->importNode($nodeList->item($index), true));
            return new Role($role);
        }
        return null;
    }

    /**
     * Return iterator of role objects
     *
     * @return \PHT\Xml\Federations\Role[]
     */
    public function getRoles()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Role');
        /** @var \PHT\Xml\Federations\Role[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Federations\Role');
        return $data;
    }
}
