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

namespace PHT\Xml\Team\Transfer;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class History extends Xml\File
{
    /**
     * Return team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
    }

    /**
     * Return team name
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
    }

    /**
     * Return team activated date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getActivatedDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('ActivatedDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return total sum of teams buys
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getBuysSum($countryCurrency = null)
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('TotalSumOfBuys')->item(0)->nodeValue, $countryCurrency);
    }

    /**
     * Return total sum of teams sales
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getSalesSum($countryCurrency = null)
    {
        return Utils\Money::convert($this->getXml()->getElementsByTagName('TotalSumOfSales')->item(0)->nodeValue, $countryCurrency);
    }

    /**
     * Return number of teams buys
     *
     * @return integer
     */
    public function getBuysNumber()
    {
        return $this->getXml()->getElementsByTagName('NumberOfBuys')->item(0)->nodeValue;
    }

    /**
     * Return number of teams sales
     *
     * @return integer
     */
    public function getSalesNumber()
    {
        return $this->getXml()->getElementsByTagName('NumberOfSales')->item(0)->nodeValue;
    }

    /**
     * Return number of transfers listed by request
     * Default period is past 7 days, if you specify a date
     * period is one month before the date
     *
     * @return integer
     */
    public function getTransferNumber()
    {
        return $this->getXml()->getElementsByTagName('Transfer')->length;
    }

    /**
     * Return transfer object
     *
     * @param integer $index
     * @return \PHT\Xml\Transfer
     */
    public function getTransfer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getTransferNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Transfer');
            $transfer = new \DOMDocument('1.0', 'UTF-8');
            $transfer->appendChild($transfer->importNode($nodeList->item($index), true));
            return new Xml\Transfer($transfer);
        }
        return null;
    }

    /**
     * Return iterator of transfer objects
     *
     * @return \PHT\Xml\Transfer[]
     */
    public function getTransfers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Transfer');
        /** @var \PHT\Xml\Transfer[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Transfer');
        return $data;
    }

    /**
     * Return start date of transfers list
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getStartDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('StartDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return end date of transfers list
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getEndDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('EndDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return page number
     *
     * @return integer
     */
    public function getCurrentPage()
    {
        return $this->getXml()->getElementsByTagName('PageIndex')->item(0)->nodeValue;
    }

    /**
     * Return number page of transfers
     *
     * @return integer
     */
    public function getTotalPage()
    {
        return $this->getXml()->getElementsByTagName('Pages')->item(0)->nodeValue;
    }

    /**
     * Return first page of transfers
     *
     * @return \PHT\Xml\Team\Transfer\History
     */
    public function getFirstPage()
    {
        return Wrapper\Team\Senior::transfershistory($this->getTeamId(), 1);
    }

    /**
     * Return next page of transfers
     *
     * @return \PHT\Xml\Team\Transfer\History
     */
    public function getNextPage()
    {
        if ($this->getCurrentPage() + 1 > $this->getTotalPage()) {
            return null;
        }
        return Wrapper\Team\Senior::transfershistory($this->getTeamId(), $this->getCurrentPage() + 1);
    }

    /**
     * Return previous page of transfers
     *
     * @return \PHT\Xml\Team\Transfer\History
     */
    public function getPreviousPage()
    {
        if ($this->getCurrentPage() - 1 < 1) {
            return null;
        }
        return Wrapper\Team\Senior::transfershistory($this->getTeamId(), $this->getCurrentPage() - 1);
    }

    /**
     * Return last page of transfers
     *
     * @return \PHT\Xml\Team\Transfer\History
     */
    public function getLastPage()
    {
        return Wrapper\Team\Senior::transfershistory($this->getTeamId(), $this->getTotalPage());
    }

    /**
     * Return a page of transfers, start at 1 and ends at getTotalPage()
     *
     * @param integer $page
     * @return \PHT\Xml\Team\Transfer\History
     */
    public function getPage($page)
    {
        $page = round($page);
        if ($page < 1 || $page > $this->getTotalPage()) {
            return null;
        }
        return Wrapper\Team\Senior::transfershistory($this->getTeamId(), $page);
    }
}
