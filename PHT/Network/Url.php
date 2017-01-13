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

namespace PHT\Network;

class Url
{
    const OAUTH_SERVER = 'https://chpp.hattrick.org';
    const XML_SERVER = 'https://chpp.hattrick.org';
    const REQUEST_URL = '/oauth/request_token.ashx';
    const AUTH_URL = '/oauth/authorize.aspx';
    const AUTHC_URL = '/oauth/authenticate.aspx';
    const ACCESS_URL = '/oauth/access_token.ashx';
    const CHECK_URL = '/oauth/check_token.ashx';
    const INVAL_URL = '/oauth/invalidate_token.ashx';
    const CHPP_URL = '/chppxml.ashx';
    const ERROR_FILE = 'chpperror.xml';
}
