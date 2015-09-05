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

class Youth extends Xml\User\Bookmark\Element
{
    /**
     * Return youth league name
     *
     * @return string
     */
    public function getYouthLeagueName()
    {
        return $this->getText();
    }

    /**
     * Return youth league id
     *
     * @return integer
     */
    public function getYouthLeagueId()
    {
        return $this->getObjectId();
    }

    /**
     * Return youth league
     *
     * @return \PHT\Xml\World\League\Youth
     */
    public function getYouthLeague()
    {
        return Wrapper\World\League::youth($this->getYouthLeagueId());
    }
}
