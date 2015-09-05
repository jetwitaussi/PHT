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

class OrdersSubstitution
{
    const BEHAVIOUR_ANY = -1;
    const BEHAVIOUR_NORMAL = 0;
    const BEHAVIOUR_OFFENSIVE = 1;
    const BEHAVIOUR_DEFENSIVE = 2;
    const BEHAVIOUR_TOWARDS_MIDDLE = 3;
    const BEHAVIOUR_TOWARDS_WING = 4;
    const TYPE_SUBSTITUTION = 1;
    const TYPE_BEHAVIOUR = 1;
    const TYPE_SWAP = 3;
    const REDCARD_IGNORE = -1;
    const REDCARD_MYPLAYER = 1;
    const REDCARD_OPPONENT = 2;
    const REDCARD_MY_CENTRAL_DEFENDER = 11;
    const REDCARD_MY_MIDFIELD = 12;
    const REDCARD_MY_FORWARD = 13;
    const REDCARD_MY_WING_BACK = 14;
    const REDCARD_MY_WING = 15;
    const REDCARD_OPPONENT_CENTRAL_DEFENDER = 21;
    const REDCARD_OPPONENT_MIDFIELD = 22;
    const REDCARD_OPPONENT_FORWARD = 23;
    const REDCARD_OPPONENT_WING_BACK = 24;
    const REDCARD_OPPONENT_WING = 25;
    const STANDING_ANY = -1;
    const STANDING_TIED = 0;
    const STANDING_LEAD = 1;
    const STANDING_DOWN = 2;
    const STANDING_LEAD_MORE_ONE = 3;
    const STANDING_DOWN_MORE_ONE = 4;
    const STANDING_NOT_DOWN = 5;
    const STANDING_NOT_LEAD = 6;
    const STANDING_LEAD_MORE_TWO = 7;
    const STANDING_DOWN_MORE_TWO = 8;
    const POSITION_GOALKEEPER = 0;
    const POSITION_RIGHT_DEFENDER = 1;
    const POSITION_RIGHT_CENTRAL_DEFENDER = 2;
    const POSITION_CENTRAL_DEFENDER = 3;
    const POSITION_LEFT_CENTRAL_DEFENDER = 4;
    const POSITION_LEFT_DEFENDER = 5;
    const POSITION_RIGHT_WINGER = 6;
    const POSITION_RIGHT_MIDFIELD = 7;
    const POSITION_CENTRAL_MIDFIELD = 8;
    const POSITION_LEFT_MIDFIELD = 9;
    const POSITION_LEFT_WINGER = 10;
    const POSITION_RIGHT_FORWARD = 11;
    const POSITION_CENTRAL_FORWARD = 12;
    const POSITION_LEFT_FORWARD = 13;

    /**
     * Player who replace
     *
     * @var integer
     */
    public $playerIn;

    /**
     * Player replaced
     *
     * @var integer
     */
    public $playerOut = 0;

    /**
     * Type of substitution (use class constants TYPE_*)
     *
     * @var integer
     */
    public $orderType;

    /**
     * Minute of substitution
     *
     * @var integer
     */
    public $minute;

    /**
     * Position of substitution (use class constants POSITION_*)
     *
     * @var integer
     */
    public $position;

    /**
     * Behaviour of substitution (use class constants BEHAVIOUR_*)
     *
     * @var integer
     */
    public $behaviour = self::BEHAVIOUR_ANY;

    /**
     * Red card condition of substitution (use class constants REDCARD_*)
     *
     * @var integer
     */
    public $card = self::REDCARD_IGNORE;

    /**
     * Set standing situation of substitution (use class constants STANDING_*)
     *
     * @var integer
     */
    public $standing = self::STANDING_ANY;
}
