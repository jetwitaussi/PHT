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

class Federation
{
    /**
     * Find federations by name, set a minium of 3 characters
     *
     * @var string
     */
    public $name;

    /**
     * Find federations by abbreviation, set a minium of 3 characters
     *
     * @var string
     */
    public $abbreviation;

    /**
     * Find federations by description, set a minium of 3 characters
     *
     * @var string
     */
    public $description;

    /**
     * Request federations of connected user
     *
     * @var boolean
     */
    public $user;

    /**
     * Request federations for a language (0 = all languages)
     *
     * @var integer
     */
    public $language = 0;

    /**
     * Request a specific result page, first page = 0, each page contains 25 results max
     *
     * @var integer
     */
    public $page = 0;
}
