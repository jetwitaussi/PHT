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

namespace PHT\Utils;

class Date
{
    /**
     * Convert date to a specific format
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function convert($date, $format)
    {
        if ($format === null) {
            return $date;
        }
        return date($format, strtotime($date));
    }

    /**
     * Analyse two dates to get second one later than first one
     *
     * @param string $startDate
     * @param string $endDate
     * @param boolean $limit
     */
    public static function analyse(&$startDate, &$endDate, $limit = true)
    {
        if ($endDate === null && $startDate !== null) {
            $endDate = date('Y-m-d');
        }
        if ($startDate === null && $endDate !== null) {
            if ($limit == true) {
                $startDate = date('Y-m-d', strtotime($endDate) - (3600 * 24 * 7 * 16)); // 1 season
            } else {
                $startDate = null;
            }
        }
        if ($startDate !== null && $endDate !== null) {
            if ($startDate > $endDate) {
                $tmp = $startDate;
                $startDate = $endDate;
                $endDate = $tmp;
            }
            if ($limit == true) {
                $start = strtotime($startDate);
                $end = strtotime($endDate);
                if ($end - $start > 3600 * 24 * 7 * 16 * 2) { // 2 seasons
                    $startDate = $endDate = null;
                }
            }
        }
    }
}
