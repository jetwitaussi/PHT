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

class E92 extends Xml\Match\Event
{
    /**
     * Return event name
     *
     * @return string
     */
    public function getName()
    {
        return 'Badly injured, leaves field';
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
     * Return player id
     *
     * @return integer
     */
    public function getInjuredPlayerId()
    {
        return $this->getRawSubjectPlayerId();
    }

    /**
     * Return player
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getInjuredPlayer()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Player\Youth::player($this->getInjuredPlayerId());
        }
        return Wrapper\Player\Senior::player($this->getInjuredPlayerId());
    }

    /**
     * Return player id
     *
     * @return integer
     */
    public function getReplacementPlayerId()
    {
        return $this->getRawObjectPlayerId();
    }

    /**
     * Return player
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getReplacementPlayer()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Player\Youth::player($this->getReplacementPlayerId());
        }
        return Wrapper\Player\Senior::player($this->getReplacementPlayerId());
    }
}
