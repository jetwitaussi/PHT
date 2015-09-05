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

namespace PHT\Config;

class Search
{
    /**
     * Search arena by arena name
     *
     * @var string
     */
    public $arenaName;

    /**
     * Search senior player by player id
     *
     * @var integer
     */
    public $seniorPlayerId;

    /**
     * Search senior match by match id
     *
     * @var integer
     */
    public $seniorMatchId;

    /**
     * Search senior team by team id
     *
     * @var integer
     */
    public $seniorTeamId;

    /**
     * Search senior team by team name
     *
     * @var string
     */
    public $seniorTeamName;

    /**
     * Search senior player by player firstname
     *
     * @var string
     */
    public $seniorPlayerFirstName;

    /**
     * Search senior player by player lastname
     *
     * @var string
     */
    public $seniorPlayerLastName;

    /**
     * Search senior league by league name
     *
     * @var string
     */
    public $seniorLeagueName;

    /**
     * Search region by region name
     *
     * @var string
     */
    public $regionName;

    /**
     * Search user by user name
     *
     * @var string
     */
    public $userName;

    /**
     * Filter search by country league id
     *
     * @var integer
     */
    public $countryLeagueId = null;

    /**
     * Request a specific result page, first page = 0, each page contains 25 results max
     *
     * @var integer
     */
    public $page = 0;
}
