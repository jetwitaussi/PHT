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

use PHT\Config;

class Player
{
    /**
     * @param string $text
     * @param string $newUrl
     * @return string
     */
    public static function updateUrl($text, $newUrl = null)
    {
        if ($newUrl !== null) {
            return str_replace(Config\Config::PLAYER_URL, $newUrl, $text);
        }
        return str_replace(Config\Config::PLAYER_URL, 'http://www.hattrick.org/goto.ashx?path=' . urlencode(Config\Config::PLAYER_URL), $text);
    }
}
