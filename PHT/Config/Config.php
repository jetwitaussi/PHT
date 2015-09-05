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

abstract class Config
{
    /**
     * chpp app consumer key
     *
     * @var string
     */
    public static $consumerKey;

    /**
     * chpp app consumer secret
     *
     * @var string
     */
    public static $consumerSecret;

    /**
     * chpp user oauth token
     *
     * @var string
     */
    public static $oauthToken;

    /**
     * chpp user oauth token
     *
     * @var string
     */
    public static $oauthTokenSecret;

    /**
     * override ht supporter status for app owner:
     * 0 = no change; -1 = deactivate; 1 = activate
     *
     * @var integer
     */
    public static $htSupporter = 0;

    /**
     * use a cache system for xml requests, options available:
     * none = do not use cache
     * session = use php internal session
     * apc = use internal php apc
     * memcached = use memcached server
     *
     * @var string
     */
    public static $cache = 'none';

    /**
     * memcached server ip
     *
     * @var string
     */
    public static $memcachedIp;

    /**
     * memcached server port
     *
     * @var integer
     */
    public static $memcachedPort;

    /**
     * proxy ip to perform queries
     *
     * @var string
     */
    public static $proxyIp = '';

    /**
     * proxy port to perform queries
     *
     * @var integer
     */
    public static $proxyPort;

    /**
     * proxy login to perform queries
     *
     * @var string
     */
    public static $proxyLogin;

    /**
     * proxy password to perform queries
     *
     * @var string
     */
    public static $proxyPasswd;

    /**
     * log level inside pht
     * either 'none', 'info', 'warn', 'error'
     *
     * @var string
     */
    public static $logLevel = 'none';

    /**
     * log file path
     *
     * @var string
     */
    public static $logFile;

    /**
     * starting index for iterations
     *
     * @var integer
     */
    public static $forIndex = 0;

    const DAYS_IN_YEAR = 112;
    const MAX_LIVE_MATCH = 20;
    const MATCH_TOCOME = 'UPCOMING';
    const MATCH_PLAYED = 'FINISHED';
    const MATCH_LIVE = 'ONGOING';
    const MATCH_YOUTH = 'youth';
    const MATCH_SENIOR = 'hattrick';
    const MATCH_TOURNAMENT = 'htointegrated';
    const MATCH_NATIONAL = 'national';
    const PLAYER_URL = '/Club/Players/Player.aspx?';
    const SENIOR = 'senior';
    const YOUTH = 'youth';
    const HOF = 'hof';
    const STAFF = 'staff';
    const HATTRICK_URL = 'http://www.hattrick.org';
    const HATTRICK_DOMAIN = 'hattrick.org';
    const BID_SELLING = 1;
    const BID_BUYING = 2;
    const BID_MOTHERCLUB = 3;
    const BID_PREVIOUSTEAM = 4;
    const BID_HOTLISTED = 5;
    const BID_LOSING = 8;
    const BID_FINISHED = 9;
    const BID_PROSPECTS = 10;
    const CHALLENGE_NORMAL = 0;
    const CHALLENGE_CUP = 1;
    const CHALLENGE_NATIONAL = 12;
    const CHALLENGE_HOME = 0;
    const CHALLENGE_AWAY = 1;
    const CHALLENGE_NEUTRAL = 2;
    const BOOKMARK_ALL = 0;
    const BOOKMARK_SENIOR_TEAM = 1;
    const BOOKMARK_SENIOR_PLAYER = 2;
    const BOOKMARK_SENIOR_MATCH = 3;
    const BOOKMARK_USER = 4;
    const BOOKMARK_SENIOR_LEAGUE = 5;
    const BOOKMARK_YOUTH_TEAM = 6;
    const BOOKMARK_YOUTH_PLAYER = 7;
    const BOOKMARK_YOUTH_MATCH = 8;
    const BOOKMARK_YOUTH_LEAGUE = 9;
    const BOOKMARK_FORUM_POST = 10;
    const BOOKMARK_FORUM_THREAD = 11;
    const STATS_NATIONAL_NT = 'NT';
    const STATS_NATIONAL_WC = 'WC';
    const STATS_ARENA_ALL = 'All';
    const STATS_ARENA_COMPETITION = 'CompOnly';
    const STATS_ARENA_LEAGUE = 'LeagueOnly';
    const STATS_ARENA_FRIENDLY = 'FriendlyOnly';
}
