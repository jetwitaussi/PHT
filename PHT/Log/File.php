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

use PHT\Config;

class File extends AbstractLogger
{
    private $minLevel;

    /**
     * @param integer $level (see \PHT\Log\Level constants)
     */
    public function __construct($level)
    {
        $this->minLevel = $level;
    }

    /**
     * Perform log action
     *
     * @param integer $level (see \PHT\Log\Level constants)
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, $context = array())
    {
        if ($level < $this->minLevel) {
            return;
        }
        file_put_contents(Config\Config::$logFile, $this->buildMessage($level, $message, $context), FILE_APPEND);
    }

    /**
     * Build message to log
     *
     * @param integer $level (see \PHT\Log\Level constants)
     * @param string $message
     * @param array $context
     * @return string
     */
    private function buildMessage($level, $message, $context = array())
    {
        return sprintf("%s|%s|%s %s\n", date(Config\Config::$logTime), strtoupper(constant('\PHT\Log\LoggerInterface::LEVEL_' . $level)), $message, $this->formatContext($context));
    }

    /**
     * Format context data for logging
     *
     * @param array $context
     * @return string
     */
    private function formatContext($context)
    {
        if (!count($context)) {
            return '';
        }
        return str_replace(array("\n", "\r", "\t"), ' ', var_export($context, true));
    }

}
