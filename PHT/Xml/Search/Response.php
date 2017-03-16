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

namespace PHT\Xml\Search;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Response extends Xml\File
{
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
     * Return search string
     *
     * @return string
     */
    public function getSearchString()
    {
        return $this->getXml()->getElementsByTagName('SearchString')->item(0)->nodeValue;
    }

    /**
     * Return search string 2
     *
     * @return string
     */
    public function getSearchString2()
    {
        return $this->getXml()->getElementsByTagName('SearchString2')->item(0)->nodeValue;
    }

    /**
     * Return what type of search was performed
     *
     * @return integer
     */
    public function getSearchType()
    {
        return $this->getXml()->getElementsByTagName('SearchType')->item(0)->nodeValue;
    }

    /**
     * Return search id
     *
     * @return integer
     */
    public function getSearchId()
    {
        return $this->getXml()->getElementsByTagName('SearchID')->item(0)->nodeValue;
    }

    /**
     * Return search league id
     *
     * @return integer
     */
    public function getSearchLeagueId()
    {
        return $this->getXml()->getElementsByTagName('SearchLeagueID')->item(0)->nodeValue;
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
     * Return number page of results
     *
     * @return integer
     */
    public function getTotalPage()
    {
        return $this->getXml()->getElementsByTagName('Pages')->item(0)->nodeValue;
    }

    /**
     * Return number of results
     *
     * @return integer
     */
    public function getResultNumber()
    {
        return $this->getXml()->getElementsByTagName('Result')->length;
    }

    /**
     * Return a result
     *
     * @param integer $index
     * @return \PHT\Xml\Search\Result
     */
    public function getResult($index)
    {
        $index = round($index);
        if ($index > Config\Config::$forIndex && $index < $this->getResultNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Result');
            $result = new \DOMDocument('1.0', 'UTF-8');
            $result->appendChild($result->importNode($nodeList->item($index), true));
            return new Result($result);
        }
        return null;
    }

    /**
     * Return iterator of result objects
     *
     * @return \PHT\Xml\Search\Result[]
     */
    public function getResults()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Result');
        /** @var \PHT\Xml\Search\Result[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Search\Result');
        return $data;
    }

    /**
     * Return first page of results
     *
     * @return \PHT\Xml\Search\Response
     */
    public function getFirstPage()
    {
        $params = $this->params;
        $params['pageIndex'] = 0;
        return Wrapper\Search::search($params);
    }

    /**
     * Return next page of results
     *
     * @return \PHT\Xml\Search\Response
     */
    public function getNextPage()
    {
        if ($this->params['pageIndex'] + 1 >= $this->getTotalPage()) {
            return null;
        }
        $params = $this->params;
        $params['pageIndex'] ++;
        return Wrapper\Search::search($params);
    }

    /**
     * Return previous page of results
     *
     * @return \PHT\Xml\Search\Response
     */
    public function getPreviousPage()
    {
        if ($this->params['pageIndex'] - 1 < 0) {
            return null;
        }
        $params = $this->params;
        $params['pageIndex'] --;
        return Wrapper\Search::search($params);
    }

    /**
     * Return last page of results
     *
     * @return \PHT\Xml\Search\Response
     */
    public function getLastPage()
    {
        $params = $this->params;
        $params['pageIndex'] = $this->getTotalPage() - 1;
        return Wrapper\Search::search($params);
    }

    /**
     * Return a page of results, start at 0 and ends at getTotalPage() - 1
     *
     * @return \PHT\Xml\Search\Response
     */
    public function getPage($page)
    {
        $page = round($page);
        if ($page < 0 || $page >= $this->getTotalPage()) {
            return null;
        }
        $params = $this->params;
        $params['pageIndex'] = $page;
        return Wrapper\Search::search($params);
    }
}
