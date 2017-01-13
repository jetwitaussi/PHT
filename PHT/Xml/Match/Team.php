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

namespace PHT\Xml\Match;

use PHT\Xml;
use PHT\Config;
use PHT\Wrapper;

class Team extends Xml\Base
{
    private $type;
    private $system;
    private $matchId;

    /**
     * @param \DOMDocument $xml
     * @param string $type
     * @param string $system
     * @param integer $matchId
     */
    public function __construct($xml, $type, $system, $matchId)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->type = $type;
        $this->system = $system;
        $this->matchId = $matchId;
    }

    /**
     * Return team id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->getXml()->getElementsByTagName($this->type . 'TeamID')->item(0)->nodeValue;
    }

    /**
     * Return team details
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth|\PHT\Xml\Team\National
     */
    public function getTeam()
    {
        if ($this->system == Config\Config::MATCH_YOUTH) {
            return Wrapper\Team\Youth::team($this->getId());
        } elseif ($this->system == Config\Config::MATCH_NATIONAL) {
            return Wrapper\National::team($this->getId());
        }
        return Wrapper\Team\Senior::team($this->getId());
    }

    /**
     * Return team name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getXml()->getElementsByTagName($this->type . 'TeamName')->item(0)->nodeValue;
    }

    /**
     * Return team lineup
     *
     * @return \PHT\Xml\Match\Lineup
     */
    public function getLineup()
    {
        if ($this->system == Config\Config::MATCH_YOUTH) {
            return Wrapper\Match::youthlineup($this->matchId, $this->getId());
        } elseif ($this->system == Config\Config::MATCH_TOURNAMENT) {
            return Wrapper\Match::tournamentlineup($this->matchId, $this->getId());
        }
        return Wrapper\Match::seniorlineup($this->matchId, $this->getId());
    }

    /**
     * Return team orders
     *
     * @return \PHT\Xml\Match\Orders
     */
    public function getOrders()
    {
        if ($this->system == Config\Config::MATCH_YOUTH) {
            return Wrapper\Match::youthorders($this->matchId, $this->getId());
        } elseif ($this->system == Config\Config::MATCH_TOURNAMENT) {
            return Wrapper\Match::tournamentorders($this->matchId, $this->getId());
        }
        return Wrapper\Match::seniororders($this->matchId, $this->getId());
    }

    /**
     * Set orders and return match orders sent object
     *
     * @param \PHT\Config\Orders $orders
     * @return \PHT\Xml\Match\Orders\Sent
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public function setOrders(Config\Orders $orders)
    {
        return Wrapper\Match::setorders($orders, $this->getId());
    }

    /**
     * Return dress URI
     *
     * @return string
     */
    public function getDressURI()
    {
        $uri = $this->getXml()->getElementsByTagName('DressURI');
        if ($uri->length) {
            return $uri->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return goals number
     *
     * @return integer
     */
    public function getGoals()
    {
        return $this->getXml()->getElementsByTagName($this->type . 'Goals')->item(0)->nodeValue;
    }

    /**
     * Return tactic type
     *
     * @return integer
     */
    public function getTacticType()
    {
        return $this->getXml()->getElementsByTagName('TacticType')->item(0)->nodeValue;
    }

    /**
     * Return tactic skill
     *
     * @return integer
     */
    public function getTacticSkill()
    {
        return $this->getXml()->getElementsByTagName('TacticSkill')->item(0)->nodeValue;
    }

    /**
     * Return miedfield rating
     *
     * @return integer
     */
    public function getMidfieldRating()
    {
        return $this->getXml()->getElementsByTagName('RatingMidfield')->item(0)->nodeValue;
    }

    /**
     * Return right defense rating
     *
     * @return integer
     */
    public function getRightDefenseRating()
    {
        return $this->getXml()->getElementsByTagName('RatingRightDef')->item(0)->nodeValue;
    }

    /**
     * Return central defense rating
     *
     * @return integer
     */
    public function getCentralDefenseRating()
    {
        return $this->getXml()->getElementsByTagName('RatingMidDef')->item(0)->nodeValue;
    }

    /**
     * Return left defense rating
     *
     * @return integer
     */
    public function getLeftDefenseRating()
    {
        return $this->getXml()->getElementsByTagName('RatingLeftDef')->item(0)->nodeValue;
    }

    /**
     * Return right attack rating
     *
     * @return integer
     */
    public function getRightAttackRating()
    {
        return $this->getXml()->getElementsByTagName('RatingRightAtt')->item(0)->nodeValue;
    }

    /**
     * Return central attack rating
     *
     * @return integer
     */
    public function getCentralAttackRating()
    {
        return $this->getXml()->getElementsByTagName('RatingMidAtt')->item(0)->nodeValue;
    }

    /**
     * Return left attack rating
     *
     * @return integer
     */
    public function getLeftAttackRating()
    {
        return $this->getXml()->getElementsByTagName('RatingLeftAtt')->item(0)->nodeValue;
    }

    /**
     * Return defensive indirect set pieces rating
     *
     * @return integer
     */
    public function getIndirectSetPiecesDefenseRating()
    {
        return $this->getXml()->getElementsByTagName('RatingIndirectSetPiecesDef')->item(0)->nodeValue;
    }

    /**
     * Return attacking indirect set pieces rating
     *
     * @return integer
     */
    public function getIndirectSetPiecesAttackRating()
    {
        return $this->getXml()->getElementsByTagName('RatingIndirectSetPiecesAtt')->item(0)->nodeValue;
    }

    /**
     * Return HatStats note
     *
     * @return integer
     */
    public function getHatStats()
    {
        return ($this->getMidfieldRating() * 3) +
            $this->getRightDefenseRating() +
            $this->getLeftDefenseRating() +
            $this->getCentralDefenseRating() +
            $this->getRightAttackRating() +
            $this->getLeftAttackRating() +
            $this->getCentralAttackRating();
    }

    /**
     * Return LoddarStats note
     *
     * @return float
     */
    public function getLoddarStats()
    {
        $HQ = function ($x) {
            return 2.0 * ($x / ($x + 80));
        };
        $base = 1.0;
        $weight = 4.0;
        $midfieldLevel = $base + $weight * $this->getMidfieldRating();
        $lattack = $base + $weight * $this->getLeftAttackRating();
        $cattack = $base + $weight * $this->getCentralAttackRating();
        $rattack = $base + $weight * $this->getRightAttackRating();
        $ldefence = $base + $weight * $this->getLeftDefenseRating();
        $cdefence = $base + $weight * $this->getCentralDefenseRating();
        $rdefence = $base + $weight * $this->getRightDefenseRating();
        $MFS = 0.0;
        $VF = 0.47;
        $AF = 1.0 - $VF;
        $ZG = 0.37;
        $AG = (1.0 - $ZG) / 2.0;
        $KG = 0.25;
        $MFF = $MFS + (1 - $MFS) * $HQ($midfieldLevel);
        $KK = 0;
        $tactics = $this->getTacticType();
        $tacticsLevel = $this->getTacticSkill();
        if ($tactics == 1) {
            $KK = $KG * 2 * $tacticsLevel / ($tacticsLevel + 20);
        }
        $KZG = $ZG;
        if ($tactics == 3) {
            $KZG += 0.2 * ($tacticsLevel - 1.0) / 19.0 + 0.2;
        } elseif ($tactics == 4) {
            $KZG -= 0.2 * ($tacticsLevel - 1.0) / 19.0 + 0.2;
        }
        $KAG = (1.0 - $KZG) / 2.0;
        $attackValue = ($AF + $KK) * ($KZG * $HQ($cattack) + $KAG * ($HQ($lattack) + $HQ($rattack)));
        $defenceValue = $VF * ($ZG * $HQ($cdefence) + $AG * ($HQ($ldefence) + $HQ($rdefence)));
        $value = 80 * $MFF * ($attackValue + $defenceValue);
        return round($value, 2);
    }

    /**
     * Return match attitude if given
     *
     * @return integer
     */
    public function getAttitude()
    {
        $node = $this->getXml()->getElementsByTagName('TeamAttitude');
        if ($node !== null && $node->length) {
            return $node->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return team formation
     *
     * @return string
     */
    public function getFormation()
    {
        return $this->getXml()->getElementsByTagName('Formation')->item(0)->nodeValue;
    }

    /**
     * Return team possession in first halftime
     *
     * @return integer
     */
    public function getPossessionFirstHalf()
    {
        if ($this->getXml()->getElementsByTagName('PossessionFirstHalf')->length) {
            return $this->getXml()->getElementsByTagName('PossessionFirstHalf')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return team possession in second halftime
     *
     * @return integer
     */
    public function getPossessionSecondHalf()
    {
        if ($this->getXml()->getElementsByTagName('PossessionSecondHalf')->length) {
            return $this->getXml()->getElementsByTagName('PossessionSecondHalf')->item(0)->nodeValue;
        }
        return null;
    }
}
