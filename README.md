## PHT v3.0

Work in progress, feel free to fork, fix, enhance, or give ideas, thought, remarks on pht forum

PHT follows recommandations psr-0, psr-1, psr-2 and log system is psr-3 compliant

Chpp oauth connection is separated from main pht object, follow next code example

There are tons of chaining functions in many classes, so you can get extra data easily, but remember it loads xml each time if you don't use cache

Don't forget to use utf-8 encoding

Happy testing!

---

Requirements:

PHP 5.3+

Extensions:
 - curl
 - dom
 - libxml
 - pcre
 - Reflexion
 - xml

Optional for cache purpose:
 - session
 - apc
 - memcached

---

/!\ Read carefully, this example is not complete, read comments and save data according to your needs

Connection:

```
<?php
require_once 'PHT/autoload.php';
$config = array(
    'CONSUMER_KEY' => 'xxxxxxxxxxxxxx',
    'CONSUMER_SECRET' => 'xxxxxxxxxxxxx'
);
$HT = new \PHT\Connection($config);
$auth = $HT->getPermanentAuthorization('http://your-server/callback-page.php'); // put your own url :)
if ($auth === false) {
    // handle failed connection
    echo "Impossible to initiate chpp connection";
    exit();
}
$tmpToken = $auth->temporaryToken; // save this token somewhere (session, database, file, ...) it's needed in next step
header('Location: ' . $auth->url); // redirect to hattrick login page, or get the url and show a link on your site
exit();
?>
```
In the callback-page file:
```
<?php
require_once 'PHT/autoload.php';
$config = array(
    'CONSUMER_KEY' => 'xxxxxxxxxxxxxx',
    'CONSUMER_SECRET' => 'xxxxxxxxxxxxx',
    'CACHE' => 'none',
    'LOG_TYPE' => 'file',
    'LOG_LEVEL' => \PHT\Log\Level::DEBUG,
    'LOG_FILE' => __DIR__ . '/pht.log',
);
$HT = new \PHT\Connection($config);
// retrive the $tmpToken saved in previous step
$access = $HT->getChppAccess($tmpToken, $_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
if ($access === false) {
    // handle failed connection
    echo "Impossible to confirm chpp connection";
    exit();
}
// if you want to save user credentials for future use
// do it now by saving $access->oauthToken and $access->oauthTokenSecret
// then you can request xml data
$config['OAUTH_TOKEN'] = $access->oauthToken;
$config['OAUTH_TOKEN_SECRET'] = $access->oauthTokenSecret;
try {
    $HT = new \PHT\PHT($config);
    echo $HT->getClub()->getTeamName();
    // then explore PHT\PHT object, all requests start from there, another example:
    $league = $HT->getSeniorLeague();
    echo $league->getCountry()->getEnglishName(); // chaining with country
    foreach($league->getTeams() as $team)
    {
        // chaining with team details
        echo $team->getPosition().' : '.$team->getName().' ('.$team->getTeam()->getShortName().')<br/>';
    }
    
    // you can handle bad argument without going out of main process
    try {
        $HT->setBid(12345, 123456789, \PHT\Utils\Money\FRANCE, -1000);
    } catch(\PHT\Exception\InvalidArguementException $e) {
        echo $e->getMessage(); // amount must be positive
    }

    // look at your log file to see cache in action
    echo $HT->getClub()->getTeamName(); // no xml request done;
    // clear the cache for club file
    \PHT\Cache\Driver::getInstance()->clear('club');
    // request xml again:
    echo $HT->getClub()->getTeamName();
    
    // get HTI team
    $teamConf = new \PHT\Config\Team();
    $teamConf->international = true;
    echo $HT->getSeniorTeam($teamConf)->getName();

} catch(\PHT\Exception\ChppException $e) {
    // chpp request returns xml content
    // but response generate an error
    // maybe wrong data, maybe element not found, ...
    echo $e->getErrorCode().': '.$e->getError();
    // you can also get whole xml response like any other chpp request:
    echo $e->getXml(false);

} catch(\PHT\Exception\NetworkException $e) {
    // chpp request does not return xml
    // so probably html content due to server down or server error
    echo $e->getError();
}
?>
```
---

List of config parameters:
```
CONSUMER_KEY           : your chpp app consumer key
CONSUMER_SECRET        : your chpp app consumer secret
OAUTH_TOKEN            : chpp user token
OAUTH_TOKEN_SECRET     : chpp user token secret
HT_SUPPORTER           : override ht supporter level, by default 0 (0=no change, -1=deactivate, 1=activate)
STARTING_INDEX         : used for loop, by default 0 (pht v2 has starting index at 1)
PROXY_IP               : set your proxy ip
PROXY_PORT             : set your proxy port
PROXY_USER             : set your proxy username
PROXY_PASSWORD         : set your proxy password
LOG_TYPE               : set log type, pht provides: 'file' and 'none', set a classname to use another logger
LOG_LEVEL              : set minimum level of log (see \PHT\Log\Level constants)
LOG_TIME               : set log time format (use php date() format)
LOG_FILE               : set log filename, prefer full path name
CACHE                  : set cache mechanism you want to use: 'none', 'apc', 'session', 'memory', 'memcached'. default: 'none'
CACHE_PREFIX           : set a prefix for cache key, default 'PHT_',
CACHE_TTL              : set a default ttl in seconds for caching xml request, default: 3600
MEMCACHED_SERVER_IP    : set ip of memcached server
MEMCACHED_SERVER_PORT  : set port of memcached server
```
