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

class None extends AbstractLogger
{
    /**
     * Perform log action
     *
     * @param integer $level (see \PHT\Log\Level constants)
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, $context = array())
    {
        // noop
    }
}
