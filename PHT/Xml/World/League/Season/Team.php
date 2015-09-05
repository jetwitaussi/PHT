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

namespace PHT\Xml\World\League\Season;

use PHT\Config;
use PHT\Wrapper;

class Team
{
    private $type = null;
    private $id = null;
    private $name = null;
    private $goalsFor = null;
    private $goalsAgainst = null;
    private $won = null;
    private $draw = null;
    private $lost = null;
    private $points = null;
    private $played = null;

    /**
     * @param integer $id
     * @param array $data
     * @param string $type
     */
    public function __construct($id, $data, $type)
    {
        $this->type = $type;
        $this->id = $id;
        $this->name = $data['name'];
        $this->points = $data['points'];
        $this->goalsFor = $data['goalsfor'];
        $this->goalsAgainst = $data['goalsagainst'];
        $this->won = $data['won'];
        $this->draw = $data['draw'];
        $this->lost = $data['lost'];
        $this->played = $data['played'];
    }

    /**
     * Return team id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get team details
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth
     */
    public function getTeam()
    {
        if ($this->type == Config\Config::SENIOR) {
            return Wrapper\Team\Senior::team($this->getId());
        } elseif ($this->type == Config\Config::YOUTH) {
            return Wrapper\Team\Youth::team($this->getId());
        }
        return null;
    }

    /**
     * Return team name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return points number
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Return goals for team
     *
     * @return integer
     */
    public function getGoalsFor()
    {
        return $this->goalsFor;
    }

    /**
     * Return goals against team
     *
     * @return integer
     */
    public function getGoalsAgainst()
    {
        return $this->goalsAgainst;
    }

    /**
     * Return goal average
     *
     * @return integer
     */
    public function getGoalAverage()
    {
        return $this->getGoalsFor() - $this->getGoalsAgainst();
    }

    /**
     * Return number of won matches
     *
     * @return integer
     */
    public function getWon()
    {
        return $this->won;
    }

    /**
     * Return number of draw
     *
     * @return integer
     */
    public function getDraw()
    {
        return $this->draw;
    }

    /**
     * Return number of lost matches
     *
     * @return integer
     */
    public function getLost()
    {
        return $this->lost;
    }

    /**
     * Return number of played matches
     *
     * @return integer
     */
    public function getNumberPlayedMatches()
    {
        return $this->played;
    }
}
