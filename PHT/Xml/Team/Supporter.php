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

class Supporter extends Xml\User\Supporter
{
    /**
     * @param \DOMDocument $xml
     */
    public function __construct($xml)
    {
        parent::__construct($xml);

        $tags = array(
            'LastMatchId' => 'MatchId',
            'LastMatchDate' => 'MatchDate',
            'LastMatchHomeTeamId' => 'HomeTeamId',
            'LastMatchHomeTeamName' => 'HomeTeamName',
            'LastMatchHomeGoals' => 'HomeGoals',
            'LastMatchAwayTeamId' => 'AwayTeamId',
            'LastMatchAwayTeamName' => 'AwayTeamName',
            'LastMatchAwayGoals' => 'AwayGoals',
            'NextMatchId' => 'MatchId',
            'NextMatchMatchDate' => 'MatchDate',
            'NextMatchHomeTeamId' => 'HomeTeamId',
            'NextMatchHomeTeamName' => 'HomeTeamName',
            'NextMatchAwayTeamId' => 'AwayTeamId',
            'NextMatchAwayTeamName' => 'AwayTeamName',
        );
        foreach ($tags as $old => $new) {
            $oNode = $this->getXml()->getElementsByTagName($old)->item(0);
            $nNode = $this->getXml()->createElement($new, $oNode->nodeValue);
            $oNode->parentNode->replaceChild($nNode, $oNode);
        }
    }

    /**
     * Return last match chunk
     *
     * @return \PHT\Xml\Match\Chunk
     */
    public function getLastMatch()
    {
        return new Xml\Match\Chunk($this->getXml('LastMatch'));
    }

    /**
     * Return next match chunk
     *
     * @return \PHT\Xml\Match\Chunk
     */
    public function getNextMatch()
    {
        return new Xml\Match\Chunk($this->getXml('NextMatch'));
    }

    /**
     * Return if team has a press announcement
     *
     * @return boolean
     */
    public function hasPressAnnouncement()
    {
        return $this->getXml()->getElementsByTagName('PressAnnouncement')->length;
    }

    /**
     * Return press announcement date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getPressAnnouncementDate($format = null)
    {
        if ($this->hasPressAnnouncement()) {
            return Utils\Date::convert($this->getXml()->getElementsByTagName('PressAnnouncementSendDate')->item(0)->nodeValue, $format);
        }
        return null;
    }

    /**
     * Return press announcement subject
     *
     * @return string
     */
    public function getPressAnnouncementSubject()
    {
        if ($this->hasPressAnnouncement()) {
            return $this->getXml()->getElementsByTagName('PressAnnouncementSubject')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return press announcement body
     *
     * @return string
     */
    public function getPressAnnouncementBody()
    {
        if ($this->hasPressAnnouncement()) {
            return $this->getXml()->getElementsByTagName('PressAnnouncementBody')->item(0)->nodeValue;
        }
        return null;
    }
}
