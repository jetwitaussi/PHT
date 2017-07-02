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
use PHT\Utils;
use PHT\Wrapper;

class Orders extends Xml\File
{
    /**
     * Are orders available ?
     *
     * @return boolean
     */
    public function isDataAvailable()
    {
        return strtolower($this->getXml()->getElementsByTagName('MatchData')->item(0)->getAttribute('Available')) == 'true';
    }

    /**
     * Return match id
     *
     * @return integer
     */
    public function getMatchId()
    {
        return $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
    }

    /**
     * Return match details
     *
     * @param boolean $events
     * @return \PHT\Xml\Match
     */
    public function getMatch($events = true)
    {
        if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
            return Wrapper\Match::youth($this->getMatchId(), $events);
        } elseif (in_array($this->getMatchType(), array(50, 51, 60, 61, 80))) {
            return Wrapper\Match::tournament($this->getMatchId(), $events);
        }
        return Wrapper\Match::senior($this->getMatchId(), $events);
    }

    /**
     * Return home team id
     *
     * @return integer
     */
    public function getHomeTeamId()
    {
        if ($this->isDataAvailable()) {
            return $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return home team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth|\PHT\Xml\Team\National
     */
    public function getHomeTeam()
    {
        if ($this->isDataAvailable()) {
            if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                return Wrapper\Team\Youth::team($this->getHomeTeamId());
            } elseif (in_array($this->getMatchType(), array(10, 11, 12))) {
                return Wrapper\National::team($this->getHomeTeamId());
            }
            return Wrapper\Team\Senior::team($this->getHomeTeamId());
        }
        return null;
    }

    /**
     * Return home team name
     *
     * @return string
     */
    public function getHomeTeamName()
    {
        if ($this->isDataAvailable()) {
            return $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return away team id
     *
     * @return integer
     */
    public function getAwayTeamId()
    {
        if ($this->isDataAvailable()) {
            return $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return away team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth|\PHT\Xml\Team\National
     */
    public function getAwayTeam()
    {
        if ($this->isDataAvailable()) {
            if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                return Wrapper\Team\Youth::team($this->getAwayTeamId());
            } elseif (in_array($this->getMatchType(), array(10, 11, 12))) {
                return Wrapper\National::team($this->getAwayTeamId());
            }
            return Wrapper\Team\Senior::team($this->getAwayTeamId());
        }
        return null;
    }

    /**
     * Return away team name
     *
     * @return string
     */
    public function getAwayTeamName()
    {
        if ($this->isDataAvailable()) {
            return $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return arena id
     *
     * @return integer
     */
    public function getArenaId()
    {
        if ($this->isDataAvailable()) {
            return $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return arena id
     *
     * @return \PHT\Xml\Team\Arena
     */
    public function getArena()
    {
        if ($this->isDataAvailable()) {
            $id = $this->getArenaId();
            if ($id > 0) {
                return Wrapper\Team\Senior::arena($id);
            }
        }
        return null;
    }

    /**
     * Return arena name
     *
     * @return string
     */
    public function getArenaName()
    {
        if ($this->isDataAvailable()) {
            return $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return match date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getMatchDate($format = null)
    {
        if ($this->isDataAvailable()) {
            return Utils\Date::convert($this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue, $format);
        }
        return null;
    }

    /**
     * Return match type
     *
     * @return integer
     */
    public function getMatchType()
    {
        if ($this->isDataAvailable()) {
            return $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Is attitude value available ?
     *
     * @return boolean
     */
    public function isAttituteAvailable()
    {
        if ($this->isDataAvailable()) {
            return strtolower($this->getXml()->getElementsByTagName('Attitude')->item(0)->getAttribute('Available')) == 'true';
        }
        return null;
    }

    /**
     * Return attitude value
     *
     * @return integer
     */
    public function getAttitude()
    {
        if ($this->isDataAvailable() && $this->isAttituteAvailable()) {
            return $this->getXml()->getElementsByTagName('Attitude')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return tactic value
     *
     * @return integer
     */
    public function getTactic()
    {
        if ($this->isDataAvailable()) {
            return $this->getXml()->getElementsByTagName('TacticType')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return coach modifier value
     *
     * @return integer
     */
    public function getCoachModifier()
    {
        if ($this->isDataAvailable() && strtolower($this->getXml()->getElementsByTagName('CoachModifier')->item(0)->getAttribute('Available')) == 'true') {
            return $this->getXml()->getElementsByTagName('CoachModifier')->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Return number players
     *
     * @return integer
     */
    public function getPlayerNumber()
    {
        if ($this->isDataAvailable()) {
            $xpath = new \DOMXPath($this->getXml());
			return $xpath->query('//Positions/Player')->length;
        }
        return null;
    }

    /**
     * Return lineup player object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Lineup\Player
     */
    public function getPlayer($index)
    {
        if ($this->isDataAvailable()) {
            $index = round($index);
            if ($index >= Config\Config::$forIndex && $index < $this->getPlayerNumber() + Config\Config::$forIndex) {
                $type = Config\Config::MATCH_SENIOR;
                if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                    $type = Config\Config::MATCH_YOUTH;
                }
                $index -= Config\Config::$forIndex;
                $xpath = new \DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Positions/Player');
                $player = new \DOMDocument('1.0', 'UTF-8');
                $player->appendChild($player->importNode($nodeList->item($index), true));
                return new Lineup\Player($player, $type);
            }
        }
        return null;
    }

    /**
     * Return iterator of lineup player objects
     *
     * @return \PHT\Xml\Match\Lineup\Player[]
     */
    public function getPlayers()
    {
        if ($this->isDataAvailable()) {
            $type = Config\Config::MATCH_SENIOR;
            if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                $type = Config\Config::MATCH_YOUTH;
            }
            $xpath = new \DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Positions/Player');
            /** @var \PHT\Xml\Match\Lineup\Player[] $data */
            $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Lineup\Player', $type);
            return $data;
        }
        return null;
    }

	/**
	 * Return number bench players
	 *
	 * @return integer
	 */
	public function getBenchPlayerNumber()
	{
		if ($this->isDataAvailable()) {
			$xpath = new \DOMXPath($this->getXml());
			return $xpath->query('//Bench/Player')->length;
		}
		return null;
	}

	/**
	 * Return bench player object
	 *
	 * @param integer $index
	 * @return \PHT\Xml\Match\Lineup\Player
	 */
	public function getBenchPlayer($index)
	{
		if ($this->isDataAvailable()) {
			$index = round($index);
			if ($index >= Config\Config::$forIndex && $index < $this->getBenchPlayerNumber() + Config\Config::$forIndex) {
				$type = Config\Config::MATCH_SENIOR;
				if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
					$type = Config\Config::MATCH_YOUTH;
				}
				$index -= Config\Config::$forIndex;
				$xpath = new \DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bench/Player');
				$player = new \DOMDocument('1.0', 'UTF-8');
				$player->appendChild($player->importNode($nodeList->item($index), true));
				return new Lineup\Player($player, $type);
			}
		}
		return null;
	}

	/**
	 * Return iterator of bench player objects
	 *
	 * @return \PHT\Xml\Match\Lineup\Player[]
	 */
	public function getBenchPlayers()
	{
		if ($this->isDataAvailable()) {
			$type = Config\Config::MATCH_SENIOR;
			if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
				$type = Config\Config::MATCH_YOUTH;
			}
			$xpath = new \DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Bench/Player');
			/** @var \PHT\Xml\Match\Lineup\Player[] $data */
			$data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Lineup\Player', $type);
			return $data;
		}
		return null;
	}

	/**
	 * Return number kickers
	 *
	 * @return integer
	 */
	public function getKickersNumber()
	{
		if ($this->isDataAvailable()) {
			$xpath = new \DOMXPath($this->getXml());
			return $xpath->query('//Kickers/Player')->length;
		}
		return null;
	}

	/**
	 * Return kicker player object
	 *
	 * @param integer $index
	 * @return \PHT\Xml\Match\Lineup\Player
	 */
	public function getKicker($index)
	{
		if ($this->isDataAvailable()) {
			$index = round($index);
			if ($index >= Config\Config::$forIndex && $index < $this->getKickersNumber() + Config\Config::$forIndex) {
				$type = Config\Config::MATCH_SENIOR;
				if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
					$type = Config\Config::MATCH_YOUTH;
				}
				$index -= Config\Config::$forIndex;
				$xpath = new \DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Kickers/Player');
				$player = new \DOMDocument('1.0', 'UTF-8');
				$player->appendChild($player->importNode($nodeList->item($index), true));
				return new Lineup\Player($player, $type);
			}
		}
		return null;
	}

	/**
	 * Return iterator of kickers player objects
	 *
	 * @return \PHT\Xml\Match\Lineup\Player[]
	 */
	public function getKickers()
	{
		if ($this->isDataAvailable()) {
			$type = Config\Config::MATCH_SENIOR;
			if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
				$type = Config\Config::MATCH_YOUTH;
			}
			$xpath = new \DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Kickers/Player');
			/** @var \PHT\Xml\Match\Lineup\Player[] $data */
			$data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Lineup\Player', $type);
			return $data;
		}
		return null;
	}

	/**
	 * Return set pieces player object
	 *
	 * @return \PHT\Xml\Match\Lineup\Player
	 */
	public function getSetPieces()
	{
		if ($this->isDataAvailable()) {
			$type = Config\Config::MATCH_SENIOR;
			if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
				$type = Config\Config::MATCH_YOUTH;
			}
			$xpath = new \DOMXPath($this->getXml());
			$nodeList = $xpath->query('//SetPieces');
			$player = new \DOMDocument('1.0', 'UTF-8');
			$player->appendChild($player->importNode($nodeList->item(0), true));
			return new Lineup\Player($player, $type);
		}
		return null;
	}

	/**
	 * Return captain player object
	 *
	 * @return \PHT\Xml\Match\Lineup\Player
	 */
	public function getCaptain()
	{
		if ($this->isDataAvailable()) {
			$type = Config\Config::MATCH_SENIOR;
			if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
				$type = Config\Config::MATCH_YOUTH;
			}
			$xpath = new \DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Captain');
			$player = new \DOMDocument('1.0', 'UTF-8');
			$player->appendChild($player->importNode($nodeList->item(0), true));
			return new Lineup\Player($player, $type);
		}
		return null;
	}

    /**
     * Return lineup player object
     *
     * @param integer $id
     * @return \PHT\Xml\Match\Lineup\Player
     */
    public function getPlayerByRoleId($id)
    {
        if ($this->isDataAvailable()) {
            $id = round($id);
            if (($id >= 100 && $id < 119) || ($id == 17 || $id == 18)) {
                $type = Config\Config::MATCH_SENIOR;
                if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                    $type = Config\Config::MATCH_YOUTH;
                }
                $xpath = new \DOMXPath($this->getXml());
                $nodeList = $xpath->query('//RoleID[.="' . $id . '"]');
                $player = new \DOMDocument('1.0', 'UTF-8');
                $player->appendChild($player->importNode($nodeList->item(0)->parentNode, true));
                return new Lineup\Player($player, $type);
            }
        }
        return null;
    }

    /**
     * Return number player orders
     *
     * @return integer
     */
    public function getPlayerOrderNumber()
    {
        if ($this->isDataAvailable()) {
            $xpath = new \DOMXPath($this->getXml());
            return $xpath->query('//PlayerOrder')->length;
        }
        return null;
    }

    /**
     * Return player order object
     *
     * @param integer $index
     * @return \PHT\Xml\Match\Orders\Order
     */
    public function getPlayerOrder($index)
    {
        if ($this->isDataAvailable()) {
            $index = round($index);
            if ($index >= Config\Config::$forIndex && $index < $this->getPlayerOrderNumber() + Config\Config::$forIndex) {
                $type = Config\Config::MATCH_SENIOR;
                if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                    $type = Config\Config::MATCH_YOUTH;
                }
                $index -= Config\Config::$forIndex;
                $xpath = new \DOMXPath($this->getXml());
                $nodeList = $xpath->query('//PlayerOrder');
                $order = new \DOMDocument('1.0', 'UTF-8');
                $order->appendChild($order->importNode($nodeList->item($index), true));
                return new Orders\Order($order, $type);
            }
        }
        return null;
    }

    /**
     * Return iterator of player order objects
     *
     * @return \PHT\Xml\Match\Orders\Order[]
     */
    public function getPlayerOrders()
    {
        if ($this->isDataAvailable()) {
            $type = Config\Config::MATCH_SENIOR;
            if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                $type = Config\Config::MATCH_YOUTH;
            }
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//PlayerOrder');
            /** @var \PHT\Xml\Match\Orders\Order[] $data */
            $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Match\Orders\Order', $type);
            return $data;
        }
        return null;
    }

    /**
     * Return number of orders for a player
     *
     * @param integer $playerId
     * @return integer
     */
    public function getPlayerOrderNumberByPlayerId($playerId)
    {
        if ($this->isDataAvailable()) {
            $xpath = new \DOMXPath($this->getXml());
            return $xpath->query('//SubjectPlayerID[.="' . $playerId . '"]')->length;
        }
        return null;
    }

    /**
     * Return player order object
     *
     * @param integer $playerId
     * @param integer $index
     * @return \PHT\Xml\Match\Orders\Order
     */
    public function getPlayerOrderByPlayerId($playerId, $index)
    {
        if ($this->isDataAvailable()) {
            if ($index >= Config\Config::$forIndex && $index < $this->getPlayerOrderNumberByPlayerId($playerId) + Config\Config::$forIndex) {
                $type = Config\Config::MATCH_SENIOR;
                if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                    $type = Config\Config::MATCH_YOUTH;
                }
                $index -= Config\Config::$forIndex;
                $xpath = new \DOMXPath($this->getXml());
                $nodeList = $xpath->query('//SubjectPlayerID[.="' . $playerId . '"]');
                $order = new \DOMDocument('1.0', 'UTF-8');
                $order->appendChild($order->importNode($nodeList->item($index)->parentNode, true));
                return new Orders\Order($order, $type);
            }
        }
        return null;
    }

    /**
     * Return iterator of player order objects
     *
     * @param integer $playerId
     * @return \PHT\Xml\Match\Orders\Order[]
     */
    public function getPlayerOrdersByPlayerId($playerId)
    {
        if ($this->isDataAvailable()) {
            $type = Config\Config::MATCH_SENIOR;
            if (in_array($this->getMatchType(), array(100, 101, 103, 105, 106))) {
                $type = Config\Config::MATCH_YOUTH;
            }
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//SubjectPlayerID[.="' . $playerId . '"]');
            $order = new \DOMDocument('1.0', 'UTF-8');
            for ($i = 0; $i < $nodeList->length; $i++) {
                $order->appendChild($order->importNode($nodeList->item($i)->parentNode, true));
            }
            $nodes = $order->getElementsByTagName('PlayerOrder');
            /** @var \PHT\Xml\Match\Orders\Order[] $data */
            $data = new Utils\XmlIterator($nodes, '\PHT\Xml\Match\Orders\Order', $type);
            return $data;
        }
        return null;
    }
}
