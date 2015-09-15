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

namespace PHT\Log;

interface LoggerInterface
{
    const LEVEL_800 = 'emergency';
    const LEVEL_700 = 'alert';
    const LEVEL_600 = 'critical';
    const LEVEL_500 = 'error';
    const LEVEL_400 = 'warning';
    const LEVEL_300 = 'notice';
    const LEVEL_200 = 'info';
    const LEVEL_100 = 'debug';

    /**
     * Perform log action
     *
     * @param integer $level (see \PHT\Log\Level constants)
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, $context = array());
}
