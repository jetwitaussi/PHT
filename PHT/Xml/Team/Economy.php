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

namespace PHT\Xml\Team;

use PHT\Xml;
use PHT\Utils;
use PHT\Wrapper;

class Economy extends Xml\File
{
    private $money = null;

    /**
     * @param string $xml
     * @param integer $money
     */
    public function __construct($xml, $money = null)
    {
        parent::__construct($xml);
        $this->money = $money;
    }

    /**
     * Return senior team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return senior team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return senior team name
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return club cash value
     *
     * @return integer
     */
    public function getCash()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('Cash')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return club expexted cash value
     *
     * @return integer
     */
    public function getExpectedCash()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('ExpectedCash')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return sponsors level
     *
     * @return integer
     */
    public function getSponsorsLevel()
    {
        $sponsorsPopularityAvailable = strtolower($this->getXml()->getElementsByTagName('SponsorsPopularity')->item(0)->getAttribute('Available')) == 'true';
        if ($sponsorsPopularityAvailable == true) {
            return $this->getXml()->getElementsByTagName('SponsorsPopularity')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return supporters level
     *
     * @return integer
     */
    public function getSupportersLevel()
    {
        $supportersPopularityAvailable = strtolower($this->getXml()->getElementsByTagName('SupportersPopularity')->item(0)->getAttribute('Available')) == 'true';
        if ($supportersPopularityAvailable == true) {
            return $this->getXml()->getElementsByTagName('SupportersPopularity')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return fan club size
     *
     * @return integer
     */
    public function getFanClubSize()
    {
        return $this->getXml()->getElementsByTagName('FanClubSize')->item(0)->nodeValue;
    }

    /**
     * Return expected week money
     *
     * @return integer
     */
    public function getWeekExpected()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('ExpectedWeeksTotal')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return last week total
     *
     * @return integer
     */
    public function getLastWeekTotal()
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('LastWeeksTotal')->item(0)->nodeValue, $this->money);
    }

    /**
     * Return income object
     *
     * @return \PHT\Xml\Team\Economy\Income
     */
    public function getIncome()
    {
        return new Economy\Income($this->getXml(), $this->money);
    }

    /**
     * Return costs object
     *
     * @return \PHT\Xml\Team\Economy\Costs
     */
    public function getCosts()
    {
        return new Economy\Costs($this->getXml(), $this->money);
    }

    /**
     * Return last income object
     *
     * @return \PHT\Xml\Team\Economy\Income
     */
    public function getLastIncome()
    {
        return new Economy\Income($this->getXml(), $this->money, true);
    }

    /**
     * Return last costs object
     *
     * @return \PHT\Xml\Team\Economy\Costs
     */
    public function getLastCosts()
    {
        return new Economy\Costs($this->getXml(), $this->money, true);
    }
}
