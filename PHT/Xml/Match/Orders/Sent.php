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

namespace PHT\Xml\Match\Orders;

use PHT\Xml;

class Sent extends Xml\File
{
    /**
     * Return match id
     *
     * @return integer
     */
    public function getMatchId()
    {
        return $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
    }

    /**
     * Return if match is youth
     *
     * @return boolean
     */
    public function isYouth()
    {
        return strtolower($this->getXml()->getElementsByTagName('IsYouth')->item(0)->nodeValue) == 'true';
    }

    /**
     * Return if orders were set correctly
     *
     * @return boolean
     */
    public function ordersSet()
    {
        return strtolower($this->getXml()->getElementsByTagName('MatchData')->item(0)->getAttribute('OrdersSet')) == 'true';
    }

    /**
     * Return reason when setting orders failed
     *
     * @return string
     */
    public function getReason()
    {
        if ($this->getXml()->getElementsByTagName('Reason')->length) {
            return $this->getXml()->getElementsByTagName('Reason')->item(0)->nodeValue;
        }
        return null;
    }
}
