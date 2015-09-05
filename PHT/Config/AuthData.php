<?php
/**
 * PHT
 *
 * @author Telesphore
 * @link https://github.com/jetwitaussi/PHT
 * @version 3.0
 * @license "THE BEER-WARE LICENSE" (Revision 42):
 * Telesphore wrote this file.  As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return.
 */

namespace PHT\Config;

class AuthData
{
    /**
     *
     * @var string Temporary token given at first auth step and needed in second step
     */
    public $temporaryToken;

    /**
     * @var string Hattrick auth url to redirect user
     */
    public $url;

    /**
     * @var string Final oauth token for chpp access
     */
    public $oauthToken;

    /**
     * @var string Final oauth token secret for chpp access
     */
    public $oauthTokenSecret;
}
