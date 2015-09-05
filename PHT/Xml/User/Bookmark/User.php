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

namespace PHT\Xml\User\Bookmark;

use PHT\Xml;
use PHT\Wrapper;

class User extends Xml\User\Bookmark\Element
{
    /**
     * Return user name
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->getText();
    }

    /**
     * Return user id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->getObjectId();
    }

    /**
     * Return user
     *
     * @return \PHT\Xml\User
     */
    public function getUser()
    {
        return Wrapper\User::user($this->getUserId());
    }
}
