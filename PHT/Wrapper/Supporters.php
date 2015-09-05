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

class Supporters
{
    /**
     * @param integer $teamId
     * @param integer $userId
     * @param integer $page
     * @param integer $size
     * @return \PHT\Xml\Supporters
     */
    public static function listing($teamId = null, $userId = null, $page = 0, $size = 50)
    {
        $params = array('file' => 'supporters', 'version' => Config\Version::SUPPORTERS, 'actionType' => 'supportedteams');
        if ($teamId !== null) {
            $params['teamId'] = $teamId;
            $params['actionType'] = 'mysupporters';
        } elseif ($userId !== null) {
            $params['userId'] = $userId;
        }
        if ($page !== null) {
            $params['pageIndex'] = $page;
        }
        if ($size !== null) {
            $params['pageSize'] = $size;
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Supporters(Network\Request::fetchUrl($url), $params);
    }
}
