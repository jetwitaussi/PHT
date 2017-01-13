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

namespace PHT\Config;

use PHT\Utils;

class TransferMarket
{
    const SKILL_GOALKEEPER = 1;
    const SKILL_STAMINA = 2;
    const SKILL_SETPIECES = 3;
    const SKILL_DEFENDING = 4;
    const SKILL_SCORING = 5;
    const SKILL_WINGER = 6;
    const SKILL_PASSING = 7;
    const SKILL_PLAYMAKING = 8;
    const SKILL_TRAINER = 9;
    const SKILL_LEADERSHIP = 10;
    const SKILL_EXPERIENCE = 11;

    private $ageMin = null;
    private $dayMin = null;
    private $ageMax = null;
    private $dayMax = null;
    private $skillType1 = null;
    private $skillMin1 = null;
    private $skillMax1 = null;
    private $skillType2 = null;
    private $skillMin2 = null;
    private $skillMax2 = null;
    private $skillType3 = null;
    private $skillMin3 = null;
    private $skillMax3 = null;
    private $skillType4 = null;
    private $skillMin4 = null;
    private $skillMax4 = null;
    private $specialty = null;
    private $country = null;
    private $tsiMin = null;
    private $tsiMax = null;
    private $priceMin = null;
    private $priceMax = null;
    private $pageSize = null;
    private $pageIndex = null;

    /**
     * Set min age (Can also set min days with float age parameter)
     *
     * @param float $age
     */
    public function setMinAge($age)
    {
        if (round($age) != $age) {
            list($this->ageMin, $this->dayMin) = explode('.', (string)$age);
        } else {
            $this->ageMin = $age;
        }
    }

    /**
     * Set max age (Can also set max days with float age parameter)
     *
     * @param float $age
     */
    public function setMaxAge($age)
    {
        if (round($age) != $age) {
            list($this->ageMax, $this->dayMax) = explode('.', (string)$age);
        } else {
            $this->ageMax = $age;
        }
    }

    /**
     * Set min days
     *
     * @param integer $days
     */
    public function setMinDays($days)
    {
        $this->dayMin = $days;
    }

    /**
     * Set max days
     *
     * @param integer $days
     */
    public function setMaxDays($days)
    {
        $this->dayMax = $days;
    }

    /**
     * Set first skill search
     *
     * @param integer $type
     * @param integer $min
     * @param integer $max
     */
    public function setFirstSkill($type, $min = null, $max = null)
    {
        $this->setSkill(1, $type, $min, $max);
    }

    /**
     * Set second skill search
     *
     * @param integer $type
     * @param integer $min
     * @param integer $max
     */
    public function setSecondSkill($type, $min = null, $max = null)
    {
        $this->setSkill(2, $type, $min, $max);
    }

    /**
     * Set third skill search
     *
     * @param integer $type
     * @param integer $min
     * @param integer $max
     */
    public function setThirdSkill($type, $min = null, $max = null)
    {
        $this->setSkill(3, $type, $min, $max);
    }

    /**
     * Set fourth skill search
     *
     * @param integer $type
     * @param integer $min
     * @param integer $max
     */
    public function setFourthSkill($type, $min = null, $max = null)
    {
        $this->setSkill(4, $type, $min, $max);
    }

    /**
     * @param integer $num
     * @param integer $type
     * @param integer $min
     * @param integer $max
     */
    private function setSkill($num, $type, $min, $max)
    {
        $this->{'skillType' . $num} = $type;
        if ($min !== null) {
            $this->{'skillMin' . $num} = $min;
        }
        if ($max !== null) {
            $this->{'skillMax' . $num} = $max;
        }
    }

    /**
     * Set specialty
     *
     * @param integer $specialtyId
     */
    public function setSpecialty($specialtyId)
    {
        $this->specialty = $specialtyId;
    }

    /**
     * Set native country
     *
     * @param integer $countryId
     */
    public function setCountryId($countryId)
    {
        $this->country = $countryId;
    }

    /**
     * Set min TSI
     *
     * @param integer $tsi
     */
    public function setMinTSI($tsi)
    {
        $this->tsiMin = $tsi;
    }

    /**
     * Set max TSI
     *
     * @param integer $tsi
     */
    public function setMaxTSI($tsi)
    {
        $this->tsiMax = $tsi;
    }

    /**
     * Set min price
     *
     * @param integer $price
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     */
    public function setMinPrice($price, $countryCurrency = Utils\Money::SVERIGE)
    {
        $this->priceMin = Utils\Money::toSEK($price, $countryCurrency);
    }

    /**
     * Set max price
     *
     * @param integer $price
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     */
    public function setMaxPrice($price, $countryCurrency = Utils\Money::SVERIGE)
    {
        $this->priceMax = Utils\Money::toSEK($price, $countryCurrency);
    }

    /**
     * Set result page size
     *
     * @param integer $size
     */
    public function setSize($size)
    {
        $this->pageSize = $size;
    }

    /**
     * Set result page index
     *
     * @param integer $page
     */
    public function setPage($page)
    {
        $this->pageIndex = $page;
    }

    /**
     * Returns min age
     *
     * @return integer
     */
    public function getMinAge()
    {
        return $this->ageMin;
    }

    /**
     * Returns max age
     *
     * @return integer
     */
    public function getMaxAge()
    {
        return $this->ageMax;
    }

    /**
     * Returns min days
     *
     * @return integer
     */
    public function getMinDays()
    {
        return $this->dayMin;
    }

    /**
     * Returns max days
     *
     * @return integer
     */
    public function getMaxDays()
    {
        return $this->dayMax;
    }

    /**
     * Returns first skill type, min and max values
     *
     * @return array
     */
    public function getFirstSkill()
    {
        return array($this->skillType1, $this->skillMin1, $this->skillMax1);
    }

    /**
     * Returns second skill type, min and max values
     *
     * @return array
     */
    public function getSecondSkill()
    {
        return array($this->skillType2, $this->skillMin2, $this->skillMax2);
    }

    /**
     * Returns third skill type, min and max values
     *
     * @return array
     */
    public function getThirdSkill()
    {
        return array($this->skillType3, $this->skillMin3, $this->skillMax3);
    }

    /**
     * Returns fourth skill type, min and max values
     *
     * @return array
     */
    public function getFourthSkill()
    {
        return array($this->skillType4, $this->skillMin4, $this->skillMax4);
    }

    /**
     * Returns specialty id
     *
     * @return integer
     */
    public function getSpecialty()
    {
        return $this->specialty;
    }

    /**
     * Returns country id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->country;
    }

    /**
     * Returns min tsi
     *
     * @return integer
     */
    public function getMinTSI()
    {
        return $this->tsiMin;
    }

    /**
     * Returns max tsi
     *
     * @return integer
     */
    public function getMaxTSI()
    {
        return $this->tsiMax;
    }

    /**
     * Returns min price
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getMinPrice($countryCurrency = null)
    {
        if ($countryCurrency !== null) {
            return Utils\Money::convert($this->priceMin, $countryCurrency);
        }
        return $this->priceMin;
    }

    /**
     * Returns max price
     *
     * @param integer $countryCurrency (Constant taken from \PHT\Utils\Money class)
     * @return integer
     */
    public function getMaxPrice($countryCurrency = null)
    {
        if ($countryCurrency !== null) {
            return Utils\Money::convert($this->priceMax, $countryCurrency);
        }
        return $this->priceMax;
    }

    /**
     * Returns page size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->pageSize;
    }

    /**
     * Returns page index
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->pageIndex;
    }
}
