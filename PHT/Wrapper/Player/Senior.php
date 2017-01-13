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
use PHT\Exception;
use PHT\Utils;

class Senior
{
    /**
     * @param integer $playerId
     * @param boolean $includeMatchInfo
     * @return \PHT\Xml\Player\Senior
     */
    public static function player($playerId, $includeMatchInfo = true)
    {
        $params = array('file' => 'playerdetails', 'version' => Config\Version::PLAYERDETAILS, 'playerID' => $playerId);
        if ($includeMatchInfo == true) {
            $params['includeMatchInfo'] = 'true';
        }
        $url = Network\Request::buildUrl($params);
        return new Xml\Player\Senior(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $playerId
     * @return \PHT\Xml\Player\Transfer\History
     */
    public static function transfershistory($playerId)
    {
        $url = Network\Request::buildUrl(array('file' => 'transfersplayer', 'playerID' => $playerId, 'version' => Config\Version::TRANSFERSPLAYER));
        return new Xml\Player\Transfer\History(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $teamId
     * @param integer $playerId
     * @param integer $countryCurrency
     * @param integer $amount
     * @param integer $maxAmount
     * @return \PHT\Xml\Player\Senior
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public static function bid($teamId, $playerId, $countryCurrency, $amount = null, $maxAmount = null)
    {
        if ($amount === null && $maxAmount === null || ($amount !== null && $amount <= 0) || ($maxAmount !== null && $maxAmount <= 0)) {
            throw new Exception\InvalidArgumentException('To bid on a player you need to set a positive $amount or $maxAmount or both');
        }
        $params = array('file' => 'playerdetails', 'actionType' => 'placeBid', 'teamId' => $teamId, 'playerID' => $playerId, 'version' => Config\Version::PLAYERDETAILS);
        if ($amount !== null) {
            $amount = Utils\Money::toSEK($amount, $countryCurrency);
            $params['placeBid'] = $amount;
        }
        if ($maxAmount !== null) {
            $maxAmount = Utils\Money::toSEK($maxAmount, $countryCurrency);
            $params['maxBidAmount'] = $maxAmount;
        }
        $url = Network\Request::buildUrl($params);
        $data = Network\Request::fetchUrl($url);
        $xml = new \DOMDocument('1.0', 'UTF-8');
        $xml->loadXml($data);
        $xpath = new \DOMXPath($xml);
        $nodeList = $xpath->query("//View");
        $node = new \DOMDocument('1.0', 'UTF-8');
        $node->appendChild($node->importNode($nodeList->item(0), true));
        return new Xml\Player\Senior($node);
    }

    /**
     * @param integer $transferId
     * @param integer $category
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public static function ignoretransfer($transferId = null, $category = null)
    {
        if ($transferId === null && $category === null) {
            throw new Exception\InvalidArgumentException('At least one parameter is mandatory, either $transferId or $category');
        }
        $params = array('file' => 'currentbids', 'version' => Config\Version::CURRENTBIDS, 'actionType' => 'ignoreTransfer');
        if ($transferId !== null) {
            $params['transferId'] = $transferId;
        }
        if ($category !== null) {
            $params['trackingTypeId'] = $category;
        }
        $url = Network\Request::buildUrl($params);
        Network\Request::fetchUrl($url);
    }

    /**
     * @param integer $playerId
     * @return \PHT\Xml\Player\Training
     */
    public static function training($playerId)
    {
        $params = array('file' => 'trainingevents', 'playerID' => $playerId, 'version' => Config\Version::TRAININGEVENTS);
        $url = Network\Request::buildUrl($params);
        return new Xml\Player\Training(Network\Request::fetchUrl($url));
    }

    /**
     * @param integer $playerId
     * @return \PHT\Xml\Player\History
     */
    public static function history($playerId)
    {
        $url = Network\Request::buildUrl(array('file' => 'playerevents', 'playerID' => $playerId, 'version' => Config\Version::PLAYEREVENTS));
        return new Xml\Player\History(Network\Request::fetchUrl($url));
    }
}
