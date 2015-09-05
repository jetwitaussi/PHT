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

namespace PHT\Xml\Team\Staff;

use PHT\Xml;
use PHT\Wrapper;

class Avatar extends Xml\Avatar
{
    private $teamId;

    /**
     * @param \DOMDocument $xml
     * @param array $data
     */
    public function __construct($xml, $data)
    {
        parent::__construct($xml);
        $this->teamId = $data['team'];
    }

    /**
     * Return employee id
     *
     * @return integer
     */
    public function getEmployeeId()
    {
        return $this->getXml()->getElementsByTagName('StaffId')->item(0)->nodeValue;
    }

    /**
     * Return employee
     *
     * @return \PHT\Xml\Team\Staff\Employee
     */
    public function getEmployee()
    {
        $staff = Wrapper\Team\Senior::staff($this->teamId);
        foreach ($staff->getEmployees() as $employee) {
            if ($employee->getId() == $this->getEmployeeId()) {
                return $employee;
            }
        }
        return null;
    }
}
