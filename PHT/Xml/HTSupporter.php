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

class HTSupporter extends File
{
    /**
     * Is the user Hattrick Supporter ?
     *
     * @return boolean
     */
    public function isHtSupporter()
    {
        $supp = '';
        if ($this->getXml()->getElementsByTagName('UserSupporterTier')->length) {
            $supp = $this->getXml()->getElementsByTagName('UserSupporterTier')->item(0)->nodeValue;
        } elseif ($this->getXml()->getElementsByTagName('SupporterTier')->length) {
            $supp = $this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue;
        }
        return $supp != '' && $supp != 'none';
    }

    /**
     * Is the user Silver Supporter ?
     *
     * @return boolean
     */
    public function isHtSupporterSilver()
    {
        if ($this->getXml()->getElementsByTagName('UserSupporterTier')->length) {
            return strtolower($this->getXml()->getElementsByTagName('UserSupporterTier')->item(0)->nodeValue) == "silver";
        } elseif ($this->getXml()->getElementsByTagName('SupporterTier')->length) {
            return strtolower($this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue) == "silver";
        }
        return false;
    }

    /**
     * Is the user Gold Supporter ?
     *
     * @return boolean
     */
    public function isHtSupporterGold()
    {
        if ($this->getXml()->getElementsByTagName('UserSupporterTier')->length) {
            return strtolower($this->getXml()->getElementsByTagName('UserSupporterTier')->item(0)->nodeValue) == "gold";
        } elseif ($this->getXml()->getElementsByTagName('SupporterTier')->length) {
            return strtolower($this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue) == "gold";
        }
        return false;
    }

    /**
     * Is the user Platinum Supporter ?
     *
     * @return boolean
     */
    public function isHtSupporterPlatinum()
    {
        if ($this->getXml()->getElementsByTagName('UserSupporterTier')->length) {
            return strtolower($this->getXml()->getElementsByTagName('UserSupporterTier')->item(0)->nodeValue) == "platinum";
        } elseif ($this->getXml()->getElementsByTagName('SupporterTier')->length) {
            return strtolower($this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue) == "platinum";
        }
        return false;
    }

    /**
     * Is the user Diamond Supporter ?
     *
     * @return boolean
     */
    public function isHtSupporterDiamond()
    {
        if ($this->getXml()->getElementsByTagName('UserSupporterTier')->length) {
            return strtolower($this->getXml()->getElementsByTagName('UserSupporterTier')->item(0)->nodeValue) == "diamond";
        } elseif ($this->getXml()->getElementsByTagName('SupporterTier')->length) {
            return strtolower($this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue) == "diamond";
        }
        return false;
    }

    /**
     * Get Supporter level
     *
     * @return string
     */
    public function getHtSupporterLevel()
    {
        if ($this->getXml()->getElementsByTagName('UserSupporterTier')->length) {
            return $this->getXml()->getElementsByTagName('UserSupporterTier')->item(0)->nodeValue;
        } elseif ($this->getXml()->getElementsByTagName('SupporterTier')->length) {
            return $this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue;
        }
        return null;
    }
}
