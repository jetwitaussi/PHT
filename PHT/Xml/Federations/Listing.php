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

namespace PHT\Xml\Federations;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Listing extends Xml\HTSupporter
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
     * Return number of federation
     *
     * @return integer
     */
    public function getFederationNumber()
    {
        return $this->getXml()->getElementsByTagName('Alliance')->length;
    }

    /**
     * Return federation chunk object
     *
     * @param integer $index
     * @return \PHT\Xml\Federations\Chunk
     */
    public function getFederation($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getFederationNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Alliance');
            $alliance = new \DOMDocument('1.0', 'UTF-8');
            $alliance->appendChild($alliance->importNode($nodeList->item($index), true));
            return new Chunk($alliance);
        }
        return null;
    }

    /**
     * Return iterator of federation chunk objects
     *
     * @return \PHT\Xml\Federations\Chunk[]
     */
    public function getFederations()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Alliance');
        /** @var \PHT\Xml\Federations\Chunk[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Federations\Chunk');
        return $data;
    }

    /**
     * Return page index
     *
     * @return integer
     */
    public function getCurrentPage()
    {
        return $this->getXml()->getElementsByTagName('PageIndex')->item(0)->nodeValue;
    }

    /**
     * Return page number
     *
     * @return integer
     */
    public function getTotalPage()
    {
        return $this->getXml()->getElementsByTagName('Pages')->item(0)->nodeValue;
    }

    /**
     * Return first page of results
     *
     * @return \PHT\Xml\Federations\Listing
     */
    public function getFirstPage()
    {
        $params = $this->params;
        $params['pageIndex'] = 0;
        return Wrapper\Federation::search($params);
    }

    /**
     * Return next page of results
     *
     * @return \PHT\Xml\Federations\Listing
     */
    public function getNextPage()
    {
        if ($this->params['pageIndex'] + 1 >= $this->getTotalPage()) {
            return null;
        }
        $params = $this->params;
        $params['pageIndex'] ++;
        return Wrapper\Federation::search($params);
    }

    /**
     * Return previous page of results
     *
     * @return \PHT\Xml\Federations\Listing
     */
    public function getPreviousPage()
    {
        if ($this->params['pageIndex'] - 1 < 0) {
            return null;
        }
        $params = $this->params;
        $params['pageIndex'] --;
        return Wrapper\Federation::search($params);
    }

    /**
     * Return last page of results
     *
     * @return \PHT\Xml\Federations\Listing
     */
    public function getLastPage()
    {
        $params = $this->params;
        $params['pageIndex'] = $this->getTotalPage() - 1;
        return Wrapper\Federation::search($params);
    }

    /**
     * Return a page of results, start at 0 and ends at getTotalPage() - 1
     *
     * @param integer $page
     * @return \PHT\Xml\Federations\Listing
     */
    public function getPage($page)
    {
        $page = round($page);
        if ($page < 0 || $page >= $this->getTotalPage()) {
            return null;
        }
        $params['pageIndex'] = $page;
        return Wrapper\Federation::search($params);
    }
}
