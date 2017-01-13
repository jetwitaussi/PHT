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
use PHT\Config;
use PHT\Utils;
use PHT\Wrapper;

class Avatars extends Xml\File
{
    private $type;
    private $teamId;

    /**
     * @param string $xml
     * @param string $type
     * @param integer $teamId
     */
    public function __construct($xml, $type, $teamId = null)
    {
        parent::__construct($xml);
        $this->type = $type;
        $this->teamId = $teamId;
    }

    /**
     * Return team id
     *
     * @return integer
     */
    public function getTeamId()
    {
        if ($this->type == Config\Config::STAFF) {
            return $this->teamId;
        } elseif ($this->type == Config\Config::YOUTH) {
            return $this->getXml()->getElementsByTagName('YouthTeamId')->item(0)->nodeValue;
        }
        return $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
    }

    /**
     * Return team
     *
     * @return \PHT\Xml\Team\Senior|\PHT\Xml\Team\Youth
     */
    public function getTeam()
    {
        if ($this->type == Config\Config::YOUTH) {
            return Wrapper\Team\Youth::team($this->getTeamId());
        }
        return Wrapper\Team\Senior::team($this->getTeamId());
    }

    /**
     * Return avatar number
     *
     * @return integer
     */
    public function getAvatarNumber()
    {
        if ($this->type == Config\Config::STAFF) {
            return $this->getXml()->getElementsByTagName('Staff')->length;
        } elseif ($this->type == Config\Config::YOUTH) {
            return $this->getXml()->getElementsByTagName('YouthPlayer')->length;
        }
        return $this->getXml()->getElementsByTagName('Player')->length;
    }

    /**
     * Return avatar object
     *
     * @param integer $index
     * @return \PHT\Xml\Player\Avatar|\PHT\Xml\Team\Staff\Avatar
     */
    public function getAvatar($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getAvatarNumber() + Config\Config::$forIndex) {
            $tag = 'Player';
            if ($this->type == Config\Config::STAFF) {
                $tag = 'Staff';
            } elseif ($this->type == Config\Config::YOUTH) {
                $tag = 'YouthPlayer';
            }
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query("//" . $tag);
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            if ($this->type == Config\Config::STAFF) {
                return new Xml\Team\Staff\Avatar($node, array('type' => $this->type, 'team' => $this->getTeamId()));
            }
            return new Xml\Player\Avatar($node, array('type' => $this->type, 'team' => $this->getTeamId()));
        }
        return null;
    }

    /**
     * Return iterator of avatar objects
     *
     * @return \PHT\Xml\Player\Avatar[]|\PHT\Xml\Team\Staff\Avatar[]
     */
    public function getAvatars()
    {
        $tag = 'Player';
        $ns = '\PHT\Xml\Player\Avatar';
        if ($this->type == Config\Config::STAFF) {
            $tag = 'Staff';
            $ns = '\PHT\Xml\Team\Staff\Avatar';
        } elseif ($this->type == Config\Config::YOUTH) {
            $tag = 'YouthPlayer';
        }
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//" . $tag);
        /** @var \PHT\Xml\Player\Avatar[]|\PHT\Xml\Team\Staff\Avatar[] $data */
        $data = new Utils\XmlIterator($nodeList, $ns, array('type' => $this->type, 'team' => $this->getTeamId()));
        return $data;
    }

    /**
     * Return avatar object
     *
     * @param integer $id
     * @return \PHT\Xml\Player\Avatar|\PHT\Xml\Team\Staff\Avatar
     */
    public function getAvatarById($id)
    {
        $tag = 'PlayerID';
        if ($this->type == Config\Config::STAFF) {
            $tag = 'StaffId';
        } elseif ($this->type == Config\Config::YOUTH) {
            $tag = 'YouthPlayerID';
        }
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//" . $tag . "[.='" . $id . "']");
        if ($nodeList->length) {
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item(0)->parentNode, true));
            if ($this->type == Config\Config::STAFF) {
                return new Xml\Team\Staff\Avatar($node, array('type' => $this->type, 'team' => $this->getTeamId()));
            }
            return new Xml\Player\Avatar($node, array('type' => $this->type, 'team' => $this->getTeamId()));
        }
        return null;
    }
}
