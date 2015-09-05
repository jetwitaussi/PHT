## PHT v3.0

Work in progress, feel free to fork, fix, enhance, or give ideas, thought, remarks on pht forum

PHT follows recommandations psr-0, psr-1 and psr-2

Currently there is no cache nor log, xml is always requested, turn on php error to see what happens if something fails

Chpp oauth connection is separated from main pht object, follow next code example

There are tons of chaining functions in many classes, so you can get extra data easily, but remember it loads xml each time

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
    'CONSUMER_SECRET' => 'xxxxxxxxxxxxx'
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
	foreach($league->getLeagueTeams() as $team)
	{
		// chaining with team details
		echo $team->getPosition().' : '.$team->getName().' ('.$team->getTeam()->getShortTeamName().')<br/>';
	}
} catch(\PHT\Exception\Exception $e) {
	echo $e->getError();
}
?>
```
---

List of config parameters:
```
CONSUMER_KEY           : your chpp app consumer key
CONSUMER_SECRET        : your chpp app consumer secret
HT_SUPPORTER           : override ht supporter level, by default 0 (0=no change, -1=deactivate, 1=activate)
STARTING_INDEX         : used for loop, by default 0 (pht v2 has starting index at 1)
PROXY_IP               : set your proxy ip
PROXY_PORT             : set your proxy port
PROXY_USER             : set your proxy username
PROXY_PASSWORD         : set your proxy password
CACHE                  : will be used to set cache mechanism you want to use, not functional right now
MEMCACHED_SERVER_IP    : will be used to set ip of memcached server, not functional right now
MEMCACHED_SERVER_PORT  : will be used to set port of memcached server, not functional right now
LOG_LEVEL              : will be used to set level of log (like debug, warn, error, ...), not functional right now
LOG_FILE               : will be used to set log filename, not functional right now
```