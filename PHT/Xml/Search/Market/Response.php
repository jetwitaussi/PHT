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

namespace PHT\Xml\Search\Market;

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
     * Returns total number of transfer results, returns -1 if total greater than 100 and only 100 firsts are available
     *
     * @return integer
     */
    public function getTotalResults()
    {
        return $this->getXml()->getElementsByTagName('ItemCount')->item(0)->nodeValue;
    }

    /**
     * Returns page size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->getXml()->getElementsByTagName('PageSize')->item(0)->nodeValue;
    }

    /**
     * Returns current page index
     *
     * @return integer
     */
    public function getCurrentPage()
    {
        return $this->getXml()->getElementsByTagName('PageIndex')->item(0)->nodeValue;
    }

    /**
     * Return number of results
     *
     * @return integer
     */
    public function getResultNumber()
    {
        return $this->getXml()->getElementsByTagName('TransferResult')->length;
    }

    /**
     * Return result object
     *
     * @param integer $index
     * @return \PHT\Xml\Search\Market\Result
     */
    public function getResult($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getResultNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//TransferResult');
            $result = new \DOMDocument('1.0', 'UTF-8');
            $result->appendChild($result->importNode($nodeList->item($index), true));
            return new Result($result);
        }
        return null;
    }

    /**
     * Return iterator of result objects
     *
     * @return \PHT\Xml\Search\Market\Result[]
     */
    public function getResults()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//TransferResult');
        /** @var \PHT\Xml\Search\Market\Result[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Search\Market\Result');
        return $data;
    }

    /**
     * Return first page of results
     *
     * @return \PHT\Xml\Search\Market\Response
     */
    public function getFirstPage()
    {
        $params = $this->params;
        $params['pageIndex'] = 0;
        return Wrapper\Search::market($params);
    }

    /**
     * Return next page of results
     *
     * @return \PHT\Xml\Search\Market\Response
     */
    public function getNextPage()
    {
        if ($this->getTotalResults() > $this->getResultNumber()) {
            $totalpage = ceil($this->getTotalResults() / max(1, $this->getSize()));
            if ($this->params['pageIndex'] + 1 >= $totalpage) {
                return null;
            }
        }
        $params = $this->params;
        $params['pageIndex'] ++;
        return Wrapper\Search::market($params);
    }

    /**
     * Return previous page of results
     *
     * @return \PHT\Xml\Search\Market\Response
     */
    public function getPreviousPage()
    {
        if ($this->params['pageIndex'] - 1 < 0) {
            return null;
        }
        $params = $this->params;
        $params['pageIndex'] --;
        return Wrapper\Search::market($params);
    }

    /**
     * Return last page of results
     *
     * @return \PHT\Xml\Search\Market\Response
     */
    public function getLastPage()
    {
        $lastpage = ceil(100 / max(1, $this->getSize()));
        if ($this->getTotalResults() > 0) {
            $lastpage = ceil($this->getTotalResults() / max(1, $this->getSize()));
        }
        $params = $this->params;
        $params['pageIndex'] = $lastpage - 1;
        return Wrapper\Search::market($params);
    }

    /**
     * Return a page of results, start at 0 and end depends on size and results number
     *
     * @return \PHT\Xml\Search\Market\Response
     */
    public function getPage($page)
    {
        $page = round($page);
        $totalpage = ceil(100 / max(1, $this->getSize()));
        if ($this->getTotalResults() > 0) {
            $totalpage = ceil($this->getTotalResults() / max(1, $this->getSize()));
        }
        if ($page < 0 || $page >= $totalpage) {
            return null;
        }
        $params['pageIndex'] = $page;
        return Wrapper\Search::market($params);
    }
}
