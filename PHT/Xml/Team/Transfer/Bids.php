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

class Bids extends Xml\File
{
    private $type;

    /**
     * @param string $xml
     * @param integer $type
     */
    public function __construct($xml, $type = null)
    {
        parent::__construct($xml);
        $this->type = $type;
    }

    /**
     * Return team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
    }

    /**
     * Return team
     *
     * @return \PHT\Xml\Team\Senior
     */
    public function getTeam()
    {
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return number of bids
     *
     * @return integer
     */
    public function getBidNumber()
    {
        if ($this->type === null) {
            return $this->getXml()->getElementsByTagName('BidItem')->length;
        }
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BidItems[@TrackingTypeID="' . $this->type . '"]/BidItem')->length;
    }

    /**
     * Return bid object
     *
     * @param integer $index
     * @return \PHT\Xml\Player\Bid
     */
    public function getBid($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBidNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $bid = new \DOMDocument('1.0', 'UTF-8');
            if ($this->type === null) {
                $nodeList = $xpath->query('//BidItem');
                $type = $nodeList->item($index)->parentNode->getAttribute('TrackingTypeID');
            } else {
                $nodeList = $xpath->query('//BidItems[@TrackingTypeID="' . $this->type . '"]/BidItem');
                $type = $this->type;
            }
            $nodeList->item($index)->appendChild(new \DOMNode('Type', $type));
            $bid->appendChild($bid->importNode($nodeList->item($index), true));
            return new Xml\Player\Bid($bid);
        }
        return null;
    }

    /**
     * Return iterator of bid objects
     *
     * @return \PHT\Xml\Player\Bid[]
     */
    public function getBids()
    {
        $xpath = new \DOMXPath($this->getXml());
        $bid = new \DOMDocument('1.0', 'UTF-8');
        if ($this->type === null) {
            $nodeList = $xpath->query('//BidItem');
            for ($i = 0; $i < $nodeList->length; $i++) {
                $nodeList->item($i)->appendChild(new \DOMNode('Type', $nodeList->item($i)->parentNode->getAttribute('TrackingTypeID')));
            }
        } else {
            $nodeList = $xpath->query('//BidItems[@TrackingTypeID="' . $this->type . '"]/BidItem');
            for ($i = 0; $i < $nodeList->length; $i++) {
                $nodeList->item($i)->appendChild(new \DOMNode('Type', $this->type));
            }
        }
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bid->appendChild($bid->importNode($nodeList->item($i), true));
        }
        $nodes = $bid->getElementsByTagName('BidItem');
        /** @var \PHT\Xml\Player\Bid[] $data */
        $data = new Utils\XmlIterator($nodes, 'Xml\Player\Bid');
        return $data;
    }

    /**
     * Ignore a transfer or a category of transfers
     *
     * @param integer $transferId
     * @param integer $category Only values 5 (hotlisted), 8 (losing bids) and 9 (finished) are allowed.
     */
    public function ignoreTransfer($transferId = null, $category = null)
    {
        Wrapper\Player\Senior::ignoretransfer($transferId, $category);
    }

    /**
     * Delete finished transfers
     */
    public function deleteFinishedTransfers()
    {
        Wrapper\Team\Senior::deletetransfers($this->getTeamId());
    }
}
