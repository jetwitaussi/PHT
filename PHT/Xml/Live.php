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
use PHT\Network;

class Live extends File
{
    private $liveJson = array();

    public function __construct($xml)
    {
        parent::__construct($xml);
    }

    /**
     * Update live to get new events
     */
    public function update()
    {
        $json = array();
        foreach ($this->getMatches() as $match) {
            $json[] = array(
                'matchId' => $match->getId(),
                'sourceSystem' => $match->getSourceSystem(),
                'index' => $match->getLastEventIndexShown()
            );
        }
        if (count($json)) {
            $this->liveJson = array('matches' => $this->mergeLiveJson($this->liveJson['matches'], $json));
        }
        $params = array('file' => 'live', 'actionType' => 'viewNew', 'version' => Config\Version::LIVE, 'lastShownIndexes' => json_encode($this->liveJson));
        $url = Network\Request::buildUrl($params);
        $xml = Network\Request::fetchUrl($url);
        $this->xmlText = $xml;
        $this->xml = new \DOMDocument('1.0', 'UTF-8');
        $this->xml->loadXml($xml);
    }

    /**
     * Reload live to get all events
     */
    public function reload()
    {
        $params = array('file' => 'live', 'actionType' => 'viewAll', 'version' => Config\Version::LIVE);
        $url = Network\Request::buildUrl($params);
        $xml = Network\Request::fetchUrl($url);
        $this->xmlText = $xml;
        $this->xml = new \DOMDocument('1.0', 'UTF-8');
        $this->xml->loadXml($xml);
        $this->liveJson = array();
    }

    /**
     * Add a match to live
     *
     * @param integer $matchId
     * @param string $sourceSystem
     * @param boolean $includeLineup
     * @return boolean
     */
    public function addMatch($matchId, $sourceSystem = Config\Config::MATCH_SENIOR, $includeLineup = true)
    {
        if ($this->getMatchNumber() >= Config\Config::MAX_LIVE_MATCH) {
            return false;
        }
        $params = array('file' => 'live', 'actionType' => 'addMatch', 'matchID' => $matchId, 'version' => Config\Version::LIVE, 'sourceSystem' => $sourceSystem);
        if ($includeLineup === true) {
            $params['includeStartingLineup'] = 'true';
        }
        $url = Network\Request::buildUrl($params);
        $xml = Network\Request::fetchUrl($url);
        $this->xmlText = $xml;
        $this->xml = new \DOMDocument('1.0', 'UTF-8');
        $this->xml->loadXml($xml);
        return true;
    }

    /**
     * Remove a match from live
     *
     * @param integer $matchId
     * @param string $sourceSystem
     * @param boolean $reload Perform reload after delete, if set to false live is just updated
     */
    public function deleteMatch($matchId, $sourceSystem = Config\Config::MATCH_SENIOR, $reload = false)
    {
        $params = array('file' => 'live', 'actionType' => 'deleteMatch', 'matchID' => $matchId, 'version' => Config\Version::LIVE, 'sourceSystem' => $sourceSystem);
        $url = Network\Request::buildUrl($params);
        Network\Request::fetchUrl($url);
        if ($reload) {
            $this->reload();
        } else {
            $this->update();
        }
    }

    /**
     * Clear live, remove all matches
     */
    public function clear()
    {
        $params = array('file' => 'live', 'actionType' => 'clearAll', 'version' => Config\Version::LIVE);
        $url = Network\Request::buildUrl($params);
        Network\Request::fetchUrl($url);
        $this->reload();
    }

    /**
     * @param array $original
     * @param array $new
     * @return array
     */
    private function mergeLiveJson($original, $new)
    {
        foreach ($new as $tab) {
            $id = $tab['matchId'];
            $e = null;
            if (count($original)) {
                foreach ($original as $i => $otab) {
                    if ($otab['matchId'] == $id) {
                        $e = $i;
                        break;
                    }
                }
            }
            if ($e !== null) {
                $original[$e] = $tab;
            } else {
                $original[] = $tab;
            }
        }
        return $original;
    }

    /**
     * Return number of match in list
     *
     * @return integer
     */
    public function getMatchNumber()
    {
        return $this->getXml()->getElementsByTagName('Match')->length;
    }

    /**
     * Return live match object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Live
     */
    public function getMatch($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getMatchNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Match');
            $match = new \DOMDocument('1.0', 'UTF-8');
            $match->appendChild($match->importNode($nodeList->item($index), true));
            return new Match\Live($match);
        }
        return null;
    }

    /**
     * Return iterator of live match objects
     *
     * @return \PHT\Xml\Match\Live[]
     */
    public function getMatches()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Match');
        /** @var \PHT\Xml\Match\Live[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Live');
        return $data;
    }
}
