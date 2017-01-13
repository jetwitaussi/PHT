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

namespace PHT\Xml;

use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Supporters extends File
{
    const USER_ACTION = 'mysupporters';

    private $params;

    /**
     * @param string $xml
     * @param array $params
     */
    public function __construct($xml, $params)
    {
        parent::__construct($xml);
        $this->params = $params;
    }

    /**
     * Return total number of supporters
     *
     * @return integer
     */
    public function getTotalSupporter()
    {
        if ($this->params['actionType'] == self::USER_ACTION) {
            return $this->getXml()->getElementsByTagName('MySupporters')->item(0)->getAttribute('TotalItems');
        }
        return $this->getXml()->getElementsByTagName('SupportedTeams')->item(0)->getAttribute('TotalItems');
    }

    /**
     * Return number of supporters
     *
     * @return integer
     */
    public function getSupporterNumber()
    {
        if ($this->params['actionType'] == self::USER_ACTION) {
            return $this->getXml()->getElementsByTagName('SupporterTeam')->length;
        }
        return $this->getXml()->getElementsByTagName('SupportedTeam')->length;
    }

    /**
     * Return user or team supporter object
     *
     * @param integer $index
     * @return \PHT\Xml\Team\Supporter|\PHT\Xml\User\Supporter
     */
    public function getSupporter($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getSupporterNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            if ($this->params['actionType'] == self::USER_ACTION) {
                $nodeList = $xpath->query("//MySupporters/SupporterTeam");
            } else {
                $nodeList = $xpath->query("//SupportedTeams/SupportedTeam");
            }
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            if ($this->params['actionType'] == self::USER_ACTION) {
                return new User\Supporter($node);
            }
            return new Team\Supporter($node);
        }
        return null;
    }

    /**
     * Return iterator of user or team supporter objects
     *
     * @return \PHT\Xml\Team\Supporter[]|\PHT\Xml\User\Supporter[]
     */
    public function getSupporters()
    {
        $xpath = new \DOMXPath($this->getXml());
        if ($this->params['actionType'] == self::USER_ACTION) {
            $nodeList = $xpath->query("//MySupporters/SupporterTeam");
            $type = '\PHT\Xml\User\Supporter';
        } else {
            $nodeList = $xpath->query("//SupportedTeams/SupportedTeam");
            $type = '\PHT\Xml\Team\Supporter';
        }
        /** @var \PHT\Xml\Team\Supporter[]|\PHT\Xml\User\Supporter[] $data */
        $data = new Utils\XmlIterator($nodeList, $type);
        return $data;
    }

    /**
     * Return current page number
     *
     * @return integer
     */
    public function getCurrentPage()
    {
        return $this->params['pageIndex'];
    }

    /**
     * Return number page of results
     *
     * @return integer
     */
    public function getTotalPage()
    {
        return ceil($this->getTotalSupporter() / max(1, $this->params['pageSize']));
    }

    /**
     * Return first page of supporters
     *
     * @return \PHT\Xml\Supporters
     */
    public function getFirstPage()
    {
        $userId = $teamId = null;
        if (isset($this->params['teamId'])) {
            $teamId = $this->params['teamId'];
        } elseif (isset($this->params['userId'])) {
            $userId = $this->params['userId'];
        }
        return Wrapper\Supporters::listing($teamId, $userId, 0, $this->params['pageSize']);
    }

    /**
     * Return next page of supporters
     *
     * @return \PHT\Xml\Supporters
     */
    public function getNextPage()
    {
        if ($this->getCurrentPage() + 1 >= $this->getTotalPage()) {
            return null;
        }
        $userId = $teamId = null;
        if (isset($this->params['teamId'])) {
            $teamId = $this->params['teamId'];
        } elseif (isset($this->params['userId'])) {
            $userId = $this->params['userId'];
        }
        return Wrapper\Supporters::listing($teamId, $userId, $this->getCurrentPage() + 1, $this->params['pageSize']);
    }

    /**
     * Return previous page of supporters
     *
     * @return \PHT\Xml\Supporters
     */
    public function getPreviousPage()
    {
        if ($this->getCurrentPage() - 1 < 0) {
            return null;
        }
        $userId = $teamId = null;
        if (isset($this->params['teamId'])) {
            $teamId = $this->params['teamId'];
        } elseif (isset($this->params['userId'])) {
            $userId = $this->params['userId'];
        }
        return Wrapper\Supporters::listing($teamId, $userId, $this->getCurrentPage() - 1, $this->params['pageSize']);
    }

    /**
     * Return last page of supporters
     *
     * @return \PHT\Xml\Supporters
     */
    public function getLastPage()
    {
        $userId = $teamId = null;
        if (isset($this->params['teamId'])) {
            $teamId = $this->params['teamId'];
        } elseif (isset($this->params['userId'])) {
            $userId = $this->params['userId'];
        }
        return Wrapper\Supporters::listing($teamId, $userId, $this->getTotalPage() - 1, $this->params['pageSize']);
    }

    /**
     * Return a page of supporters, start at 0 and ends at getTotalPage() - 1
     *
     * @param integer $page
     * @return \PHT\Xml\Supporters
     */
    public function getPage($page)
    {
        $page = round($page);
        if ($page < 0 || $page >= $this->getTotalPage()) {
            return null;
        }
        $userId = $teamId = null;
        if (isset($this->params['teamId'])) {
            $teamId = $this->params['teamId'];
        } elseif (isset($this->params['userId'])) {
            $userId = $this->params['userId'];
        }
        return Wrapper\Supporters::listing($teamId, $userId, $page, $this->params['pageSize']);
    }
}
