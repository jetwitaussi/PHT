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

namespace PHT\Xml\User\Bookmark\Player;

use PHT\Xml;
use PHT\Utils;
use PHT\Wrapper;

class Senior extends Xml\User\Bookmark\Element
{
    /**
     * Return senior player name
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->getText();
    }

    /**
     * Return senior player id
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->getObjectId();
    }

    /**
     * Return senior player
     *
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Player\Senior
     */
    public function getPlayer($includeMatchInfo = true)
    {
        return Wrapper\Player\Senior::player($this->getPlayerId(), $includeMatchInfo);
    }

    /**
     * Return senior player deadline
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDeadline($format = null)
    {
        if ($this->getText2()) {
            return Utils\Date::convert($this->getText2(), $format);
        }
        return null;
    }
}
