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

namespace PHT\Wrapper\Player;

use PHT\Xml;
use PHT\Config;
use PHT\Network;

class Youth
{
    /**
     * @param integer $playerId
     * @param boolean $unlockSkills
     * @param boolean $showScoutCall
     * @param boolean $showLastMatch
     * @return \PHT\Xml\Player\Youth
     */
    public static function player($playerId, $unlockSkills = false, $showScoutCall = true, $showLastMatch = true)
    {
        $params = array('file' => 'youthplayerdetails', 'youthPlayerId' => $playerId, 'version' => Config\Version::YOUTHPLAYERDETAILS, 'actionType' => 'details');
        if ($unlockSkills === true) {
            $params['actionType'] = 'unlockskills';
        }
        if ($showScoutCall === true) {
            $params['showScoutCall'] = 'true';
        }
        if ($showLastMatch === true) {
            $params['showLastMatch'] = 'true';
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Player\Youth(Network\Request::fetchUrl($url));
    }
}
