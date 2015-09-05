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

namespace PHT\Xml\User\Bookmark\League;

use PHT\Xml;
use PHT\Wrapper;

class Senior extends Xml\User\Bookmark\Element
{
    /**
     * Return senior league name
     *
     * @return string
     */
    public function getSeniorLeagueName()
    {
        return $this->getText();
    }

    /**
     * Return senior league id
     *
     * @return integer
     */
    public function getSeniorLeagueId()
    {
        return $this->getObjectId();
    }

    /**
     * Return senior league
     *
     * @return \PHT\Xml\World\League\Senior
     */
    public function getSeniorLeague()
    {
        return Wrapper\World\League::senior($this->getSeniorLeagueId());
    }

    /**
     * Return league id
     *
     * @return integer
     */
    public function getLeagueId()
    {
        return $this->getObjectId2();
    }

    /**
     * Return country
     *
     * @return \PHT\Xml\World\Country
     */
    public function getCountry()
    {
        return Wrapper\World::country($this->getLeagueId());
    }
}
