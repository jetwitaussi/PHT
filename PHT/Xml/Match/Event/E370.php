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

namespace PHT\Xml\Match\Event;

use PHT\Xml;
use PHT\Config;
use PHT\Wrapper;

class E370 extends Xml\Match\Event
{
    /**
     * Return event name
     *
     * @return string
     */
    public function getName()
    {
        return 'Player position swap: team is behind';
    }

    /**
     * Return team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getRawSubjectTeamId();
    }

    /**
     * Return team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth|\PHT\Xml\Team\National
     */
    public function getTeam()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Team\Youth::team($this->getTeamId());
        } elseif ($this->type == Config\Config::MATCH_NATIONAL) {
            return Wrapper\National::team($this->getTeamId());
        }
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return player id, leaving player or player changing behaviour or player swap
     *
     * @return integer
     */
    public function getPlayerSwappedId()
    {
        return $this->getRawSubjectPlayerId();
    }

    /**
     * Return player, leaving player or player changing behaviour or player swap
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getPlayerSwapped()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Player\Youth::player($this->getPlayerSwappedId());
        }
        return Wrapper\Player\Senior::player($this->getPlayerSwappedId());
    }

    /**
     * Return player id, entering player or player changing behaviour or player swap
     *
     * @return integer
     */
    public function getPlayerSwapWithId()
    {
        return $this->getRawObjectPlayerId();
    }

    /**
     * Return player, entering player or player changing behaviour or player swap
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getPlayerSwapWith()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Player\Youth::player($this->getPlayerSwapWithId());
        }
        return Wrapper\Player\Senior::player($this->getPlayerSwapWithId());
    }
}
