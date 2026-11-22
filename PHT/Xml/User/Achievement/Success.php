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

namespace PHT\Xml\User\Achievement;

use PHT\Xml;
use PHT\Utils;

class Success extends Xml\Base
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
     * Return achievement type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->getXml()->getElementsByTagName('AchievementTypeID')->item(0)->nodeValue;
    }

    /**
     * Return achievement text
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getXml()->getElementsByTagName('AchievementTitle')->item(0)->nodeValue;
    }

    /**
     * Return achievement text
     *
     * @return string
     */
    public function getText()
    {
        return $this->getXml()->getElementsByTagName('AchievementText')->item(0)->nodeValue;
    }

    /**
     * Return achievement category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->getXml()->getElementsByTagName('CategoryID')->item(0)->nodeValue;
    }

    /**
     * Return if achievement is hidden, then real category is not shown
     *
     * @return boolean
     */
    public function isHidden()
    {
        return $this->getCategory() == 0;
    }

    /**
     * Return achievement category group, same as the tab where achievement is listed on the site
     * (1: Ranking, Team, Matches and Manager, 2: Special Awards, 3: Supporter, 4: HT Arena)
     *
     * @return integer
     */
    public function getCategoryGroup()
    {
        return $this->getXml()->getElementsByTagName('CategoryGroupID')->item(0)->nodeValue;
    }

    /**
     * Return achievement date
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getDate($format = null)
    {
        return Utils\Date::convert($this->getXml()->getElementsByTagName('EventDate')->item(0)->nodeValue, $format);
    }

    /**
     * Return achievement points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->getXml()->getElementsByTagName('Points')->item(0)->nodeValue;
    }

    /**
     * Return if achievement is multi level
     *
     * @return boolean
     */
    public function isMultiLevel()
    {
        return strtolower($this->getXml()->getElementsByTagName('MultiLevel')->item(0)->nodeValue) == "true";
    }

    /**
     * Return achievement number of events
     *
     * @return integer
     */
    public function getNumberOfEvents()
    {
        return $this->getXml()->getElementsByTagName('NumberOfEvents')->item(0)->nodeValue;
    }

    /**
     * Return user's current rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->getXml()->getElementsByTagName('Rank')->item(0)->nodeValue;
    }

    /**
     * Return if achievement is fully completed
     *
     * @return boolean
     */
    public function isCompleted()
    {
        return strtolower($this->getXml()->getElementsByTagName('Completed')->item(0)->nodeValue) == "true";
    }

    /**
     * Return maximum number of points achievable for this achievement
     *
     * @return integer
     */
    public function getMaxPoints()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('/Achievement/MaxPoints')->item(0)->nodeValue;
    }

    /**
     * Return best value reached towards next variation,
     * only for multi level achievements not completed and with progress beyond current variation
     *
     * @return integer
     */
    public function getProgressValue()
    {
        $node = $this->getXml()->getElementsByTagName('ProgressValue')->item(0);
        if ($node !== null) {
            return $node->nodeValue;
        }
        return null;
    }

    /**
     * Return date when progress value was reached
     *
     * @param string $format (php date() function format)
     * @return string
     */
    public function getProgressValueDate($format = null)
    {
        $node = $this->getXml()->getElementsByTagName('ProgressValueDate')->item(0);
        if ($node !== null) {
            return Utils\Date::convert($node->nodeValue, $format);
        }
        return null;
    }

    /**
     * Return currently achieved variation, only for started multi level achievements
     *
     * @return \PHT\Xml\User\Achievement\Variation
     */
    public function getCurrentVariation()
    {
        return $this->getVariation('CurrentVariation');
    }

    /**
     * Return next variation to reach, only for multi level achievements with a further variation
     *
     * @return \PHT\Xml\User\Achievement\Variation
     */
    public function getNextVariation()
    {
        return $this->getVariation('NextVariation');
    }

    /**
     * @param string $tag
     * @return \PHT\Xml\User\Achievement\Variation
     */
    private function getVariation($tag)
    {
        $node = $this->getXml()->getElementsByTagName($tag)->item(0);
        if ($node !== null && $node->hasChildNodes()) {
            $variation = new \DOMDocument('1.0', 'UTF-8');
            $variation->appendChild($variation->importNode($node, true));
            return new Variation($variation);
        }
        return null;
    }
}
