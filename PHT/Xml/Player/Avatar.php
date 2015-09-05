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

namespace PHT\Xml\Player;

use PHT\Xml;
use PHT\Config;
use PHT\Wrapper;

class Avatar extends Xml\Avatar
{
    private $type;
    private $teamId;

    /**
     * @param \DOMDocument $xml
     * @param array $data
     */
    public function __construct($xml, $data)
    {
        parent::__construct($xml);
        $this->type = $data['type'];
        $this->teamId = $data['team'];
    }

    /**
     * Return player id
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
    }

    /**
     * Return player
     *
     * @return \PHT\Xml\Player\Senior|\PHT\Xml\Player\Youth|\PHT\Xml\Player\Hof
     */
    public function getPlayer()
    {
        if ($this->type == Config\Config::YOUTH) {
            return Wrapper\Player\Youth::player($this->getPlayerId());
        } elseif ($this->type == Config\Config::HOF) {
            $hof = Wrapper\Team\Senior::hofplayers($this->teamId);
            foreach ($hof->getPlayers() as $player) {
                if ($player->getId() == $this->getPlayerId()) {
                    return $player;
                }
            }
        }
        return Wrapper\Player\Senior::player($this->getPlayerId());
    }
}
