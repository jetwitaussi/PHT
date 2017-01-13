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

namespace PHT\Xml\Compendium;

use PHT\Xml;
use PHT\Wrapper;

class National extends Xml\Base
{
    /**
     * @param \DOMDocument $xml
     */
    public function __construct($xml)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
    }

    /**
     * Return national team id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName('NationalTeamId')->item(0)->nodeValue;
    }

    /**
     * Return national team name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName('NationalTeamName')->item(0)->nodeValue;
    }

    /**
     * Get national team details
     *
     * @return \PHT\Xml\Team\National
     */
    public function getTeam()
    {
        return Wrapper\National::team($this->getId());
    }

    /**
     * Get national team players
     *
     * @return \PHT\Xml\National\Players
     */
    public function getPlayers()
    {
        return Wrapper\National::players($this->getId());
    }

    /**
     * Get array of team matches
     *
     * @return \PHT\Xml\Match\National[]
     */
    public function getMatches()
    {
        $u20 = strpos($this->getName(), 'U-20') !== false;
        $matches = Wrapper\National::matches($u20);
        $ok = array();
        foreach ($matches->getMatches() as $match) {
            if ($match->getHomeTeamName() == $this->getName() || $match->getAwayTeamName() == $this->getName()) {
                $ok[] = $match;
            }
        }
        return $ok;
    }
}
