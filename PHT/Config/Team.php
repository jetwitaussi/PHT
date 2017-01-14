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

class Team
{
    /**
     * Set to id to get a specific team
     * @var integer|boolean
     */
    public $id = null;

    /**
     * Set to true to get the user's primary team
     * @var boolean
     */
    public $primary = false;

    /**
     * Set to true to get the user's secondary team
     * @var boolean
     */
    public $secondary = false;

    /**
     * Set to true to get the user's HTI team, not used for youth team
     * @var boolean
     */
    public $international = false;

    /**
     * Set to id to get user's team, combine with primary or secondary
     * @var integer
     */
    public $userId = null;
}
