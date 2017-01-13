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
use PHT\Wrapper;

class Youth extends Xml\User\Bookmark\Element
{
    /**
     * Return youth player name
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->getText();
    }

    /**
     * Return youth player id
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->getObjectId();
    }

    /**
     * Return youth player
     *
     * @param boolean $unlockSkills
     * @param boolean $showScoutCall
     * @param boolean $showLastMatch
     * @return \PHT\Xml\Player\Youth
     */
    public function getPlayer($unlockSkills = false, $showScoutCall = true, $showLastMatch = true)
    {
        return Wrapper\Player\Youth::player($this->getPlayerId(), $unlockSkills, $showScoutCall, $showLastMatch);
    }
}
