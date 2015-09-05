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

class E210 extends Xml\Match\Event
{
    /**
     * Return event name
     *
     * @return string
     */
    public function getName()
    {
        return 'No equalizer goal home team free kick';
    }

    /**
     * Goal?
     *
     * @return boolean
     */
    public function isGoal()
    {
        return false;
    }

    /**
     * Return player id
     *
     * @return integer
     */
    public function getAttackingPlayerId()
    {
        return $this->getRawSubjectPlayerId();
    }

    /**
     * Return player
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getAttackingPlayer()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Player\Youth::player($this->getAttackingPlayerId());
        }
        return Wrapper\Player\Senior::player($this->getAttackingPlayerId());
    }

    /**
     * Return team id
     *
     * @return integer
     */
    public function getAttackingTeamId()
    {
        return $this->getRawSubjectTeamId();
    }

    /**
     * Return team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth|\PHT\Xml\Team\National
     */
    public function getAttackingTeam()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Team\Youth::team($this->getAttackingTeamId());
        } elseif ($this->type == Config\Config::MATCH_NATIONAL) {
            return Wrapper\National::team($this->getAttackingTeamId());
        }
        return Wrapper\Team\Senior::team($this->getAttackingTeamId());
    }

    /**
     * Return player id
     *
     * @return integer
     */
    public function getDefendingGoalieId()
    {
        return $this->getRawObjectPlayerId();
    }

    /**
     * Return player
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth
     */
    public function getDefendingGoalie()
    {
        if ($this->type == Config\Config::MATCH_YOUTH) {
            return Wrapper\Player\Youth::player($this->getDefendingGoalieId());
        }
        return Wrapper\Player\Senior::player($this->getDefendingGoalieId());
    }
}
