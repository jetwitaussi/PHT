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

namespace PHT\Xml\User\Bookmark\Team;

use PHT\Xml;
use PHT\Wrapper;

class Senior extends Xml\User\Bookmark\Element
{
    /**
     * Return senior team name
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getText();
    }

    /**
     * Return senior team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getObjectId();
    }

    /**
     * Return senior team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return team's user name
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->getText2();
    }
}
