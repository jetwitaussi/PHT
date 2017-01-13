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

class I18n extends File
{
    /**
     * Return number of languages
     *
     * @return integer
     */
    public function getLanguageNumber()
    {
        return $this->getXml()->getElementsByTagName('Language')->length;
    }

    /**
     * Return language details
     *
     * @param integer $index
     * @return \PHT\Xml\I18n\Language
     */
    public function getLanguage($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getLanguageNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Language');
            $langue = new \DOMDocument('1.0', 'UTF-8');
            $langue->appendChild($langue->importNode($nodeList->item($index), true));
            return new I18n\Language($langue);
        }
        return null;
    }

    /**
     * Return iterator of language objects
     *
     * @return \PHT\Xml\I18n\Language[]
     */
    public function getLanguages()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Language');
        /** @var \PHT\Xml\I18n\Language[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\I18n\Language');
        return $data;
    }
}
