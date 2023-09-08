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

namespace PHT\Xml\Team\Staff;

use PHT\Xml;

class TrainerAvatar extends Xml\Avatar
{
    /**
     * @param \DOMDocument $xml
     * @param array $data
     */
    public function __construct($xml, $data = null)
    {
        parent::__construct($xml);
    }

    /**
     * Return employee id
     *
     * @return integer
     */
    public function getTrainerId()
    {
        return $this->getXml()->getElementsByTagName('TrainerId')->item(0)->nodeValue;
    }
}
