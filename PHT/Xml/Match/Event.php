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
use PHT\Utils;

class Event extends Xml\Base
{
    protected $type;

    /**
     * @param \DOMDocument $xml
     * @param string $type
     */
    public function __construct($xml, $type)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
        $this->type = $type;
    }

    /**
     * Return event minute
     *
     * @return integer
     */
    public function getMinute()
    {
        return $this->getXml()->getElementsByTagName('Minute')->item(0)->nodeValue;
    }

    /**
     * Return match part when event happened
     *
     * @return integer
     */
    public function getMatchPart()
    {
        return $this->getXml()->getElementsByTagName('MatchPart')->item(0)->nodeValue;
    }

    /**
     * Return event subject player id raw value
     *
     * @return integer
     */
    public function getRawSubjectPlayerId()
    {
        return $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
    }

    /**
     * Return event subject team id raw value
     *
     * @return integer
     */
    public function getRawSubjectTeamId()
    {
        return $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
    }

    /**
     * Return event object player id raw value
     *
     * @return integer
     */
    public function getRawObjectPlayerId()
    {
        return $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
    }

    /**
     * Return event key
     *
     * @return string
     */
    public function getKey()
    {
        $node = $this->getXml()->getElementsByTagName('EventKey');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        } else {
            return $this->getKeyId() . '_' . $this->getKeyVariation();
        }
    }

    /**
     * Return event key id
     *
     * @return integer
     */
    public function getKeyId()
    {
        $node = $this->getXml()->getElementsByTagName('EventTypeID');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        } else {
            $key = explode('_', $this->getKey());
            return $key[0];
        }
    }

    /**
     * Return event key variation
     *
     * @return integer
     */
    public function getKeyVariation()
    {
        $node = $this->getXml()->getElementsByTagName('EventVariation');
        if ($node->length) {
            return $node->item(0)->nodeValue;
        } else {
            $key = explode('_', $this->getKey());
            return $key[1];
        }
    }

    /**
     * Return event text
     *
     * @param string $playerUrlReplacement (given url is concat with : PlayerID=xxxxxxx )
     * @return string
     */
    public function getText($playerUrlReplacement = null)
    {
        $text = $this->getXml()->getElementsByTagName('EventText')->item(0)->nodeValue;
        return Utils\Player::updateUrl($text, $playerUrlReplacement);
    }

    /**
     * Return specifics availables methods on event
     *
     * @return array
     */
    public function getMethodsNames()
    {
        $f = new \ReflectionClass(get_class($this));
        $methods = array();
        foreach ($f->getMethods() as $m) {
            if ($m->class == get_class($this)) {
                $methods[] = $m->name;
            }
        }
        return $methods;
    }
}
