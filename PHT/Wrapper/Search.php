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

namespace PHT\Wrapper;

use PHT\Xml;
use PHT\Config;
use PHT\Network;

class Search
{
    /**
     * @param array $params
     * @return \PHT\Xml\Search\Response
     */
    public static function search($params)
    {
        $base = array('file' => 'search', 'version' => Config\Version::SEARCH);
        $base = array_merge($base, $params);
        $url = Network\Request::buildUrl($base);
        return new Xml\Search\Response(Network\Request::fetchUrl($url), $params);
    }

    /**
     * @param array $params
     * @return \PHT\Xml\Search\Market\Response
     */
    public static function market($params)
    {
        $base = array('file' => 'transfersearch', 'version' => Config\Version::TRANSFERSEARCH);
        $base = array_merge($base, $params);
        $url = Network\Request::buildUrl($base);
        return new Xml\Search\Market\Response(Network\Request::fetchUrl($url), $params);
    }
}
