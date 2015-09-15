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

abstract class AbstractLogger implements LoggerInterface
{
    /**
     * Emergency log
     *
     * @param string $message
     * @param array  $context
     */
    public function emergency($message, array $context = array())
    {
        $this->log(Level::EMERGENCY, $message, $context);
    }

    /**
     * Alert log
     *
     * @param string $message
     * @param array  $context
     */
    public function alert($message, array $context = array())
    {
        $this->log(Level::ALERT, $message, $context);
    }

   /**
     * Critical log
     *
     * @param string $message
     * @param array  $context
     */
    public function critical($message, array $context = array())
    {
        $this->log(Level::CRITICAL, $message, $context);
    }

    /**
     * Error log
     *
     * @param string $message
     * @param array  $context
     */
    public function error($message, array $context = array())
    {
        $this->log(Level::ERROR, $message, $context);
    }

    /**
     * warning log
     *
     * @param string $message
     * @param array  $context
     */
    public function warning($message, array $context = array())
    {
        $this->log(Level::WARNING, $message, $context);
    }

    /**
     * Notice log
     *
     * @param string $message
     * @param array  $context
     */
    public function notice($message, array $context = array())
    {
        $this->log(Level::NOTICE, $message, $context);
    }

    /**
     * Info log
     *
     * @param string $message
     * @param array  $context
     */
    public function info($message, array $context = array())
    {
        $this->log(Level::INFO, $message, $context);
    }

    /**
     * Ddebug log
     *
     * @param string $message
     * @param array  $context
     */
    public function debug($message, array $context = array())
    {
        $this->log(Level::DEBUG, $message, $context);
    }

}
