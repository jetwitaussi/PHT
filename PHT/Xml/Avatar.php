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

class Avatar extends Base
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
     * Return background image url
     *
     * @return string
     */
    public function getBackground()
    {
        return $this->getXml()->getElementsByTagName('BackgroundImage')->item(0)->nodeValue;
    }

    /**
     * Return player number
     *
     * @return integer
     */
    public function getLayerNumber()
    {
        return $this->getXml()->getElementsByTagName('Layer')->length;
    }

    /**
     * Return avatar layer object
     *
     * @param integer $index
     * @return \PHT\Xml\Avatar\Layer
     */
    public function getLayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getLayerNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query("//Layer");
            $node = new \DOMDocument('1.0', 'UTF-8');
            $node->appendChild($node->importNode($nodeList->item($index), true));
            return new Avatar\Layer($node);
        }
        return null;
    }

    /**
     * Return iterator of avatar layer objects
     *
     * @return \PHT\Xml\Avatar\Layer[]
     */
    public function getLayers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query("//Layer");
        /** @var \PHT\Xml\Avatar\Layer[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\Avatar\Layer');
        return $data;
    }

    /**
     * Return avatar html code
     *
     * @param string $css
     * @param string $class
     * @return string
     */
    public function getHtml($css = null, $class = null)
    {
        $html = '<div style="position:relative; height:155px; width:110px; background: url(' . Config\Config::HATTRICK_URL . $this->getBackground() . '); ';
        if ($css !== null) {
            $html .= $css;
        }
        $html .= '"';
        if ($class !== null) {
            $html .= ' class="' . $class . '"';
        }
        $html .= '>';
        foreach ($this->getLayers() as $layer) {
            $img = $layer->getImage();
            if (strpos($img, Config\Config::HATTRICK_DOMAIN) === false) {
                $img = Config\Config::HATTRICK_URL . $img;
            }
            $html .= '<img src="' . $img . '" style="position:absolute; left:' . $layer->getX() . 'px; top:' . $layer->getY() . 'px;"/>';
        }
        $html .= '</div>';
        return $html;
    }
}
