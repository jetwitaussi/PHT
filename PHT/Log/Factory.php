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

use PHT\Exception;

class Factory
{
    /**
     * Create a logger instance
     *
     * @param string $type
     * @param integer $level (see \PHT\Log\Level constants)
     * @return mixed|\PHT\Log\AbstractLogger
     * @throws \PHT\Exception\InvalidArgumentException
     */
    public static function create($type, $level)
    {
        switch ($type) {

            case 'none':
                return new None($level);

            case 'file':
                return new File($level);

            default:
                if (class_exists($type)) {
                    return new $type($level);
                }
                throw new Exception\InvalidArgumentException("Unknow logger type: $type");
        }
    }
}
