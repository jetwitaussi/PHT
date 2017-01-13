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

namespace PHT\Xml\Team;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Staff extends Xml\File
{
    private $teamId;

    public function __construct($xml, $teamId)
    {
        parent::__construct($xml);
        $this->teamId = $teamId;
    }

    /**
     * Return number of employees
     *
     * @return integer
     */
    public function getEmployeeNumber()
    {
        return $this->getXml()->getElementsByTagName('Staff')->length;
    }

    /**
     * Return employee object
     *
     * @param integer $index
     * @return \PHT\Xml\Team\Staff\Employee
     */
    public function getEmployee($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getEmployeeNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Staff');
            $staff = new \DOMDocument('1.0', 'UTF-8');
            $staff->appendChild($staff->importNode($nodeList->item($index), true));
            return new Staff\Employee($staff, $this->teamId);
        }
        return null;
    }

    /**
     * Return iterator of employee objects
     *
     * @return \PHT\Xml\Team\Staff\Employee[]
     */
    public function getEmployees()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Staff');
        /** @var \PHT\Xml\Team\Staff\Employee[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Team\Staff\Employee', $this->teamId);
        return $data;
    }

    /**
     * Return staff total costs
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getCosts($countryCurrency = null)
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('TotalCost')->item(0)->nodeValue, $countryCurrency);
    }
}
