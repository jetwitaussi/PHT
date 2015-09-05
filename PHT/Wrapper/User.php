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

class User
{
    /**
     * @param integer $userId
     * @return \PHT\Xml\User
     */
    public static function user($userId = null)
    {
        $params = array('file' => 'managercompendium', 'version' => Config\Version::MANAGERCOMPENDIUM);
        if ($userId !== null) {
            $params['userId'] = $userId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\User(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @return \PHT\Xml\Club
     */
    public static function club($teamId = null)
    {
        $params = array('file' => 'club', 'version' => Config\Version::CLUB);
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Club(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $userId
     * @return \PHT\Xml\User\Achievements
     */
    public static function achievements($userId = null)
    {
        $params = array('file' => 'achievements', 'version' => Config\Version::ACHIEVEMENTS);
        if ($userId !== null) {
            $params['userID'] = $userId;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\User\Achievements(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $type
     * @return \PHT\Xml\User\Bookmarks
     */
    public static function bookmarks($type = Config\Config::BOOKMARK_ALL)
    {
        $url = Network\Request::buildUrl(array('file' => 'bookmarks', 'bookmarkTypeID' => $type, 'version' => Config\Version::BOOKMARKS));
        return new Xml\User\Bookmarks(Network\Request::fetchUrl($url));
    }
}
