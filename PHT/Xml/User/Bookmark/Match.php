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

namespace PHT\Xml\User\Bookmark;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Match extends Xml\User\Bookmark\Element
{
    /**
     * Return teams names
     *
     * @return string
     */
    public function getTeams()
    {
        return $this->getText();
    }

    /**
     * Return match id
     *
     * @return integer
     */
    public function getMatchId()
    {
        return $this->getObjectId();
    }

    /**
     * Return match
     *
     * @return \PHT\Xml\Match
     */
    public function getMatch()
    {
        if ($this->getType() == Config\Config::BOOKMARK_YOUTH_MATCH) {
            return Wrapper\Match::youth($this->getMatchId());
        }
        return Wrapper\Match::senior($this->getMatchId());
    }

    /**
     * Return match date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getMatchDate($format = null)
    {
        return Utils\Date::convert($this->getText2(), $format);
    }
}
