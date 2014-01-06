<?php
/**
 * PHT 2.19.1 - 2014-01-06
 *
 * @author Telesphore
 * @link http://pht.htloto.org
 * @version 2.19.1
 * @license http://www.php.net/license/3_0.txt
 */

/** */
class CHPPConnection
{
	//---global---
	private $consumerKey;
	private $consumerSecret;
	private $callback;
	protected $signatureMethod = 'HMAC-SHA1';
	protected $version = '1.0';
	private $dom;
	private $oauthToken;
	private $oauthTokenSecret;
	protected $oauthFirstTokenSecret;
	private $logfile = null;
	private $canlogfile = null;
	private $overrideSupporter = 0;
	//------------

	//---data---
	private $club = array();
	private $teams = array();
	private $teamsFlags = array();
	private $teamsSupporters = array();
	private $teamsUserid = array();
	private $teamsFlagsUserid = array();
	private $usersSupporters = array();
	private $economy = null;
	private $regions = array();
	private $leagues = array();
	private $leaguesSeason = array();
	private $worldDetails = null;
	private $worldDetailsByCountry = array();
	private $worldDetailsByLeague = array();
	private $worldLanguages = null;
	private $training = array();
	private $trainingStats = array();
	private $teamsTransfersHistory = array();
	private $playersTransfersHistory = array();
	private $players = array();
	private $teamsPlayers = array();
	private $teamsOldPlayers = array();
	private $teamsOldCoaches = array();
	private $matches = array();
	private $teamsMatches = array();
	private $youthMatches = array();
	private $youthTeamsMatches = array();
	private $matchesDetails = array();
	private $youthMatchesDetails = array();
	private $teamsArchivesMatches = array();
	private $youthTeamsArchivesMatches = array();
	private $lineups = array();
	private $youthLineups = array();
	private $tournamentLineups = array();
	private $liveNumber = null;
	private $liveMatches = null;
	private $live = null;
	private $liveAllEvents = false;
	private $liveJson = null;
	private $nationalAteams = null;
	private $nationalU20teams = null;
	private $nationalAmatches = null;
	private $nationalU20matches = null;
	private $nationalTeamDetails = array();
	private $nationalPlayers = array();
	private $nationalPlayersStats = array();
	private $nationalPlayersStatsWC = array();
	private $worldCupGroups = array();
	private $worldCupMatches = array();
	private $alliancesName = array();
	private $alliancesAbbr = array();
	private $alliancesDesc = array();
	private $alliancesIds = array();
	private $allianceDetails = array();
	private $allianceMembers = array();
	private $allianceMembersLetters = array();
	private $allianceRoles = array();
	private $allianceRoleDetails = array();
	private $arenas = array();
	private $arenasTeams = array();
	private $arenasStats = array();
	private $myArenaStats = array();
	private $myArenaStatsTeams = array();
	private $matchOrders = array();
	private $youthMatchOrders = array();
	private $tournamentMatchOrders = array();
	private $challenges = array();
	private $challengeableteams = array();
	private $cups = array();
	private $searchTeamId = array();
	private $searchTeamName = array();
	private $searchUserName = array();
	private $searchPlayerId = array();
	private $searchPlayerName = array();
	private $searchRegionName = array();
	private $searchArenaName = array();
	private $searchMatchId = array();
	private $searchSeriesName = array();
	private $searchSeriesId = array();
	private $bookmarks = array();
	private $bookmarksTeams = null;
	private $bookmarksPlayers = null;
	private $bookmarksMatches = null;
	private $bookmarksConfUsers = null;
	private $bookmarksLeagues = null;
	private $bookmarksYouthTeams = null;
	private $bookmarksYouthPlayers = null;
	private $bookmarksYouthMatches = null;
	private $bookmarksYouthLeagues = null;
	private $bookmarksConfPosts = null;
	private $bookmarksConfThreads = null;
	private $trainingEvents = array();
	private $playerEvents = array();
	private $fans = array();
	private $achievements = array();
	private $flagsHome = array();
	private $flagsAway = array();
	private $flagsPlayers = array();
	private $flagsRaisedPlayers = array();
	private $hofPlayers = array();
	private $userAlliances = null;
	private $youthTeams = array();
	private $youthScouts = array();
	private $avatars = array();
	private $hofAvatars = array();
	private $youthAvatars = array();
	private $youthTeamsPlayers = array();
	private $youthTeamsPlayersDetails = null;
	private $youthTeamsPlayersUnlockskills = null;
	private $youthPlayers = array();
	private $tournamentMatchesDetails = array();
	private $primaryTeam = array();
	private $secondaryTeam = array();
	private $tournament = array();
	private $tournaments = array();
	private $tournamentLeagues = array();
	private $tournamentMatches = array();
	private $teamsLadders = array();
	private $ladders = array();
	private $laddersTeam = array();
	private $translations = array();
	private $youthLeagues = array();
	private $youthLeaguesSeasons = array();
	private $compendium = array();
	//----------

	//---url---
	const OAUTH_SERVER	= 'https://chpp.hattrick.org';
	const XML_SERVER		= 'http://chpp.hattrick.org';
	const REQUEST_URL		= '/oauth/request_token.ashx';
	const AUTH_URL			= '/oauth/authorize.aspx';
	const AUTHC_URL			= '/oauth/authenticate.aspx';
	const ACCESS_URL		= '/oauth/access_token.ashx';
	const CHECK_URL			= '/oauth/check_token.ashx';
	const INVAL_URL			= '/oauth/invalidate_token.ashx';
	const CHPP_URL			= '/chppxml.ashx';
	const ERROR_FILE		= 'chpperror.xml';
	//---------

	/**
	 * Create a CHPP connection to Hattrick
	 *
	 * @param String $consumerKey
	 * @param String $consumerSecret
	 * @param String $callback
	 */
	public function __construct($consumerKey, $consumerSecret, $callback = '')
	{
		$this->consumerKey = $consumerKey;
		$this->consumerSecret = $consumerSecret;
		$this->callback = $callback;
	}

	/**
	 * Get hattrick authorize url for chpp application
	 *
	 * @param String $scope
	 * @return String
	 */
	public function getAuthorizeUrl($scope = null)
	{
		return $this->getAuthUrl(self::AUTH_URL, $scope);
	}

	/**
	 * Get hattrick authenticate url for chpp application
	 *
	 * @param String $scope
	 * @return String
	 */
	public function getAuthenticateUrl($scope = null)
	{
		return $this->getAuthUrl(self::AUTHC_URL, $scope);
	}

	/**
	 * @param String $page
	 * @param String $scope
	 * @return String
	 */
	protected function getAuthUrl($authPage, $scope)
	{
		$params = array(
			'oauth_consumer_key' => $this->consumerKey,
			'oauth_signature_method' => $this->signatureMethod,
			'oauth_timestamp' => $this->getTimestamp(),
			'oauth_nonce' => $this->getNonce(),
			'oauth_callback' => $this->callback,
			'oauth_version' => $this->version
		);
		$signature = $this->buildSignature(self::OAUTH_SERVER.self::REQUEST_URL, $params);
		$params['oauth_signature'] = $signature;
		uksort($params, 'strcmp');
		$url = $this->buildOauthUrl(self::OAUTH_SERVER.self::REQUEST_URL, $params);
		if($this->canLog())
		{
			$this->log("[OAUTH] Request url: ".$url);
		}
		$return = $this->fetchUrl($url, false);
		$result = explode('&', $return);
		foreach($result as $val)
		{
			$t = explode('=', $val);
			$$t[0] = urldecode($t[1]);
		}
		if($this->canLog())
		{
			$this->log("[OAUTH] Request token: ".$oauth_token);
			$this->log("[OAUTH] Request token secret: ".$oauth_token_secret);
		}
		$this->setOauthToken($oauth_token);
		$this->oauthFirstTokenSecret = $oauth_token_secret;
		$url = self::OAUTH_SERVER.$authPage.'?oauth_token='.urlencode($this->getOauthToken());
		if($scope !== null)
		{
			$url .= '&scope='.$scope;
		}
		if($this->canLog())
		{
			$this->log("[OAUTH] Authorize url: ".$url);
		}
		return $url;
	}

	/**
	 * Get access token for chpp application
	 *
	 * @param String $oauthToken
	 * @param String $verifier
	 */
	public function retrieveAccessToken($oauthToken, $verifier)
	{
		$params = array(
			'oauth_consumer_key' => $this->consumerKey,
			'oauth_signature_method' => $this->signatureMethod,
			'oauth_timestamp' => $this->getTimestamp(),
			'oauth_nonce' => $this->getNonce(),
			'oauth_token' => $oauthToken,
			'oauth_verifier' => $verifier,
			'oauth_version' => $this->version
		);
		$signature = $this->buildSignature(self::OAUTH_SERVER.self::ACCESS_URL, $params, $this->oauthFirstTokenSecret);
		$params['oauth_signature'] = $signature;
		uksort($params, 'strcmp');
		$url = $this->buildOauthUrl(self::OAUTH_SERVER.self::ACCESS_URL, $params);
		if($this->canLog())
		{
			$this->log("[OAUTH] Access url: ".$url);
		}
		$return = $this->fetchUrl($url, false);
		$result = explode('&', $return);
		foreach($result as $val)
		{
			$t = explode('=', $val);
			$$t[0] = urldecode($t[1]);
		}
		if(isset($oauth_token))
		{
			$this->setOauthToken($oauth_token);
			if($this->canLog())
			{
				$this->log("[OAUTH] Access token: ".$oauth_token);
			}
		}
		if(isset($oauth_token_secret))
		{
			$this->setOauthTokenSecret($oauth_token_secret);
			if($this->canLog())
			{
				$this->log("[OAUTH] Access token secret: ".$oauth_token_secret);
			}
		}
	}

	/**
	 * Returns HTCheckToken object
	 *
	 * @param String $oauthToken
	 * @param String $oauthTokenSecret
	 * @return HTCheckToken
	 */
	public function checkToken($oauthToken = null, $oauthTokenSecret = null)
	{
		if($oauthToken === null)
		{
			$oauthToken = $this->getOauthToken();
		}
		if($oauthTokenSecret === null)
		{
			$oauthTokenSecret = $this->getOauthTokenSecret();
		}
		$params = array(
			'oauth_consumer_key' => $this->consumerKey,
			'oauth_signature_method' => $this->signatureMethod,
			'oauth_timestamp' => $this->getTimestamp(),
			'oauth_nonce' => $this->getNonce(),
			'oauth_token' => $oauthToken,
			'oauth_version' => $this->version
		);
		$signature = $this->buildSignature(self::OAUTH_SERVER.self::CHECK_URL, $params, $oauthTokenSecret);
		$params['oauth_signature'] = $signature;
		uksort($params, 'strcmp');
		$url = $this->buildOauthUrl(self::OAUTH_SERVER.self::CHECK_URL, $params);
		if($this->canLog())
		{
			$this->log("[OAUTH] Check token url: ".$url);
		}
		return new HTCheckToken($this->fetchUrl($url, false));
	}

	/**
	 * Invalidate a token
	 *
	 * @param String $oauthToken
	 * @param String $oauthTokenSecret
	 */
	public function invalidateToken($oauthToken = null, $oauthTokenSecret = null)
	{
		if($oauthToken === null)
		{
			$oauthToken = $this->getOauthToken();
		}
		if($oauthTokenSecret === null)
		{
			$oauthTokenSecret = $this->getOauthTokenSecret();
		}
		$params = array(
			'oauth_consumer_key' => $this->consumerKey,
			'oauth_signature_method' => $this->signatureMethod,
			'oauth_timestamp' => $this->getTimestamp(),
			'oauth_nonce' => $this->getNonce(),
			'oauth_token' => $oauthToken,
			'oauth_version' => $this->version
		);
		$signature = $this->buildSignature(self::OAUTH_SERVER.self::INVAL_URL, $params, $oauthTokenSecret);
		$params['oauth_signature'] = $signature;
		uksort($params, 'strcmp');
		$url = $this->buildOauthUrl(self::OAUTH_SERVER.self::INVAL_URL, $params);
		if($this->canLog())
		{
			$this->log("[OAUTH] Invalidate token: ".$url);
		}
		$this->fetchUrl($url, false);
	}

	/**
	 * Return user's oauth token
	 *
	 * @return String
	 */
	public function getOauthToken()
	{
		return $this->oauthToken;
	}

	/**
	 * Return user's oauth token secret
	 *
	 * @return String
	 */
	public function getOauthTokenSecret()
	{
		return $this->oauthTokenSecret;
	}

	/**
	 * Set user's oauth token
	 *
	 * @param String $token
	 */
	public function setOauthToken($token)
	{
		$this->oauthToken = $token;
	}

	/**
	 * Set user's oauth token secret
	 *
	 * @param String $secret
	 */
	public function setOauthTokenSecret($secret)
	{
		$this->oauthTokenSecret = $secret;
	}

	/**
	 * @return Integer
	 */
	protected function getTimestamp()
	{
		return time();
	}

	/**
	 * @return String
	 */
	protected function getNonce()
	{
		return md5(microtime().mt_rand());
	}

	/**
	 * Get oauth signature
	 *
	 * @param String $url
	 * @param Array $params
	 * @param String $token
	 * @return String
	 */
	protected function buildSignature($url, $params, $token = '', $method = 'GET')
	{
		$parts = array($method, $url, $this->buildHttpQuery($params));
		$parts = implode('&', $this->urlencodeRfc3986($parts));
		if($this->canLog())
		{
			$this->log("[OAUTH] Base string: ".$parts);
		}
		$key_parts = array($this->consumerSecret, $token);
		$key = implode('&', $this->urlencodeRfc3986($key_parts));
		$sign = base64_encode(hash_hmac('sha1', $parts, $key, true));
		if($this->canLog())
		{
			$this->log("[OAUTH] Generate signature: ".$sign);
		}
		return $sign;
	}

	/**
	 * Get url parameters for oauth query
	 *
	 * @param Array $params
	 * @return String
	 */
	protected function buildHttpQuery($params)
	{
		if(!count($params))
		{
			return '';
		}
		$keys = $this->urlencodeRfc3986(array_keys($params));
		$values = $this->urlencodeRfc3986(array_values($params));
		$params = array_combine($keys, $values);
		uksort($params, 'strcmp');
		$pairs = array();
		foreach ($params as $parameter => $value)
		{
			if(is_array($value))
			{
				sort($value, SORT_STRING);
				foreach($value as $duplicate_value)
				{
					$pairs[] = $parameter . '=' . $duplicate_value;
				}
			}
			else
			{
				$pairs[] = $parameter . '=' . $value;
			}
		}
		return implode('&', $pairs);
	}

	/**
	 * Urlencode parameters properly for oauth query
	 *
	 * @param Array|String $input
	 * @return String
	 */
	protected function urlencodeRfc3986($input)
	{
		if(is_array($input))
	 	{
	  	return array_map(array(&$this, 'urlencodeRfc3986'), $input);
		}
		return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($input)));
	}

	/**
	 * Activate log
	 *
	 * @param String $filePath
	 */
	public function startLog($filePath = null)
	{
		if($filePath === null)
		{
			$filePath = dirname(__FILE__).'/PHT.log';
		}
		$this->canlogfile = null;
		$this->logfile = $filePath;
	}

	/**
	 * Deactivate log
	 */
	public function stopLog()
	{
		$this->logfile = null;
		$this->canlogfile = null;
	}

	/**
	 * Check if log can be performed
	 *
	 * @return Boolean
	 */
	public function canLog()
	{
		if($this->canlogfile === null)
		{
			if($this->logfile !== null)
			{
				if((file_exists($this->logfile) && !is_dir($this->logfile) && is_writeable($this->logfile))
				|| (!file_exists($this->logfile) && touch($this->logfile)))
				{
					$this->canlogfile = true;
				}
				else
				{
					$this->canlogfile = false;
				}
			}
			else
			{
				$this->canlogfile = false;
			}
		}
		return $this->canlogfile;
	}

	/**
	 * Write text to log file
	 *
	 * @param String $text
	 */
	public function log($text)
	{
		if($this->canLog())
		{
			file_put_contents($this->logfile, date("Y-m-d H:i:s ").$text."\n", FILE_APPEND);
		}
	}

	/**
	 * Return if chpp server is available or not
	 *
	 * @param Integer $timeout (Timeout in milliseconds)
	 * @return Boolean
	 */
	public function pingChppServer($timeout = 1000)
	{
		$curl = curl_init(self::XML_SERVER);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($curl, CURLOPT_TIMEOUT_MS, (int)$timeout);
		curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		return ((int)$code) === 200;
	}

	/**
	 * Force HT Supporter to be activated
	 */
	public function enableHTSupporter()
	{
		$this->overrideSupporter = 1;
	}

	/**
	 * Force HT Supporter to be deactivated
	 */
	public function disableHTSupporter()
	{
		$this->overrideSupporter = -1;
	}

	/**
	 * Do not override HT Supporter status in any way
	 */
	public function resetHTSupporter()
	{
		$this->overrideSupporter = 0;
	}

	/**
	 * Load data of user's club
	 *
	 * @param Integer $teamId
	 * @return HTClub
	 */
	public function getClub($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->club[$teamId]) || $this->club[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'club', 'version'=>'1.3', 'teamId'=>$teamId));
			$this->club[$teamId] = new HTClub($this->fetchUrl($url));
		}
		return $this->club[$teamId];
	}

	/**
	 * Clear cache of club
	 *
	 * @param Integer $teamId
	 */
	public function clearClub($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->club[$teamId] = null;
	}

	/**
	 * Clear cache of all clubs
	 */
	public function clearClubs()
	{
		$this->club = array();
	}

	/**
	 * Get data of user's team by default or of teamId if given
	 *
	 * @param Integer $id
	 * @return HTTeam
	 */
	public function getTeam($id = null)
	{
		if(!isset($this->teams[$id]) || $this->teams[$id] === null)
		{
			$params = array('file'=>'teamdetails', 'version'=>'3.1');
			if($id !== null)
			{
				$params['teamID'] = $id;
			}
			$url = $this->buildUrl($params);

			$this->teams[$id] = new HTTeam($this->fetchUrl($url), $id);
		}
		return $this->teams[$id];
	}

	/**
	 * Delete cache of team data
	 *
	 * @param Integer $id
	 */
	public function clearTeam($id = null)
	{
		if($id === null)
		{
			$id = $this->getClub()->getTeamId();
		}
		$this->teams[$id] = null;
	}

	/**
	 * Clear cache of all teams
	 */
	public function clearTeams()
	{
		$this->teams = array();
	}

	/**
	 * Returns HTTeam object
	 *
	 * @param Integer $userId
	 * @return HTTeam
	 */
	public function getPrimaryTeam($userId = null)
	{
		if(!isset($this->primaryTeam[$userId]) || $this->primaryTeam[$userId] === null)
		{
			$params = array('file'=>'teamdetails', 'version'=>'3.1');
			if($userId !== null)
			{
				 $params['userID'] = $userId;
			}
			$url = $this->buildUrl($params);
			$xml = $this->fetchUrl($url);

			$doc = new DOMDocument('1.0', 'UTF-8');
			$doc->loadXml($xml);

			$teams = $doc->getElementsByTagName('Team');
			for($t=0; $t<$teams->length; $t++)
			{
				$txml = new DOMDocument('1.0', 'UTF-8');
				$txml->appendChild($txml->importNode($teams->item($t), true));
				if(strtolower($txml->getElementsByTagName('IsPrimaryClub')->item(0)->nodeValue) == 'false')
				{
					$doc->getElementsByTagName('Teams')->item(0)->removeChild($teams->item($t));
				}
			}
			if($doc->getElementsByTagName('Team')->length)
			{
				$this->primaryTeam[$userId] = new HTTeam($doc->saveXML());
			}
			else
			{
				return null;
			}
		}
		return $this->primaryTeam[$userId];
	}

	/**
	 * Clear primary team
	 *
	 * @param Integer $userId
	 */
	public function clearPrimaryTeam($userId = null)
	{
		$this->primaryTeam[$userId] = null;
	}

	/**
	 * Clear all primary teams
	 */
	public function clearPrimaryTeams()
	{
		$this->primaryTeam = array();
	}

	/**
	 * Returns HTTeam object
	 *
	 * @param Integer $userId
	 * @return HTTeam
	 */
	public function getSecondaryTeam($userId = null)
	{
		if(!isset($this->secondaryTeam[$userId]) || $this->secondaryTeam[$userId] === null)
		{
			$params = array('file'=>'teamdetails', 'version'=>'3.1');
			if($userId !== null)
			{
				 $params['userID'] = $userId;
			}
			$url = $this->buildUrl($params);
			$xml = $this->fetchUrl($url);

			$doc = new DOMDocument('1.0', 'UTF-8');
			$doc->loadXml($xml);

			$teams = $doc->getElementsByTagName('Team');
			for($t=0; $t<$teams->length; $t++)
			{
				$txml = new DOMDocument('1.0', 'UTF-8');
				$txml->appendChild($txml->importNode($teams->item($t), true));
				if(strtolower($txml->getElementsByTagName('IsPrimaryClub')->item(0)->nodeValue) == 'true')
				{
					$doc->getElementsByTagName('Teams')->item(0)->removeChild($teams->item($t));
				}
			}
			if($doc->getElementsByTagName('Team')->length)
			{
				$this->secondaryTeam[$userId] = new HTTeam($doc->saveXML());
			}
			else
			{
				return null;
			}
		}
		return $this->secondaryTeam[$userId];
	}

	/**
	 * Clear secondary team
	 *
	 * @param Integer $userId
	 */
	public function clearSecondaryTeam($userId = null)
	{
		$this->secondaryTeam[$userId] = null;
	}

	/**
	 * Clear all secondary teams
	 */
	public function clearSecondaryTeams()
	{
		$this->secondaryTeam = array();
	}

	/**
	 * Return HTTeamFlags object
	 *
	 * @param Integer $id
	 * @param Boolean $includeDomesticFlags
	 * @return HTTeamFlags
	 */
	public function getTeamFlags($id = null, $includeDomesticFlags = false)
	{
		if(!isset($this->teamsFlags[$id][$includeDomesticFlags]) || $this->teamsFlags[$id][$includeDomesticFlags] === null)
		{
			$params = array('file'=>'teamdetails', 'version'=>'3.1', 'includeFlags'=>'true');
			if($id !== null)
			{
				$params['teamID'] = $id;
			}
			if($includeDomesticFlags == true)
			{
				$params['includeDomesticFlags'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->teamsFlags[$id][$includeDomesticFlags] = new HTTeamFlags($this->fetchUrl($url), $id);
		}
		return $this->teamsFlags[$id][$includeDomesticFlags];
	}

	/**
	 * Delete cache of team flags data
	 *
	 * @param Integer $id
	 */
	public function clearTeamFlags($id = null)
	{
		if($id === null)
		{
			$id = $this->getClub()->getTeamId();
		}
		$this->teamsFlags[$id] = null;
	}

	/**
	 * Clear cache of all teams flags
	 */
	public function clearTeamsFlags()
	{
		$this->teamsFlags = array();
	}

	/**
	 * Get data of user's team by default or of userId if given
	 *
	 * @param Integer $id
	 * @param Integer $teamId
	 * @return HTTeam
	 */
	public function getTeamByUserId($id = null, $teamId = null)
	{
		if($id === null)
		{
			$id = $this->getClub()->getUserId();
		}
		if(!isset($this->teamsUserid[$id][$teamId]) || $this->teamsUserid[$id][$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'teamdetails', 'userID'=>$id, 'version'=>'3.1'));
			$this->teamsUserid[$id][$teamId] = new HTTeam($this->fetchUrl($url), $teamId);
		}
		return $this->teamsUserid[$id][$teamId];
	}

	/**
	 * Delete cache of team by userid data
	 *
	 * @param Integer $id
	 * @param Integer $teamId
	 */
	public function clearTeamByUserId($id = null, $teamId = null)
	{
		if($id === null)
		{
			$id = $this->getClub()->getUserId();
		}
		$this->teamsUserid[$id][$teamId] = null;
	}

	/**
	 * Clear cache of all teams by user id
	 */
	public function clearTeamsByUserId()
	{
		$this->teamsUserid = array();
	}

	/**
	 * Get data of user's team flags by default or of userId if given
	 *
	 * @param Integer $id
	 * @param Boolean $includeDomesticFlags
	 * @return HTTeamFlags
	 */
	public function getTeamFlagsByUserId($id = null, $includeDomesticFlags = false, $teamId = null)
	{
		if($id === null)
		{
			$id = $this->getClub()->getUserId();
		}
		if(!isset($this->teamsFlagsUserid[$id][$includeDomesticFlags][$teamId]) || $this->teamsFlagsUserid[$id][$includeDomesticFlags][$teamId] === null)
		{
			$params = array('file'=>'teamdetails', 'userID'=>$id, 'version'=>'3.1', 'includeFlags'=>'true');
			if($includeDomesticFlags == true)
			{
				$params['includeDomesticFlags'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->teamsFlagsUserid[$id][$includeDomesticFlags][$teamId] = new HTTeamFlags($this->fetchUrl($url), $teamId);
		}
		return $this->teamsFlagsUserid[$id][$includeDomesticFlags][$teamId];
	}

	/**
	 * Delete cache of team flags by userid data
	 *
	 * @param Integer $id
	 */
	public function clearTeamFlagsByUserId($id = null)
	{
		if($id === null)
		{
			$id = $this->getClub()->getUserId();
		}
		$this->teamsFlagsUserid[$id] = null;
	}

	/**
	 * Clear cache of all teams flags by user id
	 */
	public function clearTeamsFlagsByUserId()
	{
		$this->teamsFlagsUserid = array();
	}

	/**
	 * Get team supported teams
	 *
	 * @param Integer $teamId
	 * @param Integer $pageIndex
	 * @param Integer $pageSize
	 * @return HTTeamSupporters
	 */
	public function getTeamSupporters($teamId = null, $pageIndex = null, $pageSize = null)
	{
		if(!isset($this->teamsSupporters[$teamId][$pageIndex][$pageSize]) || $this->teamsSupporters[$teamId][$pageIndex][$pageSize] === null)
		{
			$params = array('file'=>'supporters', 'version'=>'1.0', 'actionType'=>'supportedteams');
			if($teamId !== null)
			{
				$params['teamId'] = $teamId;
			}
			if($pageIndex !== null)
			{
				$params['pageIndex'] = $pageIndex;
			}
			if($pageSize !== null)
			{
				$params['pageSize'] = $pageSize;
			}
			$url = $this->buildUrl($params);
			$this->teamsSupporters[$teamId][$pageIndex][$pageSize] = new HTTeamSupporters($this->fetchUrl($url));
		}
		return $this->teamsSupporters[$teamId][$pageIndex][$pageSize];
	}

	/**
	 * Delete cache of team supporters data
	 *
	 * @param Integer $teamId
	 */
	public function clearTeamSupporters($teamId = null)
	{
		$this->teamsSupporters[$teamId] = null;
	}

	/**
	 * Clear cache of all teams supporters
	 */
	public function clearTeamsSupporters()
	{
		$this->teamsSupporters = array();
	}

	/**
	 * Get teams supporterd by user
	 *
	 * @param Integer $userId
	 * @param Integer $pageIndex
	 * @param Integer $pageSize
	 * @return HTUserSupporters
	 */
	public function getUserSupporters($userId = null, $pageIndex = null, $pageSize = null)
	{
		if(!isset($this->usersSupporters[$userId][$pageIndex][$pageSize]) || $this->usersSupporters[$userId][$pageIndex][$pageSize] === null)
		{
			$params = array('file'=>'supporters', 'version'=>'1.0', 'actionType'=>'mysupporters');
			if($userId !== null)
			{
				$params['userId'] = $userId;
			}
			if($pageIndex !== null)
			{
				$params['pageIndex'] = $pageIndex;
			}
			if($pageSize !== null)
			{
				$params['pageSize'] = $pageSize;
			}
			$url = $this->buildUrl($params);
			$this->usersSupporters[$userId][$pageIndex][$pageSize] = new HTUserSupporters($this->fetchUrl($url));
		}
		return $this->usersSupporters[$userId][$pageIndex][$pageSize];
	}

	/**
	 * Delete cache of user supporters data
	 *
	 * @param Integer $userId
	 */
	public function clearUserSupporters($userId = null)
	{
		$this->usersSupporters[$userId] = null;
	}

	/**
	 * Clear cache of all users supporters
	 */
	public function clearUsersSupporters()
	{
		$this->usersSupporters = array();
	}

	/**
	 * Get economy data of user's club, converted in country currency if specfied
	 *
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 * @return HTEconomy
	 */
	public function getEconomy($countryCurrency = null, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->economy[$teamId][$countryCurrency]) || $this->economy[$teamId][$countryCurrency] === null)
		{
			$url = $this->buildUrl(array('file'=>'economy', 'version'=>'1.3', 'teamId'=>$teamId));
			$this->economy[$teamId][$countryCurrency] = new HTEconomy($this->fetchUrl($url), $countryCurrency);
		}
		return $this->economy[$teamId][$countryCurrency];
	}

	/**
	 * Clear cache of economy
	 */
	public function clearEconomy()
	{
		$this->economy = null;
	}

	/**
	 * Return HTRegion object
	 *
	 * @param Integer $regionId
	 * @return HTRegion
	 */
	public function getRegion($regionId = null)
	{
		if($regionId === null)
		{
			$regionId = $this->getTeam()->getRegionId();
		}
		if(!isset($this->regions[$regionId]) || $this->regions[$regionId] === null)
		{
			$url = $this->buildUrl(array('file'=>'regiondetails', 'regionID'=>$regionId, 'version'=>'1.2'));
			$this->regions[$regionId] = new HTRegion($this->fetchUrl($url));
		}
		return $this->regions[$regionId];
	}

	/**
	 * Clear cache of region
	 */
	public function clearRegion($regionId = null)
	{
		if($regionId === null)
		{
			$regionId = $this->getTeam()->getRegionId();
		}
		$this->regions[$regionId] = null;
	}

	/**
	 * Clear cache of all regions
	 */
	public function clearAllRegions()
	{
		$this->regions= array();
	}

	/**
	 * Return a HTLeague object
	 *
	 * @param Integer $id
	 * @return HTLeague
	 */
	public function getLeague($id = null)
	{
		if($id === null)
		{
			$id = $this->getTeam()->getLeagueLevelId();
		}
		if(!isset($this->leagues[$id]) || $this->leagues[$id] === null)
		{
			$url = $this->buildUrl(array('file'=>'leaguedetails', 'leagueLevelUnitID'=>$id, 'version'=>'1.4'));
			$this->leagues[$id] = new HTLeague($this->fetchUrl($url));
		}
		return $this->leagues[$id];
	}

	/**
	 * Delete cache of league
	 *
	 * @param Integer $id
	 */
	public function clearLeague($id = null)
	{
		if($id === null)
		{
			$id = $this->getTeam()->getLeagueLevelId();
		}
		$this->leagues[$id] = null;
	}

	/**
	 * Delete cache of all leagues
	 */
	public function clearLeagues()
	{
		$this->leagues = array();
	}

	/**
	 * Return a HTLeagueSeason object
	 *
	 * @param Integer $leagueLevelId
	 * @param Integer $season
	 * @return HTLeagueSeason
	 */
	public function getLeagueSeason($leagueLevelId = null, $season = null)
	{
		if($leagueLevelId === null)
		{
			$leagueLevelId = $this->getLeague()->getLeagueLevelId();
		}
		if(!isset($this->leaguesSeason[$leagueLevelId][$season]) || $this->leaguesSeason[$leagueLevelId][$season] === null)
		{
			$params = array('file'=>'leaguefixtures', 'leagueLevelUnitID'=>$leagueLevelId, 'version'=>'1.2');
			if($season !== null)
			{
				$params['season'] = $season;
			}
			$url = $this->buildUrl($params);
			$this->leaguesSeason[$leagueLevelId][$season] = new HTLeagueSeason($this->fetchUrl($url));
		}
		return $this->leaguesSeason[$leagueLevelId][$season];
	}

	/**
	 * Delete cache of league fixture
	 *
	 * @param Integer $leagueLevelId
	 * @param Integer $season
	 */
	public function clearLeagueSeason($leagueLevelId = null, $season = null)
	{
		if($leagueLevelId === null)
		{
			$leagueLevelId = $this->getLeague()->getLeagueLevelId();
		}
		if($season === null)
		{
			$season = $this->getLeagueSeason()->getSeason();
		}
		$this->leaguesSeason[$leagueLevelId][$season] = null;
	}

	/**
	 * Delete all leagues fixture caches
	 */
	public function clearLeaguesSeasons()
	{
		$this->leaguesSeason = array();
	}

	/**
	 * Returns HTYouthLeague object
	 *
	 * @param Integer $youthLeagueId
	 * @return HTYouthLeague
	 */
	public function getYouthLeague($youthLeagueId = null)
	{
		if($youthLeagueId === null)
		{
			$youthLeagueId = $this->getYouthTeam()->getLeagueId();
		}
		if(!isset($this->youthLeagues[$youthLeagueId]) || $this->youthLeagues[$youthLeagueId] === null)
		{
			$url = $this->buildUrl(array('file'=>'youthleaguedetails', 'version'=>'1.0', 'youthleagueid'=>$youthLeagueId));
			$this->youthLeagues[$youthLeagueId] = new HTYouthLeague($this->fetchUrl($url));
		}
		return $this->youthLeagues[$youthLeagueId];
	}

	/**
	 * Clear youth league
	 *
	 * @param Integer $youthLeagueId
	 */
	public function clearYouthLeague($youthLeagueId = null)
	{
		if($youthLeagueId === null)
		{
			$youthLeagueId = $this->getYouthTeam()->getLeagueId();
		}
		$this->youthLeagues[$youthLeagueId] = null;
	}

	/**
	 * Clear all youth leagues
	 */
	public function clearYouthLeagues()
	{
		$this->youthLeagues = array();
	}

	/**
	 * Return a HTYouthLeagueSeason object
	 *
	 * @param Integer $youthLeagueId
	 * @param Integer $season
	 * @return HTLeagueSeason
	 */
	public function getYouthLeagueSeason($youthLeagueId = null, $season = null)
	{
		if($youthLeagueId === null)
		{
			$youthLeagueId = $this->getYouthTeam()->getLeagueId();
		}
		if(!isset($this->youthLeaguesSeasons[$youthLeagueId][$season]) || $this->youthLeaguesSeasons[$youthLeagueId][$season] === null)
		{
			$params = array('file'=>'youthleaguefixtures', 'youthleagueid'=>$youthLeagueId, 'version'=>'1.0');
			if($season !== null)
			{
				$params['season'] = $season;
			}
			$url = $this->buildUrl($params);
			$this->youthLeaguesSeasons[$youthLeagueId][$season] = new HTYouthLeagueSeason($this->fetchUrl($url));
		}
		return $this->youthLeaguesSeasons[$youthLeagueId][$season];
	}

	/**
	 * Delete cache of youth league fixture
	 *
	 * @param Integer $youthLeagueId
	 * @param Integer $season
	 */
	public function clearYouthLeagueSeason($youthLeagueId = null, $season = null)
	{
		if($youthLeagueId === null)
		{
			$youthLeagueId = $this->getYouthTeam()->getLeagueId();
		}
		$this->youthLeaguesSeasons[$youthLeagueId][$season] = null;
	}

	/**
	 * Delete all youth leagues fixture caches
	 */
	public function clearYouthLeaguesSeasons()
	{
		$this->youthLeaguesSeasons = array();
	}

	/**
	 * Return HTWorldDetails object
	 *
	 * @return HTWorldDetails
	 */
	public function getWorldDetails($includeRegions = false)
	{
		if(!isset($this->worldDetails[$includeRegions]) || $this->worldDetails[$includeRegions] === null)
		{
			$params = array('file'=>'worlddetails', 'version'=>'1.5');
			if($includeRegions === true)
			{
				$params['includeRegions'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->worldDetails[$includeRegions] = new HTWorldDetails($this->fetchUrl($url));
		}
		return $this->worldDetails[$includeRegions];
	}

	/**
	 * Delete cache of world detail
	 */
	public function clearWorldDetails()
	{
		$this->worldDetails = null;
	}

	/**
	 * Return HTWorldDetails object
	 *
	 * @return HTWorldDetails
	 */
	public function getWorldDetailsByCountryId($countryId, $includeRegions = false)
	{
		if(!isset($this->worldDetailsByCountry[$countryId][$includeRegions]) || $this->worldDetailsByCountry[$countryId][$includeRegions] === null)
		{
			$params = array('file'=>'worlddetails', 'version'=>'1.5', 'countryID'=>$countryId);
			if($includeRegions === true)
			{
				$params['includeRegions'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->worldDetailsByCountry[$countryId][$includeRegions] = new HTWorldDetails($this->fetchUrl($url));
		}
		return $this->worldDetailsByCountry[$countryId][$includeRegions];
	}

	/**
	 * Delete cache of world detail by country id
	 */
	public function clearWorldDetailsByCountryId()
	{
		$this->worldDetailsByCountry = array();
	}

	/**
	 * Return HTWorldDetails object
	 *
	 * @return HTWorldDetails
	 */
	public function getWorldDetailsByLeagueId($leagueId, $includeRegions = false)
	{
		if(!isset($this->worldDetailsByLeague[$leagueId][$includeRegions]) || $this->worldDetailsByLeague[$leagueId][$includeRegions] === null)
		{
			$params = array('file'=>'worlddetails', 'version'=>'1.5', 'leagueID'=>$leagueId);
			if($includeRegions === true)
			{
				$params['includeRegions'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->worldDetailsByLeague[$leagueId][$includeRegions] = new HTWorldDetails($this->fetchUrl($url));
		}
		return $this->worldDetailsByLeague[$leagueId][$includeRegions];
	}

	/**
	 * Delete cache of world detail by league id
	 */
	public function clearWorldDetailsByLeagueId()
	{
		$this->worldDetailsByLeague = array();
	}

	/**
	 * Return HTWorldLanguages object
	 *
	 * @return HTWorldLanguages
	 */
	public function getWorldLanguages()
	{
		if(!isset($this->worldLanguages) || $this->worldLanguages === null)
		{
			$url = $this->buildUrl(array('file'=>'worldlanguages', 'version'=>'1.2'));
			$this->worldLanguages = new HTWorldLanguages($this->fetchUrl($url));
		}
		return $this->worldLanguages;
	}

	/**
	 * Delete cache of world langauges
	 */
	public function clearWorldLanguages()
	{
		$this->worldLanguages = null;
	}

	/**
	 * Return HTTraining object
	 *
	 * @param Integer $teamId
	 * @return HTTraining
	 */
	public function getTraining($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->training[$teamId]) || $this->training[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'training', 'version'=>'2.2', 'teamId'=>$teamId));
			$this->training[$teamId] = new HTTraining($this->fetchUrl($url));
		}
		return $this->training[$teamId];
	}

	/**
	 * Delete team cache of training
	 *
	 * @param Integer $teamId
	 */
	public function clearTraining($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->training[$teamId] = null;
	}

	/**
	 * Delete all cache of training
	 */
	public function clearTrainings()
	{
		$this->training = array();
	}

	/**
	 * Return HTTrainingStats object
	 *
	 * @param Integer $leagueId
	 * @return HTTrainingStats
	 */
	public function getTrainingStats($leagueId = null)
	{
		if(!isset($this->trainingStats[$leagueId]) || $this->trainingStats[$leagueId] === null)
		{
			$params = array('file'=>'training', 'actionType'=>'stats', 'version'=>'2.2');
			if($leagueId !== null)
			{
				$params['leagueID'] = $leagueId;
			}
			$url = $this->buildUrl($params);
			$this->trainingStats[$leagueId] = new HTTrainingStats($this->fetchUrl($url));
		}
		return $this->trainingStats[$leagueId];
	}

	/**
	 * Delete training stats cache of league id
	 *
	 * @param unknown_type $leagueId
	 */
	public function clearTrainingStats($leagueId = null)
	{
		$this->trainingStats[$leagueId] = null;
	}

	/**
	 * Delete all caches of training stats
	 */
	public function clearTrainingsStats()
	{
		$this->trainingStats = array();
	}

	/**
	 * Set training type
	 *
	 * @param Integer $teamId
	 * @param Integer $type (0 General (Deprecated), 1 Stamina (Deprecated), 2 Set Pieces, 3 Defending, 4	Scoring, 5 Cross Pass (Winger), 6 Shooting, 7 Short Passes, 8 Playmaking, 9 Goaltending, 10 Through Passes, 11 Defensive Positions, 12 Wing Attacks)
	 * @return Boolean
	 */
	public function setTrainingType($teamId, $type)
	{
		$params = array('file'=>'training', 'actionType'=>'setTraining', 'version'=>'2.2', 'teamId'=>$teamId, 'trainingType'=>$type);
		$url = $this->buildUrl($params);
		$xml = new HTGlobal($this->fetchUrl($url));
		return strtolower($xml->getXml()->getElementsByTagName('TrainingSet')->item(0)->nodeValue) == 'true';
	}

	/**
	 * Set training level
	 *
	 * @param Integer $teamId
	 * @param Integer $level
	 * @return Boolean
	 */
	public function setTrainingLevel($teamId, $level)
	{
		$params = array('file'=>'training', 'actionType'=>'setTraining', 'version'=>'2.2', 'teamId'=>$teamId, 'trainingLevel'=>$level);
		$url = $this->buildUrl($params);
		$xml = new HTGlobal($this->fetchUrl($url));
		return strtolower($xml->getXml()->getElementsByTagName('TrainingSet')->item(0)->nodeValue) == 'true';
	}

	/**
	 * Set training stamina level
	 *
	 * @param Integer $teamId
	 * @param Integer $level
	 * @return Boolean
	 */
	public function setTrainingStamina($teamId, $level)
	{
		$params = array('file'=>'training', 'actionType'=>'setTraining', 'version'=>'2.2', 'teamId'=>$teamId, 'trainingLevelStamina'=>$level);
		$url = $this->buildUrl($params);
		$xml = new HTGlobal($this->fetchUrl($url));
		return strtolower($xml->getXml()->getElementsByTagName('TrainingSet')->item(0)->nodeValue) == 'true';
	}

	/**
	 * Return HTTranslation object
	 *
	 * @param Integer $languageId
	 * @return HTTranslation
	 */
	public function getTranslation($languageId)
	{
		if(!isset($this->translations[$languageId]) || $this->translations[$languageId] === null)
		{
			$url = $this->buildUrl(array('file'=>'translations', 'languageId'=>$languageId, 'version'=>'1.0'));
			$this->translations[$languageId] = new HTTranslation($this->fetchUrl($url));
		}
		return $this->translations[$languageId];
	}

	/**
	 * Delete cache of translation
	 *
	 * @param Integer $languageId
	 */
	public function clearTranslation($languageId)
	{
		$this->translations[$languageId] = null;
	}

	/**
	 * Delete all caches of translations
	 */
	public function clearTranslations()
	{
		$this->translations = array();
	}

	/**
	 * Return HTTranslation object
	 *
	 * @return HTTranslation
	 */
	public function getUserTranslation()
	{
		if(!isset($this->translations[0]) || $this->translations[0] === null)
		{
			$url = $this->buildUrl(array('file'=>'translations', 'version'=>'1.0'));
			$this->translations[0] = new HTTranslation($this->fetchUrl($url));
		}
		return $this->translations[0];
	}

	/**
	 * Delete cache of user translation
	 */
	public function clearUserTranslation()
	{
		$this->translations[0] = null;
	}

	/**
	 * Return HTTeamTransferHistory object
	 *
	 * @param Integer $teamId
	 * @param Integer $pageIndex
	 * @return HTTeamTransferHistory
	 */
	public function getTeamTransfersHistory($teamId = null, $pageIndex = 1)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->teamsTransfersHistory[$teamId][$pageIndex]) || $this->teamsTransfersHistory[$teamId][$pageIndex] === null)
		{
			$url = $this->buildUrl(array('file'=>'transfersTeam', 'teamID'=>$teamId, 'pageIndex'=>$pageIndex, 'version'=>'1.2'));
			$this->teamsTransfersHistory[$teamId][$pageIndex] = new HTTeamTransferHistory($this->fetchUrl($url));
		}
		return $this->teamsTransfersHistory[$teamId][$pageIndex];
	}

	/**
	 * Delete cache of team transfers history
	 *
	 * @param Integer $teamId
	 */
	public function clearTeamTransfersHistory($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->teamsTransfersHistory[$teamId] = null;
	}

	/**
	 * Delete all caches of teams transfers history
	 */
	public function clearTeamsTransfersHistory()
	{
		$this->teamsTransfersHistory = array();
	}

	/**
	 * Return HTPlayerTransferHistory object
	 *
	 * @param Integer $playerId
	 * @return HTPlayerTransferHistory
	 */
	public function getPlayerTransfersHistory($playerId)
	{
		if(!isset($this->playersTransfersHistory[$playerId]) || $this->playersTransfersHistory[$playerId] === null)
		{
			$url = $this->buildUrl(array('file'=>'transfersPlayer', 'playerID'=>$playerId, 'version'=>'1.1'));
			$this->playersTransfersHistory[$playerId] = new HTPlayerTransferHistory($this->fetchUrl($url));
		}
		return $this->playersTransfersHistory[$playerId];
	}

	/**
	 * Delete cache of player transfers history
	 *
	 * @param Integer $playerId
	 */
	public function clearPlayerTransfersHistory($playerId)
	{
		$this->teamsTransfersHistory[$playerId] = null;
	}

	/**
	 * Delete all caches of players transfers history
	 */
	public function clearPlayersTransfersHistory()
	{
		$this->playersTransfersHistory = array();
	}

	/**
	 * Return HTPlayer object
	 *
	 * @param Integer $teamId
	 * @param Integer $playerId
	 * @param Integer $bid
	 * @param Integer $currency
	 * @return HTPlayer
	 */
	public function placeBid($teamId, $playerId, $bid, $currency = HTMoney::Sverige)
	{
		$bid = HTMoney::toSEK($bid, $currency);
		$url = $this->buildUrl(array('file'=>'playerdetails', 'actionType'=>'placeBid', 'teamId'=>$teamId, 'playerID'=>$playerId, 'placeBid'=>$bid, 'version'=>'2.5'));
		$data = $this->fetchUrl($url);
		$xml = new DOMDocument('1.0', 'UTF-8');
		$xml->loadXml($data);
		$xpath = new DOMXPath($xml);
		$nodeList = $xpath->query("//View");
		$node = new DOMDocument('1.0', 'UTF-8');
		$node->appendChild($node->importNode($nodeList->item(0), true));
		return new HTPlayer($node);
	}

	/**
	 * Return HTTeamPlayers object
	 *
	 * @param Integer $teamId
	 * @return HTTeamPlayers
	 */
	public function getTeamPlayers($teamId = null, $includeMatchInfo = false)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->teamsPlayers[$teamId][$includeMatchInfo]) || $this->teamsPlayers[$teamId][$includeMatchInfo] === null)
		{
			$params = array('file'=>'players', 'version'=>'2.3', 'actionType'=>'view', 'teamID'=>$teamId);
			if($includeMatchInfo == true)
			{
				$params['includeMatchInfo'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->teamsPlayers[$teamId][$includeMatchInfo] = new HTTeamPlayers($this->fetchUrl($url));
		}
		return $this->teamsPlayers[$teamId][$includeMatchInfo];
	}

	/**
	 * Delete cache of team players list
	 *
	 * @param Integer $teamId
	 */
	public function clearTeamPlayers($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->teamsPlayers[$teamId] = null;
	}

	/**
	 * Delete all caches of teams players
	 */
	public function clearTeamsPlayers()
	{
		$this->teamsPlayers = array();
	}

	/**
	 * Return HTTeamOldPlayers object
	 *
	 * @param Integer $teamId
	 * @return HTTeamOldPlayers
	 */
	public function getTeamOldPlayers($teamId = null, $includeMatchInfo = false)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->teamsOldPlayers[$teamId][$includeMatchInfo]) || $this->teamsOldPlayers[$teamId][$includeMatchInfo] === null)
		{
			$params = array('file'=>'players', 'version'=>'2.3', 'actionType'=>'viewOldies', 'teamID'=>$teamId);
			if($includeMatchInfo == true)
			{
				$params['includeMatchInfo'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->teamsOldPlayers[$teamId][$includeMatchInfo] = new HTTeamOldPlayers($this->fetchUrl($url));
		}
		return $this->teamsOldPlayers[$teamId][$includeMatchInfo];
	}

	/**
	 * Delete cache of team old players list
	 *
	 * @param Integer $teamId
	 */
	public function clearTeamOldPlayers($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->teamsOldPlayers[$teamId] = null;
	}

	/**
	 * Delete all caches of teams old players
	 */
	public function clearTeamsOldPlayers()
	{
		$this->teamsOldPlayers = array();
	}

	/**
	 * Return HTTeamOldCoaches object
	 *
	 * @param Integer $teamId
	 * @return HTTeamOldCoaches
	 */
	public function getTeamOldCoaches($teamId = null, $includeMatchInfo = false)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->teamsOldCoaches[$teamId][$includeMatchInfo]) || $this->teamsOldCoaches[$teamId][$includeMatchInfo] === null)
		{
			$params = array('file'=>'players', 'version'=>'2.3', 'actionType'=>'viewOldCoaches', 'teamID'=>$teamId);
			if($includeMatchInfo == true)
			{
				$params['includeMatchInfo'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->teamsOldCoaches[$teamId][$includeMatchInfo] = new HTTeamOldCoaches($this->fetchUrl($url));
		}
		return $this->teamsOldCoaches[$teamId][$includeMatchInfo];
	}

	/**
	 * Delete cache of team old coaches list
	 *
	 * @param Integer $teamId
	 */
	public function clearTeamOldCoaches($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->teamsOldCoaches[$teamId] = null;
	}

	/**
	 * Delete all caches of teams old coaches
	 */
	public function clearTeamsOldCoaches()
	{
		$this->teamsOldCoaches = array();
	}

	/**
	 * Return HTPlayer object
	 *
	 * @param Integer $playerId
	 * @param Boolean $includeMatchInfo
	 * @return HTPlayer
	 */
	public function getPlayer($playerId, $includeMatchInfo = false)
	{
		if(!isset($this->players[$playerId][$includeMatchInfo]) || $this->players[$playerId][$includeMatchInfo] === null)
		{
			$params = array('file'=>'playerdetails', 'version'=>'2.5', 'playerID'=>$playerId);
			if($includeMatchInfo == true)
			{
				$params['includeMatchInfo'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->players[$playerId][$includeMatchInfo] = new HTPlayer($this->fetchUrl($url));
		}
		return $this->players[$playerId][$includeMatchInfo];
	}

	/**
	 * Delete cache of a player
	 *
	 * @param Integer $playerId
	 */
	public function clearPlayer($playerId)
	{
		$this->players[$playerId] = null;
	}

	/**
	 * Delete cache of all players
	 */
	public function clearPlayers()
	{
		$this->players = array();
	}

	/**
	 * Return HTTeamMatches object
	 *
	 * @param Integer $teamId
	 * @param String $showBeforeDate (format should be : yyyy-mm-dd  - If no specify : returned matches are from now - 28 days to now + 28 days)
	 * @return HTTeamMatches
	 */
	public function getSeniorTeamMatches($teamId = null, $showBeforeDate = null)
	{
		if($showBeforeDate === null || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $showBeforeDate))
		{
			if($teamId === null)
			{
				$teamId = $this->getTeam()->getTeamId();
			}
			if(!isset($this->teamsMatches[$teamId][strtotime($showBeforeDate)]) || $this->teamsMatches[$teamId][strtotime($showBeforeDate)] === null)
			{
				$url = $this->buildUrl(array('file'=>'matches', 'teamID'=>$teamId, 'lastMatchDate'=>$showBeforeDate, 'version'=>'2.7'));
				$this->teamsMatches[$teamId][strtotime($showBeforeDate)] = new HTTeamMatches($this->fetchUrl($url));
			}
			return $this->teamsMatches[$teamId][strtotime($showBeforeDate)];
		}
		return null;
	}

	/**
	 * Delete cache matches of team
	 *
	 * @param Integer $teamId
	 */
	public function clearSeniorTeamMatches($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->teamsMatches[$teamId] = null;
	}

	/**
	 * Delete all caches of teams matches
	 */
	public function clearSeniorTeamsMatches()
	{
		$this->teamsMatches = array();
	}

	/**
	 * Return HTTeamMatches object
	 *
	 * @param Integer $youthTeamId
	 * @param String $showBeforeDate (format should be : yyyy-mm-dd  - If no specify : returned matches are from now - 28 days to now + 28 days)
	 * @return HTTeamMatches
	 */
	public function getYouthTeamMatches($youthTeamId = null, $showBeforeDate = null)
	{
		if($showBeforeDate === null || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $showBeforeDate))
		{
			if($youthTeamId === null)
			{
				$youthTeamId = $this->getTeam()->getYouthTeamId();
			}
			if(!isset($this->youthTeamsMatches[$youthTeamId][strtotime($showBeforeDate)]) || $this->youthTeamsMatches[$youthTeamId][strtotime($showBeforeDate)] === null)
			{
				$url = $this->buildUrl(array('file'=>'matches', 'teamID'=>$youthTeamId, 'isYouth'=>'true', 'lastMatchDate'=>$showBeforeDate, 'version'=>'2.7'));
				$this->youthTeamsMatches[$youthTeamId][strtotime($showBeforeDate)] = new HTTeamMatches($this->fetchUrl($url));
			}
			return $this->youthTeamsMatches[$youthTeamId][strtotime($showBeforeDate)];
		}
		return null;
	}

	/**
	 * Delete cache matches of youth team
	 *
	 * @param Integer $youthTeamId
	 */
	public function clearYouthTeamMatches($youthTeamId = null)
	{
		if($youthTeamId === null)
		{
			$youthTeamId = $this->getTeam()->getYouthTeamId();
		}
		$this->youthTeamsMatches[$youthTeamId] = null;
	}

	/**
	 * Delete all caches of youth teams matches
	 */
	public function clearYouthTeamsMatches()
	{
		$this->youthTeamsMatches = array();
	}

	/**
	 * Return HTMatch object
	 *
	 * @param Integer $matchId
	 * @param Boolean $matchEvents
	 * @return HTMatch
	 */
	public function getSeniorMatchDetails($matchId, $matchEvents = true)
	{
		if(!isset($this->matchesDetails[$matchId][$matchEvents]) || $this->matchesDetails[$matchId][$matchEvents] === null)
		{
			$params = array('file'=>'matchdetails', 'version'=>'2.5', 'sourceSystem'=>'hattrick', 'matchID'=>$matchId);
			if($matchEvents === true)
			{
				$params['matchEvents'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->matchesDetails[$matchId][$matchEvents] = new HTMatch($this->fetchUrl($url));
		}
		return $this->matchesDetails[$matchId][$matchEvents];
	}

	/**
	 * Delete cache of match detail
	 *
	 * @param Integer $matchId
	 */
	public function clearSeniorMatchDetail($matchId)
	{
		$this->matchesDetails[$matchId] = null;
	}

	/**
	 * Delete cache of all matches details
	 */
	public function clearSeniorMatchesDetails()
	{
		$this->matchesDetails = array();
	}

	/**
	 * Return HTMatch object
	 *
	 * @param Integer $matchId
	 * @param Boolean $matchEvents
	 * @return HTMatch
	 */
	public function getYouthMatchDetails($matchId, $matchEvents = true)
	{
		if(!isset($this->youthMatchesDetails[$matchId][$matchEvents]) || $this->youthMatchesDetails[$matchId][$matchEvents] === null)
		{
			$params = array('file'=>'matchdetails', 'version'=>'2.5', 'sourceSystem'=>'youth', 'matchID'=>$matchId);
			if($matchEvents === true)
			{
				$params['matchEvents'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->youthMatchesDetails[$matchId][$matchEvents] = new HTMatch($this->fetchUrl($url));
		}
		return $this->youthMatchesDetails[$matchId][$matchEvents];
	}

	/**
	 * Delete cache of youth match detail
	 *
	 * @param Integer $matchId
	 */
	public function clearYouthMatchDetail($matchId)
	{
		$this->youthMatchesDetails[$matchId] = null;
	}

	/**
	 * Delete cache of all youth matches details
	 */
	public function clearYouthMatchesDetails()
	{
		$this->youthMatchesDetails = array();
	}

	/**
	 * Return HTMatch object
	 *
	 * @param Integer $matchId
	 * @param Boolean $matchEvents
	 * @return HTMatch
	 */
	public function getTournamentMatchDetails($matchId, $matchEvents = true)
	{
		if(!isset($this->tournamentMatchesDetails[$matchId][$matchEvents]) || $this->tournamentMatchesDetails[$matchId][$matchEvents] === null)
		{
			$params = array('file'=>'matchdetails', 'version'=>'2.5', 'sourceSystem'=>'htointegrated', 'matchID'=>$matchId);
			if($matchEvents === true)
			{
				$params['matchEvents'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->tournamentMatchesDetails[$matchId][$matchEvents] = new HTMatch($this->fetchUrl($url));
		}
		return $this->tournamentMatchesDetails[$matchId][$matchEvents];
	}

	/**
	 * Delete cache of tournament match detail
	 *
	 * @param Integer $matchId
	 */
	public function clearTournamentMatchDetail($matchId)
	{
		$this->tournamentMatchesDetails[$matchId] = null;
	}

	/**
	 * Delete cache of all tournament matches details
	 */
	public function clearTournamentMatchesDetails()
	{
		$this->tournamentMatchesDetails = array();
	}

	/**
	 * Return HTMatchArchive object
	 *
	 * @param Integer $teamId
	 * @param String $startDate (format should be : yyyy-mm-dd)
	 * @param String $endDate (format should be : yyyy-mm-dd)
	 * @param Integer $season
	 * @return HTMatchArchive
	 */
	public function getSeniorTeamArchiveMatches($teamId = null, $startDate = null, $endDate = null, $season = null)
	{
		if($startDate === null || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $startDate))
		{
			if($endDate === null || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $endDate))
			{
				$this->analyseDate($startDate, $endDate);
				if($teamId === null)
				{
					$teamId = $this->getClub()->getTeamId();
				}
				if(!isset($this->teamsArchivesMatches[$teamId][strtotime($startDate)][strtotime($endDate)][$season]) || $this->teamsArchivesMatches[$teamId][strtotime($startDate)][strtotime($endDate)][$season] === null)
				{
					$url = $this->buildUrl(array('file'=>'matchesarchive', 'teamID'=>$teamId, 'isYouth'=>'false', 'firstMatchDate'=>$startDate, 'lastMatchDate'=>$endDate, 'season'=>$season, 'version'=>'1.2'));
					$this->teamsArchivesMatches[$teamId][strtotime($startDate)][strtotime($endDate)][$season] = new HTMatchArchive($this->fetchUrl($url));
				}
				return $this->teamsArchivesMatches[$teamId][strtotime($startDate)][strtotime($endDate)][$season];
			}
		}
		return null;
	}

	/**
	 * Delete cache of team's archive matches
	 *
	 * @param Integer $teamId
	 */
	public function clearSeniorTeamArchiveMatches($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getClub()->getTeamId();
		}
		$this->teamsArchivesMatches[$teamId] = null;
	}

	/**
	 * Delete all caches of teams archive matches
	 */
	public function clearSeniorTeamsArchiveMatches()
	{
		$this->teamsArchivesMatches = array();
	}

	/**
	 *  Return HTMatchArchive object
	 *
	 * @param Integer $youthTeamId
	 * @param String $startDate (format should be : yyyy-mm-dd)
	 * @param String $endDate (format should be : yyyy-mm-dd)
	 * @return HTMatchArchive
	 */
	public function getYouthTeamArchiveMatches($youthTeamId = null, $startDate = null, $endDate = null)
	{
		if($startDate === null || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $startDate))
		{
			if($endDate === null || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $endDate))
			{
				$this->analyseDate($startDate, $endDate);
				if($youthTeamId === null)
				{
					$youthTeamId = $this->getTeam()->getYouthTeamId();
				}
				if(!isset($this->youthTeamsArchivesMatches[$youthTeamId][strtotime($startDate)][strtotime($endDate)]) || $this->youthTeamsArchivesMatches[$youthTeamId][strtotime($startDate)][strtotime($endDate)] === null)
				{
					$url = $this->buildUrl(array('file'=>'matchesarchive', 'teamID'=>$youthTeamId, 'isYouth'=>'true', 'firstMatchDate'=>$startDate, 'lastMatchDate'=>$endDate, 'version'=>'1.2'));
					$this->youthTeamsArchivesMatches[$youthTeamId][strtotime($startDate)][strtotime($endDate)] = new HTMatchArchive($this->fetchUrl($url));
				}
				return $this->youthTeamsArchivesMatches[$youthTeamId][strtotime($startDate)][strtotime($endDate)];
			}
		}
		return null;
	}

	/**
	 * Delete cache of youth team's archive matches
	 *
	 * @param Integer $teamId
	 */
	public function clearYouthTeamArchiveMatches($youthTeamId = null)
	{
		if($youthTeamId === null)
		{
			$youthTeamId = $this->getTeam()->getYouthTeamId();
		}
		$this->youthTeamsArchivesMatches[$youthTeamId] = null;
	}

	/**
	 * Delete all caches of youth teams archive matches
	 */
	public function clearYouthTeamsArchiveMatches()
	{
		$this->youthTeamsArchivesMatches = array();
	}

	/**
	 * Return senior HTLineup object
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 * @return HTLineup
	 */
	public function getSeniorLineup($matchId = null, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getClub()->getTeamId();
		}
		if($matchId === null)
		{
			$matchId = $this->getSeniorTeamMatches()->getLastMatch()->getId();
		}
		if(!isset($this->lineups[$matchId][$teamId]) || $this->lineups[$matchId][$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'matchlineup', 'version'=>'1.8', 'sourceSystem'=>'hattrick', 'matchID'=>$matchId, 'teamID'=>$teamId));
			$this->lineups[$matchId][$teamId] = new HTLineup($this->fetchUrl($url));
		}
		return $this->lineups[$matchId][$teamId];
	}

	/**
	 * Delete cache of senior lineup
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 */
	public function clearSeniorLineup($matchId = null, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getClub()->getTeamId();
		}
		if($matchId === null)
		{
			$matchId = $this->getSeniorTeamMatches()->getLastMatch()->getId();
		}
		$this->lineups[$matchId][$teamId] = null;
	}

	/**
	 * Delete all caches of senior lineups
	 */
	public function clearSeniorLineups()
	{
		$this->lineups = array();
	}

	/**
	 * Return youth HTLineup object
	 *
	 * @param Integer $matchId
	 * @param Integer $youthTeamId
	 * @return HTLineup
	 */
	public function getYouthLineup($matchId = null, $youthTeamId = null)
	{
		if($youthTeamId === null)
		{
			$youthTeamId = $this->getTeam()->getYouthTeamId();
		}
		if($matchId === null)
		{
			$matchId = $this->getYouthTeamMatches()->getLastMatch()->getId();
		}
		if(!isset($this->youthLineups[$matchId][$youthTeamId]) || $this->youthLineups[$matchId][$youthTeamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'matchlineup', 'version'=>'1.8', 'matchID'=>$matchId, 'teamID'=>$youthTeamId, 'sourceSystem'=>'youth'));
			$this->youthLineups[$matchId][$youthTeamId] = new HTLineup($this->fetchUrl($url));
		}
		return $this->youthLineups[$matchId][$youthTeamId];
	}

	/**
	 * Delete cache of youth lineup
	 *
	 * @param Integer $matchId
	 * @param Integer $youthTeamId
	 */
	public function clearYouthLineup($matchId = null, $youthTeamId = null)
	{
		if($youthTeamId === null)
		{
			$youthTeamId = $this->getTeam()->getYouthTeamId();
		}
		if($matchId === null)
		{
			$matchId = $this->getSeniorTeamMatches()->getLastMatch()->getId();
		}
		$this->youthLineups[$matchId][$youthTeamId] = null;
	}

	/**
	 * Delete all caches of youth lineups
	 */
	public function clearYouthLineups()
	{
		$this->youthLineups = array();
	}

	/**
	 * Return tournament HTLineup object
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 * @return HTLineup
	 */
	public function getTournamentLineup($matchId, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getClub()->getTeamId();
		}
		if(!isset($this->tournamentLineups[$matchId][$teamId]) || $this->tournamentLineups[$matchId][$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'matchlineup', 'version'=>'1.8', 'sourceSystem'=>'htointegrated', 'matchID'=>$matchId, 'teamID'=>$teamId));
			$this->tournamentLineups[$matchId][$teamId] = new HTLineup($this->fetchUrl($url));
		}
		return $this->tournamentLineups[$matchId][$teamId];
	}

	/**
	 * Delete cache of tournament lineup
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 */
	public function clearTournamentLineup($matchId, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getClub()->getTeamId();
		}
		$this->tournamentLineups[$matchId][$teamId] = null;
	}

	/**
	 * Delete all caches of tournament lineups
	 */
	public function clearTournamentLineups()
	{
		$this->tournamentLineups = array();
	}

	/**
	 * Add a match to HT-Live
	 *
	 * @param Integer $matchId
	 * @param String $sourceSystem
	 */
	public function addLiveMatch($matchId, $sourceSystem = 'hattrick')
	{
		if($this->liveNumber < 20)
		{
			$url = $this->buildUrl(array('file'=>'live', 'actionType'=>'addMatch', 'matchID'=>$matchId, 'version'=>'1.8', 'sourceSystem'=>$sourceSystem));
			$this->fetchUrl($url);
			if($this->liveNumber === null)
			{
				$this->liveNumber = 1;
			}
			else
			{
				$this->liveNumber++;
			}
		}
	}

	/**
	 * Delete a match in HT-Live
	 *
	 * @param Integer $matchId
	 * @param String $sourceSystem
	 */
	public function deleteLiveMatch($matchId, $sourceSystem = 'hattrick')
	{
		$url = $this->buildUrl(array('file'=>'live', 'actionType'=>'deleteMatch', 'matchID'=>$matchId, 'version'=>'1.8', 'sourceSystem'=>$sourceSystem));
		$this->fetchUrl($url);
		$this->liveNumber--;
		if($this->liveNumber <= 0)
		{
			$this->liveNumber = null;
			$this->liveJson = null;
		}
	}

	/**
	 * Clear all matches in HT-Live, you have to init live after this method to re-use live
	 */
	public function clearAllLiveMatches()
	{
		$url = $this->buildUrl(array('file'=>'live', 'actionType'=>'clearAll', 'version'=>'1.8'));
		$this->fetchUrl($url);
		$this->liveNumber = null;
		$this->liveJson = null;
	}

	/**
	 * Return number of match in HT-Live
	 *
	 * @return Integer
	 */
	public function getLiveMatchNumber()
	{
		return $this->liveNumber;
	}

	/**
	 * Return HTLive object
	 *
	 * @param Boolean $forceAllEvents
	 * @param Boolean $includeStartingLineup
	 * @return HTLive
	 */
	public function getLive($forceAllEvents = false, $includeStartingLineup = false)
	{
		$action = 'viewNew';
		if($this->liveAllEvents === false || $forceAllEvents === true)
		{
			$action = 'viewAll';
			$this->liveAllEvents = true;
		}
		$params = array('file'=>'live', 'actionType'=>$action, 'version'=>'1.8');
		if($includeStartingLineup === true)
		{
			$params['includeStartingLineup'] = 'true';
		}
		if($this->liveJson !== null && $action == 'viewNew')
		{
			$params['lastShownIndexes'] = json_encode($this->liveJson);
		}
		$url = $this->buildUrl($params);
		$liveObject = new HTLive($this->fetchUrl($url));
		$this->liveNumber = $liveObject->getMatchNumber();
		$json = array();
		for($i=1; $i<=$this->liveNumber; $i++)
		{
			$match = $liveObject->getMatch($i);
			$json[] = array(
				'matchId' => $match->getId(),
				'sourceSystem' => $match->getSourceSystem(),
				'index' 	=> $match->getLastEventIndexShown()
			);
		}
		if(count($json))
		{
			$this->liveJson = array('matches' => $this->mergeLiveJson($this->liveJson['matches'], $json));
		}
		return $liveObject;
	}

	/**
	 * @param Array $original
	 * @param Array $new
	 * @return Array
	 */
	private function mergeLiveJson($original, $new)
	{
		foreach ($new as $tab)
		{
			$id = $tab['matchId'];
			$e = null;
			if(count($original))
			{
				foreach ($original as $i => $otab)
				{
					if($otab['matchId'] == $id)
					{
						$e = $i;
						break;
					}
				}
			}
			if($e !== null)
			{
				$original[$e] = $tab;
			}
			else
			{
				$original[] = $tab;
			}
		}
		return $original;
	}

	/**
	 * Return A HTNationalTeams object
	 *
	 * @return HTNationalTeams
	 */
	public function getATeamList()
	{
		if(!isset($this->nationalAteams) || $this->nationalAteams === null)
		{
			$url = $this->buildUrl(array('file'=>'nationalteams', 'leagueOfficeTypeID'=>2, 'version'=>'1.5'));
			$this->nationalAteams = new HTNationalTeams($this->fetchUrl($url));
		}
		return $this->nationalAteams;
	}

	/**
	 * Delete cache of national teams data
	 */
	public function clearATeamList()
	{
		$this->nationalAteams = null;
	}

	/**
	 * Return U20 HTNationalTeams object
	 *
	 * @return HTNationalTeams
	 */
	public function getU20TeamList()
	{
		if(!isset($this->nationalU20teams) || $this->nationalU20teams === null)
		{
			$url = $this->buildUrl(array('file'=>'nationalteams', 'leagueOfficeTypeID'=>4, 'version'=>'1.5'));
			$this->nationalU20teams = new HTNationalTeams($this->fetchUrl($url));
		}
		return $this->nationalU20teams;
	}

	/**
	 * Delete cache of national U20 teams data
	 */
	public function clearU20TeamList()
	{
		$this->nationalU20teams = null;
	}

	/**
	 * Return HTNationalTeamDetail object
	 *
	 * @param Integer $teamId
	 * @return HTNationalTeamDetail
	 */
	public function getNationalTeamDetail($teamId)
	{
		if(!isset($this->nationalTeamDetails[$teamId]) || $this->nationalTeamDetails[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'nationalteamdetails', 'teamID'=>$teamId, 'version'=>'1.8'));
			$this->nationalTeamDetails[$teamId] = new HTNationalTeamDetail($this->fetchUrl($url));
		}
		return $this->nationalTeamDetails[$teamId];
	}

	/**
	 * Delete cache of national team details
	 *
	 * @param Integer $teamId
	 */
	public function clearNationalTeamDetails($teamId)
	{
		$this->nationalTeamDetails[$teamId] = null;
	}

	/**
	 * Delete all caches of national teams details
	 */
	public function clearNationalTeamsDetails()
	{
		$this->nationalTeamDetails = array();
	}

	/**
	 * Return HTNationalMatches object
	 *
	 * @return HTNationalMatches
	 */
	public function getAMatchesList()
	{
		if(!isset($this->nationalAmatches) || $this->nationalAmatches === null)
		{
			$url = $this->buildUrl(array('file'=>'nationalteammatches', 'leagueOfficeTypeID'=>2, 'version'=>'1.3'));
			$this->nationalAmatches = new HTNationalMatches($this->fetchUrl($url));
		}
		return $this->nationalAmatches;
	}

	/**
	 * Delete cache of national A matches
	 */
	public function clearAMatchesList()
	{
		$this->nationalAmatches = null;
	}

	/**
	 * Return HTNationalMatches object
	 *
	 * @return HTNationalMatches
	 */
	public function getU20MatchesList()
	{
		if(!isset($this->nationalU20matches) || $this->nationalU20matches === null)
		{
			$url = $this->buildUrl(array('file'=>'nationalteammatches', 'leagueOfficeTypeID'=>4, 'version'=>'1.3'));
			$this->nationalU20matches = new HTNationalMatches($this->fetchUrl($url));
		}
		return $this->nationalU20matches;
	}

	/**
	 * Delete cache of national U20 matches
	 */
	public function clearU20MatchesList()
	{
		$this->nationalU20matches = null;
	}

	/**
	 * Return HTNationalPlayers object
	 *
	 * @param Integer $teamId
	 * @return HTNationalPlayers
	 */
	public function getNationalPlayers($teamId)
	{
		if(!isset($this->nationalPlayers[$teamId]) || $this->nationalPlayers[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'nationalplayers', 'actionType'=>'view', 'teamID'=>$teamId, 'version'=>'1.3'));
			$this->nationalPlayers[$teamId] = new HTNationalPlayers($this->fetchUrl($url));
		}
		return $this->nationalPlayers[$teamId];
	}

	/**
	 * Delete cache of national players in a team
	 *
	 * @param Integer $teamId
	 */
	public function clearNationalPlayers($teamId)
	{
		$this->nationalPlayers[$teamId] = null;
	}

	/**
	 * Delete all caches of national players
	 */
	public function clearAllNationalPlayers()
	{
		$this->nationalPlayers = array();
	}

	/**
	 * Return HTNationalPlayersStats object
	 *
	 * @param Integer $teamId
	 * @param Boolean $showAll
	 * @return HTNationalPlayersStats
	 */
	public function getNationalPlayersStats($teamId, $showAll = true)
	{
		if(!isset($this->nationalPlayersStats[$teamId][$showAll]) || $this->nationalPlayersStats[$teamId][$showAll] === null)
		{
			$url = $this->buildUrl(array('file'=>'nationalplayers', 'actionType'=>'supporterStats', 'teamID'=>$teamId, 'matchTypeCategory'=>'NT', 'showAll'=>$showAll, 'version'=>'1.3'));
			$this->nationalPlayersStats[$teamId][$showAll] = new HTNationalPlayersStats($this->fetchUrl($url));
		}
		return $this->nationalPlayersStats[$teamId][$showAll];
	}

	/**
	 * Delete cache of national players stats in a team
	 *
	 * @param Integer $teamId
	 */
	public function clearNationalPlayersStats($teamId)
	{
		$this->nationalPlayersStats[$teamId] = null;
	}

	/**
	 * Delete all caches of national players
	 */
	public function clearAllNationalPlayersStats()
	{
		$this->nationalPlayersStats = array();
	}

	/**
	 * Return HTNationalPlayersStats object
	 *
	 * @param Integer $teamId
	 * @param Boolean $showAll
	 * @return HTNationalPlayersStats
	 */
	public function getNationalPlayersStatsWorldCup($teamId, $showAll = true)
	{
		if(!isset($this->nationalPlayersStatsWC[$teamId][$showAll]) || $this->nationalPlayersStatsWC[$teamId][$showAll] === null)
		{
			$url = $this->buildUrl(array('file'=>'nationalplayers', 'actionType'=>'supporterStats', 'teamID'=>$teamId, 'matchTypeCategory'=>'WC', 'showAll'=>$showAll, 'version'=>'1.3'));
			$this->nationalPlayersStatsWC[$teamId][$showAll] = new HTNationalPlayersStats($this->fetchUrl($url));
		}
		return $this->nationalPlayersStatsWC[$teamId][$showAll];
	}

	/**
	 * Delete cache of national players stats in a team
	 *
	 * @param Integer $teamId
	 */
	public function clearNationalPlayersStatsWorldCup($teamId)
	{
		$this->nationalPlayersStatsWC[$teamId] = null;
	}

	/**
	 * Delete all caches of national players
	 */
	public function clearAllNationalPlayersStatsWorldCup()
	{
		$this->nationalPlayersStatsWC = array();
	}

	/**
	 * Return HTWorldCupGroup object
	 *
	 * @param Integer $cupId
	 * @param Integer $season
	 * @param Integer $matchRound
	 * @return HTWorldCupGroup
	 */
	public function getWorldCupGroup($cupId = null, $season = null, $matchRound = null)
	{
		if(!isset($this->worldCupGroups[$cupId][$season][$matchRound]) || $this->worldCupGroups[$cupId][$season][$matchRound] === null)
		{
			$url = $this->buildUrl(array('file'=>'worldcup', 'actionType'=>'viewGroups', 'cupID'=>$cupId, 'season'=>$season, 'matchRound'=>$matchRound, 'version'=>'1.2'));
			$this->worldCupGroups[$cupId][$season][$matchRound] = new HTWorldCupGroup($this->fetchUrl($url));
		}
		return $this->worldCupGroups[$cupId][$season][$matchRound];
	}

	/**
	 * Delete cache of world cup group
	 *
	 * @param Integer $cupId
	 * @param Integer $season
	 * @param Integer $matchRound
	 */
	public function clearWorldCupGroup($cupId = null, $season = null, $matchRound = null)
	{
		$this->worldCupGroups[$cupId][$season][$matchRound] = null;
	}

	/**
	 * Delete all caches of world cup groups
	 */
	public function clearWorldCupGroups()
	{
		$this->worldCupGroups = array();
	}

	/**
	 * Return HTWorldCupMatches object
	 *
	 * @param Integer $cupId
	 * @param Integer $season
	 * @param Integer $matchRound
	 * @return HTWorldCupGroup
	 */
	public function getWorldCupMatches($cupSeriesUnitID, $cupId = null, $season = null, $matchRound = null)
	{
		if(!isset($this->worldCupMatches[$cupSeriesUnitID][$cupId][$season][$matchRound]) || $this->worldCupMatches[$cupSeriesUnitID][$cupId][$season][$matchRound] === null)
		{
			$url = $this->buildUrl(array('file'=>'worldcup', 'actionType'=>'viewMatches', 'cupID'=>$cupId, 'season'=>$season, 'matchRound'=>$matchRound, 'cupSeriesUnitID'=>$cupSeriesUnitID, 'version'=>'1.2'));
			$this->worldCupMatches[$cupSeriesUnitID][$cupId][$season][$matchRound] = new HTWorldCupMatches($this->fetchUrl($url));
		}
		return $this->worldCupMatches[$cupSeriesUnitID][$cupId][$season][$matchRound];
	}

	/**
	 * Delete cache of world cup group
	 *
	 * @param Integer $cupId
	 * @param Integer $season
	 * @param Integer $matchRound
	 */
	public function clearWorldCupMatches($cupSeriesUnitID, $cupId = null, $season = null, $matchRound = null)
	{
		$this->worldCupMatches[$cupSeriesUnitID][$cupId][$season][$matchRound] = null;
	}

	/**
	 * Delete all caches of world cup groups
	 */
	public function clearAllWorldCupMatches()
	{
		$this->worldCupMatches = array();
	}

	/**
	 * Return HTAlliances object
	 *
	 * @param Integer $languageId
	 * @return HTAlliances
	 */
	public function getAlliances($languageId = -1, $pageIndex = 0)
	{
		if(!isset($this->alliancesIds[$languageId][$pageIndex]) || $this->alliancesIds[$languageId][$pageIndex] === null)
		{
			$this->alliancesIds[$languageId][$pageIndex] = $this->getAlliancesObject(array('searchLanguageID'=>$languageId));
		}
		return $this->alliancesIds[$languageId][$pageIndex];
	}

	/**
	 * Return HTAlliances object
	 *
	 * @param String $name
	 * @return HTAlliances
	 */
	public function getAlliancesByName($name, $languageId = -1, $pageIndex = 0)
	{
		if(!isset($this->alliancesName[$name][$languageId][$pageIndex]) || $this->alliancesName[$name][$languageId][$pageIndex] === null)
		{
			$this->alliancesName[$name][$languageId][$pageIndex] = $this->getAlliancesObject(array('searchFor'=>$name, 'searchType'=>1, 'searchLanguageID'=>$languageId, 'pageIndex'=>$pageIndex));
		}
		return $this->alliancesName[$name][$languageId][$pageIndex];
	}

	/**
	 * Return HTAlliances object
	 *
	 * @param String $abbreviation
	 * @return HTAlliances
	 */
	public function getAlliancesByAbbreviation($abbreviation, $languageId = -1, $pageIndex = 0)
	{
		if(!isset($this->alliancesAbbr[$abbreviation][$languageId][$pageIndex]) || $this->alliancesAbbr[$abbreviation][$languageId][$pageIndex] === null)
		{
			$this->alliancesAbbr[$abbreviation][$languageId][$pageIndex] = $this->getAlliancesObject(array('searchFor'=>$abbreviation, 'searchType'=>2, 'searchLanguageID'=>$languageId, 'pageIndex'=>$pageIndex));
		}
		return $this->alliancesAbbr[$abbreviation][$languageId][$pageIndex];
	}

	/**
	 * Return HTAlliances object
	 *
	 * @param String $description
	 * @return HTAlliances
	 */
	public function getAlliancesByDescription($description, $languageId = -1, $pageIndex = 0)
	{
		if(!isset($this->alliancesDesc[$description][$languageId][$pageIndex]) || $this->alliancesDesc[$description][$languageId][$pageIndex] === null)
		{
			$this->alliancesDesc[$description][$languageId][$pageIndex] = $this->getAlliancesObject(array('searchFor'=>$description, 'searchType'=>3, 'searchLanguageID'=>$languageId, 'pageIndex'=>$pageIndex));
		}
		return $this->alliancesDesc[$description][$languageId][$pageIndex];
	}

	/**
	 * @param Array $extraParams
	 * @return HTAlliances
	 */
	protected function getAlliancesObject($extraParams = array())
	{
		$params = array_merge(array('file'=>'alliances', 'version'=>'1.4'), $extraParams);
		$url = $this->buildUrl($params);
		return new HTAlliances($this->fetchUrl($url));
	}

	/**
	 * Returns HTAlliances object
	 *
	 * @return HTAlliances
	 */
	public function getUserAlliances()
	{
		if(!isset($this->userAlliances) || $this->userAlliances === null)
		{
			$url = $this->buildUrl(array('file'=>'alliances', 'searchType'=>5, 'version'=>'1.4'));
			$this->userAlliances = new HTAlliances($this->fetchUrl($url));
		}
		return $this->userAlliances;
	}

	/**
	 * Return HTArena object
	 *
	 * @param Integer $arenaId
	 * @return HTArena
	 */
	public function getArenaDetails($arenaId = null)
	{
		if($arenaId === null)
		{
			$arenaId = $this->getTeam()->getArenaId();
		}
		if(!isset($this->arenas[$arenaId]) || $this->arenas[$arenaId] === null)
		{
			$url = $this->buildUrl(array('file'=>'arenadetails', 'arenaID'=>$arenaId, 'version'=>'1.5'));
			$this->arenas[$arenaId] = new HTArena($this->fetchUrl($url));
		}
		return $this->arenas[$arenaId];
	}

	/**
	 * Delete cache of arena
	 *
	 * @param Integer $arenaId
	 */
	public function clearArenaDetails($arenaId = null)
	{
		if($arenaId === null)
		{
			$arenaId = $this->getTeam()->getArenaId();
		}
		$this->arenas[$arenaId] = null;
	}

	/**
	 * Delete all caches of arenas
	 */
	public function clearArenasDetails()
	{
		$this->arenas = array();
	}

	/**
	 * Return HTArena object
	 *
	 * @param Integer $teamId
	 * @return HTArena
	 */
	public function getArenaDetailsByTeam($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->arenasTeams[$teamId]) || $this->arenasTeams[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'arenadetails', 'teamID'=>$teamId, 'version'=>'1.5'));
			$this->arenasTeams[$teamId] = new HTArena($this->fetchUrl($url));
		}
		return $this->arenasTeams[$teamId];
	}

	/**
	 * Delete cache of arena by team
	 *
	 * @param Integer $teamId
	 */
	public function clearArenaDetailsByTeam($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->arenasTeams[$teamId] = null;
	}

	/**
	 * Delete all caches of arenas by teams
	 */
	public function clearArenasDetailsByTeam()
	{
		$this->arenasTeams = array();
	}

	/**
	 * Return HTArenaMyStats object
	 *
	 * @param String $matchType (constant taken from HTArenaMyStats class)
	 * @param String $startDate (format should be : yyyy-mm-dd)
	 * @param String $endDate (format should be : yyyy-mm-dd)
	 * @param Integer $arenaId
	 * @return HTArenaMyStats
	 */
	public function getMyArenaStats($matchType = HTArenaMyStats::ALL, $startDate= null, $endDate = null, $arenaId = null)
	{
		if($startDate === null || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $startDate))
		{
			if($endDate === null || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $endDate))
			{
				$this->analyseDate($startDate, $endDate, false);
				if(!in_array($matchType, array(HTArenaMyStats::ALL, HTArenaMyStats::COMP, HTArenaMyStats::LEAGUE, HTArenaMyStats::FRIENDLY)))
				{
					$matchType = HTArenaMyStats::ALL;
				}
				if($arenaId === null)
				{
					$arenaId = $this->getTeam()->getArenaId();
				}
				if(!isset($this->myArenaStats[$arenaId][$matchType][strtotime($startDate)][strtotime($endDate)]) || $this->myArenaStats[$arenaId][$matchType][strtotime($startDate)][strtotime($endDate)] === null)
				{
					$url = $this->buildUrl(array('file'=>'arenadetails', 'statsType'=>'MyArena', 'matchType'=>$matchType, 'firstDate'=>$startDate, 'lastDate'=>$endDate, 'version'=>'1.5', 'arenaID'=>$arenaId));
					$this->myArenaStats[$arenaId][$matchType][strtotime($startDate)][strtotime($endDate)] = new HTArenaMyStats($this->fetchUrl($url));
				}
				return $this->myArenaStats[$arenaId][$matchType][strtotime($startDate)][strtotime($endDate)];
			}
		}
		return null;
	}

	/**
	 * Delete cache of my arena statistics
	 *
	 * @param String $matchType (constant taken from HTArenaMyStats class)
	 * @param Integer $arenaId
	 */
	public function clearMyArenaStats($matchType = HTArenaMyStats::ALL, $arenaId = null)
	{
		if(!in_array($matchType, array(HTArenaMyStats::ALL, HTArenaMyStats::COMP, HTArenaMyStats::LEAGUE, HTArenaMyStats::FRIENDLY)))
		{
			$matchType = HTArenaMyStats::ALL;
		}
		if($arenaId === null)
		{
			$arenaId = $this->getTeam()->getArenaId();
		}
		$this->myArenaStats[$arenaId][$matchType] = null;
	}

	/**
	 * Delete all caches of my arena statistics
	 */
	public function clearMyArenaAllStats()
	{
		$this->myArenaStats = array();
	}

	/**
	 * Return HTArenaMyStats object
	 *
	 * @param String $matchType (constant taken from HTArenaMyStats class)
	 * @param String $startDate (format should be : yyyy-mm-dd)
	 * @param String $endDate (format should be : yyyy-mm-dd)
	 * @param Integer $teamId
	 * @return HTArenaMyStats
	 */
	public function getMyArenaStatsByTeam($matchType = HTArenaMyStats::ALL, $startDate= null, $endDate = null, $teamId = null)
	{
		if($startDate === null || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $startDate))
		{
			if($endDate === null || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $endDate))
			{
				$this->analyseDate($startDate, $endDate, false);
				if(!in_array($matchType, array(HTArenaMyStats::ALL, HTArenaMyStats::COMP, HTArenaMyStats::LEAGUE, HTArenaMyStats::FRIENDLY)))
				{
					$matchType = HTArenaMyStats::ALL;
				}
				if($teamId === null)
				{
					$teamId = $this->getTeam()->getTeamId();
				}
				if(!isset($this->myArenaStatsTeams[$teamId][$matchType][strtotime($startDate)][strtotime($endDate)]) || $this->myArenaStatsTeams[$teamId][$matchType][strtotime($startDate)][strtotime($endDate)] === null)
				{
					$url = $this->buildUrl(array('file'=>'arenadetails', 'statsType'=>'MyArena', 'matchType'=>$matchType, 'firstDate'=>$startDate, 'lastDate'=>$endDate, 'version'=>'1.5', 'teamId'=>$teamId));
					$this->myArenaStatsTeams[$teamId][$matchType][strtotime($startDate)][strtotime($endDate)] = new HTArenaMyStats($this->fetchUrl($url));
				}
				return $this->myArenaStatsTeams[$teamId][$matchType][strtotime($startDate)][strtotime($endDate)];
			}
		}
		return null;
	}

	/**
	 * Delete cache of my arena by team statistics
	 *
	 * @param String $matchType (constant taken from HTArenaMyStats class)
	 * @param Integer $teamId
	 */
	public function clearMyArenaStatsByTeam($matchType = HTArenaMyStats::ALL, $teamId = null)
	{
		if(!in_array($matchType, array(HTArenaMyStats::ALL, HTArenaMyStats::COMP, HTArenaMyStats::LEAGUE, HTArenaMyStats::FRIENDLY)))
		{
			$matchType = HTArenaMyStats::ALL;
		}
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->myArenaStatsTeams[$teamId][$matchType] = null;
	}

	/**
	 * Delete all caches of my arena statistics
	 */
	public function clearMyArenaByTeamAllStats()
	{
		$this->myArenaStatsTeams = array();
	}

	/**
	 * Return HTArenasStats object
	 *
	 * @param Integer $leagueId (by default 0 = global statistics)
	 * @return HTArenasStats
	 */
	public function getOthersArenasStats($leagueId = 0)
	{
		if(!isset($this->arenasStats[$leagueId]) || $this->arenasStats[$leagueId] === null)
		{
			$url = $this->buildUrl(array('file'=>'arenadetails', 'statsType'=>'OtherArenas', 'statsLeagueID'=>$leagueId, 'version'=>'1.5'));
			$this->arenasStats[$leagueId] = new HTArenasStats($this->fetchUrl($url));
		}
		return $this->arenasStats[$leagueId];
	}

	/**
	 * Delete cache of arena statistics for a league
	 *
	 * @param unknown_type $leagueId
	 */
	public function clearOthersArenasStats($leagueId = 0)
	{
		$this->arenasStats[$leagueId] = null;
	}

	/**
	 * Delete all caches of arenas statistics
	 */
	public function clearOthersArenasAllStats()
	{
		$this->arenasStats = array();
	}

	/**
	 * Return HTMatchOrders object
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 * @return HTMatchOrders
	 */
	public function getSeniorMatchOrders($matchId, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->matchOrders[$teamId][$matchId]) || $this->matchOrders[$teamId][$matchId] === null)
		{
			$url = $this->buildUrl(array('file'=>'matchorders', 'version'=>'2.3', 'matchID'=>$matchId, 'sourceSystem'=>'hattrick', 'teamId'=>$teamId));
			$this->matchOrders[$teamId][$matchId] = new HTMatchOrders($this->fetchUrl($url));
		}
		return $this->matchOrders[$teamId][$matchId];
	}

	/**
	 * Delete cache of match orders
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 */
	public function clearSeniorMatchOrders($matchId, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->matchOrders[$teamId][$matchId] = null;
	}

	/**
	 * Delete all caches of matches orders
	 */
	public function clearSeniorMatchesOrders()
	{
		$this->matchOrders = array();
	}

	/**
	 * Return HTMatchOrders object
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 * @return HTMatchOrders
	 */
	public function getYouthMatchOrders($matchId, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->youthMatchOrders[$teamId][$matchId]) || $this->youthMatchOrders[$teamId][$matchId] === null)
		{
			$url = $this->buildUrl(array('file'=>'matchorders', 'version'=>'2.3', 'matchID'=>$matchId, 'sourceSystem'=>'youth', 'teamId'=>$teamId));
			$this->youthMatchOrders[$teamId][$matchId] = new HTMatchOrders($this->fetchUrl($url));
		}
		return $this->youthMatchOrders[$teamId][$matchId];
	}

	/**
	 * Delete cache of youth match orders
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 */
	public function clearYouthMatchOrders($matchId, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->youthMatchOrders[$teamId][$matchId] = null;
	}

	/**
	 * Delete all caches of youth matches orders
	 */
	public function clearYouthMatchesOrders()
	{
		$this->youthMatchOrders = array();
	}

	/**
	 * Return HTMatchOrders object
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 * @return HTMatchOrders
	 */
	public function getTournamentMatchOrders($matchId, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->tournamentMatchOrders[$teamId][$matchId]) || $this->tournamentMatchOrders[$teamId][$matchId] === null)
		{
			$url = $this->buildUrl(array('file'=>'matchorders', 'version'=>'2.3', 'matchID'=>$matchId, 'sourceSystem'=>'htointegrated', 'teamId'=>$teamId));
			$this->tournamentMatchOrders[$teamId][$matchId] = new HTMatchOrders($this->fetchUrl($url));
		}
		return $this->tournamentMatchOrders[$teamId][$matchId];
	}

	/**
	 * Delete cache of tournament match orders
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 */
	public function clearTournamentMatchOrders($matchId, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->tournamentMatchOrders[$teamId][$matchId] = null;
	}

	/**
	 * Delete all caches of tournament matches orders
	 */
	public function clearTournamentMatchesOrders()
	{
		$this->tournamentMatchOrders = array();
	}

	/**
	 * Return HTChallenges object
	 *
	 * @param Integer $teamId
	 * @return HTChallenges
	 */
	public function getChallenges($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->challenges[$teamId]) || $this->challenges[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'challenges', 'version'=>'1.4', 'teamId'=>$teamId));
			$this->challenges[$teamId] = new HTChallenges($this->fetchUrl($url));
		}
		return $this->challenges[$teamId];
	}

	/**
	 * Delete cache of team id challenges
	 */
	public function clearChallenges($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->challenges[$teamId] = null;
	}

	/**
	 * Delete cache of all challenges
	 */
	public function clearChallengesAll()
	{
		$this->challenges = array();
	}

	/**
	 * Return HTChallengeableTeams object
	 *
	 * @param Array $teamIds
	 * @param Integer $teamId
	 * @return HTChallengeableTeams
	 */
	public function getChallengeableTeams($teamIds, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$ids = implode(',', $teamIds);
		if(!isset($this->challengeableteams[$teamId][$ids]) || $this->challengeableteams[$teamId][$ids] === null)
		{
			$url = $this->buildUrl(array('file'=>'challenges', 'version'=>'1.4', 'actionType'=>'challengeable', 'suggestedTeamIds'=>$ids, 'teamId'=>$teamId));
			$this->challengeableteams[$teamId][$ids] = new HTChallengeableTeams($this->fetchUrl($url));
		}
		return $this->challengeableteams[$teamId][$ids];
	}

	/**
	 * Delete cache of challengeable teams
	 *
	 * @param Array $teamIds
	 * @param Integer $teamId
	 */
	public function clearChallengeableTeams($teamIds, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$ids = implode(',', $teamIds);
		$this->challengeableteams[$teamId][$ids] = null;
	}

	/**
	 * Delete cache of challengeable teams
	 */
	public function clearChallengeableTeamsAll()
	{
		$this->challengeableteams = array();
	}

	/**
	 * Challenge a team
	 *
	 * @param Integer $teamId
	 * @param Integer $matchType
	 * @param Integer $matchPlace
	 * @param Integer $arenaId
	 * @param Integer $userTeamId
	 */
	public function challengeTeam($teamId, $matchType, $matchPlace, $arenaId = null, $userTeamId = null)
	{
		if($userTeamId === null)
		{
			$userTeamId = $this->getTeam()->getTeamId();
		}
		$params = array('file'=>'challenges', 'version'=>'1.4', 'actionType'=>'challenge', 'opponentTeamId'=>$teamId, 'matchType'=>$matchType, 'matchPlace'=>$matchPlace, 'teamId'=>$userTeamId);
		if($arenaId !== null)
		{
			$params['neutralArenaId'] = $arenaId;
		}
		$url = $this->buildUrl($params);
		$this->fetchUrl($url);
	}

	/**
	 * Accept a challenge
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 */
	public function acceptChallenge($matchId, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$url = $this->buildUrl(array('file'=>'challenges', 'version'=>'1.4', 'actionType'=>'accept', 'trainingMatchId'=>$matchId, 'teamId'=>$teamId));
		$this->fetchUrl($url);
	}

	/**
	 * Decline a challenge
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 */
	public function declineChallenge($matchId, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$url = $this->buildUrl(array('file'=>'challenges', 'version'=>'1.4', 'actionType'=>'decline', 'trainingMatchId'=>$matchId, 'teamId'=>$teamId));
		$this->fetchUrl($url);
	}

	/**
	 * Withdraw a challenge
	 *
	 * @param Integer $matchId
	 * @param Integer $teamId
	 */
	public function withdrawChallenge($matchId, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$url = $this->buildUrl(array('file'=>'challenges', 'version'=>'1.4', 'actionType'=>'withdraw', 'trainingMatchId'=>$matchId, 'teamId'=>$teamId));
		$this->fetchUrl($url);
	}

	/**
	 * Return HTCup object
	 *
	 * @param Integer $cupId
	 * @param Integer $round
	 * @param Integer $season
	 * @param Integer $afterMatchId
	 * @return HTCup
	 */
	public function getCup($cupId, $round, $season = null, $afterMatchId = null)
	{
		if(!isset($this->cups[$cupId][$round][$season][$afterMatchId]) || $this->cups[$cupId][$round][$season][$afterMatchId] === null)
		{
			$url = $this->buildUrl(array('file'=>'cupmatches', 'cupID'=>$cupId, 'cupRound'=>$round, 'season'=>$season, 'startAfterMatchId'=>$afterMatchId, 'version'=>'1.4'));
			$this->cups[$cupId][$round][$season][$afterMatchId] = new HTCup($this->fetchUrl($url));
		}
		return $this->cups[$cupId][$round][$season][$afterMatchId];
	}

	/**
	 * Delete cache of a cup
	 *
	 * @param Integer $cupId
	 */
	public function clearCup($cupId)
	{
		$this->cups[$cupId] = null;
	}

	/**
	 * Delete all caches of cups
	 */
	public function clearCups()
	{
		$this->cups = array();
	}

	/**
	 * Return HTSearch object
	 *
	 * @param Integer $teamId
	 * @return HTSearch
	 */
	public function searchTeamById($teamId)
	{
		if(!isset($this->searchTeamId[$teamId]) || $this->searchTeamId[$teamId] === null)
		{
			$params = array('searchType'=>'4', 'searchID'=>$teamId);
			$this->searchTeamId[$teamId] = $this->search($params);
		}
		return $this->searchTeamId[$teamId];
	}

	/**
	 * Delete cache of search team by id
	 *
	 * @param Integer $teamId
	 */
	public function clearSearchTeamById($teamId)
	{
		$this->searchTeamId[$teamId] = null;
	}

	/**
	 * Delete all caches of search team by id
	 */
	public function clearAllSearchTeamById()
	{
		$this->searchTeamId = array();
	}

	/**
	 * Return HTSearch object
	 *
	 * @param String $teamName
	 * @param Integer $pageIndex
	 * @return HTSearch
	 */
	public function searchTeamByName($teamName, $pageIndex = 0)
	{
		if(!isset($this->searchTeamName[$teamName][$pageIndex]) || $this->searchTeamName[$teamName][$pageIndex] === null)
		{
			$params = array('searchType'=>'4', 'searchString'=>urlencode($teamName), 'pageIndex'=>$pageIndex);
			$this->searchTeamName[$teamName][$pageIndex] = $this->search($params);
		}
		return $this->searchTeamName[$teamName][$pageIndex];
	}

	/**
	 * Delete cache of search team by name
	 *
	 * @param String $teamName
	 */
	public function clearSearchTeamByName($teamName)
	{
		$this->searchTeamName[$teamName] = null;
	}

	/**
	 * Delete all caches of search team by name
	 */
	public function clearAllSearchTeamByName()
	{
		$this->searchTeamName = array();
	}

	/**
	 * Return HTSearch object
	 *
	 * @param String $loginName
	 * @param Integer $pageIndex
	 * @return HTSearch
	 */
	public function searchUserByName($name, $pageIndex = 0)
	{
		if(!isset($this->searchUserName[$name][$pageIndex]) || $this->searchUserName[$name][$pageIndex] === null)
		{
			$params = array('searchType'=>'2', 'searchString'=>urlencode($name), 'pageIndex'=>$pageIndex);
			$this->searchUserName[$name][$pageIndex] = $this->search($params);
		}
		return $this->searchUserName[$name][$pageIndex];
	}

	/**
	 * Delete cache of search user by loginname
	 *
	 * @param String $loginName
	 */
	public function clearSearchUserByName($name)
	{
		$this->searchUserName[$name] = null;
	}

	/**
	 * Delete all caches of search user by loginname
	 */
	public function clearAllSearchUserByName()
	{
		$this->searchUserName = array();
	}

	/**
	 * Return HTSearch object
	 *
	 * @param Integer $playerId
	 * @return HTSearch
	 */
	public function searchPlayerById($playerId)
	{
		if(!isset($this->searchPlayerId[$playerId]) || $this->searchPlayerId[$playerId] === null)
		{
			$params = array('searchType'=>'0', 'searchID'=>$playerId);
			$this->searchPlayerId[$playerId] = $this->search($params);
		}
		return $this->searchPlayerId[$playerId];
	}

	/**
	 * Delete cache of search player by id
	 *
	 * @param Integer $playerId
	 */
	public function clearSearchPlayerById($playerId)
	{
		$this->searchPlayerId[$playerId] = null;
	}

	/**
	 * Delete all caches of search player by id
	 */
	public function clearAllSearchPlayerById()
	{
		$this->searchPlayerId = array();
	}

	/**
	 * Return HTSearch object
	 *
	 * @param String $firstName
	 * @param String $lastName
	 * @param Integer $pageIndex
	 * @return HTSearch
	 */
	public function searchPlayerByName($firstName, $lastName, $pageIndex = 0, $leagueId = null)
	{
		if($leagueId === null)
		{
			$leagueId = $this->getTeam()->getLeagueId();
		}
		if(!isset($this->searchPlayerName[$firstName][$lastName][$pageIndex][$leagueId]) || $this->searchPlayerName[$firstName][$lastName][$pageIndex][$leagueId] === null)
		{
			$params = array('searchType'=>'0', 'searchString'=>$firstName, 'searchString2'=>$lastName, 'pageIndex'=>$pageIndex, 'searchLeagueID'=>$leagueId);
			$this->searchPlayerName[$firstName][$lastName][$pageIndex][$leagueId] = $this->search($params);
		}
		return $this->searchPlayerName[$firstName][$lastName][$pageIndex][$leagueId];
	}

	/**
	 * Delete cache of search player by name
	 *
	 * @param String $firstName
	 * @param String $lastName
	 */
	public function clearSearchPlayerByName($firstName, $lastName)
	{
		$this->searchPlayerName[$firstName][$lastName] = null;
	}

	/**
	 * Delete all caches of search player by name
	 */
	public function clearAllSearchPlayerByName()
	{
		$this->searchPlayerName = array();
	}

	/**
	 * Return HTSearch object
	 *
	 * @param String $regionName
	 * @param Integer $pageIndex
	 * @return HTSearch
	 */
	public function searchRegionByName($regionName, $pageIndex = 0)
	{
		if(!isset($this->searchRegionName[$regionName][$pageIndex]) || $this->searchRegionName[$regionName][$pageIndex] === null)
		{
			$params = array('searchType'=>'5', 'searchString'=>$regionName, 'pageIndex'=>$pageIndex);
			$this->searchRegionName[$regionName][$pageIndex] = $this->search($params);
		}
		return $this->searchRegionName[$regionName][$pageIndex];
	}

	/**
	 * Delete cache of search region by name
	 *
	 * @param String $regionName
	 */
	public function clearSearchRegionByName($regionName)
	{
		$this->searchRegionName[$regionName] = array();
	}

	/**
	 * Delete all caches of search region by name
	 */
	public function clearAllSearchRegionByName()
	{
		$this->searchRegionName = null;
	}

	/**
	 * Return HTSearch object
	 *
	 * @param String $regionName
	 * @param Integer $pageIndex
	 * @return HTSearch
	 */
	public function searchArenaByName($arenaName, $pageIndex = 0)
	{
		if(!isset($this->searchArenaName[$arenaName][$pageIndex]) || $this->searchArenaName[$arenaName][$pageIndex] === null)
		{
			$params = array('searchType'=>'1', 'searchString'=>$arenaName, 'pageIndex'=>$pageIndex);
			$this->searchArenaName[$arenaName][$pageIndex] = $this->search($params);
		}
		return $this->searchArenaName[$arenaName][$pageIndex];
	}

	/**
	 * Delete cache of search arena by name
	 *
	 * @param String $arenaName
	 */
	public function clearSearchArenaByName($arenaName)
	{
		$this->searchArenaName[$arenaName] = null;
	}

	/**
	 * Delete all caches of search arena by name
	 */
	public function clearAllSearchArenaByName()
	{
		$this->searchArenaName = array();
	}

	/**
	 * Return HTSearch object
	 *
	 * @param Integer $matchId
	 * @return HTSearch
	 */
	public function searchMatch($matchId)
	{
		if(!isset($this->searchMatchId[$matchId]) || $this->searchMatchId[$matchId] === null)
		{
			$params = array('searchType'=>'6', 'searchID'=>$matchId);
			$this->searchMatchId[$matchId] = $this->search($params);
		}
		return $this->searchMatchId[$matchId];
	}

	/**
	 * Delete cache of search match
	 *
	 * @param String $matchId
	 */
	public function clearSearchMatch($matchId)
	{
		$this->searchMatchId[$matchId] = null;
	}

	/**
	 * Delete all caches of search arena by name
	 */
	public function clearAllSearchMatch()
	{
		$this->searchMatchId = array();
	}

	/**
	 * Return HTSearch object
	 *
	 * @param Integer $serieId
	 * @param Integer $leagueId
	 * @return HTSearch
	 */
	public function searchSerieById($serieId, $leagueId = null)
	{
		if($leagueId === null)
		{
			$leagueId = $this->getTeam()->getLeagueId();
		}
		if(!isset($this->searchSeriesId[$serieId][$leagueId]) || $this->searchSeriesId[$serieId][$leagueId] === null)
		{
			$params = array('searchType'=>'3', 'searchID'=>$serieId, 'searchLeagueID'=>$leagueId);
			$this->searchSeriesId[$serieId][$leagueId] = $this->search($params);
		}
		return $this->searchSeriesId[$serieId][$leagueId];
	}

	/**
	 * Delete cache of search serie by id
	 *
	 * @param Integer $serieId
	 * @param Integer $leagueId
	 */
	public function clearSearchSerieById($serieId, $leagueId = null)
	{
		if($leagueId === null)
		{
			$leagueId = $this->getTeam()->getLeagueId();
		}
		$this->searchSeriesId[$serieId][$leagueId] = null;
	}

	/**
	 * Delete all caches of search serie by id
	 */
	public function clearAllSearchSerieById()
	{
		$this->searchSeriesId = array();
	}

	/**
	 * Return HTSearch object
	 *
	 * @param String $serieName
	 * @param Integer $leagueId
	 * @return HTSearch
	 */
	public function searchSerieByName($serieName, $leagueId = null)
	{
		if($leagueId === null)
		{
			$leagueId = $this->getTeam()->getLeagueId();
		}
		if(!isset($this->searchSeriesName[$serieName][$leagueId]) || $this->searchSeriesName[$serieName][$leagueId] === null)
		{
			$params = array('searchType'=>'3', 'searchString'=>$serieName, 'searchLeagueID'=>$leagueId);
			$this->searchSeriesName[$serieName][$leagueId] = $this->search($params);
		}
		return $this->searchSeriesName[$serieName][$leagueId];
	}

	/**
	 * Delete cache of search serie by name
	 *
	 * @param String $serieName
	 * @param Integer $leagueId
	 */
	public function clearSearchSerieByName($serieName, $leagueId = null)
	{
		if($leagueId === null)
		{
			$leagueId = $this->getTeam()->getLeagueId();
		}
		$this->searchSeriesName[$serieName][$leagueId] = null;
	}

	/**
	 * Delete all caches of search serie by id
	 */
	public function clearAllSearchSerieByName()
	{
		$this->searchSeriesName = array();
	}

	/**
	 * @param Array $extraParams
	 * @return HTSearch
	 */
	protected function search($extraParams)
	{
		$params = array('file'=>'search', 'version'=>'1.2');
		$params = array_merge($params, $extraParams);
		$url = $this->buildUrl($params);
		return new HTSearch($this->fetchUrl($url));
	}

	/**
	 * Return HTSearchTransferCriteria object
	 *
	 * @return HTSearchTransferCriteria
	 */
	public function createNewSearchTransfer()
	{
		return new HTSearchTransferCriteria();
	}

	/**
	 * Return HTSearchTransfer object
	 *
	 * @param HTSearchTransferCriteria $criteria
	 * @return HTSearchTransfer
	 */
	public function searchTransfer($criteria)
	{
		if(!$criteria instanceof HTSearchTransferCriteria)
		{
			return null;
		}
		$params = array('file'=>'transfersearch', 'version'=>'1.0', 'ageMin'=>$criteria->getMinAge(), 'ageMax'=>$criteria->getMaxAge());
		if($criteria->getMinDays() !== null)
		{
			$params['ageDaysMin'] = $criteria->getMinDays();
		}
		if($criteria->getMaxDays() !== null)
		{
			$params['ageDaysMax'] = $criteria->getMaxDays();
		}
		$skill1 = $criteria->getFirstSkill();
		$params['skillType1'] = $skill1[0];
		$params['minSkillValue1'] = $skill1[1];
		$params['maxSkillValue1'] = $skill1[2];
		$skill2 = $criteria->getSecondSkill();
		if($skill2[0] !== null)
		{
			$params['skillType2'] = $skill2[0];
		}
		if($skill2[1] !== null)
		{
			$params['minSkillValue2'] = $skill2[1];
		}
		if($skill2[2] !== null)
		{
			$params['maxSkillValue2'] = $skill2[2];
		}
		$skill3 = $criteria->getThirdSkill();
		if($skill3[0] !== null)
		{
			$params['skillType3'] = $skill3[0];
		}
		if($skill3[1] !== null)
		{
			$params['minSkillValue3'] = $skill3[1];
		}
		if($skill3[2] !== null)
		{
			$params['maxSkillValue3'] = $skill3[2];
		}
		$skill4 = $criteria->getFourthSkill();
		if($skill4[0] !== null)
		{
			$params['skillType4'] = $skill4[0];
		}
		if($skill4[1] !== null)
		{
			$params['minSkillValue4'] = $skill4[1];
		}
		if($skill4[2] !== null)
		{
			$params['maxSkillValue4'] = $skill4[2];
		}
		if($criteria->getSpeciality() !== null)
		{
			$params['specialty'] = $criteria->getSpeciality();
		}
		if($criteria->getCountry() !== null)
		{
			$params['nativeCountryId'] = $criteria->getCountry();
		}
		if($criteria->getMinTSI() !== null)
		{
			$params['tsiMin'] = $criteria->getMinTSI();
		}
		if($criteria->getMaxTSI() !== null)
		{
			$params['tsiMax'] = $criteria->getMaxTSI();
		}
		if($criteria->getMinPrice() !== null)
		{
			$params['priceMin'] = $criteria->getMinPrice();
		}
		if($criteria->getMaxPrice() !== null)
		{
			$params['priceMax'] = $criteria->getMaxPrice();
		}
		if($criteria->getPageSize() !== null)
		{
			$params['pageSize'] = $criteria->getPageSize();
		}
		if($criteria->getPageIndex() !== null)
		{
			$params['pageIndex'] = $criteria->getPageIndex();
		}

		$url = $this->buildUrl($params);
		return new HTSearchTransfer($this->fetchUrl($url));
	}

	/**
	 * Return HTBookmarks object
	 *
	 * @param Integer $type
	 * @return HTBookmarks
	 */
	public function getBookmarks($type = HTBookmarksGlobal::ALL)
	{
		$type = round($type);
		if($type < HTBookmarksGlobal::ALL || $type > HTBookmarksGlobal::CONFTHREADS)
		{
			$type = HTBookmarksGlobal::ALL;
		}
		if(!isset($this->bookmarks[$type]) || $this->bookmarks[$type] === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>$type, 'version'=>'1.0'));
			$this->bookmarks[$type] = new HTBookmarks($this->fetchUrl($url));
		}
		return $this->bookmarks[$type];
	}

	/**
	 * Delete cache of bookmarks for given type
	 *
	 * @param Integer $type
	 */
	public function clearBookmarks($type = HTBookmarksGlobal::ALL)
	{
		$type = round($type);
		if($type < HTBookmarksGlobal::ALL || $type > HTBookmarksGlobal::CONFTHREADS)
		{
			$type = HTBookmarksGlobal::ALL;
		}
		$this->bookmarks[$type] = null;
	}

	/**
	 * Delete all cache of bookmarks
	 */
	public function clearAllBookmarks()
	{
		$this->bookmarks = array();
	}

	/**
	 * Return HTBookmarksTeams object
	 *
	 * @return HTBookmarksTeams
	 */
	public function getBookmarksTeams()
	{
		if(!isset($this->bookmarksTeams) || $this->bookmarksTeams === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>'1', 'version'=>'1.0'));
			$this->bookmarksTeams = new HTBookmarksTeams($this->fetchUrl($url));
		}
		return $this->bookmarksTeams;
	}

	/**
	 * Delete cache of bookmarks teams
	 */
	public function clearBookmarksTeams()
	{
		$this->bookmarksTeams = null;
	}

	/**
	 * Return HTBookmarksPlayers object
	 *
	 * @return HTBookmarksPlayers
	 */
	public function getBookmarksPlayers()
	{
		if(!isset($this->bookmarksPlayers) || $this->bookmarksPlayers === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>'2', 'version'=>'1.0'));
			$this->bookmarksPlayers = new HTBookmarksPlayers($this->fetchUrl($url));
		}
		return $this->bookmarksPlayers;
	}

	/**
	 * Delete cache of bookmarks players
	 */
	public function clearBookmarksPlayers()
	{
		$this->bookmarksPlayers = null;
	}

	/**
	 * Return HTBookmarksMatches object
	 *
	 * @return HTBookmarksMatches
	 */
	public function getBookmarksMatches()
	{
		if(!isset($this->bookmarksMatches) || $this->bookmarksMatches === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>'3', 'version'=>'1.0'));
			$this->bookmarksMatches = new HTBookmarksMatches($this->fetchUrl($url));
		}
		return $this->bookmarksMatches;
	}

	/**
	 * Delete cache of bookmarks matches
	 */
	public function clearBookmarksMatches()
	{
		$this->bookmarksMatches = null;
	}

	/**
	 * Return HTBookmarksConfUsers object
	 *
	 * @return HTBookmarksConfUsers
	 */
	public function getBookmarksConfUsers()
	{
		if(!isset($this->bookmarksConfUsers) || $this->bookmarksConfUsers === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>'4', 'version'=>'1.0'));
			$this->bookmarksConfUsers = new HTBookmarksConfUsers($this->fetchUrl($url));
		}
		return $this->bookmarksConfUsers;
	}

	/**
	 * Delete cache of bookmarks conference users
	 */
	public function clearBookmarksConfUsers()
	{
		$this->bookmarksConfUsers = null;
	}

	/**
	 * Return HTBookmarksLeagues object
	 *
	 * @return HTBookmarksLeagues
	 */
	public function getBookmarksLeagues()
	{
		if(!isset($this->bookmarksLeagues) || $this->bookmarksLeagues === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>'5', 'version'=>'1.0'));
			$this->bookmarksLeagues = new HTBookmarksLeagues($this->fetchUrl($url));
		}
		return $this->bookmarksLeagues;
	}

	/**
	 * Delete cache of bookmarks leagues
	 */
	public function clearBookmarksLeagues()
	{
		$this->bookmarksLeagues = null;
	}

	/**
	 * Return HTBookmarksYouthTeams object
	 *
	 * @return HTBookmarksYouthTeams
	 */
	public function getBookmarksYouthTeams()
	{
		if(!isset($this->bookmarksYouthTeams) || $this->bookmarksYouthTeams === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>'6', 'version'=>'1.0'));
			$this->bookmarksYouthTeams = new HTBookmarksYouthTeams($this->fetchUrl($url));
		}
		return $this->bookmarksYouthTeams;
	}

	/**
	 * Delete cache of bookmarks youth teams
	 */
	public function clearBookmarksYouthTeams()
	{
		$this->bookmarksYouthTeams = null;
	}

	/**
	 * Return HTBookmarksYouthPlayers object
	 *
	 * @return HTBookmarksYouthPlayers
	 */
	public function getBookmarksYouthPlayers()
	{
		if(!isset($this->bookmarksYouthPlayers) || $this->bookmarksYouthPlayers === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>'7', 'version'=>'1.0'));
			$this->bookmarksYouthPlayers = new HTBookmarksYouthPlayers($this->fetchUrl($url));
		}
		return $this->bookmarksYouthPlayers;
	}

	/**
	 * Delete cache of bookmarks youth players
	 */
	public function clearBookmarksYouthPlayers()
	{
		$this->bookmarksYouthPlayers = null;
	}

	/**
	 * Return HTBookmarksYouthMatches object
	 *
	 * @return HTBookmarksYouthMatches
	 */
	public function getBookmarksYouthMatches()
	{
		if(!isset($this->bookmarksYouthMatches) || $this->bookmarksYouthMatches === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>'8', 'version'=>'1.0'));
			$this->bookmarksYouthMatches = new HTBookmarksYouthMatches($this->fetchUrl($url));
		}
		return $this->bookmarksYouthMatches;
	}

	/**
	 * Delete cache of bookmarks youth matches
	 */
	public function clearBookmarksYouthMatches()
	{
		$this->bookmarksYouthMatches = null;
	}

	/**
	 * Return HTBookmarksYouthLeagues object
	 *
	 * @return HTBookmarksYouthLeagues
	 */
	public function getBookmarksYouthLeagues()
	{
		if(!isset($this->bookmarksYouthLeagues) || $this->bookmarksYouthLeagues === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>'9', 'version'=>'1.0'));
			$this->bookmarksYouthLeagues = new HTBookmarksYouthLeagues($this->fetchUrl($url));
		}
		return $this->bookmarksYouthLeagues;
	}

	/**
	 * Delete cache of bookmarks youth leagues
	 */
	public function clearBookmarksYouthLeagues()
	{
		$this->bookmarksYouthLeagues = null;
	}

	/**
	 * Return HTBookmarksConfPosts object
	 *
	 * @return HTBookmarksConfPosts
	 */
	public function getBookmarksConfPosts()
	{
		if(!isset($this->bookmarksConfPosts) || $this->bookmarksConfPosts === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>'10', 'version'=>'1.0'));
			$this->bookmarksConfPosts = new HTBookmarksConfPosts($this->fetchUrl($url));
		}
		return $this->bookmarksConfPosts;
	}

	/**
	 * Delete cache of bookmarks conference posts
	 */
	public function clearBookmarksConfPosts()
	{
		$this->bookmarksConfPosts = null;
	}

	/**
	 * Return HTBookmarksConfThreads object
	 *
	 * @return HTBookmarksConfThreads
	 */
	public function getBookmarksConfThreads()
	{
		if(!isset($this->bookmarksConfThreads) || $this->bookmarksConfThreads === null)
		{
			$url = $this->buildUrl(array('file'=>'bookmarks', 'bookmarkTypeID'=>'11', 'version'=>'1.0'));
			$this->bookmarksConfThreads = new HTBookmarksConfThreads($this->fetchUrl($url));
		}
		return $this->bookmarksConfThreads;
	}

	/**
	 * Delete cache of bookmarks conference threads
	 */
	public function clearBookmarksConfThreads()
	{
		$this->bookmarksConfThreads = null;
	}

	/**
	 * Return HTTrainingEvents object
	 *
	 * @param Integer $playerId
	 * @return HTTrainingEvents
	 */
	public function getTrainingEvents($playerId)
	{
		if(!isset($this->trainingEvents[$playerId]) || $this->trainingEvents[$playerId] === null)
		{
			$url = $this->buildUrl(array('file'=>'trainingevents', 'playerID'=>$playerId, 'version'=>'1.3'));
			$this->trainingEvents[$playerId] = new HTTrainingEvents($this->fetchUrl($url));
		}
		return $this->trainingEvents[$playerId];
	}

	/**
	 * Delete cache of training events for a player
	 *
	 * @param Integer $playerId
	 */
	public function clearTrainingEvents($playerId)
	{
		$this->trainingEvents[$playerId] = null;
	}

	/**
	 * Delete all cache of training events
	 */
	public function clearTrainingsEvents()
	{
		$this->trainingEvents = array();
	}

	/**
	 * Return HTPlayerEvents object
	 *
	 * @param Integer $playerId
	 * @param Integer $languageId
	 * @return HTPlayerEvents
	 */
	public function getPlayerEvents($playerId)
	{
		if(!isset($this->playerEvents[$playerId]) || $this->playerEvents[$playerId] === null)
		{
			$url = $this->buildUrl(array('file'=>'playerevents', 'playerID'=>$playerId, 'version'=>'1.3'));
			$this->playerEvents[$playerId] = new HTPlayerEvents($this->fetchUrl($url));
		}
		return $this->playerEvents[$playerId];
	}

	/**
	 * Delete cache of player events
	 *
	 * @param Integer $playerId
	 */
	public function clearPlayerEvents($playerId)
	{
		$this->playerEvents[$playerId] = null;
	}

	/**
	 * Delete all cache of players events
	 */
	public function clearPlayersEvents()
	{
		$this->playerEvents = array();
	}

	/**
	 * Return HTAllianceDetails object
	 *
	 * @param Integer $allianceId
	 * @return HTAllianceDetails
	 */
	public function getAllianceDetails($allianceId)
	{
		if(!isset($this->allianceDetails[$allianceId]) || $this->allianceDetails[$allianceId] === null)
		{
			$url = $this->buildUrl(array('file'=>'alliancedetails', 'allianceID'=>$allianceId, 'showRules'=>'true', 'showLoggedIn'=>'true', 'version'=>'1.5'));
			$this->allianceDetails[$allianceId] = new HTAllianceDetails($this->fetchUrl($url));
		}
		return $this->allianceDetails[$allianceId];
	}

	/**
	 * Delete cache of alliance details
	 *
	 * @param Integer $allianceId
	 */
	public function clearAllianceDetails($allianceId)
	{
		$this->allianceDetails[$allianceId] = null;
	}

	/**
	 * Delete all cache of alliances details
	 */
	public function clearAlliancesDetails()
	{
		$this->allianceDetails = array();
	}

	/**
	 * Return HTAllianceMembers object
	 *
	 * @param Integer $allianceId
	 * @return HTAllianceMembers
	 */
	public function getAllianceMembers($allianceId)
	{
		if(!isset($this->allianceMembers[$allianceId]) || $this->allianceMembers[$allianceId] === null)
		{
			$url = $this->buildUrl(array('file'=>'alliancedetails', 'actionType'=>'members', 'allianceID'=>$allianceId, 'version'=>'1.5'));
			$this->allianceMembers[$allianceId] = new HTAllianceMembers($this->fetchUrl($url));
		}
		return $this->allianceMembers[$allianceId];
	}

	/**
	 * Delete cache of alliance members
	 *
	 * @param Integer $allianceId
	 */
	public function clearAllianceMembers($allianceId)
	{
		$this->allianceMembers[$allianceId] = null;
	}

	/**
	 * Delete all cache of alliances members
	 */
	public function clearAlliancesMembers()
	{
		$this->allianceMembers = array();
	}

	/**
	 * Return HTAllianceMembers object
	 *
	 * @param Integer $allianceId
	 * @param String $letter
	 * @return HTAllianceMembers
	 */
	public function getAllianceMembersByLetter($allianceId, $letter)
	{
		$letter = ord(strtoupper($letter));
		if($letter < 65 || $letter > 90)
		{
			$letter = 0;
		}
		if(!isset($this->allianceMembersLetters[$allianceId][$letter]) || $this->allianceMembersLetters[$allianceId][$letter] === null)
		{
			$url = $this->buildUrl(array('file'=>'alliancedetails', 'actionType'=>'membersSubset', 'allianceID'=>$allianceId, 'subSet'=>$letter, 'version'=>'1.5'));
			$this->allianceMembersLetters[$allianceId][$letter] = new HTAllianceMembers($this->fetchUrl($url));
		}
		return $this->allianceMembersLetters[$allianceId][$letter];
	}

	/**
	 * Delete cache of alliance members by letter
	 *
	 * @param Integer $allianceId
	 */
	public function clearAllianceMembersByLetter($allianceId, $letter)
	{
		$letter = ord(strtoupper($letter));
		if($letter < 65 || $letter > 90)
		{
			$letter = 0;
		}
		$this->allianceMembersLetters[$allianceId][$letter] = null;
	}

	/**
	 * Delete cache of alliance members by letters
	 *
	 * @param Integer $allianceId
	 */
	public function clearAllianceMembersByLetters($allianceId)
	{
		$this->allianceMembersLetters[$allianceId] = null;
	}

	/**
	 * Delete all cache of alliances by letters
	 */
	public function clearAlliancesMembersByLetters()
	{
		$this->allianceMembersLetters = array();
	}

	/**
	 * Return HTAllianceRoles object
	 *
	 * @param Integer $allianceId
	 * @return HTAllianceMembers
	 */
	public function getAllianceRoles($allianceId)
	{
		if(!isset($this->allianceRoles[$allianceId]) || $this->allianceRoles[$allianceId] === null)
		{
			$url = $this->buildUrl(array('file'=>'alliancedetails', 'actionType'=>'roles', 'allianceID'=>$allianceId, 'version'=>'1.5'));
			$this->allianceRoles[$allianceId] = new HTAllianceRoles($this->fetchUrl($url));
		}
		return $this->allianceRoles[$allianceId];
	}

	/**
	 * Delete cache of alliance roles
	 *
	 * @param Integer $allianceId
	 */
	public function clearAllianceRoles($allianceId)
	{
		$this->allianceRoles[$allianceId] = null;
	}

	/**
	 * Delete all cache of alliances roles
	 */
	public function clearAlliancesRoles()
	{
		$this->allianceRoles = array();
	}

	/**
	 * Return HTFans object
	 *
	 * @param Integer $teamId
	 * @return HTFans
	 */
	public function getFans($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->fans[$teamId]) || $this->fans[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'fans', 'version'=>'1.2', 'teamId'=>$teamId));
			$this->fans[$teamId] = new HTFans($this->fetchUrl($url));
		}
		return $this->fans[$teamId];
	}

	/**
	 * Delete cache of fan team
	 */
	public function clearFans($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->fans[$teamId] = null;
	}

	/**
	 * Delete cache of fans
	 */
	public function clearAllFans()
	{
		$this->fans = null;
	}

	/**
	 * Return HTAchievements object
	 *
	 * @return HTAchievements
	 */
	public function getAchievements($userId = null)
	{
		if($userId === null)
		{
			$userId = $this->getClub()->getUserId();
		}
		if(!isset($this->achievements[$userId]) || $this->achievements[$userId] === null)
		{
			$url = $this->buildUrl(array('file'=>'achievements', 'userID'=>$userId, 'version'=>'1.1'));
			$this->achievements[$userId] = new HTAchievements($this->fetchUrl($url));
		}
		return $this->achievements[$userId];
	}

	/**
	 * Delete cache of achievements
	 */
	public function clearAchivements($userId = null)
	{
		if($userId === null)
		{
			$userId = $this->getClub()->getUserId();
		}
		$this->achievements[$userId] = null;
	}

	/**
	 * Delete cache of all achievements
	 */
	public function clearAllAchivements()
	{
		$this->achievements = array();
	}

	/**
	 * Return HTHofPlayers object
	 *
	 * @return HTHofPlayers
	 */
	public function getHofPlayers($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->hofPlayers[$teamId]) || $this->hofPlayers[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'hofplayers', 'teamId'=>$teamId, 'version'=>'1.0'));
			$this->hofPlayers[$teamId] = new HTHofPlayers($this->fetchUrl($url));
		}
		return $this->hofPlayers[$teamId];
	}

	/**
	 * Delete cache of hof players
	 */
	public function clearHofPlayers($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->hofPlayers[$teamId] = null;
	}

	/**
	 * Delete cache of all achievements
	 */
	public function clearAllHofPlayers()
	{
		$this->hofPlayers = array();
	}

	/**
	 * Return HTAvatars object
	 *
	 * @return HTAvatars
	 */
	public function getAvatars($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->avatars[$teamId]) || $this->avatars[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'avatars', 'version'=>'1.1', 'teamId'=>$teamId));
			$this->avatars[$teamId] = new HTAvatars($this->fetchUrl($url));
		}
		return $this->avatars[$teamId];
	}

	/**
	 * Delete cache of avatars
	 */
	public function clearAvatars($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->avatars[$teamId] = null;
	}

	/**
	 * Delete cache of all avatars
	 */
	public function clearAllAvatars()
	{
		$this->avatars = array();
	}

	/**
	 * Return HTAvatars object
	 *
	 * @return HTAvatars
	 */
	public function getHofAvatars()
	{
		if(!isset($this->hofAvatars) || $this->hofAvatars === null)
		{
			$url = $this->buildUrl(array('file'=>'avatars', 'version'=>'1.1', 'actionType'=>'hof'));
			$this->hofAvatars = new HTAvatars($this->fetchUrl($url));
		}
		return $this->hofAvatars;
	}

	/**
	 * Delete cache of hof avatars
	 */
	public function clearHofAvatars()
	{
		$this->hofAvatars = null;
	}

	/**
	 * Return HTYouthAvatars object
	 *
	 * @return HTYouthAvatars
	 */
	public function getYouthAvatars($youthTeamId = null)
	{
		if($youthTeamId === null)
		{
			$youthTeamId = $this->getTeam()->getYouthTeamId();
		}
		if(!isset($this->youthAvatars[$youthTeamId]) || $this->youthAvatars[$youthTeamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'youthavatars', 'version'=>'1.1', 'youthTeamId'=>$youthTeamId));
			$this->youthAvatars[$youthTeamId] = new HTYouthAvatars($this->fetchUrl($url));
		}
		return $this->youthAvatars[$youthTeamId];
	}

	/**
	 * Delete team cache of avatars
	 *
	 * @param Integer $youthTeamId
	 */
	public function clearYouthAvatars($youthTeamId = null)
	{
		if($youthTeamId === null)
		{
			$youthTeamId = $this->getTeam()->getYouthTeamId();
		}
		$this->youthAvatars[$youthTeamId] = null;
	}

	/**
	 * Delete all cache of avatars
	 */
	public function clearYouthAvatarsAll()
	{
		$this->youthAvatars = array();
	}

	/**
	 * @param String $startDate
	 * @param String $endDate
	 * @param Boolean $limit
	 */
	protected function analyseDate(&$startDate, &$endDate, $limit = true)
	{
		if($endDate === null && $startDate !== null)
		{
			$endDate = date('Y-m-d');
		}
		if($startDate === null && $endDate !== null)
		{
			if($limit == true)
			{
				$startDate = date('Y-m-d', strtotime($endDate)-(3600*24*7*16)); // 1 season
			}
			else
			{
				$startDate = null;
			}
		}
		if($startDate !== null && $endDate !== null)
		{
			if($startDate > $endDate)
			{
				$tmp = $startDate; $startDate = $endDate; $endDate = $tmp;
			}
			if($limit == true)
			{
				$start = strtotime($startDate);
				$end = strtotime($endDate);
				if($end-$start > 3600*24*7*16*2) // 2 seasons
				{
					$startDate = $endDate = null;
				}
			}
		}
	}

	/**
	 * @param String $url
	 * @param Boolean $check
	 * @param Array $postParams
	 * @return String
	 */
	protected function fetchUrl($url, $check = true, $postParams = array())
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		if(count($postParams))
		{
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postParams));
		}
		else
		{
			curl_setopt($curl, CURLOPT_POST, false);
		}
		if($this->canLog())
		{
			$this->log("[URL] Start fetching ".$url);
			if(count($postParams))
			{
				$this->log("[URL] POST data: ".var_export($postParams, true));
			}
			$startTime = microtime(true);
		}
		$xmlData = curl_exec($curl);
		if($this->canLog())
		{
			$endTime = microtime(true);
			$this->log("[URL] Fetch done in: ".($endTime-$startTime));
		}
		curl_close($curl);
		if($check === true)
		{
			$this->checkXmlData($xmlData);
		}
		return $xmlData;
	}

	/**
	 * @param String $xmlData
	 * @throws HTError
	 */
	protected function checkXmlData($xmlData)
	{
		$tmpXml = xml_parser_create();
		if(!xml_parse($tmpXml, $xmlData, true))
		{
			throw new HTError($xmlData, false);
		}
		xml_parser_free($tmpXml);

		$tmpXml = new DOMDocument('1.0', 'UTF-8');
		$tmpXml->loadXML($xmlData);
		$filename = $tmpXml->getElementsByTagName('FileName');
		if($filename->length == 0)
		{
			if($this->canLog())
			{
				$this->log("[ERROR] XML Error: ".$xmlData);
			}
			throw new HTError($xmlData, true);
		}
		if($filename->item(0)->nodeValue == self::ERROR_FILE)
		{
			if($this->canLog())
			{
				$this->log("[ERROR] CHPP Error: ".$xmlData);
			}
			throw new HTError($xmlData, true);
		}
	}

	/**
	 * @param String $file
	 * @param Array $params
	 * @return String
	 */
	protected function buildOauthUrl($file, $params)
	{
		$url = $file."?";
		foreach ($params as $param => $value)
		{
			$url .= $param."=".$this->urlencodeRfc3986($value)."&";
		}
		return substr($url,0,-1);
	}

	/**
	 * @param Array $params
	 * @return String
	 */
	protected function buildUrl($params, $postParams = array())
	{
		if($this->overrideSupporter !== 0)
		{
			$params['overrideIsSupporter'] = $this->overrideSupporter;
		}
		if($this->canLog())
		{
			$this->log("[PARAMS] Query params: ".var_export($params, true));
		}
		$params = array_merge($params, array(
			'oauth_consumer_key'=>$this->consumerKey,
			'oauth_signature_method'=>$this->signatureMethod,
			'oauth_timestamp'=>$this->getTimestamp(),
			'oauth_nonce'=>$this->getNonce(),
			'oauth_token'=>$this->getOauthToken(),
			'oauth_version'=>$this->version
		));
		$signature = $this->buildSignature(self::XML_SERVER.self::CHPP_URL, array_merge($params, $postParams), $this->getOauthTokenSecret(), ($postParams == array() ? 'GET' : 'POST'));
		$params['oauth_signature'] = $signature;
		uksort($params, 'strcmp');
		return $this->buildOauthUrl(self::XML_SERVER.self::CHPP_URL, $params);
	}

	/**
	 * Return HTFlags object
	 *
	 * @param Integer $teamId
	 * @param Boolean $useEnglishName
	 * @return HTFlags
	 */
	public function getFlagsPlayers($teamId = null, $useEnglishName = true)
	{
		$team = $this->getTeam($teamId);
		if(!isset($this->flagsPlayers[$team->getTeamId()][$useEnglishName]) || $this->flagsPlayers[$team->getTeamId()][$useEnglishName] === null)
		{
			$players = $this->getTeamPlayers($team->getTeamId());
			$worldDetails = $this->getWorldDetails();
			$tmp = array();
			for($i=1; $i<=$players->getNumberPlayers(); $i++)
			{
				$tmp[] = $worldDetails->getLeagueByCountryId($players->getPlayer($i)->getCountryId())->getId();
			}
			$tmp = array_unique($tmp);
			sort($tmp);
			$flags = array();
			foreach($tmp as $id)
			{
				$league = $worldDetails->getLeagueById($id);
				if($useEnglishName)
				{
					$name = $league->getEnglishName();
				}
				else
				{
					$name = $league->getCountryName();
				}
				$flags[$name] = $league;
			}
			$this->flagsPlayers[$team->getTeamId()][$useEnglishName] = new HTFlags($flags);
		}
		return $this->flagsPlayers[$team->getTeamId()][$useEnglishName];
	}

	/**
	 * Clear cache of team players flags
	 *
	 * @param Integer $teamId
	 */
	public function clearFlagsPlayers($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->flagsPlayers[$teamId] = null;
	}

	/**
	 * Clear cache of all team players flags
	 */
	public function clearAllFlagsPlayers()
	{
		$this->flagsPlayers = array();
	}

	/**
	 * Return HTFlags object
	 *
	 * @param Integer $teamId
	 * @param Boolean $useEnglishName
	 * @return HTFlags
	 */
	public function getFlagsRaisedPlayers($teamId = null, $useEnglishName = true)
	{
		$team = $this->getTeam($teamId);
		if(!isset($this->flagsRaisedPlayers[$team->getTeamId()][$useEnglishName]) || $this->flagsRaisedPlayers[$team->getTeamId()][$useEnglishName] === null)
		{
			$players = $this->getTeamOldPlayers($team->getTeamId());
			$worldDetails = $this->getWorldDetails();
			$tmp = array();
			for($i=1; $i<=$players->getNumberPlayers(); $i++)
			{
				$tmp[] = $worldDetails->getLeagueByName($players->getPlayer($i)->getLeagueName())->getId();
			}
			$tmp = array_unique($tmp);
			sort($tmp);
			$flags = array();
			foreach($tmp as $id)
			{
				$league = $worldDetails->getLeagueById($id);
				if($useEnglishName)
				{
					$name = $league->getEnglishName();
				}
				else
				{
					$name = $league->getCountryName();
				}
				$flags[$name] = $league;
			}
			$this->flagsRaisedPlayers[$team->getTeamId()][$useEnglishName] = new HTFlags($flags);
		}
		return $this->flagsRaisedPlayers[$team->getTeamId()][$useEnglishName];
	}

	/**
	 * Clear cache of team raised players flags
	 *
	 * @param Integer $teamId
	 */
	public function clearFlagsRaisedPlayers($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->flagsRaisedPlayers[$teamId] = null;
	}

	/**
	 * Clear cache of all team raised players flags
	 */
	public function clearAllFlagsRaisedPlayers()
	{
		$this->flagsRaisedPlayers = array();
	}

	/**
	 * Is match a derby ?
	 *
	 * @param Integer $matchId
	 * @param Boolean $isYouth
	 * @return Boolean
	 */
	public function isMatchADerby($matchId, $isYouth = false)
	{
		if($isYouth)
		{
			$match = $this->getYouthMatchDetails($matchId);
		}
		else
		{
			$match = $this->getSeniorMatchDetails($matchId);
		}
		return $this->getTeam($match->getHomeTeam()->getId())->getRegionId() == $this->getTeam($match->getAwayTeam()->getId())->getRegionId();
	}

	/**
	 * Return HTSetLineup object
	 *
	 * @param Integer $matchId
	 * @param String $sourceSystem
	 * @return HTSetLineup
	 */
	public function createNewLineup($matchId, $sourceSystem = HTMatch::SENIOR)
	{
		return new HTSetLineup($matchId, $sourceSystem);
	}

	/**
	 * Return HTLineupResult object
	 *
	 * @param HTSetLineup $lineup
	 * @param Integer $teamId
	 * @return HTLineupResult
	 */
	public function sendLineup($lineup, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$json = $lineup->getJson();
		$url = $this->buildUrl(array('file'=>'matchorders', 'actionType'=>'setmatchorder', 'version'=>'2.3', 'teamId'=>$teamId, 'matchID'=>$lineup->getMatchId(), 'sourceSystem'=>$lineup->getSourceSystem()), array('lineup'=>$json));
		return new HTLineupResult($this->fetchUrl($url, true, array('lineup'=>$json)));
	}

	/**
	 * Return HTLineupPrediction object
	 *
	 * @param HTSetLineup $lineup
	 * @param Integer $teamId
	 * @return HTLineupPrediction
	 */
	public function predictRatings($lineup = null, $teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$params = array('file'=>'matchorders', 'actionType'=>'predictratings', 'version'=>'2.3', 'teamId'=>$teamId);
		if($lineup !== null)
		{
			$json = $lineup->getJson();
			$params['matchID'] = $lineup->getMatchId();
			$params['sourceSystem'] = $lineup->getSourceSystem();
			$url = $this->buildUrl($params, array('lineup'=>$json));
			return new HTLineupPrediction($this->fetchUrl($url, true, array('lineup'=>$json)));
		}
		else
		{
			$url = $this->buildUrl($params);
			return new HTLineupPrediction($this->fetchUrl($url));
		}
	}

	/**
	 * Return HTYouthTeam object
	 *
	 * @param Integer $id
	 * @return HTYouthTeam
	 */
	public function getYouthTeam($id = null)
	{
		if($id === null)
		{
			$id = $this->getTeam()->getYouthTeamId();
		}
		if(!isset($this->youthTeams[$id]) || $this->youthTeams[$id] === null)
		{
			$url = $this->buildUrl(array('file'=>'youthteamdetails', 'youthTeamId'=>$id, 'version'=>'1.0'));
			$this->youthTeams[$id] = new HTYouthTeam($this->fetchUrl($url));
		}
		return $this->youthTeams[$id];
	}

	/**
	 * Delete cache of youth team data
	 *
	 * @param Integer $id
	 */
	public function clearYouthTeam($id = null)
	{
		if($id === null)
		{
			$id = $this->getTeam()->getYouthTeamId();
		}
		$this->youthTeams[$id] = null;
	}

	/**
	 * Clear cache of all youth teams
	 */
	public function clearYouthTeams()
	{
		$this->youthTeams = array();
	}

	/**
	 * Return HTYouthScouts object
	 *
	 * @param Integer $teamId
	 * @return HTYouthScouts
	 */
	public function getYouthScouts($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getYouthTeamId();
		}
		if(!isset($this->youthScouts[$teamId]) || $this->youthScouts[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'youthteamdetails', 'youthTeamId'=>$teamId, 'version'=>'1.0', 'showScouts'=>'true'));
			$this->youthScouts[$teamId] = new HTYouthScouts($this->fetchUrl($url));
		}
		return $this->youthScouts[$teamId];
	}

	/**
	 * Delete cache of youth scout data
	 *
	 * @param Integer $teamId
	 */
	public function clearYouthScouts($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getYouthTeamId();
		}
		$this->youthScouts[$teamId] = null;
	}

	/**
	 * Clear cache of all youth scouts
	 */
	public function clearAllYouthScouts()
	{
		$this->youthScouts = array();
	}

	/**
	 * Return HTYouthTeamPlayers object
	 *
	 * @param Integer $teamId
	 * @param String $orderBy
	 * @return HTYouthTeamPlayers
	 */
	public function getYouthTeamPlayers($teamId = null, $orderBy = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getYouthTeamId();
		}
		if($teamId === null)
		{
			return null;
		}
		if(!isset($this->youthTeamsPlayers[$teamId][$orderBy]) || $this->youthTeamsPlayers[$teamId][$orderBy] === null)
		{
			$params = array('file'=>'youthplayerlist', 'youthTeamId'=>$teamId, 'version'=>'1.0', 'actionType'=>'list');
			if($orderBy !== null)
			{
				$params['orderBy'] = $orderBy;
			}
			$url = $this->buildUrl($params);
			$this->youthTeamsPlayers[$teamId][$orderBy] = new HTYouthTeamPlayers($this->fetchUrl($url));
		}
		return $this->youthTeamsPlayers[$teamId][$orderBy];
	}

	/**
	 * Delete cache of youth team players data
	 *
	 * @param Integer $teamId
	 */
	public function clearYouthTeamPlayers($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getYouthTeamId();
		}
		$this->youthTeamsPlayers[$teamId] = null;
	}

	/**
	 * Clear cache of all youth teams players
	 */
	public function clearYouthTeamsPlayers()
	{
		$this->youthTeamsPlayers = array();
	}

	/**
	 * Return HTYouthTeamPlayers object
	 *
	 * @param Integer $teamId
	 * @param Boolean $showScoutCall
	 * @param Boolean $showLastMatch
	 * @param String $orderBy
	 * @return HTYouthTeamPlayers
	 */
	public function getYouthTeamPlayersDetails($teamId = null, $showScoutCall = false, $showLastMatch = false, $orderBy = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getYouthTeamId();
		}
		if($teamId === null)
		{
			return null;
		}
		if(!isset($this->youthTeamsPlayersDetails[$teamId][$showScoutCall][$showLastMatch][$orderBy]) || $this->youthTeamsPlayersDetails[$teamId][$showScoutCall][$showLastMatch][$orderBy] === null)
		{
			$params = array('file'=>'youthplayerlist', 'version'=>'1.0', 'actionType'=>'details', 'youthTeamID'=>$teamId);
			if($showScoutCall === true)
			{
				$params['showScoutCall'] = 'true';
			}
			if($showLastMatch === true)
			{
				$params['showLastMatch'] = 'true';
			}
			if($orderBy !== null)
			{
				$params['orderBy'] = $orderBy;
			}
			$url = $this->buildUrl($params);
			$this->youthTeamsPlayersDetails[$teamId][$showScoutCall][$showLastMatch][$orderBy] = new HTYouthTeamPlayers($this->fetchUrl($url));
		}
		return $this->youthTeamsPlayersDetails[$teamId][$showScoutCall][$showLastMatch][$orderBy];
	}

	/**
	 * Clear cache of youth team players details
	 *
	 * @param Integer $teamId
	 */
	public function clearYouthTeamPlayersDetails($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getYouthTeamId();
		}
		$this->youthTeamsPlayersDetails[$teamId] = null;
	}

	/**
	 * Clear cache of youth teams players details
	 */
	public function clearYouthTeamsPlayersDetails()
	{
		$this->youthTeamsPlayersDetails = null;
	}

	/**
	 * Return HTYouthTeamPlayers object
	 *
	 * @param Integer $teamId
	 * @param Boolean $showScoutCall
	 * @param Boolean $showLastMatch
	 * @param String $orderBy
	 * @return HTYouthTeamPlayers
	 */
	public function getYouthTeamPlayersUnlockskills($teamId, $showScoutCall = false, $showLastMatch = false, $orderBy = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getYouthTeamId();
		}
		if($teamId === null)
		{
			return null;
		}
		if(!isset($this->youthTeamsPlayersUnlockskills[$teamId][$showScoutCall][$showLastMatch][$orderBy]) || $this->youthTeamsPlayersUnlockskills[$teamId][$showScoutCall][$showLastMatch][$orderBy] === null)
		{
			$params = array('file'=>'youthplayerlist', 'version'=>'1.0', 'actionType'=>'unlockskills', 'youthTeamID'=>$teamId);
			if($showScoutCall === true)
			{
				$params['showScoutCall'] = 'true';
			}
			if($showLastMatch === true)
			{
				$params['showLastMatch'] = 'true';
			}
			if($orderBy !== null)
			{
				$params['orderBy'] = $orderBy;
			}
			$url = $this->buildUrl($params);
			$this->youthTeamsPlayersUnlockskills[$teamId][$showScoutCall][$showLastMatch][$orderBy] = new HTYouthTeamPlayers($this->fetchUrl($url));
		}
		return $this->youthTeamsPlayersUnlockskills[$teamId][$showScoutCall][$showLastMatch][$orderBy];
	}

	/**
	 * Clear cache of youth teams players unlockskills
	 *
	 * @param Integer $teamId
	 */
	public function clearYouthTeamPlayersUnlockskills($teamId)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getYouthTeamId();
		}
		$this->youthTeamsPlayersUnlockskills[$teamId] = null;
	}

	/**
	 * Clear cache of youth teams players unlockskills
	 */
	public function clearYouthTeamsPlayersUnlockskills()
	{
		$this->youthTeamsPlayersUnlockskills = null;
	}

	/**
	 * Return HTYouthPlayer object
	 *
	 * @param Integer $id
	 * @param Boolean $unlockSkills
	 * @param Boolean $showScoutCall
	 * @param Boolean $showLastMatch
	 * @return HTYouthPlayer
	 */
	public function getYouthPlayer($id, $unlockSkills = false, $showScoutCall = false, $showLastMatch = false)
	{
		if(!isset($this->youthPlayers[$id][$unlockSkills][$showScoutCall][$showLastMatch]) || $this->youthPlayers[$id][$unlockSkills][$showScoutCall][$showLastMatch] === null)
		{
			$params = array('file'=>'youthplayerdetails', 'youthPlayerId'=>$id, 'version'=>'1.0', 'actionType'=>'details');
			if($unlockSkills === true)
			{
				$params['actionType'] = 'unlockskills';
			}
			if($showScoutCall === true)
			{
				$params['showScoutCall'] = 'true';
			}
			if($showLastMatch === true)
			{
				$params['showLastMatch'] = 'true';
			}
			$url = $this->buildUrl($params);
			$this->youthPlayers[$id][$unlockSkills][$showScoutCall][$showLastMatch] = new HTYouthPlayer($this->fetchUrl($url));
		}
		return $this->youthPlayers[$id][$unlockSkills][$showScoutCall][$showLastMatch];
	}

	/**
	 * Delete cache of youth player data
	 *
	 * @param Integer $id
	 */
	public function clearYouthPlayer($id = null)
	{
		$this->youthPlayers[$id] = null;
	}

	/**
	 * Clear cache of all youth players
	 */
	public function clearYouthPlayers()
	{
		$this->youthPlayers = array();
	}

	/**
	 * Return HTTournaments object
	 *
	 * @param Integer $teamId
	 * @return HTTournaments
	 */
	public function getTournaments($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->tournaments[$teamId]) || $this->tournaments[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'tournamentlist', 'teamId'=>$teamId, 'version'=>'1.0'));
			$this->tournaments[$teamId] = new HTTournaments($this->fetchUrl($url));
		}
		return $this->tournaments[$teamId];
	}

	/**
	 * Clear tournaments list of a teamid
	 *
	 * @param Integer $teamId
	 */
	public function clearTournaments($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->tournaments[$teamId] = null;
	}

	/**
	 * Clear all tournaments list
	 */
	public function clearAllTournaments()
	{
		$this->tournaments = array();
	}

	/**
	 * Return HTTournamentLeagueTable object
	 *
	 * @param Integer $tournamentId
	 * @return HTTournamentLeagueTable
	 */
	public function getTournamentLeagueTables($tournamentId)
	{
		if(!isset($this->tournamentLeagues[$tournamentId]) || $this->tournamentLeagues[$tournamentId] === null)
		{
			$url = $this->buildUrl(array('file'=>'tournamentleaguetables', 'tournamentId'=>$tournamentId, 'version'=>'1.0'));
			$this->tournamentLeagues[$tournamentId] = new HTTournamentLeagueTable($this->fetchUrl($url));
		}
		return $this->tournamentLeagues[$tournamentId];
	}

	/**
	 * Clear tournament league tables
	 *
	 * @param Integer $tournamentId
	 */
	public function clearTournamentLeagueTables($tournamentId)
	{
		$this->tournamentLeagues[$tournamentId] = null;
	}

	/**
	 * Clear all tournaments league tables
	 */
	public function clearAllTournamentLeagueTables()
	{
		$this->tournamentLeagues = array();
	}

	/**
	 * Return HTTournamentMatches object
	 *
	 * @param Integer $tournamentId
	 * @return HTTournamentMatches
	 */
	public function getTournamentMatches($tournamentId)
	{
		if(!isset($this->tournamentMatches[$tournamentId]) || $this->tournamentMatches[$tournamentId] === null)
		{
			$url = $this->buildUrl(array('file'=>'tournamentfixtures', 'tournamentId'=>$tournamentId, 'version'=>'1.0'));
			$this->tournamentMatches[$tournamentId] = new HTTournamentMatches($this->fetchUrl($url));
		}
		return $this->tournamentMatches[$tournamentId];
	}

	/**
	 * Clear tournament matches
	 *
	 * @param Integer $tournamentId
	 */
	public function clearTournamentMatches($tournamentId)
	{
		$this->tournamentMatches[$tournamentId] = null;
	}

	/**
	 * Clear all tournaments matches
	 */
	public function clearAllTournamentMatches()
	{
		$this->tournamentMatches = array();
	}

	/**
	 * Return HTTournament object
	 *
	 * @param Integer $tournamentId
	 * @return HTTournament
	 */
	public function getTournament($tournamentId)
	{
		if(!isset($this->tournament[$tournamentId]) || $this->tournament[$tournamentId] === null)
		{
			$url = $this->buildUrl(array('file'=>'tournamentdetails', 'tournamentId'=>$tournamentId, 'version'=>'1.0'));
			$this->tournament[$tournamentId] = new HTTournament($this->fetchUrl($url));
		}
		return $this->tournament[$tournamentId];
	}

	/**
	 * Clear tournament
	 *
	 * @param Integer $tournamentId
	 */
	public function clearTournament($tournamentId)
	{
		$this->tournament[$tournamentId] = null;
	}

	/**
	 * Clear all tournaments
	 */
	public function clearAllTournament()
	{
		$this->tournament = array();
	}

	/**
	 * Return HTTeamLadders object
	 *
	 * @param Integer $teamId
	 * @return HTTeamLadders
	 */
	public function getTeamLadders($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		if(!isset($this->teamsLadders[$teamId]) || $this->teamsLadders[$teamId] === null)
		{
			$url = $this->buildUrl(array('file'=>'ladderlist', 'teamId'=>$teamId, 'version'=>'1.0'));
			$this->teamsLadders[$teamId] = new HTTeamLadders($this->fetchUrl($url));
		}
		return $this->teamsLadders[$teamId];
	}

	/**
	 * Clear team ladders
	 *
	 * @param Integer $tournamentId
	 */
	public function clearTeamLadders($teamId = null)
	{
		if($teamId === null)
		{
			$teamId = $this->getTeam()->getTeamId();
		}
		$this->teamsLadders[$teamId] = null;
	}

	/**
	 * Clear all teams ladders
	 */
	public function clearAllTeamLadders()
	{
		$this->teamsLadders = array();
	}

	/**
	 * Return HTLadder object
	 *
	 * @param Integer $ladderId
	 * @param Integer $pageIndex
	 * @param Integer $pageSisze
	 * @return HTLadder
	 */
	public function getLadder($ladderId, $pageIndex = 0, $pageSize = 25)
	{
		if(!isset($this->ladders[$ladderId][$pageIndex][$pageSize]) || $this->ladders[$ladderId][$pageIndex][$pageSize] === null)
		{
			$url = $this->buildUrl(array('file'=>'ladderdetails', 'ladderId'=>$ladderId, 'version'=>'1.0', 'pageindex'=>$pageIndex, 'pagesize'=>$pageSize));
			$this->ladders[$ladderId][$pageIndex][$pageSize] = new HTLadder($this->fetchUrl($url));
		}
		return $this->ladders[$ladderId][$pageIndex][$pageSize];
	}

	/**
	 * Clear ladder
	 *
	 * @param Integer $ladderId
	 */
	public function clearLadder($ladderId)
	{
		$this->ladders[$ladderId] = null;
	}

	/**
	 * Clear all ladders
	 */
	public function clearLadders()
	{
		$this->ladders = array();
	}

	/**
	 * Return HTLadder object
	 *
	 * @param Integer $ladderId
	 * @return HTLadder
	 */
	public function getLadderTeamPosition($ladderId, $teamId, $pageSize = 25)
	{
		if(!isset($this->laddersTeam[$ladderId][$teamId][$pageSize]) || $this->laddersTeam[$ladderId][$teamId][$pageSize] === null)
		{
			$url = $this->buildUrl(array('file'=>'ladderdetails', 'ladderId'=>$ladderId, 'version'=>'1.0', 'teamId'=>$teamId, 'pagesize'=>$pageSize));
			$this->laddersTeam[$ladderId][$teamId][$pageSize] = new HTLadder($this->fetchUrl($url));
		}
		return $this->laddersTeam[$ladderId][$teamId][$pageSize];
	}

	/**
	 * Clear ladder team position
	 *
	 * @param Integer $ladderId
	 * @param Integer $teamId
	 */
	public function clearLadderTeamPosition($ladderId, $teamId)
	{
		$this->laddersTeam[$ladderId][$teamId] = null;
	}

	/**
	 * Clear all ladders teams positions
	 */
	public function clearLaddersTeamPosition()
	{
		$this->laddersTeam = array();
	}

	/**
	 * Return HTManagerCompendium object
	 *
	 * @param Integer $userId
	 * @return HTManagerCompendium
	 */
	public function getManagerCompendium($userId = null)
	{
		if(!isset($this->compendium[$userId]) || $this->compendium[$userId] === null)
		{
			$params = array('file'=>'managercompendium', 'version'=>'1.0');
			if($userId !== null)
			{
				$params['userId'] = $userId;
			}
			$url = $this->buildUrl($params);
			$this->compendium[$userId] = new HTManagerCompendium($this->fetchUrl($url));
		}
		return $this->compendium[$userId];
	}

	/**
	 * Clear manager compendium
	 *
	 * @param Integer $userId
	 */
	public function clearManagerCompendium($userId = null)
	{
		$this->compendium[$userId] = null;
	}

	/**
	 * Clear all managers compendium
	 */
	public function clearAllManagerCompendium()
	{
		$this->compendium = array();
	}
}
class HTXml
{
	/**
	 * @var DOMDocument
	 */
	protected $xml;
	protected $xmlText;

	/**
	 * Return XML data
	 *
	 * @param Boolean $asObject
	 * @return DOMDocument
	 */
	public function getXml($asObject = true)
	{
		if($this->xml === null || @$this->xml->saveXML() === null)
		{
			$this->xml = new DOMDocument('1.0', 'UTF-8');
			$this->xml->loadXML($this->xmlText);
		}
		if($asObject == true)
		{
			return $this->xml;
		}
		return $this->xml->saveXML();
	}
}
class HTFunction
{
	const PLAYERURL = '/Common/Players/PlayerDetails.aspx?';

	/**
	 * Convert date to a specific format
	 *
	 * @param String $date
	 * @param String $format
	 * @return String
	 */
	public static function convertDate($date, $format)
	{
		return date($format, strtotime($date));
	}

	/**
	 * Change url of player link in match text
	 *
	 * @param String $text
	 * @param String $newUrl
	 * @return String
	 */
	public static function updatePlayerUrl($text, $newUrl)
	{
		return str_replace(self::PLAYERURL , $newUrl, $text);
	}
}
class HTGlobal extends HTXml
{
	protected $userId = null;
	protected $fetchedDate = null;
	protected $xmlFileVersion = null;
	protected $xmlFileName = null;

	/**
	 * Create an instance
	 *
	 * @param String $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml;
		$this->xml = new DOMDocument('1.0', 'UTF-8');
		$this->xml->loadXml($xml);
	}

	/**
	 * Return UserId of connected user
	 *
	 * @return Integer
	 */
	public function getUserId()
	{
		if(!isset($this->userId) || $this->userId === null)
		{
			$this->userId = $this->getXml()->getElementsByTagName('UserID')->item(0)->nodeValue;
		}
		return $this->userId;
	}

	/**
	 * Return fetched date of xml file
	 *
	 * @return String
	 */
	public function getFetchedDate($format = null)
	{
		if(!isset($this->fetchedDate[$format]) || $this->fetchedDate[$format] === null)
		{
			$this->fetchedDate[$format] = $this->getXml()->getElementsByTagName('FetchedDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->fetchedDate[$format] = HTFunction::convertDate($this->fetchedDate[$format], $format);
			}
		}
		return $this->fetchedDate[$format];
	}

	/**
	 * Return version of xml file
	 *
	 * @return Integer
	 */
	public function getXmlFileVersion()
	{
		if(!isset($this->xmlFileVersion) || $this->xmlFileVersion === null)
		{
			$this->xmlFileVersion = $this->getXml()->getElementsByTagName('Version')->item(0)->nodeValue;
		}
		return $this->xmlFileVersion;
	}

	/**
	 * Return name of xml file
	 *
	 * @return String
	 */
	public function getXmlFileName()
	{
		if(!isset($this->xmlFileName) || $this->xmlFileName === null)
		{
			$this->xmlFileName = $this->getXml()->getElementsByTagName('FileName')->item(0)->nodeValue;
		}
		return $this->xmlFileName;
	}
}
class HTCommonSubscriber extends HTCommonTeam
{
	protected $isHtSupporter = null;
	protected $htSupporter = null;
	protected $isSilverSupporter = null;
	protected $isGoldSupporter = null;
	protected $isPlatinumSupporter = null;

	/**
	 * Is the user Hattrick Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporter()
	{
		if(!isset($this->isHtSupporter) || $this->isHtSupporter === null)
		{
			$this->isHtSupporter = $this->getXml()->getElementsByTagName('UserSupporterTier')->item(0)->nodeValue != "";
		}
		return $this->isHtSupporter;
	}

	/**
	 * Is the user Silver Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporterSilver()
	{
		if(!isset($this->isSilverSupporter) || $this->isSilverSupporter === null)
		{
			$this->isSilverSupporter = strtolower($this->getXml()->getElementsByTagName('UserSupporterTier')->item(0)->nodeValue) == "silver";
		}
		return $this->isSilverSupporter;
	}

	/**
	 * Is the user Gold Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporterGold()
	{
		if(!isset($this->isGoldSupporter) || $this->isGoldSupporter === null)
		{
			$this->isGoldSupporter = strtolower($this->getXml()->getElementsByTagName('UserSupporterTier')->item(0)->nodeValue) == "gold";
		}
		return $this->isGoldSupporter;
	}

	/**
	 * Is the user Platinum Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporterPlatinum()
	{
		if(!isset($this->isPlatinumSupporter) || $this->isPlatinumSupporter === null)
		{
			$this->isPlatinumSupporter = strtolower($this->getXml()->getElementsByTagName('UserSupporterTier')->item(0)->nodeValue) == "platinum";
		}
		return $this->isPlatinumSupporter;
	}

	/**
	 * Get Supporter level
	 *
	 * @return String
	 */
	public function getHtSupporterLevel()
	{
		if(!isset($this->htSupporter) || $this->htSupporter === null)
		{
			$this->htSupporter = $this->getXml()->getElementsByTagName('UserSupporterTier')->item(0)->nodeValue;
		}
		return $this->htSupporter;
	}
}
class HTCommonTeam extends HTGlobal
{
	protected $teamId = null;
	protected $teamName = null;

	/**
 	 * Return Team Id of connected user
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$this->teamId = $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
		}
		return $this->teamId;
	}

	/**
	 * Return Team name of connected user
	 *
	 * @return String
	 */
	public function getTeamName()
	{
		if(!isset($this->teamName) || $this->teamName === null)
		{
			$this->teamName = $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
		}
		return $this->teamName;
	}
}
class HTCommonLeague extends HTGlobal
{
	protected $leagueId = null;
	protected $leagueName = null;

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$this->leagueName = $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
		}
		return $this->leagueName;
	}
}
class HTCommonLeagueLevel extends HTGlobal
{
	protected $leagueLevelId = null;
	protected $leagueLevelName = null;

	/**
	 * Return league level id
	 *
	 * @return Integer
	 */
	public function getLeagueLevelId()
	{
		if(!isset($this->leagueLevelId) || $this->leagueLevelId === null)
		{
			$this->leagueLevelId = $this->getXml()->getElementsByTagName('LeagueLevelUnitID')->item(0)->nodeValue;
		}
		return $this->leagueLevelId;
	}

	/**
	 * Return league level name
	 *
	 * @return Integer
	 */
	public function getLeagueLevelName()
	{
		if(!isset($this->leagueLevelName) || $this->leagueLevelName === null)
		{
			$this->leagueLevelName = $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
		}
		return $this->leagueLevelName;
	}
}
class HTCommonAvatar extends HTXml
{
	protected $background = null;
	protected $layerNumber = null;
	protected $layer = null;
	protected $html = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return background image url
	 *
	 * @return String
	 */
	public function getBackground()
	{
		if(!isset($this->background) || $this->background === null)
		{
			$this->background = $this->getXml()->getElementsByTagName('BackgroundImage')->item(0)->nodeValue;
		}
		return $this->background;
	}

	/**
	 * Return player number
	 *
	 * @return Integer
	 */
	public function getLayerNumber()
	{
		if(!isset($this->layerNumber) || $this->layerNumber === null)
		{
			$this->layerNumber = $this->getXml()->getElementsByTagName('Layer')->length;
		}
		return $this->layerNumber	;
	}

	/**
	 * Return HTLayer object
	 *
	 * @return HTLayer
	 */
	public function getLayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getLayerNumber())
		{
			--$index;
			if(!isset($this->layer[$index]) || $this->layer[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//Layer");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->layer[$index] = new HTLayer($node);
			}
			return $this->layer[$index];
		}
		return null;
	}

	/**
	 * Return avatar html code
	 *
	 * @param String $css
	 * @param String $class
	 * @return String
	 */
	public function getHtml($css = null, $class = null)
	{
		if(!isset($this->html[$css][$class]) || $this->html[$css][$class] === null)
		{
			$this->html[$css][$class] = '<div style="position:relative; height:155px; width:110px; background: url(http://www.hattrick.org'.$this->getBackground().'); ';
			if($css !== null)
			{
				$this->html[$css][$class] .= $css;
			}
			$this->html[$css][$class] .= '"';
			if($class !== null)
			{
				$this->html[$css][$class] .= ' class="'.$class.'"';
			}
			$this->html[$css][$class] .= '>';
			for($l=1; $l<=$this->getLayerNumber(); $l++)
			{
				$layer = $this->getLayer($l);
				$img = $layer->getImage();
				if(strpos($img, 'hattrick.org') === false)
				{
					$img = 'http://www.hattrick.org'.$img;
				}
				$this->html[$css][$class] .= '<img src="'.$img.'" style="position:absolute; left:'.$layer->getX().'px; top:'.$layer->getY().'px;"/>';
			}
			$this->html[$css][$class] .= '</div>';
		}
		return $this->html[$css][$class];
	}
}
class HTCheckToken extends HTGlobal
{
	private $token = null;
	private $created = null;
	private $user = null;
	private $expires = null;
	private $permissions = null;

	/**
	 * Return token
	 *
	 * @return String
	 */
	public function getToken()
	{
		if(!isset($this->token) || $this->token === null)
		{
			$this->token = $this->getXml()->getElementsByTagName('Token')->item(0)->nodeValue;
		}
		return $this->token;
	}

	/**
	 * Return creation date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getCreationDate($format = null)
	{
		if(!isset($this->created[$format]) || $this->created[$format] === null)
		{
			$this->created[$format] = $this->getXml()->getElementsByTagName('Created')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->created[$format] = HTFunction::convertDate($this->created[$format], $format);
			}
		}
		return $this->created[$format];
	}

	/**
	 * Return user id
	 *
	 * @return Integer
	 */
	public function getUser()
	{
		if(!isset($this->user) || $this->user === null)
		{
			$this->user = $this->getXml()->getElementsByTagName('User')->item(0)->nodeValue;
		}
		return $this->user;
	}

	/**
	 * Return expiration date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getExpirationDate($format = null)
	{
		if(!isset($this->expires[$format]) || $this->expires[$format] === null)
		{
			$this->expires[$format] = $this->getXml()->getElementsByTagName('Expires')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->expires[$format] = HTFunction::convertDate($this->expires[$format], $format);
			}
		}
		return $this->expires[$format];
	}

	/**
	 * Return granted permissions
	 *
	 * @return String
	 */
	public function getExtendedPermissions()
	{
		if(!isset($this->permissions) || $this->permissions === null)
		{
			$this->permissions = $this->getXml()->getElementsByTagName('ExtendedPermissions')->item(0)->nodeValue;
		}
		return $this->permissions;
	}

	/**
	 * Returns if token is valid or not
	 *
	 * @return Boolean
	 */
	public function isValid()
	{
		return $this->getXml()->getElementsByTagName('Expires')->length != 0;
	}
}
class HTHofPlayers extends HTGlobal
{
	private $playerNumber = null;
	private $player = null;

	/**
	 * Return player number
	 *
	 * @return Integer
	 */
	public function getPlayerNumber()
	{
		if(!isset($this->playerNumber) || $this->playerNumber === null)
		{
			$this->playerNumber = $this->getXml()->getElementsByTagName('Player')->length;
		}
		return $this->playerNumber	;
	}

	/**
	 * Return HTHofPlayer object
	 *
	 * @return HTHofPlayer
	 */
	public function getPlayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getPlayerNumber())
		{
			--$index;
			if(!isset($this->player[$index]) || $this->player[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//Player");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->player[$index] = new HTHofPlayer($node);
			}
			return $this->player[$index];
		}
		return null;
	}
}
class HTHofPlayer extends HTXml
{
	private $playerId = null;
	private $firstName = null;
	private $nickName = null;
	private $lastName = null;
	private $age = null;
	private $nextBirthday = null;
	private $arrivalDate = null;
	private $expertType = null;
	private $hofDate = null;
	private $hofAge = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('PlayerId')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Return player fristname
	 *
	 * @return String
	 */
	public function getFirstName()
	{
		if(!isset($this->firstName) || $this->firstName === null)
		{
			$this->firstName = $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
		}
		return $this->firstName;
	}

	/**
	 * Return player nickname
	 *
	 * @return String
	 */
	public function getNickName()
	{
		if(!isset($this->nickName) || $this->nickName === null)
		{
			$this->nickName = $this->getXml()->getElementsByTagName('NickName')->item(0)->nodeValue;
		}
		return $this->nickName;
	}

	/**
	 * Return player lastname
	 *
	 * @return String
	 */
	public function getLastName()
	{
		if(!isset($this->lastName) || $this->lastName === null)
		{
			$this->lastName = $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
		}
		return $this->lastName;
	}

	/**
	 * Return player age
	 *
	 * @return Integer
	 */
	public function getAge()
	{
		if(!isset($this->age) || $this->age === null)
		{
			$this->age = $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
		}
		return $this->age;
	}

	/**
	 * Return player next birthday date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getNextBirthdayDate($format = null)
	{
		if(!isset($this->nextBirthday[$format]) || $this->nextBirthday[$format] === null)
		{
			$this->nextBirthday[$format] = $this->getXml()->getElementsByTagName('NextBirthday')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->nextBirthday[$format] = HTFunction::convertDate($this->nextBirthday[$format], $format);
			}
		}
		return $this->nextBirthday[$format];
	}

	/**
	 * Return player arrival date in team
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getArrivalDate($format = null)
	{
		if(!isset($this->arrivalDate[$format]) || $this->arrivalDate[$format] === null)
		{
			$this->arrivalDate[$format] = $this->getXml()->getElementsByTagName('ArrivalDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->arrivalDate[$format] = HTFunction::convertDate($this->arrivalDate[$format], $format);
			}
		}
		return $this->arrivalDate[$format];
	}

	/**
	 * Return player expert type
	 *
	 * @return Integer
	 */
	public function getExpertType()
	{
		if(!isset($this->expertType) || $this->expertType === null)
		{
			$this->expertType = $this->getXml()->getElementsByTagName('ExpertType')->item(0)->nodeValue;
		}
		return $this->expertType;
	}

	/**
	 * Return player arrival date in hof
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getHofDate($format = null)
	{
		if(!isset($this->hofDate[$format]) || $this->hofDate[$format] === null)
		{
			$this->hofDate[$format] = $this->getXml()->getElementsByTagName('HofDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->hofDate[$format] = HTFunction::convertDate($this->hofDate[$format], $format);
			}
		}
		return $this->hofDate[$format];
	}

	/**
	 * Return player age when he came in hof
	 *
	 * @return Integer
	 */
	public function getHofAge()
	{
		if(!isset($this->hofAge) || $this->hofAge === null)
		{
			$this->hofAge = $this->getXml()->getElementsByTagName('HofAge')->item(0)->nodeValue;
		}
		return $this->hofAge;
	}
}
class HTAvatars extends HTGlobal
{
	private $teamId = null;
	private $playerNumber = null;
	private $player = null;
	private $playerById = null;

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$this->teamId = $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
		}
		return $this->teamId;
	}

	/**
	 * Return player number
	 *
	 * @return Integer
	 */
	public function getPlayerNumber()
	{
		if(!isset($this->playerNumber) || $this->playerNumber === null)
		{
			$this->playerNumber = $this->getXml()->getElementsByTagName('Player')->length;
		}
		return $this->playerNumber	;
	}

	/**
	 * Return HTPlayerAvatar object
	 *
	 * @return HTPlayerAvatar
	 */
	public function getPlayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getPlayerNumber())
		{
			--$index;
			if(!isset($this->player[$index]) || $this->player[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//Player");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->player[$index] = new HTPlayerAvatar($node);
			}
			return $this->player[$index];
		}
		return null;
	}

	/**
	 * Return HTPlayerAvatar object
	 *
	 * @return HTPlayerAvatar
	 */
	public function getPlayerById($id)
	{
		if(!isset($this->playerById[$id]) || $this->playerById[$id] === null)
		{
			$this->playerById[$id] = null;
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//PlayerID[.='".$id."']");
			if($nodeList->length)
			{
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item(0)->parentNode, true));
				$this->playerById[$id] = new HTPlayerAvatar($node);
			}
		}
		return $this->playerById[$id];
	}
}
class HTPlayerAvatar extends HTCommonAvatar
{
	private $playerId = null;

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}
}
class HTYouthAvatars extends HTGlobal
{
	private $youthTeamId = null;
	private $playerNumber = null;
	private $player = null;
	private $playerById = null;

	/**
	 * Return youth team id
	 *
	 * @return Integer
	 */
	public function getYouthTeamId()
	{
		if(!isset($this->youthTeamId) || $this->youthTeamId === null)
		{
			$this->youthTeamId = $this->getXml()->getElementsByTagName('YouthTeamId')->item(0)->nodeValue;
		}
		return $this->youthTeamId;
	}

	/**
	 * Return youth player number
	 *
	 * @return Integer
	 */
	public function getYouthPlayerNumber()
	{
		if(!isset($this->playerNumber) || $this->playerNumber === null)
		{
			$this->playerNumber = $this->getXml()->getElementsByTagName('YouthPlayer')->length;
		}
		return $this->playerNumber	;
	}

	/**
	 * Return HTYouthPlayerAvatar object
	 *
	 * @return HTYouthPlayerAvatar
	 */
	public function getYouthPlayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getYouthPlayerNumber())
		{
			--$index;
			if(!isset($this->player[$index]) || $this->player[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//YouthPlayer");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->player[$index] = new HTYouthPlayerAvatar($node);
			}
			return $this->player[$index];
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerAvatar object
	 *
	 * @return HTYouthPlayerAvatar
	 */
	public function getYouthPlayerById($id)
	{
		if(!isset($this->playerById[$id]) || $this->playerById[$id] === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//YouthPlayerID[.='".$id."']");
			$node = new DOMDocument('1.0', 'UTF-8');
			$node->appendChild($node->importNode($nodeList->item(0)->parentNode, true));
			$this->playerById[$id] = new HTYouthPlayerAvatar($node);
		}
		return $this->playerById[$id];
	}
}
class HTYouthPlayerAvatar extends HTCommonAvatar
{
	private $playerId = null;

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('YouthPlayerID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}
}
class HTLayer extends HTXml
{
	private $x = null;
	private $y = null;
	private $image = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return X coord
	 *
	 * @return Integer
	 */
	public function getX()
	{
		if(!isset($this->x) || $this->x === null)
		{
			$this->x = $this->getXml()->getElementsByTagName('Layer')->item(0)->getAttribute('x');
		}
		return $this->x;
	}

	/**
	 * Return Y coord
	 *
	 * @return Integer
	 */
	public function getY()
	{
		if(!isset($this->y) || $this->y === null)
		{
			$this->y = $this->getXml()->getElementsByTagName('Layer')->item(0)->getAttribute('y');
		}
		return $this->y;
	}

	/**
	 * Return layer image url
	 *
	 * @return String
	 */
	public function getImage()
	{
		if(!isset($this->image) || $this->image === null)
		{
			$this->image = $this->getXml()->getElementsByTagName('Image')->item(0)->nodeValue;
		}
		return $this->image;
	}
}
class HTClub extends HTCommonTeam
{
	private $specialists = null;
	private $youthSquad = null;

	/**
	 * Return specialists object
	 *
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 * @return HTSpecialists
	 */
	public function getSpecialists($countryCurrency = null)
	{
		if(!isset($this->specialists[$countryCurrency]) || $this->specialists[$countryCurrency] === null)
		{
			$this->specialists[$countryCurrency] = new HTSpecialists($this->getXml(), $countryCurrency);
		}
		return $this->specialists[$countryCurrency];
	}

	/**
	 * Return HTYouthSquad object
	 *
	 * @return HTYouthSquad
	 */
	public function getYouthSquad()
	{
		if(!isset($this->youthSquad) || $this->youthSquad === null)
		{
			$this->youthSquad = new HTYouthSquad($this->getXml());
		}
		return $this->youthSquad;
	}
}
class HTYouthSquad extends HTXml
{
	private $investment;
	private $hasPromoted;
	private $youthLevel;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return invesment sum
	 *
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 * @return Integer
	 */
	public function getInvestment($countryCurrency = null)
	{
		if(!isset($this->investment[$countryCurrency]) || $this->investment[$countryCurrency] === null)
		{
			$this->investment[$countryCurrency] = $this->getXml()->getElementsByTagName('Investment')->item(0)->nodeValue;
			if($countryCurrency !== null)
			{
				$this->investment[$countryCurrency] = HTMoney::convert($this->investment[$countryCurrency], $countryCurrency);
			}
		}
		return $this->investment[$countryCurrency];
	}

	/**
	 * Youth promoted this week ?
	 *
	 * @return Boolean
	 */
	public function hasPromoted()
	{
		if(!isset($this->hasPromoted) || $this->hasPromoted === null)
		{
			$this->hasPromoted = strtolower($this->getXml()->getElementsByTagName('HasPromoted')->item(0)->nodeValue) == "true";
		}
		return $this->hasPromoted;
	}

	/**
	 * Return youth squad level
	 *
	 * @return Integer
	 */
	public function getYouthLevel()
	{
		if(!isset($this->youthLevel) || $this->youthLevel === null)
		{
			$this->youthLevel = $this->getXml()->getElementsByTagName('YouthLevel')->item(0)->nodeValue;
		}
		return $this->youthLevel;
	}
}
class HTSpecialists extends HTXml
{
	private $money = null;
	private $assistantTrainers = null;
	private $assistantTrainersCost = null;
	private $psychologists = null;
	private $psychologistsCost = null;
	private $pressSpokesmen = null;
	private $pressSpokesmenCost = null;
	private $physiotherapists = null;
	private $physiotherapistsCost = null;
	private $doctors = null;
	private $doctorsCost = null;
	private $totalCosts = null;
	const BASE_COST = 18000;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml, $money)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
		$this->money = $money;
	}

	/**
	 * Return number of assistant trainers
	 *
	 * @return Integer
	 */
	public function getAssistantTrainers()
	{
		if(!isset($this->assistantTrainers) || $this->assistantTrainers === null)
		{
			$this->assistantTrainers = $this->getXml()->getElementsByTagName('AssistantTrainers')->item(0)->nodeValue;
		}
		return $this->assistantTrainers;
	}

	/**
	 * Return assistant trainers cost
	 *
	 * @return Integer
	 */
	public function getAssistantTrainersCost()
	{
		if(!isset($this->assistantTrainersCost) || $this->assistantTrainersCost === null)
		{
			$this->assistantTrainersCost = $this->getAssistantTrainers() * self::BASE_COST ;
			if($this->money !== null)
			{
				$this->assistantTrainersCost = HTMoney::convert($this->assistantTrainersCost, $this->money);
			}
		}
		return $this->assistantTrainersCost;
	}

	/**
	 * Return number of psychologists
	 *
	 * @return Integer
	 */
	public function getPsychologists()
	{
		if(!isset($this->psychologists) || $this->psychologists === null)
		{
			$this->psychologists = $this->getXml()->getElementsByTagName('Psychologists')->item(0)->nodeValue;
		}
		return $this->psychologists;
	}

	/**
	 * Return psychologists cost
	 *
	 * @return Integer
	 */
	public function getPsychologistsCost()
	{
		if(!isset($this->psychologistsCost) || $this->psychologistsCost === null)
		{
			$this->psychologistsCost = $this->getPsychologists() * self::BASE_COST ;
			if($this->money !== null)
			{
				$this->psychologistsCost = HTMoney::convert($this->psychologistsCost, $this->money);
			}
		}
		return $this->psychologistsCost;
	}

	/**
	 * Return number of press spokemen
	 *
	 * @return Integer
	 */
	public function getPressSpoken()
	{
		if(!isset($this->pressSpokesmen) || $this->pressSpokesmen === null)
		{
			$this->pressSpokesmen = $this->getXml()->getElementsByTagName('PressSpokesmen')->item(0)->nodeValue;
		}
		return $this->pressSpokesmen;
	}

	/**
	 * Return press spokemen cost
	 *
	 * @return Integer
	 */
	public function getPressSpokenCost()
	{
		if(!isset($this->pressSpokesmenCost) || $this->pressSpokesmenCost === null)
		{
			$this->pressSpokesmenCost = $this->getPressSpoken() * self::BASE_COST ;
			if($this->money !== null)
			{
				$this->pressSpokesmenCost = HTMoney::convert($this->pressSpokesmenCost, $this->money);
			}
		}
		return $this->pressSpokesmenCost;
	}

	/**
	 * Return number of physiotherapists
	 *
	 * @return Integer
	 */
	public function getPhysiotherapists()
	{
		if(!isset($this->physiotherapists) || $this->physiotherapists === null)
		{
			$this->physiotherapists = $this->getXml()->getElementsByTagName('Physiotherapists')->item(0)->nodeValue;
		}
		return $this->physiotherapists;
	}

	/**
	 * Return physiotherapists cost
	 *
	 * @return Integer
	 */
	public function getPhysiotherapistsCost()
	{
		if(!isset($this->physiotherapistsCost) || $this->physiotherapistsCost === null)
		{
			$this->physiotherapistsCost = $this->getPhysiotherapists() * self::BASE_COST ;
			if($this->money !== null)
			{
				$this->physiotherapistsCost = HTMoney::convert($this->physiotherapistsCost, $this->money);
			}
		}
		return $this->physiotherapistsCost;
	}

	/**
	 * Return number of doctors
	 *
	 * @return Integer
	 */
	public function getDoctors()
	{
		if(!isset($this->doctors) || $this->doctors === null)
		{
			$this->doctors = $this->getXml()->getElementsByTagName('Doctors')->item(0)->nodeValue;
		}
		return $this->doctors;
	}

	/**
	 * Return doctors cost
	 *
	 * @return Integer
	 */
	public function getDoctorsCost()
	{
		if(!isset($this->doctorsCost) || $this->doctorsCost === null)
		{
			$this->doctorsCost = $this->getDoctors() * self::BASE_COST ;
			if($this->money !== null)
			{
				$this->doctorsCost = HTMoney::convert($this->doctorsCost, $this->money);
			}
		}
		return $this->doctorsCost;
	}

	/**
	 * Return total specialists costs
	 *
	 * @return Integer
	 */
	public function getTotalCosts()
	{
		if(!isset($this->totalCosts) || $this->totalCosts === null)
		{
			$this->totalCosts = $this->getAssistantTrainersCost()
												+	$this->getPsychologistsCost()
												+	$this->getPressSpokenCost()
												+	$this->getPhysiotherapistsCost()
												+	$this->getDoctorsCost();
		}
		return $this->totalCosts;
	}
}
class HTTeam extends HTCommonTeam
{
	private $languageId = null;
	private $languageName = null;
	private $isHTSup = null;
	private $isSilverSupporter = null;
	private $isGoldSupporter = null;
	private $isPlatinumSupporter = null;
	private $htSupporter = null;
	private $loginName = null;
	private $name = null;
	private $icq = null;
	private $signupDate = null;
	private $activationDate = null;
	private $lastLoginDate = null;
	private $shortTeamName = null;
	private $arenaId = null;
	private $arenaName = null;
	private $leagueId = null;
	private $leagueName = null;
	private $regionId = null;
	private $regionName = null;
	private $trainerId = null;
	private $homePageUrl = null;
	private $dress1 = null;
	private $dress2 = null;
	private $isBot = null;
	private $botDate = null;
	private $isInCup = null;
	private $cupId = null;
	private $cupName = null;
	private $friendlyTeamId = null;
	private $leagueLevelAvailable = null;
	private $leagueLevelId = null;
	private $leagueLevelName = null;
	private $leagueLevel = null;
	private $numberVictories = null;
	private $numberVictoriesAvailable = null;
	private $numberUndefeatAvailable = null;
	private $numberUndefeat = null;
	private $teamRank = null;
	private $logoUrl = null;
	private $fanClubId = null;
	private $fanClubName = null;
	private $fanClubSize = null;
	private $numberGuestbookMessages = null;
	private $pressSubject = null;
	private $pressText = null;
	private $pressDate = null;
	private $youthTeamId = null;
	private $youthTeamName = null;
	private $nationalTeam = null;
	private $nationalTeamsNumber = null;
	private $trophyNumber = null;
	private $trophy = null;
	private $isDeleted = null;
	private $primary = null;
	private $secondary = null;
	private $foundedDate = null;
	private $color = null;
	private $bgcolor = null;

	public function __construct($xml, $id = null)
	{
		parent::__construct($xml);
		if($this->xml->getElementsByTagName('Team')->length == 2)
		{
			$teams = $this->xml->getElementsByTagName('Team');
			if($id === null)
			{
				for($t=0; $t<$teams->length; $t++)
				{
					$txml = new DOMDocument('1.0', 'UTF-8');
					$txml->appendChild($txml->importNode($teams->item($t), true));
					if(strtolower($txml->getElementsByTagName('IsPrimaryClub')->item(0)->nodeValue) == 'false')
					{
						$this->xml->getElementsByTagName('Teams')->item(0)->removeChild($teams->item($t));
					}
				}
			}
			else
			{
				for($t=0; $t<$teams->length; $t++)
				{
					$txml = new DOMDocument('1.0', 'UTF-8');
					$txml->appendChild($txml->importNode($teams->item($t), true));
					if($txml->getElementsByTagName('TeamID')->item(0)->nodeValue != $id)
					{
						$this->xml->getElementsByTagName('Teams')->item(0)->removeChild($teams->item($t));
					}
				}
			}
			$this->xmlText = $this->xml->saveXML();
		}
	}

	/**
 	 * Return Team Id of connected user
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!$this->isDeleted())
		{
			return parent::getTeamId();
		}
		return null;
	}

	/**
	 * Return Team name of connected user
	 *
	 * @return String
	 */
	public function getTeamName()
	{
		if(!$this->isDeleted())
		{
			return parent::getTeamName();
		}
		return null;
	}

	/**
	 * Return UserId of connected user
	 *
	 * @return Integer
	 */
	public function getUserId()
	{
		if(!isset($this->userId) || $this->userId === null)
		{
			$this->userId = $this->getXml()->getElementsByTagName('UserID')->item(1)->nodeValue;
		}
		return $this->userId;
	}

	/**
	 * Return team user's language id
	 *
	 * @return Integer
	 */
	public function getLanguageId()
	{
		if(!isset($this->languageId) || $this->languageId === null)
		{
			$this->languageId = $this->getXml()->getElementsByTagName('LanguageID')->item(0)->nodeValue;
		}
		return $this->languageId;
	}

	/**
	 * Return team user's language name
	 *
	 * @return String
	 */
	public function getLanguageName()
	{
		if(!isset($this->languageName) || $this->languageName === null)
		{
			$this->languageName = $this->getXml()->getElementsByTagName('LanguageName')->item(0)->nodeValue;
		}
		return $this->languageName;
	}

	/**
	 * Is the user HT-Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporter()
	{
		if(!isset($this->isHTSup) || $this->isHTSup === null)
		{
			$this->isHTSup = $this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue != "";
		}
		return $this->isHTSup;
	}

	/**
	 * Is the user Silver Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporterSilver()
	{
		if(!isset($this->isSilverSupporter) || $this->isSilverSupporter === null)
		{
			$this->isSilverSupporter = strtolower($this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue) == "silver";
		}
		return $this->isSilverSupporter;
	}

	/**
	 * Is the user Gold Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporterGold()
	{
		if(!isset($this->isGoldSupporter) || $this->isGoldSupporter === null)
		{
			$this->isGoldSupporter = strtolower($this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue) == "gold";
		}
		return $this->isGoldSupporter;
	}

	/**
	 * Is the user Platinum Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporterPlatinum()
	{
		if(!isset($this->isPlatinumSupporter) || $this->isPlatinumSupporter === null)
		{
			$this->isPlatinumSupporter = strtolower($this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue) == "platinum";
		}
		return $this->isPlatinumSupporter;
	}

	/**
	 * Get Supporter level
	 *
	 * @return String
	 */
	public function getHtSupporterLevel()
	{
		if(!isset($this->htSupporter) || $this->htSupporter === null)
		{
			$this->htSupporter = $this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue;
		}
		return $this->htSupporter;
	}

	/**
	 * Return user public loginname
	 *
	 * @return String
	 */
	public function getLoginName()
	{
		if(!isset($this->loginName) || $this->loginName === null)
		{
			$this->loginName = $this->getXml()->getElementsByTagName('Loginname')->item(0)->nodeValue;
		}
		return $this->loginName;
	}

	/**
	 * Return irl user name if he made it public
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('Name')->item(0)->nodeValue;
		}
		if($this->name == 'HIDDEN')
		{
			return null;
		}
		return $this->loginName;
	}

	/**
	 * Return user icq number
	 *
	 * @return String
	 */
	public function getIcq()
	{
		if(!isset($this->icq) || $this->icq === null)
		{
			$this->icq = $this->getXml()->getElementsByTagName('ICQ')->item(0)->nodeValue;
		}
		return $this->loginName;
	}

	/**
	 * Return signup date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getSignupDate($format = null)
	{
		if(!isset($this->signupDate[$format]) || $this->signupDate[$format] === null)
		{
			$this->signupDate[$format] = $this->getXml()->getElementsByTagName('SignupDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->signupDate[$format] = HTFunction::convertDate($this->signupDate[$format], $format);
			}
		}
		return $this->signupDate[$format];
	}

	/**
	 * Return activation date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getActivationDate($format = null)
	{
		if(!isset($this->activationDate[$format]) || $this->activationDate[$format] === null)
		{
			$this->activationDate[$format] = $this->getXml()->getElementsByTagName('ActivationDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->activationDate[$format] = HTFunction::convertDate($this->activationDate[$format], $format);
			}
		}
		return $this->activationDate[$format];
	}

	/**
	 * Return team founded date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getFoundedDate($format = null)
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->foundedDate[$format]) || $this->foundedDate[$format] === null)
			{
				$this->foundedDate[$format] = $this->getXml()->getElementsByTagName('FoundedDate')->item(0)->nodeValue;
				if($format !== null)
				{
					$this->foundedDate[$format] = HTFunction::convertDate($this->foundedDate[$format], $format);
				}
			}
			return $this->foundedDate[$format];
		}
		return null;
	}

	/**
	 * Return last login date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getLastLoginDate($format = null)
	{
		if(!isset($this->lastLoginDate[$format]) || $this->lastLoginDate[$format] === null)
		{
			$this->lastLoginDate[$format] = $this->getXml()->getElementsByTagName('LastLoginDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->lastLoginDate[$format] = HTFunction::convertDate($this->lastLoginDate[$format], $format);
			}
		}
		return $this->lastLoginDate[$format];
	}

	/**
	 * Return short team nam
	 *
	 * @return String
	 */
	public function getShortTeamName()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->shortTeamName) || $this->shortTeamName === null)
			{
				$this->shortTeamName = $this->getXml()->getElementsByTagName('ShortTeamName')->item(0)->nodeValue;
			}
			return $this->shortTeamName;
		}
		return null;
	}

	/**
	 * Return arena id
	 *
	 * @return Integer
	 */
	public function getArenaId()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->arenaId) || $this->arenaId === null)
			{
				$this->arenaId = $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
			}
			return $this->arenaId;
		}
		return null;
	}

	/**
	 * Return arena name
	 *
	 * @return String
	 */
	public function getArenaName()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->arenaName) || $this->arenaName === null)
			{
				$this->arenaName = $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
			}
			return $this->arenaName;
		}
		return null;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->leagueId) || $this->leagueId === null)
			{
				$this->leagueId = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
			}
			return $this->leagueId;
		}
		return null;
	}

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->leagueName) || $this->leagueName === null)
			{
				$this->leagueName = $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
			}
			return $this->leagueName;
		}
		return null;
	}

	/**
	 * Return region id
	 *
	 * @return Integer
	 */
	public function getRegionId()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->regionId) || $this->regionId === null)
			{
				$this->regionId = $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
			}
			return $this->regionId;
		}
		return null;
	}

	/**
	 * Return region name
	 *
	 * @return String
	 */
	public function getRegionName()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->regionName) || $this->regionName === null)
			{
				$this->regionName = $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
			}
			return $this->regionName;
		}
		return null;
	}

	/**
	 * Return trainer id
	 *
	 * @return Integer
	 */
	public function getTrainerId()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->trainerId) || $this->trainerId === null)
			{
				$this->trainerId = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
			}
			return $this->trainerId;
		}
		return null;
	}

	/**
	 * Return home page url
	 *
	 * @return String
	 */
	public function getHomePageUrl()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->homePageUrl) || $this->homePageUrl === null)
			{
				$this->homePageUrl = $this->getXml()->getElementsByTagName('HomePage')->item(0)->nodeValue;
				if(substr($this->homePageUrl, 0, 7) !== 'http://')
				{
					$this->homePageUrl = 'http://'.$this->homePageUrl;
				}
			}
			return $this->homePageUrl;
		}
		return null;
	}

	/**
	 * Return url of main dress
	 *
	 * @return String
	 */
	public function getDressURI()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->dress1) || $this->dress1 === null)
			{
				$this->dress1 = $this->getXml()->getElementsByTagName('DressURI')->item(0)->nodeValue;
			}
			return $this->dress1;
		}
		return null;
	}

	/**
	 * Return url of alternate dress
	 *
	 * @return String
	 */
	public function getDressAlternateURI()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->dress2) || $this->dress2 === null)
			{
				$this->dress2 = $this->getXml()->getElementsByTagName('DressAlternateURI')->item(0)->nodeValue;
			}
			return $this->dress2;
		}
		return null;
	}

	/**
	 * Is the team a bot ?
	 *
	 * @return Boolean
	 */
	public function isBot()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->isBot) || $this->isBot === null)
			{
				$this->isBot = strtolower($this->getXml()->getElementsByTagName('IsBot')->item(0)->nodeValue) == "true";
			}
			return $this->isBot;
		}
		return null;
	}

	/**
	 * Return date when team became bot, if it's a bot
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getBotDate($format = null)
	{
		if(!$this->isDeleted())
		{
			if($this->isBot())
			{
				if(!isset($this->botDate[$format]) || $this->botDate[$format] === null)
				{
					$this->botDate[$format] = $this->getXml()->getElementsByTagName('BotSince')->item(0)->nodeValue;
					if($format !== null)
					{
						$this->botDate[$format] = HTFunction::convertDate($this->botDate[$format], $format);
					}
				}
				return $this->botDate[$format];
			}
		}
		return null;
	}

	/**
	 * Is the team still in cup ?
	 *
	 * @return Boolean
	 */
	public function isInCup()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->isInCup) || $this->isInCup === null)
			{
				if($this->getXml()->getElementsByTagName('StillInCup')->length)
				{
					$this->isInCup = strtolower($this->getXml()->getElementsByTagName('StillInCup')->item(0)->nodeValue) == "true";
				}
			}
			return $this->isInCup;
		}
		return null;
	}

	/**
	 * Return cup id
	 *
	 * @return Integer
	 */
	public function getCupId()
	{
		if(!$this->isDeleted())
		{
			if($this->isInCup())
			{
				if(!isset($this->cupId) || $this->cupId === null)
				{
					$this->cupId = $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
				}
				return $this->cupId;
			}
		}
		return null;
	}

	/**
	 * Return cup name
	 *
	 * @return String
	 */
	public function getCupName()
	{
		if(!$this->isDeleted())
		{
			if($this->isInCup())
			{
				if(!isset($this->cupName) || $this->cupName === null)
				{
					$this->cupName = $this->getXml()->getElementsByTagName('CupName')->item(0)->nodeValue;
				}
				return $this->cupName;
			}
		}
		return null;
	}

	/**
	 * Is League data available ?
	 *
	 * @return Boolean
	 */
	public function isLeagueLevelAvailable()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->leagueLevelAvailable) || $this->leagueLevelAvailable === null)
			{
				$this->leagueLevelAvailable = $this->getXml()->getElementsByTagName('LeagueLevelUnit')->item(0)->hasChildNodes();
			}
			return $this->leagueLevelAvailable;
		}
		return null;
	}

	/**
	 * Return league level
	 *
	 * @return Integer
	 */
	public function getLeagueLevel()
	{
		if(!$this->isDeleted())
		{
			if($this->isLeagueLevelAvailable())
			{
				if(!isset($this->leagueLevel) || $this->leagueLevel === null)
				{
					$this->leagueLevel = $this->getXml()->getElementsByTagName('LeagueLevel')->item(0)->nodeValue;
				}
				return $this->leagueLevel;
			}
		}
		return null;
	}

	/**
	 * Return league level id
	 *
	 * @return Integer
	 */
	public function getLeagueLevelId()
	{
		if(!$this->isDeleted())
		{
			if($this->isLeagueLevelAvailable())
			{
				if(!isset($this->leagueLevelId) || $this->leagueLevelId === null)
				{
					$this->leagueLevelId = $this->getXml()->getElementsByTagName('LeagueLevelUnitID')->item(0)->nodeValue;
				}
				return $this->leagueLevelId;
			}
		}
		return null;
	}

	/**
	 * Return league level name
	 *
	 * @return Integer
	 */
	public function getLeagueLevelName()
	{
		if(!$this->isDeleted())
		{
			if($this->isLeagueLevelAvailable())
			{
				if(!isset($this->leagueLevelName) || $this->leagueLevelName === null)
				{
					$this->leagueLevelName = $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
				}
				return $this->leagueLevelName;
			}
		}
		return null;
	}

	/**
	 * Return opposite team id for friendly match
	 *
	 * @return Integer
	 */
	public function getFriendlyOppositeTeamId()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->friendlyTeamId) || $this->friendlyTeamId === null)
			{
				$this->friendlyTeamId = $this->getXml()->getElementsByTagName('FriendlyTeamID')->item(0)->nodeValue;
			}
			if($this->friendlyTeamId == 0)
			{
				return null;
			}
			return $this->friendlyTeamId;
		}
		return null;
	}

	/**
	 * Return number of consecutive victories
	 *
	 * @return Integer
	 */
	public function getNumberOfVictories()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->numberVictories) || $this->numberVictories === null)
			{
				$this->numberVictories = $this->getXml()->getElementsByTagName('NumberOfVictories')->item(0)->nodeValue;
			}
			return $this->numberVictories;
		}
		return null;
	}

	/**
	 * Return number of consecutive undefeated match
	 *
	 * @return Integer
	 */
	public function getNumberOfUndefeat()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->numberUndefeat) || $this->numberUndefeat === null)
			{
				$this->numberUndefeat = $this->getXml()->getElementsByTagName('NumberOfUndefeated')->item(0)->nodeValue;
			}
			return $this->numberUndefeat;
		}
		return null;
	}

	/**
	 * Return team rank
	 *
	 * @return Integer
	 */
	public function getTeamRank()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->teamRank) || $this->teamRank === null)
			{
				$this->teamRank = $this->getXml()->getElementsByTagName('TeamRank')->item(0)->nodeValue;
			}
			return $this->teamRank;
		}
		return null;
	}

	/**
	 * Return logo url
	 *
	 * @return String
	 */
	public function getLogoUrl()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->logoUrl) || $this->logoUrl === null)
			{
				$url = $this->getXml()->getElementsByTagName('LogoURL')->item(0)->nodeValue;
				$this->logoUrl = str_replace("\\", "/", $url);
			}
			return $this->logoUrl;
		}
		return null;
	}

	/**
	 * Return fan club id
	 *
	 * @return Integer
	 */
	public function getFanClubId()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->fanClubId) || $this->fanClubId === null)
			{
				$this->fanClubId = $this->getXml()->getElementsByTagName('FanclubID')->item(0)->nodeValue;
			}
			return $this->fanClubId;
		}
		return null;
	}

	/**
	 * Return fan club name
	 *
	 * @return String
	 */
	public function getFanClubName()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->fanClubName) || $this->fanClubName === null)
			{
				$this->fanClubName = $this->getXml()->getElementsByTagName('FanclubName')->item(0)->nodeValue;
			}
			return $this->fanClubName;
		}
		return null;
	}

	/**
	 * Return fan club size
	 *
	 * @return Integer
	 */
	public function getFanClubSize()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->fanClubSize) || $this->fanClubSize === null)
			{
				$this->fanClubSize = $this->getXml()->getElementsByTagName('FanclubSize')->item(0)->nodeValue;
			}
			return $this->fanClubSize;
		}
		return null;
	}

	/**
	 * Return number of messages in user guestbook
	 *
	 * @return Integer
	 */
	public function getNumberMessageInGuestbook()
	{
		if(!$this->isDeleted())
		{
			if($this->isHtSupporter())
			{
				if(!isset($this->numberGuestbookMessages) || $this->numberGuestbookMessages === null)
				{
					$this->numberGuestbookMessages = $this->getXml()->getElementsByTagName('NumberOfGuestbookItems')->item(0)->nodeValue;
				}
				return $this->numberGuestbookMessages;
			}
		}
		return null;
	}

	/**
	 * Return title of last press announcement
	 *
	 * @return String
	 */
	public function getPressAnnouncementTitle()
	{
		if(!$this->isDeleted())
		{
			if($this->isHtSupporter())
			{
				if(!isset($this->pressSubject) || $this->pressSubject === null)
				{
					$node = $this->getXml()->getElementsByTagName('Subject');
					if($node !== null && $node->length)
					{
						$this->pressSubject = $node->item(0)->nodeValue;
					}
					else
					{
						$this->pressSubject = false;
					}
				}
				return $this->pressSubject;
			}
		}
		return null;
	}

	/**
	 * Return content of last press announcement
	 *
	 * @return String
	 */
	public function getPressAnnouncementText()
	{
		if(!$this->isDeleted())
		{
			if($this->isHtSupporter())
			{
				if(!isset($this->pressText) || $this->pressText === null)
				{
					$node = $this->getXml()->getElementsByTagName('Body');
					if($node !== null && $node->length)
					{
						$this->pressText = $node->item(0)->nodeValue;
					}
					else
					{
						$this->pressText = false;
					}
				}
				return $this->pressText;
			}
		}
		return null;
	}

	/**
	 * Return content of last press announcement
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getPressAnnouncementDate($format = null)
	{
		if(!$this->isDeleted())
		{
			if($this->isHtSupporter())
			{
				if(!isset($this->pressDate[$format]) || $this->pressDate[$format] === null)
				{
					$node = $this->getXml()->getElementsByTagName('SendDate');
					if($node !== null && $node->length)
					{
						$this->pressDate[$format] = $node->item(0)->nodeValue;
						if($format !== null)
						{
							$this->pressDate[$format] = HTFunction::convertDate($this->pressDate[$format], $format);
						}
					}
					else
					{
						$this->pressDate[$format] = false;
					}
				}
				return $this->pressDate[$format];
			}
		}
		return null;
	}

	/**
	 * Return youth team id
	 *
	 * @return Integer
	 */
	public function getYouthTeamId()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->youthTeamId) || $this->youthTeamId === null)
			{
				$this->youthTeamId = $this->getXml()->getElementsByTagName('YouthTeamID')->item(0)->nodeValue;
			}
			if($this->youthTeamId == 0)
			{
				return null;
			}
			return $this->youthTeamId;
		}
		return null;
	}

	/**
	 * Return youth team name
	 *
	 * @return String
	 */
	public function getYouthTeamName()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->youthTeamName) || $this->youthTeamName === null)
			{
				$this->youthTeamName = $this->getXml()->getElementsByTagName('YouthTeamName')->item(0)->nodeValue;
			}
			return $this->youthTeamName;
		}
		return null;
	}

	/**
	 * Return number of national team coached
	 *
	 * @return Integer
	 */
	public function getNationalTeamNumber()
	{
		if(!isset($this->nationalTeamNumber) || $this->nationalTeamNumber === null)
		{
			$this->nationalTeamNumber = $this->getXml()->getElementsByTagName('NationalTeam')->length;
		}
		return $this->nationalTeamNumber;
	}

	/**
	 * Return HTTeamNationalTeam object
	 *
	 * @return HTTeamNationalTeam
	 */
	public function getNationalTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNationalTeamNumber())
		{
			--$index;
			if(!isset($this->nationalTeam[$index]) || $this->nationalTeam[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//NationalTeam");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->nationalTeam[$index] = new HTTeamNationalTeam($node);
			}
			return $this->nationalTeam[$index];
		}
		return null;
	}

	/**
	 * Return trophy number
	 *
	 * @return Integer
	 */
	public function getTrophyNumber()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->trophyNumber) || $this->trophyNumber === null)
			{
				$this->trophyNumber = $this->getXml()->getElementsByTagName('Trophy')->length;
			}
			return $this->trophyNumber;
		}
		return null;
	}

	/**
	 * Return HTTrophy object
	 *
	 * @return HTTrophy
	 */
	public function getTrophy($index)
	{
		if(!$this->isDeleted())
		{
			$index = round($index);
			if($index > 0 && $index <= $this->getTrophyNumber())
			{
				--$index;
				if(!isset($this->trophy[$index]) || $this->trophy[$index] === null)
				{
					$xpath = new DOMXPath($this->getXml());
					$nodeList = $xpath->query("//Trophy");
					$node = new DOMDocument('1.0', 'UTF-8');
					$node->appendChild($node->importNode($nodeList->item($index), true));
					$this->trophy[$index] = new HTTrophy($node);
				}
				return $this->trophy[$index];
			}
		}
		return null;
	}

	/**
	 * Return if team does not exist anymore and is not a bot
	 *
	 * @return Boolean
	 */
	public function isDeleted()
	{
		if(!isset($this->isDeleted) || $this->isDeleted === null)
		{
			$this->isDeleted = $this->getXml()->getElementsByTagName('Team')->length == 0;
		}
		return $this->isDeleted;
	}

	/**
	 * Return if team is primary team
	 *
	 * @return Boolean
	 */
	public function isPrimaryTeam()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->primary) || $this->primary === null)
			{
				$this->primary = strtolower($this->getXml()->getElementsByTagName('IsPrimaryClub')->item(0)->nodeValue) == 'true';
			}
			return $this->primary;
		}
		return null;
	}

	/**
	 * Return if team is secondary team
	 *
	 * @return Boolean
	 */
	public function isSecondaryTeam()
	{
		if(!$this->isDeleted())
		{
			if(!isset($this->secondary) || $this->secondary === null)
			{
				$this->secondary = strtolower($this->getXml()->getElementsByTagName('IsPrimaryClub')->item(0)->nodeValue) == 'false';
			}
			return $this->secondary;
		}
		return null;
	}

	/**
	 * Return supporter color
	 *
	 * @return String
	 */
	public function getHtSupporterColor()
	{
		if(!$this->isDeleted())
		{
			if($this->isHtSupporter())
			{
				if(!isset($this->color) || $this->color === null)
				{
					$this->color = $this->getXml()->getElementsByTagName('Color')->item(0)->nodeValue;
				}
				return $this->color;
			}
		}
		return null;
	}

	/**
	 * Return supporter background color
	 *
	 * @return String
	 */
	public function getHtSupporterBackgroundColor()
	{
		if(!$this->isDeleted())
		{
			if($this->isHtSupporter())
			{
				if(!isset($this->bgcolor) || $this->bgcolor === null)
				{
					$this->bgcolor = $this->getXml()->getElementsByTagName('BackgroundColor')->item(0)->nodeValue;
				}
				return $this->bgcolor;
			}
		}
		return null;
	}
}
class HTYouthTeam extends HTGlobal
{
	private $id = null;
	private $name = null;
	private $shortName = null;
	private $date = null;
	private $countryId = null;
	private $countryName = null;
	private $regionId = null;
	private $regionName = null;
	private $arenaId = null;
	private $arenaName = null;
	private $leagueId = null;
	private $leagueName = null;
	private $leagueStatus = null;
	private $motherId = null;
	private $motherName = null;
	private $trainerId = null;
	private $nextMatchDate = null;

	/**
	 * Returns if youth team exist
	 *
	 * @return Boolean
	 */
	public function isValid()
	{
		return $this->getId() != 0;
	}

	/**
	 * Return youth team id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('YouthTeamID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return youth team name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('YouthTeamName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return youth team short name
	 *
	 * @return String
	 */
	public function getShortName()
	{
		if(!isset($this->shortName) || $this->shortName === null)
		{
			$this->shortName = $this->getXml()->getElementsByTagName('ShortTeamName')->item(0)->nodeValue;
		}
		return $this->shortName;
	}

	/**
	 * Return creation date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getCreationDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('CreatedDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return country id
	 *
	 * @return Integer
	 */
	public function getCountryId()
	{
		if(!isset($this->countryId) || $this->countryId === null)
		{
			$this->countryId = $this->getXml()->getElementsByTagName('CountryID')->item(0)->nodeValue;
		}
		return $this->countryId;
	}

	/**
	 * Return country name
	 *
	 * @return String
	 */
	public function getCountryName()
	{
		if(!isset($this->countryName) || $this->countryName === null)
		{
			$this->countryName = $this->getXml()->getElementsByTagName('CountryName')->item(0)->nodeValue;
		}
		return $this->countryName;
	}

	/**
	 * Return region id
	 *
	 * @return Integer
	 */
	public function getRegionId()
	{
		if(!isset($this->regionId) || $this->regionId === null)
		{
			$this->regionId = $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
		}
		return $this->regionId;
	}

	/**
	 * Return region name
	 *
	 * @return String
	 */
	public function getRegionName()
	{
		if(!isset($this->regionName) || $this->regionName === null)
		{
			$this->regionName = $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
		}
		return $this->regionName;
	}

	/**
	 * Return arena id
	 *
	 * @return Integer
	 */
	public function getArenaId()
	{
		if(!isset($this->arenaId) || $this->arenaId === null)
		{
			$this->arenaId = $this->getXml()->getElementsByTagName('YouthArenaID')->item(0)->nodeValue;
		}
		return $this->arenaId;
	}

	/**
	 * Return arena name
	 *
	 * @return String
	 */
	public function getArenaName()
	{
		if(!isset($this->arenaName) || $this->arenaName === null)
		{
			$this->arenaName = $this->getXml()->getElementsByTagName('YouthArenaName')->item(0)->nodeValue;
		}
		return $this->arenaName;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if($this->isValid())
		{
			if(!isset($this->leagueId) || $this->leagueId === null)
			{
				$this->leagueId = $this->getXml()->getElementsByTagName('YouthLeagueID')->item(0)->nodeValue;
			}
			return $this->leagueId;
		}
		return null;
	}

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if($this->isValid())
		{
			if(!isset($this->leagueName) || $this->leagueName === null)
			{
				$this->leagueName = $this->getXml()->getElementsByTagName('YouthLeagueName')->item(0)->nodeValue;
			}
			return $this->leagueName;
		}
		return null;
	}

	/**
	 * Return league status
	 *
	 * @return Integer
	 */
	public function getLeagueStatus()
	{
		if($this->isValid())
		{
			if(!isset($this->leagueStatus) || $this->leagueStatus === null)
			{
				$this->leagueStatus = $this->getXml()->getElementsByTagName('YouthLeagueStatus')->item(0)->nodeValue;
			}
			return $this->leagueStatus;
		}
		return null;
	}

	/**
	 * Return mother team id
	 *
	 * @return Integer
	 */
	public function getMotherTeamId()
	{
		if($this->isValid())
		{
			if(!isset($this->motherId) || $this->motherId === null)
			{
				$this->motherId = $this->getXml()->getElementsByTagName('MotherTeamID')->item(0)->nodeValue;
			}
			return $this->motherId;
		}
		return null;
	}

	/**
	 * Return mother team name
	 *
	 * @return String
	 */
	public function getMotherTeamName()
	{
		if($this->isValid())
		{
			if(!isset($this->motherName) || $this->motherName === null)
			{
				$this->motherName = $this->getXml()->getElementsByTagName('MotherTeamName')->item(0)->nodeValue;
			}
			return $this->motherName;
		}
		return null;
	}

	/**
	 * Return trainer id
	 *
	 * @return Integer
	 */
	public function getTrainerId()
	{
		if(!isset($this->trainerId) || $this->trainerId === null)
		{
			$this->trainerId = $this->getXml()->getElementsByTagName('YouthPlayerID')->item(0)->nodeValue;
		}
		return $this->trainerId;
	}

	/**
	 * Return next training match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getNextTrainingMatchDate($format = null)
	{
		if(!isset($this->nextMatchDate[$format]) || $this->nextMatchDate[$format] === null)
		{
			$this->nextMatchDate[$format] = $this->getXml()->getElementsByTagName('NextTrainingMatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->nextMatchDate[$format] = HTFunction::convertDate($this->nextMatchDate[$format], $format);
			}
		}
		return $this->nextMatchDate[$format];
	}
}
class HTYouthScouts extends HTGlobal
{
	private $scoutNumber = null;
	private $scouts = null;

	/**
	 * Return number of scout
	 *
	 * @return Integer
	 */
	public function getScoutNumber()
	{
		if(!isset($this->scoutNumber) || $this->scoutNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//Scout");
			$this->scoutNumber = $nodeList->length;
		}
		return $this->scoutNumber;
	}

	/**
	 * Return HTYouthScout object
	 *
	 * @param Integer $number
	 * @return HTYouthScout
	 */
	public function getScout($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getScoutNumber())
		{
			--$number;
			if(!isset($this->scouts[$number]) || $this->scouts[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Scout');
				$scout = new DOMDocument('1.0', 'UTF-8');
				$scout->appendChild($scout->importNode($nodeList->item($number), true));
				$this->scouts[$number] = new HTYouthScout($scout);
			}
			return $this->scouts[$number];
		}
		return null;
	}
}
class HTYouthScout extends HTXml
{
	private $id = null;
	private $name = null;
	private $age = null;
	private $countryId = null;
	private $countryName = null;
	private $regionId = null;
	private $regionName = null;
	private $inCountryId = null;
	private $inCountryName = null;
	private $inRegionId = null;
	private $inRegionName = null;
	private $hiredDate = null;
	private $lastCall = null;
	private $typeSearch = null;
	private $travel = null;
	private $travelDate = null;
	private $travelLength = null;
	private $travelType = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return scout id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('YouthScoutID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return scout name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('ScoutName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return scout age
	 *
	 * @return Integer
	 */
	public function getAge()
	{
		if(!isset($this->age) || $this->age === null)
		{
			$this->age = $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
		}
		return $this->age;
	}

	/**
	 * Return country id
	 *
	 * @return Integer
	 */
	public function getCountryId()
	{
		if(!isset($this->countryId) || $this->countryId === null)
		{
			$this->countryId = $this->getXml()->getElementsByTagName('CountryID')->item(0)->nodeValue;
		}
		return $this->countryId;
	}

	/**
	 * Return country name
	 *
	 * @return String
	 */
	public function getCountryName()
	{
		if(!isset($this->countryName) || $this->countryName === null)
		{
			$this->countryName = $this->getXml()->getElementsByTagName('CountryName')->item(0)->nodeValue;
		}
		return $this->countryName;
	}

	/**
	 * Return region id
	 *
	 * @return Integer
	 */
	public function getRegionId()
	{
		if(!isset($this->regionId) || $this->regionId === null)
		{
			$this->regionId = $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
		}
		return $this->regionId;
	}

	/**
	 * Return region name
	 *
	 * @return String
	 */
	public function getRegionName()
	{
		if(!isset($this->regionName) || $this->regionName === null)
		{
			$this->regionName = $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
		}
		return $this->regionName;
	}

	/**
	 * Return in country id
	 *
	 * @return Integer
	 */
	public function getInCountryId()
	{
		if(!isset($this->inCountryId) || $this->inCountryId === null)
		{
			$this->inCountryId = $this->getXml()->getElementsByTagName('CountryID')->item(1)->nodeValue;
		}
		return $this->inCountryId;
	}

	/**
	 * Return in country name
	 *
	 * @return String
	 */
	public function getInCountryName()
	{
		if(!isset($this->inCountryName) || $this->inCountryName === null)
		{
			$this->inCountryName = $this->getXml()->getElementsByTagName('CountryName')->item(1)->nodeValue;
		}
		return $this->inCountryName;
	}

	/**
	 * Return in region id
	 *
	 * @return Integer
	 */
	public function getInRegionId()
	{
		if(!isset($this->inRegionId) || $this->inRegionId === null)
		{
			$this->inRegionId = $this->getXml()->getElementsByTagName('RegionID')->item(1)->nodeValue;
		}
		return $this->inRegionId;
	}

	/**
	 * Return in region name
	 *
	 * @return String
	 */
	public function getInRegionName()
	{
		if(!isset($this->inRegionName) || $this->inRegionName === null)
		{
			$this->inRegionName = $this->getXml()->getElementsByTagName('RegionName')->item(1)->nodeValue;
		}
		return $this->inRegionName;
	}

	/**
	 * Return hired date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getHiredDate($format = null)
	{
		if(!isset($this->hiredDate[$format]) || $this->hiredDate[$format] === null)
		{
			$this->hiredDate[$format] = $this->getXml()->getElementsByTagName('HiredDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->hiredDate[$format] = HTFunction::convertDate($this->hiredDate[$format], $format);
			}
		}
		return $this->hiredDate[$format];
	}

	/**
	 * Return last call date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getLastCallDate($format = null)
	{
		if(!isset($this->lastCall[$format]) || $this->lastCall[$format] === null)
		{
			$this->lastCall[$format] = $this->getXml()->getElementsByTagName('LastCalled')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->lastCall[$format] = HTFunction::convertDate($this->lastCall[$format], $format);
			}
		}
		return $this->lastCall[$format];
	}

	/**
	 * Return if scout is in travel
	 *
	 * @return Boolean
	 */
	public function inTravel()
	{
		if(!isset($this->travel) || $this->travel === null)
		{
			$this->travel = $this->getXml()->getElementsByTagName('Travel')->item(0)->hasChildNodes();
		}
		return $this->travel;
	}

	/**
	 * Return travel start date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getTravelStartDate($format = null)
	{
		if($this->inTravel())
		{
			if(!isset($this->travelDate[$format]) || $this->travelDate[$format] === null)
			{
				$this->travelDate[$format] = $this->getXml()->getElementsByTagName('TravelStartDate')->item(0)->nodeValue;
				if($format !== null)
				{
					$this->travelDate[$format] = HTFunction::convertDate($this->travelDate[$format], $format);
				}
			}
			return $this->travelDate[$format];
		}
		return null;
	}

	/**
	 * Return travel length
	 *
	 * @return Integer
	 */
	public function getTravelLength()
	{
		if(!isset($this->travelLength) || $this->travelLength === null)
		{
			$this->travelLength = $this->getXml()->getElementsByTagName('TravelLength')->item(0)->nodeValue;
		}
		return $this->travelLength;
	}

	/**
	 * Return travel type
	 *
	 * @return Integer
	 */
	public function getTravelType()
	{
		if(!isset($this->travelType) || $this->travelType === null)
		{
			$this->travelType = $this->getXml()->getElementsByTagName('TravelType')->item(0)->nodeValue;
		}
		return $this->travelType;
	}

	/**
	 * Return player type search
	 *
	 * @return Integer
	 */
	public function getPlayerTypeSearch()
	{
		if(!isset($this->typeSearch) || $this->typeSearch === null)
		{
			$this->typeSearch = $this->getXml()->getElementsByTagName('PlayerTypeSearch')->item(0)->nodeValue;
		}
		return $this->typeSearch;
	}
}
class HTYouthTeamPlayers extends HTGlobal
{
	private $playerNumber = null;
	private $players = null;

	/**
	 * Return number of youth player
	 *
	 * @return Integer
	 */
	public function getYouthPlayerNumber()
	{
		if(!isset($this->playerNumber) || $this->playerNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//YouthPlayer");
			$this->playerNumber = $nodeList->length;
		}
		return $this->playerNumber;
	}

	/**
	 * Return HTYouthPlayer object
	 *
	 * @param Integer $number
	 * @return HTYouthPlayer
	 */
	public function getYouthPlayer($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getYouthPlayerNumber())
		{
			--$number;
			if(!isset($this->players[$number]) || $this->players[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//YouthPlayer');
				$youth = new DOMDocument('1.0', 'UTF-8');
				$youth->appendChild($youth->importNode($nodeList->item($number), true));
				$this->players[$number] = new HTYouthPlayer($youth->saveXML());
			}
			return $this->players[$number];
		}
		return null;
	}
}
class HTYouthPlayer extends HTGlobal
{
	private $hasDetails = null;
	private $id = null;
	private $firstname = null;
	private $lastname = null;
	private $nickname = null;
	private $age = null;
	private $days = null;
	private $arrivaldate = null;
	private $promotedin = null;
	private $number = null;
	private $statement = null;
	private $notes = null;
	private $category = null;
	private $cards = null;
	private $injury = null;
	private $speciality = null;
	private $careergoals = null;
	private $hattrickgoals = null;
	private $leaguegoals = null;
	private $friendlygoals = null;
	private $youthteamid = null;
	private $youthteamname = null;
	private $youthteamleagueid = null;
	private $seniorteamid = null;
	private $seniorteamname = null;
	private $lastmatch = null;
	private $scoutcall = null;
	private $hasSkills = null;
	private $keeperskill = null;
	private $keeperskillmax = null;
	private $defenderskill = null;
	private $defenderskillmax = null;
	private $playmakerskill = null;
	private $playmakerskillmax = null;
	private $wingerskill = null;
	private $wingerskillmax = null;
	private $scorerskill = null;
	private $scorerskillmax = null;
	private $passingskill = null;
	private $passingskillmax = null;
	private $setpiecesskill = null;
	private $setpiecesskillmax = null;

	/**
	 * Return if details are available
	 *
	 * @return Boolean
	 */
	public function hasDetails()
	{
		if(!isset($this->hasDetails) || $this->hasDetails === null)
		{
			$this->hasDetails = $this->getXml()->getElementsByTagName('Age')->length;
		}
		return $this->hasDetails;
	}

	/**
	 * Return youth player id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('YouthPlayerID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return youth player firstname
	 *
	 * @return String
	 */
	public function getFirstName()
	{
		if(!isset($this->firstname) || $this->firstname === null)
		{
			$this->firstname = $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
		}
		return $this->firstname;
	}

	/**
	 * Return youth player lastname
	 *
	 * @return String
	 */
	public function getLastName()
	{
		if(!isset($this->lastname) || $this->lastname === null)
		{
			$this->lastname = $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
		}
		return $this->lastname;
	}

	/**
	 * Return youth player nickname
	 *
	 * @return String
	 */
	public function getNickName()
	{
		if(!isset($this->nickname) || $this->nickname === null)
		{
			$this->nickname = $this->getXml()->getElementsByTagName('NickName')->item(0)->nodeValue;
		}
		return $this->nickname;
	}

	/**
	 * Return youth player age
	 *
	 * @return Integer
	 */
	public function getAge()
	{
		if($this->hasDetails())
		{
			if(!isset($this->age) || $this->age === null)
			{
				$this->age = $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
			}
			return $this->age;
		}
		return null;
	}

	/**
	 * Return youth player age days
	 *
	 * @return Integer
	 */
	public function getDays()
	{
		if($this->hasDetails())
		{
			if(!isset($this->days) || $this->days === null)
			{
				$this->days = $this->getXml()->getElementsByTagName('AgeDays')->item(0)->nodeValue;
			}
			return $this->days;
		}
		return null;
	}

	/**
	 * Return arrival date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getArrivalDate($format = null)
	{
		if($this->hasDetails())
		{
			if(!isset($this->arrivaldate[$format]) || $this->arrivaldate[$format] === null)
			{
				$this->arrivaldate[$format] = $this->getXml()->getElementsByTagName('ArrivalDate')->item(0)->nodeValue;
				if($format !== null)
				{
					$this->arrivaldate[$format] = HTFunction::convertDate($this->arrivaldate[$format], $format);
				}
			}
			return $this->arrivaldate[$format];
		}
		return null;
	}

	/**
	 * Return number of days when player can be promoted
	 *
	 * @return Integer
	 */
	public function getCanBePromotedIn()
	{
		if($this->hasDetails())
		{
			if(!isset($this->promotedin) || $this->promotedin === null)
			{
				$this->promotedin = $this->getXml()->getElementsByTagName('CanBePromotedIn')->item(0)->nodeValue;
			}
			return $this->promotedin;
		}
		return null;
	}

	/**
	 * Return youth player number
	 *
	 * @return Integer
	 */
	public function getNumber()
	{
		if($this->hasDetails())
		{
			if(!isset($this->number) || $this->number === null)
			{
				$node = $this->getXml()->getElementsByTagName('PlayerNumber');
				if($node !== null && $node->length)
				{
					$this->number = $node->item(0)->nodeValue;
				}
			}
			return $this->number;
		}
		return null;
	}

	/**
	 * Return youth player statement
	 *
	 * @return String
	 */
	public function getStatement()
	{
		if($this->hasDetails())
		{
			if(!isset($this->statement) || $this->statement === null)
			{
				$node = $this->getXml()->getElementsByTagName('Statement');
				if($node !== null && $node->length)
				{
					$this->statement = $node->item(0)->nodeValue;
				}
			}
			return $this->statement;
		}
		return null;
	}

	/**
	 * Return notes
	 *
	 * @return String
	 */
	public function getNotes()
	{
		if($this->hasDetails())
		{
			if(!isset($this->notes) || $this->notes === null)
			{
				$node = $this->getXml()->getElementsByTagName('OwnerNotes');
				if($node !== null && $node->length)
				{
					$this->notes = $node->item(0)->nodeValue;
				}
			}
			return $this->notes;
		}
		return null;
	}

	/**
	 * Return category id
	 *
	 * @return Integer
	 */
	public function getCategory()
	{
		if($this->hasDetails())
		{
			if(!isset($this->category) || $this->category === null)
			{
				$node = $this->getXml()->getElementsByTagName('PlayerCategoryID');
				if($node !== null && $node->length)
				{
					$this->category = $node->item(0)->nodeValue;
				}
			}
			return $this->category;
		}
		return null;
	}

	/**
	 * Return cards number
	 *
	 * @return Integer
	 */
	public function getCardsNumber()
	{
		if($this->hasDetails())
		{
			if(!isset($this->cards) || $this->cards === null)
			{
				$this->cards = $this->getXml()->getElementsByTagName('Cards')->item(0)->nodeValue;
			}
			return $this->cards;
		}
		return null;
	}

	/**
	 * Return injury level
	 *
	 * @return Integer
	 */
	public function getInjuryLevel()
	{
		if($this->hasDetails())
		{
			if(!isset($this->injury) || $this->injury === null)
			{
				$this->injury = $this->getXml()->getElementsByTagName('InjuryLevel')->item(0)->nodeValue;
			}
			return $this->injury;
		}
		return null;
	}

	/**
	 * Return speciality id
	 *
	 * @return Integer
	 */
	public function getSpeciality()
	{
		if($this->hasDetails())
		{
			if(!isset($this->speciality) || $this->speciality === null)
			{
				$this->speciality = $this->getXml()->getElementsByTagName('Specialty')->item(0)->nodeValue;
			}
			return $this->speciality;
		}
		return null;
	}

	/**
	 * Return career goals number
	 *
	 * @return Integer
	 */
	public function getCareerGoals()
	{
		if($this->hasDetails())
		{
			if(!isset($this->careergoals) || $this->careergoals === null)
			{
				$this->careergoals = $this->getXml()->getElementsByTagName('CareerGoals')->item(0)->nodeValue;
			}
			return $this->careergoals;
		}
		return null;
	}

	/**
	 * Return career hattricks number
	 *
	 * @return Integer
	 */
	public function getCareerHattricks()
	{
		if($this->hasDetails())
		{
			if(!isset($this->hattrickgoals) || $this->hattrickgoals === null)
			{
				$this->hattrickgoals = $this->getXml()->getElementsByTagName('CareerHattricks')->item(0)->nodeValue;
			}
			return $this->hattrickgoals;
		}
		return null;
	}

	/**
	 * Return league goals number
	 *
	 * @return Integer
	 */
	public function getLeagueGoals()
	{
		if($this->hasDetails())
		{
			if(!isset($this->leaguegoals) || $this->leaguegoals === null)
			{
				$this->leaguegoals = $this->getXml()->getElementsByTagName('LeagueGoals')->item(0)->nodeValue;
			}
			return $this->leaguegoals;
		}
		return null;
	}

	/**
	 * Return friends goals number
	 *
	 * @return Integer
	 */
	public function getFriendlyGoals()
	{
		if($this->hasDetails())
		{
			if(!isset($this->friendlygoals) || $this->friendlygoals === null)
			{
				$this->friendlygoals = $this->getXml()->getElementsByTagName('FriendlyGoals')->item(0)->nodeValue;
			}
			return $this->friendlygoals;
		}
		return null;
	}

	/**
	 * Return youth team id
	 *
	 * @return Integer
	 */
	public function getYouthTeamId()
	{
		if($this->hasDetails())
		{
			if(!isset($this->youthteamid) || $this->youthteamid === null)
			{
				$this->youthteamid = $this->getXml()->getElementsByTagName('YouthTeamID')->item(0)->nodeValue;
			}
			return $this->youthteamid;
		}
		return null;
	}

	/**
	 * Return youth team name
	 *
	 * @return String
	 */
	public function getYouthTeamName()
	{
		if($this->hasDetails())
		{
			if(!isset($this->youthteamname) || $this->youthteamname === null)
			{
				$this->youthteamname = $this->getXml()->getElementsByTagName('YouthTeamName')->item(0)->nodeValue;
			}
			return $this->youthteamname;
		}
		return null;
	}

	/**
	 * Return youth team league id
	 *
	 * @return Integer
	 */
	public function getYouthTeamLeagueId()
	{
		if($this->hasDetails())
		{
			if(!isset($this->youthteamleagueid) || $this->youthteamleagueid === null)
			{
				$this->youthteamleagueid = $this->getXml()->getElementsByTagName('YouthTeamLeagueID')->item(0)->nodeValue;
			}
			return $this->youthteamleagueid;
		}
		return null;
	}

	/**
	 * Return senior team id
	 *
	 * @return Integer
	 */
	public function getSeniorTeamId()
	{
		if($this->hasDetails())
		{
			if(!isset($this->seniorteamid) || $this->seniorteamid === null)
			{
				$this->seniorteamid = $this->getXml()->getElementsByTagName('SeniorTeamID')->item(0)->nodeValue;
			}
			return $this->seniorteamid;
		}
		return null;
	}

	/**
	 * Return senior team name
	 *
	 * @return String
	 */
	public function getSeniorTeamName()
	{
		if($this->hasDetails())
		{
			if(!isset($this->seniorteamname) || $this->seniorteamname === null)
			{
				$this->seniorteamname = $this->getXml()->getElementsByTagName('SeniorTeamName')->item(0)->nodeValue;
			}
			return $this->seniorteamname;
		}
		return null;
	}

	/**
	 * Return is skills are available
	 *
	 * @return Boolean
	 */
	public function hasSkills()
	{
		if(!isset($this->hasSkills) || $this->hasSkills === null)
		{
			$this->hasSkills = $this->getXml()->getElementsByTagName('PlayerSkills')->length;
		}
		return $this->hasSkills;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getKeeperSkill()
	{
		if($this->hasSkills())
		{
			if(!isset($this->keeperskill) || $this->keeperskill === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('KeeperSkill')->item(0), true));
				$this->keeperskill = new HTYouthPlayerSkill($skill);
			}
			return $this->keeperskill;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getKeeperSkillMax()
	{
		if($this->hasSkills())
		{
			if(!isset($this->keeperskillmax) || $this->keeperskillmax === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('KeeperSkillMax')->item(0), true));
				$this->keeperskillmax = new HTYouthPlayerSkill($skill);
			}
			return $this->keeperskillmax;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getDefenderSkill()
	{
		if($this->hasSkills())
		{
			if(!isset($this->defenderskill) || $this->defenderskill === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('DefenderSkill')->item(0), true));
				$this->defenderskill = new HTYouthPlayerSkill($skill);
			}
			return $this->defenderskill;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getDefenderSkillMax()
	{
		if($this->hasSkills())
		{
			if(!isset($this->defenderskillmax) || $this->defenderskillmax === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('DefenderSkillMax')->item(0), true));
				$this->defenderskillmax = new HTYouthPlayerSkill($skill);
			}
			return $this->defenderskillmax;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getPlaymakerSkill()
	{
		if($this->hasSkills())
		{
			if(!isset($this->playmakerskill) || $this->playmakerskill === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('PlaymakerSkill')->item(0), true));
				$this->playmakerskill = new HTYouthPlayerSkill($skill);
			}
			return $this->playmakerskill;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getPlaymakerSkillMax()
	{
		if($this->hasSkills())
		{
			if(!isset($this->playmakerskillmax) || $this->playmakerskillmax === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('PlaymakerSkillMax')->item(0), true));
				$this->playmakerskillmax = new HTYouthPlayerSkill($skill);
			}
			return $this->playmakerskillmax;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getWingerSkill()
	{
		if($this->hasSkills())
		{
			if(!isset($this->wingerskill) || $this->wingerskill === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('WingerSkill')->item(0), true));
				$this->wingerskill = new HTYouthPlayerSkill($skill);
			}
			return $this->wingerskill;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getWingerSkillMax()
	{
		if($this->hasSkills())
		{
			if(!isset($this->wingerskillmax) || $this->wingerskillmax === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('WingerSkillMax')->item(0), true));
				$this->wingerskillmax = new HTYouthPlayerSkill($skill);
			}
			return $this->wingerskillmax;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getScorerSkill()
	{
		if($this->hasSkills())
		{
			if(!isset($this->scorerskill) || $this->scorerskill === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('ScorerSkill')->item(0), true));
				$this->scorerskill = new HTYouthPlayerSkill($skill);
			}
			return $this->scorerskill;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getScorerSkillMax()
	{
		if($this->hasSkills())
		{
			if(!isset($this->scorerskillmax) || $this->scorerskillmax === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('ScorerSkillMax')->item(0), true));
				$this->scorerskillmax = new HTYouthPlayerSkill($skill);
			}
			return $this->scorerskillmax;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getPassingSkill()
	{
		if($this->hasSkills())
		{
			if(!isset($this->passingskill) || $this->passingskill === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('PassingSkill')->item(0), true));
				$this->passingskill = new HTYouthPlayerSkill($skill);
			}
			return $this->passingskill;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getPassingSkillMax()
	{
		if($this->hasSkills())
		{
			if(!isset($this->passingskillmax) || $this->passingskillmax === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('PassingSkillMax')->item(0), true));
				$this->passingskillmax = new HTYouthPlayerSkill($skill);
			}
			return $this->passingskillmax;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getSetPiecesSkill()
	{
		if($this->hasSkills())
		{
			if(!isset($this->setpiecesskill) || $this->setpiecesskill === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('SetPiecesSkill')->item(0), true));
				$this->setpiecesskill = new HTYouthPlayerSkill($skill);
			}
			return $this->setpiecesskill;
		}
		return null;
	}

	/**
	 * Return HTYouthPlayerSkill object
	 *
	 * @return HTYouthPlayerSkill
	 */
	public function getSetPiecesSkillMax()
	{
		if($this->hasSkills())
		{
			if(!isset($this->setpiecesskillmax) || $this->setpiecesskillmax === null)
			{
				$skill = new DOMDocument('1.0', 'UTF-8');
				$skill->appendChild($skill->importNode($this->getXml()->getElementsByTagName('SetPiecesSkillMax')->item(0), true));
				$this->setpiecesskillmax = new HTYouthPlayerSkill($skill);
			}
			return $this->setpiecesskillmax;
		}
		return null;
	}

	/**
	 * Return HTYouthScoutCall object
	 *
	 * @return HTYouthScoutCall
	 */
	public function getScoutCall()
	{
		if(!isset($this->scoutcall) || $this->scoutcall === null)
		{
			$node = $this->getXml()->getElementsByTagName('ScoutCall');
			if($node !== null && $node->length)
			{
				$call = new DOMDocument('1.0', 'UTF-8');
				$call->appendChild($call->importNode($node->item(0), true));
				$this->scoutcall = new HTYouthScoutCall($call);
			}
		}
		return $this->scoutcall;
	}

	/**
	 * Return HTYouthPlayerLastMatch object
	 *
	 * @return HTYouthPlayerLastMatch
	 */
	public function getLastMatch()
	{
		if(!isset($this->lastmatch) || $this->lastmatch === null)
		{
			$node = $this->getXml()->getElementsByTagName('LastMatch');
			if($node !== null && $node->length)
			{
				$match = new DOMDocument('1.0', 'UTF-8');
				$match->appendChild($match->importNode($node->item(0), true));
				$this->lastmatch = new HTYouthPlayerLastMatch($match);
			}
		}
		return $this->lastmatch;
	}
}
class HTYouthPlayerLastMatch extends HTXml
{
	private $date = null;
	private $id = null;
	private $position = null;
	private $minutes = null;
	private $rating = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('Date')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('YouthMatchID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return position code
	 *
	 * @return Integer
	 */
	public function getPosition()
	{
		if(!isset($this->position) || $this->position === null)
		{
			$this->position = $this->getXml()->getElementsByTagName('PositionCode')->item(0)->nodeValue;
		}
		return $this->position;
	}

	/**
	 * Return number of played minute
	 *
	 * @return Integer
	 */
	public function getPlayedMinute()
	{
		if(!isset($this->minutes) || $this->minutes === null)
		{
			$this->minutes = $this->getXml()->getElementsByTagName('PlayedMinutes')->item(0)->nodeValue;
		}
		return $this->minutes;
	}

	/**
	 * Return rating number
	 *
	 * @return Float
	 */
	public function getRating()
	{
		if(!isset($this->rating) || $this->rating === null)
		{
			$this->rating = $this->getXml()->getElementsByTagName('Rating')->item(0)->nodeValue;
		}
		return $this->rating;
	}
}
class HTYouthScoutCall extends HTXml
{
	private $scoutId = null;
	private $scoutName = null;
	private $regionId = null;
	private $commentNumber = null;
	private $comments = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return scout id
	 *
	 * @return Integer
	 */
	public function getScoutId()
	{
		if(!isset($this->scoutId) || $this->scoutId === null)
		{
			$this->scoutId = $this->getXml()->getElementsByTagName('ScoutId')->item(0)->nodeValue;
		}
		return $this->scoutId;
	}

	/**
	 * Return scout name
	 *
	 * @return String
	 */
	public function getScoutName()
	{
		if(!isset($this->scoutName) || $this->scoutName === null)
		{
			$this->scoutName = $this->getXml()->getElementsByTagName('ScoutName')->item(0)->nodeValue;
		}
		return $this->scoutName;
	}

	/**
	 * Return scouting region id
	 *
	 * @return Integer
	 */
	public function getRegion()
	{
		if(!isset($this->regionId) || $this->regionId === null)
		{
			$this->regionId = $this->getXml()->getElementsByTagName('ScoutingRegionID')->item(0)->nodeValue;
		}
		return $this->regionId;
	}

	/**
	 * Return number of comment
	 *
	 * @return Integer
	 */
	public function getCommentNumber()
	{
		if(!isset($this->commentNumber) || $this->commentNumber === null)
		{
			$this->commentNumber = $this->getXml()->getElementsByTagName('ScoutComment')->length;
		}
		return $this->commentNumber;
	}

	/**
	 * Return HTYouthScoutComment object
	 *
	 * @return HTYouthScoutComment
	 */
	public function getComment($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getCommentNumber())
		{
			--$index;
			if(!isset($this->comments[$index]) || $this->comments[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//ScoutComment");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->comments[$index] = new HTYouthScoutComment($node);
			}
			return $this->comments[$index];
		}
		return null;
	}
}
class HTYouthScoutComment extends HTXml
{
	private $text = null;
	private $type = null;
	private $variation = null;
	private $skilltype = null;
	private $skilllevel = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return text
	 *
	 * @return String
	 */
	public function getText()
	{
		if(!isset($this->text) || $this->text === null)
		{
			$this->text = $this->getXml()->getElementsByTagName('CommentText')->item(0)->nodeValue;
		}
		return $this->text;
	}

	/**
	 * Return type
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('CommentType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return variation
	 *
	 * @return Integer
	 */
	public function getVariation()
	{
		if(!isset($this->variation) || $this->variation === null)
		{
			$this->variation = $this->getXml()->getElementsByTagName('CommentVariation')->item(0)->nodeValue;
		}
		return $this->variation;
	}

	/**
	 * Return skill type
	 *
	 * @return Integer
	 */
	public function getSkillType()
	{
		if(!isset($this->skilltype) || $this->skilltype === null)
		{
			$this->skilltype = $this->getXml()->getElementsByTagName('CommentSkillType')->item(0)->nodeValue;
		}
		return $this->skilltype;
	}

	/**
	 * Return skill level
	 *
	 * @return Integer
	 */
	public function getSkillLevel()
	{
		if(!isset($this->skilllevel) || $this->skilllevel === null)
		{
			$this->skilllevel = $this->getXml()->getElementsByTagName('CommentSkillLevel')->item(0)->nodeValue;
		}
		return $this->skilllevel;
	}
}
class HTYouthPlayerSkill extends HTXml
{
	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return skill value
	 *
	 * @return Integer
	 */
	public function getValue()
	{
		return $this->getXml()->documentElement->nodeValue;
	}

	/**
	 * Return if skill is available
	 *
	 * @return Boolean
	 */
	public function isAvailable()
	{
		return strtolower($this->getXml()->documentElement->getAttribute('IsAvailable')) == 'true';
	}

	/**
	 * Return if skill can be trained anymore
	 *
	 * @return Boolean
	 */
	public function isMaxReached()
	{
		return strtolower($this->getXml()->documentElement->getAttribute('IsMaxReached')) == 'true';
	}

	/**
	 * Return if skill can be unlocked
	 *
	 * @return Boolean
	 */
	public function mayUnlock()
	{
		return strtolower($this->getXml()->documentElement->getAttribute('MayUnlock')) == 'true';
	}
}
class HTTrophy extends HTXml
{
	private $typeId = null;
	private $season = null;
	private $leagueLevel = null;
	private $leagueLevelUnitId = null;
	private $gainedDate = null;
	private $imageUrl = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return type id
	 *
	 * @return Integer
	 */
	public function getTypeId()
	{
		if(!isset($this->typeId) || $this->typeId === null)
		{
			$this->typeId = $this->getXml()->getElementsByTagName('TrophyTypeId')->item(0)->nodeValue;
		}
		return $this->typeId;
	}

	/**
	 * Return season number
	 *
	 * @return Integer
	 */
	public function getSeason()
	{
		if(!isset($this->season) || $this->season === null)
		{
			$this->season = $this->getXml()->getElementsByTagName('TrophySeason')->item(0)->nodeValue;
		}
		return $this->season;
	}

	/**
	 * Return league level
	 *
	 * @return Integer
	 */
	public function getLeagueLevel()
	{
		if(!isset($this->leagueLevel) || $this->leagueLevel === null)
		{
			$this->leagueLevel = $this->getXml()->getElementsByTagName('LeagueLevel')->item(0)->nodeValue;
		}
		return $this->leagueLevel;
	}

	/**
	 * Return league level unit name
	 *
	 * @return String
	 */
	public function getLeagueLevelUnitName()
	{
		if(!isset($this->leagueLevelUnitId) || $this->leagueLevelUnitId === null)
		{
			$this->leagueLevelUnitId = $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
		}
		return $this->leagueLevelUnitId;
	}

	/**
	 * Return gained date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getGainedDate($format = null)
	{
		if(!isset($this->gainedDate[$format]) || $this->gainedDate[$format] === null)
		{
			$this->gainedDate[$format] = $this->getXml()->getElementsByTagName('GainedDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->gainedDate[$format] = HTFunction::convertDate($this->gainedDate[$format], $format);
			}
		}
		return $this->gainedDate[$format];
	}

	/**
	 * Return image url
	 *
	 * @return String
	 */
	public function getImageUrl()
	{
		if(!isset($this->imageUrl) || $this->imageUrl === null)
		{
			$this->imageUrl = $this->getXml()->getElementsByTagName('ImageUrl')->item(0)->nodeValue;
		}
		return $this->imageUrl;
	}
}
class HTTeamFlags extends HTTeam
{
	private $numberFlagsHome = null;
	private $flagsHome = null;
	private $numberFlagsAway = null;
	private $flagsAway = null;

	/**
	 * Return number of home flags
	 *
	 * @return Integer
	 */
	public function getNumberFlagsHome()
	{
		if(!isset($this->numberFlagsHome) || $this->numberFlagsHome === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//HomeFlags/Flag");
			$this->numberFlagsHome = $nodeList->length;
		}
		return $this->numberFlagsHome;
	}

	/**
	 * Return number of away flags
	 *
	 * @return Integer
	 */
	public function getNumberFlagsAway()
	{
		if(!isset($this->numberFlagsAway) || $this->numberFlagsAway === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//AwayFlags/Flag");
			$this->numberFlagsAway = $nodeList->length;
		}
		return $this->numberFlagsAway;
	}

	/**
	 * Return HTTeamFlag object
	 *
	 * @param Integer $index
	 * @return HTTeamFlag
	 */
	public function getFlagHome($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberFlagsHome())
		{
			--$index;
			if(!isset($this->flagsHome[$index]) || $this->flagsHome[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//HomeFlags/Flag");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->flagsHome[$index] = new HTTeamFlag($node);
			}
			return $this->flagsHome[$index];
		}
		return null;
	}

	/**
	 * Return HTTeamFlag object
	 *
	 * @param Integer $index
	 * @return HTTeamFlag
	 */
	public function getFlagAway($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberFlagsAway())
		{
			--$index;
			if(!isset($this->flagsAway[$index]) || $this->flagsAway[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//AwayFlags/Flag");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->flagsAway[$index] = new HTTeamFlag($node);
			}
			return $this->flagsAway[$index];
		}
		return null;
	}
}
class HTTeamSupporters extends HTGlobal
{
	private $total = null;
	private $supporterNumber = null;
	private $supporterTeams = array();

	/**
	 * Return total number of supporters
	 *
	 * @return Integer
	 */
	public function getTotalSupporter()
	{
		if(!isset($this->total) || $this->total === null)
		{
			$this->total = $this->getXml()->getElementsByTagName('MySupporters')->item(0)->getAttribute('TotalItems');
		}
		return $this->total;
	}


	/**
	 * Return number of supporters
	 *
	 * @return Integer
	 */
	public function getSupporterNumber()
	{
		if(!isset($this->supporterNumber) || $this->supporterNumber === null)
		{
			$this->supporterNumber = $this->getXml()->getElementsByTagName('SupporterTeam')->length;
		}
		return $this->supporterNumber;
	}

	/**
	 * Return HTTeamSupporter object
	 *
	 * @param Integer $index
	 * @return HTTeamSupporter
	 */
	public function getSupporterTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getSupporterNumber())
		{
			--$index;
			if(!isset($this->supporterTeams[$index]) || $this->supporterTeams[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//MySupporters/SupporterTeam");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->supporterTeams[$index] = new HTTeamSupporter($node);
			}
			return $this->supporterTeams[$index];
		}
		return null;
	}
}
class HTTeamSupporter extends HTXml
{
	private $userId = null;
	private $loginName = null;
	private $teamId = null;
	private $teamName = null;
	private $leagueId = null;
	private $leagueName = null;
	private $leagueLevelUnitId = null;
	private $leagueLevelUnitName = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return user id
	 *
	 * @return Integer
	 */
	public function getUserId()
	{
		if(!isset($this->userId) || $this->userId === null)
		{
			$this->userId = $this->getXml()->getElementsByTagName('UserId')->item(0)->nodeValue;
		}
		return $this->userId;
	}

	/**
	 * Return login name
	 *
	 * @return String
	 */
	public function getLoginName()
	{
		if(!isset($this->loginName) || $this->loginName === null)
		{
			$this->loginName = $this->getXml()->getElementsByTagName('LoginName')->item(0)->nodeValue;
		}
		return $this->loginName;
	}

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$this->teamId = $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
		}
		return $this->teamId;
	}

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getTeamName()
	{
		if(!isset($this->teamName) || $this->teamName === null)
		{
			$this->teamName = $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
		}
		return $this->teamName;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$this->leagueName = $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
		}
		return $this->leagueName;
	}

	/**
	 * Return league level unit id
	 *
	 * @return Integer
	 */
	public function getLeagueLevelUnitId()
	{
		if(!isset($this->leagueLevelUnitId) || $this->leagueLevelUnitId === null)
		{
			$this->leagueLevelUnitId = $this->getXml()->getElementsByTagName('LeagueLevelUnitID')->item(0)->nodeValue;
		}
		return $this->leagueLevelUnitId;
	}

	/**
	 * Return league level unit name
	 *
	 * @return String
	 */
	public function getLeagueLevelUnitName()
	{
		if(!isset($this->leagueLevelUnitName) || $this->leagueLevelUnitName === null)
		{
			$this->leagueLevelUnitName = $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
		}
		return $this->leagueLevelUnitName;
	}
}
class HTUserSupporters extends HTGlobal
{
	private $total = null;
	private $supporterNumber = null;
	private $supporterTeams = array();

	/**
	 * Return total number of supporters
	 *
	 * @return Integer
	 */
	public function getTotalSupporter()
	{
		if(!isset($this->total) || $this->total === null)
		{
			$this->total = $this->getXml()->getElementsByTagName('SupportedTeams')->item(0)->getAttribute('TotalItems');
		}
		return $this->total;
	}

	/**
	 * Return number of supporters
	 *
	 * @return Integer
	 */
	public function getSupporterNumber()
	{
		if(!isset($this->supporterNumber) || $this->supporterNumber === null)
		{
			$this->supporterNumber = $this->getXml()->getElementsByTagName('SupportedTeam')->length;
		}
		return $this->supporterNumber;
	}

	/**
	 * Return HTTeamSupported object
	 *
	 * @param Integer $index
	 * @return HTTeamSupported
	 */
	public function getSupporterTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getSupporterNumber())
		{
			--$index;
			if(!isset($this->supporterTeams[$index]) || $this->supporterTeams[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//SupportedTeams/SupportedTeam");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->supporterTeams[$index] = new HTTeamSupported($node);
			}
			return $this->supporterTeams[$index];
		}
		return null;
	}
}
class HTTeamSupported extends HTTeamSupporter
{
	private $lastMatchId = null;
	private $lastMatchDate = null;
	private $lastMatchHomeTeamId = null;
	private $lastMatchHomeTeamName = null;
	private $lastMatchHomeGoals = null;
	private $lastMatchAwayTeamId = null;
	private $lastMatchAwayTeamName = null;
	private $lastMatchAwayGoals = null;
	private $nextMatchId = null;
	private $nextMatchDate = null;
	private $nextMatchHomeTeamId = null;
	private $nextMatchHomeTeamName = null;
	private $nextMatchAwayTeamId = null;
	private $nextMatchAwayTeamName = null;
	private $press = null;
	private $pressDate = null;
	private $pressSubject = null;
	private $pressBody = null;

	/**
	 * Return last match id
	 *
	 * @return Integer
	 */
	public function getLastMatchId()
	{
		if(!isset($this->lastMatchId) || $this->lastMatchId === null)
		{
			$this->lastMatchId = $this->getXml()->getElementsByTagName('LastMatchId')->item(0)->nodeValue;
		}
		return $this->lastMatchId;
	}

	/**
	 * Return last match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getLastMatchDate($format = null)
	{
		if(!isset($this->lastMatchDate[$format]) || $this->lastMatchDate[$format] === null)
		{
			$this->lastMatchDate[$format] = $this->getXml()->getElementsByTagName('LastMatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->lastMatchDate[$format] = HTFunction::convertDate($this->lastMatchDate[$format], $format);
			}
		}
		return $this->lastMatchDate[$format];
	}

	/**
	 * Return last match home team id
	 *
	 * @return Integer
	 */
	public function getLastMatchHomeTeamId()
	{
		if(!isset($this->lastMatchHomeTeamId) || $this->lastMatchHomeTeamId === null)
		{
			$this->lastMatchHomeTeamId = $this->getXml()->getElementsByTagName('LastMatchHomeTeamId')->item(0)->nodeValue;
		}
		return $this->lastMatchHomeTeamId;
	}

	/**
	 * Return last match home team name
	 *
	 * @return String
	 */
	public function getLastMatchHomeTeamName()
	{
		if(!isset($this->lastMatchHomeTeamName) || $this->lastMatchHomeTeamName === null)
		{
			$this->lastMatchHomeTeamName = $this->getXml()->getElementsByTagName('LastMatchHomeTeamName')->item(0)->nodeValue;
		}
		return $this->lastMatchHomeTeamName;
	}

	/**
	 * Return last match home team goals number
	 *
	 * @return Integer
	 */
	public function getLastMatchHomeGoals()
	{
		if(!isset($this->lastMatchHomeGoals) || $this->lastMatchHomeGoals === null)
		{
			$this->lastMatchHomeGoals = $this->getXml()->getElementsByTagName('LastMatchHomeGoals')->item(0)->nodeValue;
		}
		return $this->lastMatchHomeGoals;
	}

	/**
	 * Return last match away team id
	 *
	 * @return Integer
	 */
	public function getLastMatchAwayTeamId()
	{
		if(!isset($this->lastMatchAwayTeamId) || $this->lastMatchAwayTeamId === null)
		{
			$this->lastMatchAwayTeamId = $this->getXml()->getElementsByTagName('LastMatchAwayTeamId')->item(0)->nodeValue;
		}
		return $this->lastMatchAwayTeamId;
	}

	/**
	 * Return last match away team name
	 *
	 * @return String
	 */
	public function getLastMatchAwayTeamName()
	{
		if(!isset($this->lastMatchAwayTeamName) || $this->lastMatchAwayTeamName === null)
		{
			$this->lastMatchAwayTeamName = $this->getXml()->getElementsByTagName('LastMatchAwayTeamName')->item(0)->nodeValue;
		}
		return $this->lastMatchAwayTeamName;
	}

	/**
	 * Return last match away team goals number
	 *
	 * @return Integer
	 */
	public function getLastMatchAwayGoals()
	{
		if(!isset($this->lastMatchAwayGoals) || $this->lastMatchAwayGoals === null)
		{
			$this->lastMatchAwayGoals = $this->getXml()->getElementsByTagName('LastMatchAwayGoals')->item(0)->nodeValue;
		}
		return $this->lastMatchAwayGoals;
	}

	/**
	 * Return next match id
	 *
	 * @return Integer
	 */
	public function getNextMatchId()
	{
		if(!isset($this->nextMatchId) || $this->nextMatchId === null)
		{
			$this->nextMatchId = $this->getXml()->getElementsByTagName('NextMatchId')->item(0)->nodeValue;
		}
		return $this->nextMatchId;
	}

	/**
	 * Return next match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getNextMatchDate($format = null)
	{
		if(!isset($this->nextMatchDate[$format]) || $this->nextMatchDate[$format] === null)
		{
			$this->nextMatchDate[$format] = $this->getXml()->getElementsByTagName('NextMatchMatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->nextMatchDate[$format] = HTFunction::convertDate($this->nextMatchDate[$format], $format);
			}
		}
		return $this->nextMatchDate[$format];
	}

	/**
	 * Return next match home team id
	 *
	 * @return Integer
	 */
	public function getNextMatchHomeTeamId()
	{
		if(!isset($this->nextMatchHomeTeamId) || $this->nextMatchHomeTeamId === null)
		{
			$this->nextMatchHomeTeamId = $this->getXml()->getElementsByTagName('NextMatchHomeTeamId')->item(0)->nodeValue;
		}
		return $this->nextMatchHomeTeamId;
	}

	/**
	 * Return next match home team name
	 *
	 * @return String
	 */
	public function getNextMatchHomeTeamName()
	{
		if(!isset($this->nextMatchHomeTeamName) || $this->nextMatchHomeTeamName === null)
		{
			$this->nextMatchHomeTeamName = $this->getXml()->getElementsByTagName('NextMatchHomeTeamName')->item(0)->nodeValue;
		}
		return $this->nextMatchHomeTeamName;
	}

	/**
	 * Return next match away team id
	 *
	 * @return Integer
	 */
	public function getNextMatchAwayTeamId()
	{
		if(!isset($this->nextMatchAwayTeamId) || $this->nextMatchAwayTeamId === null)
		{
			$this->nextMatchAwayTeamId = $this->getXml()->getElementsByTagName('NextMatchAwayTeamId')->item(0)->nodeValue;
		}
		return $this->nextMatchAwayTeamId;
	}

	/**
	 * Return next match away team name
	 *
	 * @return String
	 */
	public function getNextMatchAwayTeamName()
	{
		if(!isset($this->nextMatchAwayTeamName) || $this->nextMatchAwayTeamName === null)
		{
			$this->nextMatchAwayTeamName = $this->getXml()->getElementsByTagName('NextMatchAwayTeamName')->item(0)->nodeValue;
		}
		return $this->nextMatchAwayTeamName;
	}

	/**
	 * Return if team has a press announcement
	 *
	 * @return Boolean
	 */
	public function hasPressAnnouncement()
	{
		if(!isset($this->press) || $this->press === null)
		{
			$this->press = $this->getXml()->getElementsByTagName('PressAnnouncement')->length == 1;
		}
		return $this->press;
	}

	/**
	 * Return press announcement date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getPressAnnouncementDate($format = null)
	{
		if($this->hasPressAnnouncement())
		{
			if(!isset($this->pressDate[$format]) || $this->pressDate[$format] === null)
			{
				$this->pressDate[$format] = $this->getXml()->getElementsByTagName('PressAnnouncementSendDate')->item(0)->nodeValue;
				if($format !== null)
				{
					$this->pressDate[$format] = HTFunction::convertDate($this->pressDate[$format], $format);
				}
			}
			return $this->pressDate[$format];
		}
		return null;
	}

	/**
	 * Return press announcement subject
	 *
	 * @return String
	 */
	public function getPressAnnouncementSubject()
	{
		if($this->hasPressAnnouncement())
		{
			if(!isset($this->pressSubject) || $this->pressSubject === null)
			{
				$this->pressSubject = $this->getXml()->getElementsByTagName('PressAnnouncementSubject')->item(0)->nodeValue;
			}
			return $this->pressSubject;
		}
		return null;
	}

	/**
	 * Return press announcement body
	 *
	 * @return String
	 */
	public function getPressAnnouncementBody()
	{
		if($this->hasPressAnnouncement())
		{
			if(!isset($this->pressBody) || $this->pressBody === null)
			{
				$this->pressBody = $this->getXml()->getElementsByTagName('PressAnnouncementBody')->item(0)->nodeValue;
			}
			return $this->pressBody;
		}
		return null;
	}
}
class HTTeamNationalTeam extends HTXml
{
	private $teamId = null;
	private $teamName = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$this->teamId = $this->getXml()->getElementsByTagName('NationalTeamID')->item(0)->nodeValue;
		}
		return $this->teamId;
	}

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getTeamName()
	{
		if(!isset($this->teamName) || $this->teamName === null)
		{
			$this->teamName = $this->getXml()->getElementsByTagName('NationalTeamName')->item(0)->nodeValue;
		}
		return $this->teamName;
	}
}
class HTEconomy extends HTCommonTeam
{
	private $money = null;
	private $cash = null;
	private $expectedCash = null;
	private $sponsorsPopularityAvailable = null;
	private $sponsorsPopularity = null;
	private $supportersPopularityAvailable = null;
	private $supportersPopularity = null;
	private $fanClubSize = null;
	private $income = null;
	private $costs = null;
	private $expectedWeeksTotal = null;
	private $lastIncome = null;
	private $lastCosts = null;
	private $lastWeekTotal = null;

	/**
	 * @param DOMDocument $xml
	 * @param Integer $money
	 */
	public function __construct($xml, $money = null)
	{
		parent::__construct($xml);
		$this->money = $money;
	}

	/**
	 * Return club cash value
	 *
	 * @return Integer
	 */
	public function getCash()
	{
		if(!isset($this->cash) || $this->cash === null)
		{
			$this->cash = HTMoney::convert($this->getXml()->getElementsByTagName('Cash')->item(0)->nodeValue, $this->money);
		}
		return $this->cash;
	}

	/**
	 * Return club expexted cash value
	 *
	 * @return Integer
	 */
	public function getExpectedCash()
	{
		if(!isset($this->expectedCash) || $this->expectedCash === null)
		{
			$this->expectedCash = HTMoney::convert($this->getXml()->getElementsByTagName('ExpectedCash')->item(0)->nodeValue, $this->money);
		}
		return $this->expectedCash;
	}

	/**
	 * Return sponsors level
	 *
	 * @return Integer
	 */
	public function getSponsorsLevel()
	{
		if(!isset($this->sponsorsPopularityAvailable) || $this->sponsorsPopularityAvailable === null)
		{
			$this->sponsorsPopularityAvailable = strtolower($this->getXml()->getElementsByTagName('SponsorsPopularity')->item(0)->getAttribute('Available')) == 'true';
		}
		if($this->sponsorsPopularityAvailable == true)
		{
			if(!isset($this->sponsorsPopularity) || $this->sponsorsPopularity === null)
			{
				$this->sponsorsPopularity = $this->getXml()->getElementsByTagName('SponsorsPopularity')->item(0)->nodeValue;
			}
			return $this->sponsorsPopularity;
		}
		return null;
	}

	/**
	 * Return supporters level
	 *
	 * @return Integer
	 */
	public function getSupportersLevel()
	{
		if(!isset($this->supportersPopularityAvailable) || $this->supportersPopularityAvailable === null)
		{
			$this->supportersPopularityAvailable = strtolower($this->getXml()->getElementsByTagName('SupportersPopularity')->item(0)->getAttribute('Available')) == 'true';
		}
		if($this->supportersPopularityAvailable == true)
		{
			if(!isset($this->supportersPopularity) || $this->supportersPopularity === null)
			{
				$this->supportersPopularity = $this->getXml()->getElementsByTagName('SupportersPopularity')->item(0)->nodeValue;
			}
			return $this->supportersPopularity;
		}
		return null;
	}

	/**
	 * Return fan club size
	 *
	 * @return Integer
	 */
	public function getFanClubSize()
	{
		if(!isset($this->fanClubSize) || $this->fanClubSize === null)
		{
			$this->fanClubSize = $this->getXml()->getElementsByTagName('FanClubSize')->item(0)->nodeValue;
		}
		return $this->fanClubSize;
	}

	/**
	 * Return income object
	 *
	 * @return HTEconomyIncome
	 */
	public function getIncome()
	{
		if(!isset($this->income) || $this->income === null)
		{
			$this->income = new HTEconomyIncome($this->getXml(), $this->money);
		}
		return $this->income;
	}

	/**
	 * Return costs object
	 *
	 * @return HTEconomyCosts
	 */
	public function getCosts()
	{
		if(!isset($this->costs) || $this->costs === null)
		{
			$this->costs = new HTEconomyCosts($this->getXml(), $this->money);
		}
		return $this->costs;
	}

	/**
	 * Return expected week money
	 *
	 * @return Integer
	 */
	public function getWeekExpected()
	{
		if(!isset($this->expectedWeeksTotal) || $this->expectedWeeksTotal === null)
		{
			$this->expectedWeeksTotal = HTMoney::convert($this->getXml()->getElementsByTagName('ExpectedWeeksTotal')->item(0)->nodeValue, $this->money);
		}
		return $this->expectedWeeksTotal;
	}

	/**
	 * Return last income object
	 *
	 * @return HTEconomyIncomeLast
	 */
	public function getLastIncome()
	{
		if(!isset($this->lastIncome) || $this->lastIncome === null)
		{
			$this->lastIncome = new HTEconomyIncomeLast($this->getXml(), $this->money);
		}
		return $this->lastIncome;
	}

	/**
	 * Return last costs object
	 *
	 * @return HTEconomyCostsLast
	 */
	public function getLastCosts()
	{
		if(!isset($this->lastCosts) || $this->lastCosts === null)
		{
			$this->lastCosts = new HTEconomyCostsLast($this->getXml(), $this->money);
		}
		return $this->lastCosts;
	}

	/**
	 * Return last week total
	 *
	 * @return Integer
	 */
	public function getLastWeekTotal()
	{
		if(!isset($this->lastWeekTotal) || $this->lastWeekTotal === null)
		{
			$this->lastWeekTotal = HTMoney::convert($this->getXml()->getElementsByTagName('LastWeeksTotal')->item(0)->nodeValue, $this->money);
		}
		return $this->lastWeekTotal;
	}
}
class HTEconomyIncome extends HTXml
{
	protected $state;
	protected $money;
	private $spectators = null;
	private $sponsors = null;
	private $financial = null;
	private $temporary = null;
	private $soldplayers = null;
	private $soldplayerscomm = null;
	private $total = null;

	/**
	 * New income object
	 *
	 * @param DOMDocument $xml
	 * @param Integer $money
	 */
	public function __construct($xml, $money = null)
	{
		$this->state = '';
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
		$this->money = $money;
	}

	/**
	 * Return spectators income
	 *
	 * @return Integer
	 */
	public function getSpectators()
	{
		if(!isset($this->spectators) || $this->spectators === null)
		{
			$this->spectators = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'IncomeSpectators')->item(0)->nodeValue, $this->money);
		}
		return $this->spectators;
	}

	/**
	 * Return sponsors income
	 *
	 * @return Integer
	 */
	public function getSponsors()
	{
		if(!isset($this->sponsors) || $this->sponsors === null)
		{
			$this->sponsors = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'IncomeSponsors')->item(0)->nodeValue, $this->money);
		}
		return $this->sponsors;
	}

	/**
	 * Return financial income
	 *
	 * @return Integer
	 */
	public function getFinancial()
	{
		if(!isset($this->financial) || $this->financial === null)
		{
			$this->financial = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'IncomeFinancial')->item(0)->nodeValue, $this->money);
		}
		return $this->financial;
	}

	/**
	 * Return sold players income
	 *
	 * @return Integer
	 */
	public function getSoldPlayers()
	{
		if(!isset($this->soldplayers) || $this->soldplayers === null)
		{
			$this->soldplayers = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'IncomeSoldPlayers')->item(0)->nodeValue, $this->money);
		}
		return $this->soldplayers;
	}

	/**
	 * Return sold players commission income
	 *
	 * @return Integer
	 */
	public function getSoldPlayersCommission()
	{
		if(!isset($this->soldplayerscomm) || $this->soldplayerscomm === null)
		{
			$this->soldplayerscomm = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'IncomeSoldPlayersCommission')->item(0)->nodeValue, $this->money);
		}
		return $this->soldplayerscomm;
	}

	/**
	 * Return temporary income
	 *
	 * @return Integer
	 */
	public function getTemporary()
	{
		if(!isset($this->temporary) || $this->temporary === null)
		{
			$this->temporary = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'IncomeTemporary')->item(0)->nodeValue, $this->money);
		}
		return $this->temporary;
	}

	/**
	 * Return total income
	 *
	 * @return Integer
	 */
	public function getTotal()
	{
		if(!isset($this->total) || $this->total === null)
		{
			$this->total = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'IncomeSum')->item(0)->nodeValue, $this->money);
		}
		return $this->total;
	}
}
class HTEconomyIncomeLast extends HTEconomyIncome
{
	/**
	 * @param DOMDocument $xml
	 * @param Integer $money
	 */
	public function __construct($xml, $money = null)
	{
		parent::__construct($xml, $money);
		$this->state = 'Last';
	}
}
class HTEconomyCosts extends HTXml
{
	protected $state;
	protected $money;
	private $arena = null;
	private $players = null;
	private $financial = null;
	private $boughtplayers = null;
	private $arenabuilding = null;
	private $temporary = null;
	private $staff = null;
	private $youth = null;
	private $total = null;

	/**
	 * New income object
	 *
	 * @param DOMDocument $xml
	 * @param Integer $money
	 */
	public function __construct($xml, $money = null)
	{
		$this->state = '';
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
		$this->money = $money;
	}

	/**
	 * Return arena cost
	 *
	 * @return Integer
	 */
	public function getArena()
	{
		if(!isset($this->arena) || $this->arena === null)
		{
			$this->arena = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'CostsArena')->item(0)->nodeValue, $this->money);
		}
		return $this->arena;
	}

	/**
	 * Return players salaries cost
	 *
	 * @return Integer
	 */
	public function getSalaries()
	{
		if(!isset($this->players) || $this->players === null)
		{
			$this->players = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'CostsPlayers')->item(0)->nodeValue, $this->money);
		}
		return $this->players;
	}

	/**
	 * Return financial cost
	 *
	 * @return Integer
	 */
	public function getFinancial()
	{
		if(!isset($this->financial) || $this->financial === null)
		{
			$this->financial = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'CostsFinancial')->item(0)->nodeValue, $this->money);
		}
		return $this->financial;
	}

	/**
	 * Return bought players cost
	 *
	 * @return Integer
	 */
	public function getBoughtPlayers()
	{
		if(!isset($this->boughtplayers) || $this->boughtplayers === null)
		{
			$this->boughtplayers = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'CostsBoughtPlayers')->item(0)->nodeValue, $this->money);
		}
		return $this->boughtplayers;
	}

	/**
	 * Return arena building cost
	 *
	 * @return Integer
	 */
	public function getArenaBuilding()
	{
		if(!isset($this->arenabuilding) || $this->arenabuilding === null)
		{
			$this->arenabuilding = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'CostsArenaBuilding')->item(0)->nodeValue, $this->money);
		}
		return $this->arenabuilding;
	}

	/**
	 * Return temporary cost
	 *
	 * @return Integer
	 */
	public function getTemporary()
	{
		if(!isset($this->temporary) || $this->temporary === null)
		{
			$this->temporary = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'CostsTemporary')->item(0)->nodeValue, $this->money);
		}
		return $this->temporary;
	}

	/**
	 * Return staff cost
	 *
	 * @return Integer
	 */
	public function getStaff()
	{
		if(!isset($this->staff) || $this->staff === null)
		{
			$this->staff = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'CostsStaff')->item(0)->nodeValue, $this->money);
		}
		return $this->staff;
	}

	/**
	 * Return youth cost
	 *
	 * @return Integer
	 */
	public function getYouth()
	{
		if(!isset($this->youth) || $this->youth === null)
		{
			$this->youth = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'CostsYouth')->item(0)->nodeValue, $this->money);
		}
		return $this->youth;
	}

	/**
	 * Return total cost
	 *
	 * @return Integer
	 */
	public function getTotal()
	{
		if(!isset($this->total) || $this->total === null)
		{
			$this->total = HTMoney::convert($this->getXml()->getElementsByTagName($this->state.'CostsSum')->item(0)->nodeValue, $this->money);
		}
		return $this->total;
	}
}
class HTEconomyCostsLast extends HTEconomyCosts
{
	/**
	 * @param DOMDocument $xml
	 * @param Integer $money
	 */
	public function __construct($xml, $money = null)
	{
		parent::__construct($xml, $money);
		$this->state = 'Last';
	}
}
class HTMoney
{
	const	AlIraq = 5	;
	const	AlJazair = 0.1 ;
	const	AlKuwayt = 25	;
	const	AlMaghrib = 1	;
	const	AlUrdun = 5	;
	const	AlYaman = 0.1 ;
	const	Andorra = 10	;
	const	Angola = 0.1 ;
	const	Argentina = 10	;
	const	Azerbaycan = 10	;
	const	Bahrain = 20	;
	const	Bangladesh = 0.2 ;
	const	Barbados = 5	;
	const	Belarus = 5	;
	const	Belgie = 10	;
	const	Benin = 10	;
	const	Bolivia = 1	;
	const	BosniaAndHercegovina = 5	;
	const	Brasil = 5	;
	const	Bulgaria = 5	;
	const	CaboVerde = 0.1 ;
	const	Canada = 5	;
	const	CeskaRepublika = 0.25 ;
	const	Chile = 50	;
	const	China = 1	;
	const	ChineseTaipei = 10	;
	const	Colombia = 10	;
	const	CostaRica = 4	;
	const	IvoryCost = 20	;
	const	CrnaGora = 10	;
	const	Cymru = 15	;
	const	Cyprus = 5	;
	const	Danmark = 1	;
	const	Deutschland = 10	;
	const	Ecuador = 10	;
	const	Eesti = 0.5 ;
	const	ElSalvador = 10	;
	const	England = 15	;
	const	Espana = 10	;
	const	Foroyar = 1	;
	const	France = 10	;
	const	Guatemala = 10	;
	const	Hanguk = 10	;
	const	Hayastan = 20	;
	const	Hellas = 10	;
	const	Honduras = 5	;
	const	HongKong = 1	;
	const	Hrvatska = 1	;
	const	India = 0.25 ;
	const	Indonesia = 1	;
	const	Iran = 1	;
	const	Ireland = 10	;
	const	Island = 0.1 ;
	const	Israel = 2	;
	const	Italia = 10	;
	const	Jamaica = 0.5 ;
	const	Kazakhstan = 0.1 ;
	const	Kenya = 0.5 ;
	const	Kyrgyzstan = 0.2 ;
	const	Latvija = 20	;
	const	Letzebuerg = 10	;
	const	Liechtenstein = 5	;
	const	Lietuva = 2.5 ;
	const	Lubnan = 5	;
	const	Magyarorszag = 50	;
	const	Makedonija = 0.5 ;
	const	Malaysia = 2.5 ;
	const	Malta = 10	;
	const	Mexico = 1	;
	const	Misr = 2.5 ;
	const	Mocambique = 0.4 ;
	const	Moldova = 0.5 ;
	const	MongolUls = 5	;
	const	Nederland = 10	;
	const	NegaraBruneiDarussalam = 5	;
	const	Nicaragua = 0.5 ;
	const	Nigeria = 0.1 ;
	const	Nippon = 0.1 ;
	const	Norge = 1	;
	const	NorthernIreland = 15	;
	const	Oceania = 5	;
	const	Osterreich = 10	;
	const	Pakistan = 0.2 ;
	const	Panama = 10	;
	const	Paraguay = 2	;
	const	Peru = 10	;
	const	Philippines = 0.25 ;
	const	Polska = 2.5 ;
	const	Portugal = 10	;
	const	PratehKampuchea = 2.5 ;
	const	PrathetThai = 0.25 ;
	const	RepublicOfGhana = 10	;
	const	RepublicaDominicana = 0.5 ;
	const	Romania = 0.5 ;
	const	Rossiya = 0.25 ;
	const	Sakartvelo = 5	;
	const	SaudiArabia = 2.5 ;
	const	Schweiz = 5	;
	const	Scotland = 15	;
	const	Senegal = 20	;
	const	Shqiperia = 50	;
	const	Singapore = 5	;
	const	Slovenija = 10	;
	const	Slovensko = 0.2 ;
	const	SouthAfrica = 1.25 ;
	const	Srbija = 1	;
	const	Suomi = 10	;
	const	Suriname = 5	;
	const	Suriyah = 10	;
	const	Sverige = 1	;
	const	Tounes = 7	;
	const	TrinidadAndTobago = 1	;
	const	Turkiye = 10	;
	const	Ukraina = 2	;
	const	Uman = 20	;
	const	UnitedArabEmirates = 4	;
	const	Uruguay = 1	;
	const	USA = 10	;
	const	Venezuela = 10	;
	const	Vietnam = 1	;

	/**
	 * Convert a money amount into country currency
	 *
	 * @param Integer $amount
	 * @param Integer $country
	 * @return Integer
	 */
	public static function convert($amount, $country)
	{
		if($country !== null)
		{
			return round($amount / $country);
		}
		return $amount;
	}

	/**
	 * Convert a money amount from a country currency to SEK amount
	 *
	 * @param Integer $amount
	 * @param Integer $country
	 * @return Integer
	 */
	public static function toSEK($amount, $country)
	{
		return floor($amount * $country);
	}
}
class HTRegion extends HTCommonLeague
{
	private $regionId = null;
	private $regionName = null;
	private $weatherId = null;
	private $tomorrowWeatherId = null;
	private $numberOfUsers = null;
	private $numberOfOnline = null;

	/**
	 * Return region id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->regionId) || $this->regionId === null)
		{
			$this->regionId = $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
		}
		return $this->regionId;
	}

	/**
	 * Return region name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->regionName) || $this->regionName === null)
		{
			$this->regionName = $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
		}
		return $this->regionName;
	}

	/**
	 * Return current weather id
	 *
	 * @return Integer
	 */
	public function getWeatherId()
	{
		if(!isset($this->weatherId) || $this->weatherId === null)
		{
			$this->weatherId = $this->getXml()->getElementsByTagName('WeatherID')->item(0)->nodeValue;
		}
		return $this->weatherId;
	}

	/**
	 * Return tomorrow weather id
	 *
	 * @return Integer
	 */
	public function getTomorrowWeatherId()
	{
		if(!isset($this->tomorrowWeatherId) || $this->tomorrowWeatherId === null)
		{
			$this->tomorrowWeatherId = $this->getXml()->getElementsByTagName('TomorrowWeatherID')->item(0)->nodeValue;
		}
		return $this->tomorrowWeatherId;
	}

	/**
	 * Return number of users
	 *
	 * @return Integer
	 */
	public function getUserNumber()
	{
		if(!isset($this->numberOfUsers) || $this->numberOfUsers === null)
		{
			$this->numberOfUsers = $this->getXml()->getElementsByTagName('NumberOfUsers')->item(0)->nodeValue;
		}
		return $this->numberOfUsers;
	}

	/**
	 * Return number of online users
	 *
	 * @return Integer
	 */
	public function getOnlineUserNumber()
	{
		if(!isset($this->numberOfOnline) || $this->numberOfOnline === null)
		{
			$this->numberOfOnline = $this->getXml()->getElementsByTagName('NumberOfOnline')->item(0)->nodeValue;
		}
		return $this->numberOfOnline;
	}
}
class HTLeague extends HTCommonLeagueLevel
{
	private $leagueLevel = null;
	private $maxLevel = null;
	private $leagueId = null;
	private $leagueName = null;
	private $teams = array();
	private $currentRound = null;

	/**
	 * Return league level
	 *
	 * @return Integer
	 */
	public function getLeagueLevel()
	{
		if(!isset($this->leagueLevel) || $this->leagueLevel === null)
		{
			$this->leagueLevel = $this->getXml()->getElementsByTagName('LeagueLevel')->item(0)->nodeValue;
		}
		return $this->leagueLevel;
	}

	/**
	 * Return max level
	 *
	 * @return Integer
	 */
	public function getMaxLevel()
	{
		if(!isset($this->maxLevel) || $this->maxLevel === null)
		{
			$this->maxLevel = $this->getXml()->getElementsByTagName('MaxLevel')->item(0)->nodeValue;
		}
		return $this->maxLevel;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$this->leagueName = $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
		}
		return $this->leagueName;
	}

	/**
	 * Return HTLeagueTeam object
	 *
	 * @param Integer $position
	 * @return HTLeagueTeam
	 */
	public function getTeam($position)
	{
		$position = round($position);
		if($position > 0 && $position < 9)
		{
			if(!isset($this->teams[$position]) || $this->teams[$position] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//Position[.='".$position."']");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item(0)->parentNode, true));
				$this->teams[$position] = new HTLeagueTeam($node);
			}
			return $this->teams[$position];
		}
		return null;
	}

	/**
	 * Return current match round
	 *
	 * @return Integer
	 */
	public function getCurrentMatchRound()
	{
		if(!isset($this->currentRound) || $this->currentRound === null)
		{
			$this->currentRound = $this->getXml()->getElementsByTagName('CurrentMatchRound')->item(0)->nodeValue;
		}
		return $this->currentRound;
	}
}
class HTYouthLeague extends HTGlobal
{
	private $youthLeagueId = null;
	private $youthLeagueName = null;
	private $youthLeagueType = null;
	private $teamNumber = null;
	private $teams = array();
	private $season = null;

	/**
	 * Return youth league id
	 *
	 * @return Integer
	 */
	public function getYouthLeagueId()
	{
		if(!isset($this->youthLeagueId) || $this->youthLeagueId === null)
		{
			$this->youthLeagueId = $this->getXml()->getElementsByTagName('YouthLeagueID')->item(0)->nodeValue;
		}
		return $this->youthLeagueId;
	}

	/**
	 * Return youth league name
	 *
	 * @return String
	 */
	public function getYouthLeagueName()
	{
		if(!isset($this->youthLeagueName) || $this->youthLeagueName === null)
		{
			$this->youthLeagueName = $this->getXml()->getElementsByTagName('YouthLeagueName')->item(0)->nodeValue;
		}
		return $this->youthLeagueName;
	}

	/**
	 * Return youth league name
	 *
	 * @return String
	 */
	public function getYouthLeagueType()
	{
		if(!isset($this->youthLeagueType) || $this->youthLeagueType === null)
		{
			$this->youthLeagueType = $this->getXml()->getElementsByTagName('YouthLeagueType')->item(0)->nodeValue;
		}
		return $this->youthLeagueType;
	}

	/**
	 * Return teams number in youth league
	 *
	 * @return Integer
	 */
	public function getTeamsNumber()
	{
		if(!isset($this->teamNumber) || $this->teamNumber === null)
		{
			$this->teamNumber = $this->getXml()->getElementsByTagName('NrOfTeamsInLeague')->item(0)->nodeValue;
		}
		return $this->teamNumber;
	}

	/**
	 * Return HTLeagueTeam object
	 *
	 * @param Integer $index
	 * @return HTLeagueTeam
	 */
	public function getTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getTeamsNumber())
		{
			--$index;
			if(!isset($this->teams[$index]) || $this->teams[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//Team");
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->teams[$index] = new HTLeagueTeam($node);
			}
			return $this->teams[$index];
		}
		return null;
	}

	/**
	 * Return season number
	 *
	 * @return Integer
	 */
	public function getSeason()
	{
		if(!isset($this->season) || $this->season === null)
		{
			$this->season = $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
		}
		return $this->season;
	}
}
class HTLeagueTeam extends HTXml
{
	private $teamId = null;
	private $position = null;
	private $hasPostionChange = null;
	private $postionChange = null;
	private $teamName = null;
	private $numberPlayedMatches = null;
	private $goalsFor = null;
	private $goalsAgainst = null;
	private $points = null;
	private $won = null;
	private $draws = null;
	private $lost = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$this->teamId = $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
		}
		return $this->teamId;
	}

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getTeamName()
	{
		if(!isset($this->teamName) || $this->teamName === null)
		{
			$this->teamName = $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
		}
		return $this->teamName;
	}

	/**
	 * Return position
	 *
	 * @return Integer
	 */
	public function getPosition()
	{
		if(!isset($this->position) || $this->position === null)
		{
			$this->position = $this->getXml()->getElementsByTagName('Position')->item(0)->nodeValue;
		}
		return $this->position;
	}

	/**
	 * Return if position change is available
	 *
	 * @return Boolean
	 */
	public function isPositionChangeAvailable()
	{
		if(!isset($this->hasPostionChange) || $this->hasPostionChange === null)
		{
			$this->hasPostionChange = $this->getXml()->getElementsByTagName('PositionChange')->length > 0;
		}
		return $this->hasPostionChange;
	}

	/**
	 * Return position change
	 *
	 * @return Integer
	 */
	public function getPositionChange()
	{
		if($this->isPositionChangeAvailable())
		{
			if(!isset($this->postionChange) || $this->postionChange === null)
			{
				$this->postionChange = $this->getXml()->getElementsByTagName('PositionChange')->item(0)->nodeValue;
			}
			return $this->postionChange;
		}
		return null;
	}

	/**
	 * Return number of played matches
	 *
	 * @return Integer
	 */
	public function getNumberOfPlayedMatches()
	{
		if(!isset($this->numberPlayedMatches) || $this->numberPlayedMatches === null)
		{
			$this->numberPlayedMatches = $this->getXml()->getElementsByTagName('Matches')->item(0)->nodeValue;
		}
		return $this->numberPlayedMatches;
	}

	/**
	 * Return number of goal for team
	 *
	 * @return Integer
	 */
	public function getGoalsFor()
	{
		if(!isset($this->goalsFor) || $this->goalsFor === null)
		{
			$this->goalsFor = $this->getXml()->getElementsByTagName('GoalsFor')->item(0)->nodeValue;
		}
		return $this->goalsFor;
	}

	/**
	 * Return number of goal against team
	 *
	 * @return Integer
	 */
	public function getGoalsAgainst()
	{
		if(!isset($this->goalsAgainst) || $this->goalsAgainst === null)
		{
			$this->goalsAgainst = $this->getXml()->getElementsByTagName('GoalsAgainst')->item(0)->nodeValue;
		}
		return $this->goalsAgainst;
	}

	/**
	 * Return total points
	 *
	 * @return Integer
	 */
	public function getPoints()
	{
		if(!isset($this->points) || $this->points === null)
		{
			$this->points = $this->getXml()->getElementsByTagName('Points')->item(0)->nodeValue;
		}
		return $this->points;
	}

	/**
	 * Return goal average
	 *
	 * @return Integer
	 */
	public function getGoalAverage()
	{
		return $this->getGoalsFor() - $this->getGoalsAgainst();
	}

	/**
	 * Return total won matches
	 *
	 * @return Integer
	 */
	public function getWonNumber()
	{
		if(!isset($this->won) || $this->won === null)
		{
			$this->won = $this->getXml()->getElementsByTagName('Won')->item(0)->nodeValue;
		}
		return $this->won;
	}

	/**
	 * Return total draws matches
	 *
	 * @return Integer
	 */
	public function getDrawsNumber()
	{
		if(!isset($this->draws) || $this->draws === null)
		{
			$this->draws = $this->getXml()->getElementsByTagName('Draws')->item(0)->nodeValue;
		}
		return $this->draws;
	}

	/**
	 * Return total lost matches
	 *
	 * @return Integer
	 */
	public function getLostNumber()
	{
		if(!isset($this->lost) || $this->lost === null)
		{
			$this->lost = $this->getXml()->getElementsByTagName('Lost')->item(0)->nodeValue;
		}
		return $this->lost;
	}
}
class HTLeagueSeason extends HTCommonLeagueLevel
{
	private $season = null;
	private $rounds = array();
	private $teams = null;

	/**
	 * Return season number
	 *
	 * @return Integer
	 */
	public function getSeason()
	{
		if(!isset($this->season) || $this->season === null)
		{
			$this->season = $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
		}
		return $this->season;
	}

	/**
	 * Return HTLeagueRound object
	 *
	 * @param Integer $id
	 * @return HTLeagueRound
	 */
	public function getRound($id)
	{
		$id = round($id);
		if($id > 0 && $id < 15)
		{
			if(!isset($this->rounds[$id]) || $this->rounds[$id] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query("//MatchRound[.='".$id."']");
				$matches = new DOMDocument('1.0', 'UTF-8');
				foreach ($nodeList as $node)
				{
					$matches->appendChild($matches->importNode($node->parentNode, true));
				}
				$this->rounds[$id] = new HTLeagueRound($matches);
			}
			return $this->rounds[$id];
		}
		return null;
	}

	/**
	 * Return total goals of the season
	 *
	 * @return Integer
	 */
	public function getTotalGoals()
	{
		$total = 0;
		for($i=1; $i<15; $i++)
		{
			$total += $this->getRound($i)->getTotalGoals();
		}
		return $total;
	}

	/**
	 * Private function to get HTOldLeagueTeam objects
	 */
	private function getTeams()
	{
		if(!isset($this->teams) || $this->teams === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//Match");
			$tmp = array();
			foreach ($nodeList as $node)
			{
				$matches = new DOMDocument('1.0', 'UTF-8');
				$matches->appendChild($matches->importNode($node, true));
				$homeId = $matches->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
				$awayId = $matches->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;

				if(!isset($tmp[$homeId]))
				{
					$tmp[$homeId] = array('name'=>'', 'goalsfor'=>0, 'goalsagainst'=>0, 'won'=>0, 'lost'=>0, 'draw'=>0, 'points'=>0, 'played'=>0);
				}
				if(!isset($tmp[$awayId]))
				{
					$tmp[$awayId] = array('name'=>'', 'goalsfor'=>0, 'goalsagainst'=>0, 'won'=>0, 'lost'=>0, 'draw'=>0, 'points'=>0, 'played'=>0);
				}

				$tmp[$homeId]['name'] = $matches->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
				$tmp[$awayId]['name'] = $matches->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
				if($matches->getElementsByTagName('HomeGoals')->length)
				{
					$homeGoals = $matches->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
					$awayGoals = $matches->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
					$tmp[$homeId]['goalsfor'] += $homeGoals;
					$tmp[$homeId]['goalsagainst'] += $awayGoals;
					$tmp[$awayId]['goalsfor'] += $awayGoals;
					$tmp[$awayId]['goalsagainst'] += $homeGoals;
					$tmp[$homeId]['played']++;
					$tmp[$awayId]['played']++;
					if($homeGoals > $awayGoals)
					{
						$tmp[$homeId]['won']++;
						$tmp[$homeId]['points'] += 3;
						$tmp[$awayId]['lost']++;
					}
					elseif($homeGoals < $awayGoals)
					{
						$tmp[$awayId]['won']++;
						$tmp[$awayId]['points'] += 3;
						$tmp[$homeId]['lost']++;
					}
					elseif($homeGoals == $awayGoals)
					{
						$tmp[$awayId]['points']++;
						$tmp[$homeId]['points']++;
						$tmp[$homeId]['draw']++;
						$tmp[$awayId]['draw']++;
					}
				}
			}
			$list = array();
			foreach ($tmp as $id => $data)
			{
				$value = str_pad($data['points'], 2, 0, STR_PAD_LEFT) . substr(str_pad((((500 + ($data['goalsfor']-$data['goalsagainst'])) + ($data['goalsfor']/100))/1000), 10, 0, STR_PAD_RIGHT), 1);
				$list[$value.'-'.$data['name']] = new HTOldLeagueTeam($id, $data);
			}
			krsort($list);
			foreach ($list as $team)
			{
				$this->teams[] = $team;
			}
			unset($tmp, $list);
		}
	}

	/**
	 * Return HTOldLeagueTeam object
	 *
	 * @param Integer $index
	 * @return HTOldLeagueTeam
	 */
	public function getTeam($index)
	{
		if($index > 0 && $index < 9)
		{
			--$index;
			if(!isset($this->teams) || $this->teams === null)
			{
				$this->getTeams();
			}
			return $this->teams[$index];
		}
		return null;
	}
}
class HTYouthLeagueSeason extends HTGlobal
{
	private $youthLeagueId = null;
	private $youthLeagueName = null;
	private $season = null;
	private $matchNumber = null;
	private $matchs = array();
	private $nextMatch = null;
	private $nextMatches = array();
	private $lastMatch = null;
	private $lastMatches = array();
	private $playingMatches = array();
	const NOT_PLAYED = 'UPCOMING';
	const PLAYED = 'FINISHED';
	const PLAYING = 'ONGOING';

	/**
	 * Return youth league id
	 *
	 * @return Integer
	 */
	public function getYouthLeagueId()
	{
		if(!isset($this->youthLeagueId) || $this->youthLeagueId === null)
		{
			$this->youthLeagueId = $this->getXml()->getElementsByTagName('YouthLeagueID')->item(0)->nodeValue;
		}
		return $this->youthLeagueId;
	}

	/**
	 * Return youth league name
	 *
	 * @return String
	 */
	public function getYouthLeagueName()
	{
		if(!isset($this->youthLeagueName) || $this->youthLeagueName === null)
		{
			$this->youthLeagueName = $this->getXml()->getElementsByTagName('YouthLeagueName')->item(0)->nodeValue;
		}
		return $this->youthLeagueName;
	}

	/**
	 * Return season number
	 *
	 * @return Integer
	 */
	public function getSeason()
	{
		if(!isset($this->season) || $this->season === null)
		{
			$this->season = $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
		}
		return $this->season;
	}

	/**
	 * Return number of matches
	 *
	 * @return Integer
	 */
	public function getMatchNumber()
	{
		if(!isset($this->matchNumber) || $this->matchNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Match');
			$this->matchNumber = $nodeList->length;
		}
		return $this->matchNumber;
	}

	/**
	 * Return HTYouthLeagueMatch object
	 *
	 * @param Integer $number
	 * @return HTYouthLeagueMatch
	 */
	public function getMatch($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getMatchNumber())
		{
			--$number;
			if(!isset($this->matchs[$number]) || $this->matchs[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Match');
				$match = new DOMDocument('1.0', 'UTF-8');
				$match->appendChild($match->importNode($nodeList->item($number), true));
				$this->matchs[$number] = new HTYouthLeagueMatch($match);
			}
			return $this->matchs[$number];
		}
		return null;
	}

	/**
	 * Return next match
	 *
	 * @return HTYouthLeagueMatch
	 */
	public function getNextMatch()
	{
		if(!isset($this->nextMatch) || $this->nextMatch === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Status[.="'.self::NOT_PLAYED.'"]');
			$xmlMatch = new DOMDocument('1.0', 'UTF-8');
			$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item(0)->parentNode, true));
			$this->nextMatch = new HTYouthLeagueMatch($xmlMatch);
		}
		return $this->nextMatch;
	}

	/**
	 * Return next matches
	 *
	 * @return Array<HTYouthLeagueMatch>
	 */
	public function getNextMatches()
	{
		if(!isset($this->nextMatches) || $this->nextMatches === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Status[.="'.self::NOT_PLAYED.'"]');
			for($i=0; $i<$nodeList->length; $i++)
			{
				$xmlMatch = new DOMDocument('1.0', 'UTF-8');
				$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($i)->parentNode, true));
				$this->nextMatches[$i] = new HTYouthLeagueMatch($xmlMatch);
			}
		}
		return $this->nextMatches;
	}

	/**
	 * Return last match
	 *
	 * @return HTYouthLeagueMatch
	 */
	public function getLastMatch()
	{
		if(!isset($this->lastMatch) || $this->lastMatch === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Status[.="'.self::PLAYED.'"]');
			$xmlMatch = new DOMDocument('1.0', 'UTF-8');
			$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($nodeList->length - 1)->parentNode, true));
			$this->lastMatch = new HTYouthLeagueMatch($xmlMatch);
		}
		return $this->lastMatch;
	}

	/**
	 * Return last matches
	 *
	 * @return Array<HTYouthLeagueMatch>
	 */
	public function getLastMatches()
	{
		if(!isset($this->lastMatches) || $this->lastMatches === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Status[.="'.self::PLAYED.'"]');
			for($i=1; $i<=$nodeList->length; $i++)
			{
				$xmlMatch = new DOMDocument('1.0', 'UTF-8');
				$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($nodeList->length - $i)->parentNode, true));
				$this->lastMatches[$i] = new HTYouthLeagueMatch($xmlMatch);
			}
		}
		return $this->lastMatches;
	}

	/**
	 * Return playing matches
	 *
	 * @return Array<HTYouthLeagueMatch>
	 */
	public function getPlayingMatches()
	{
		if(!isset($this->playingMatches) || $this->playingMatches === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Status[.="'.self::PLAYING.'"]');
			for($i=1; $i<=$nodeList->length; $i++)
			{
				$xmlMatch = new DOMDocument('1.0', 'UTF-8');
				$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($i)->parentNode, true));
				$this->playingMatches[$i] = new HTYouthLeagueMatch($xmlMatch);
			}
		}
		return $this->playingMatches;
	}
}
class HTYouthLeagueMatch extends HTXml
{
	private $id = null;
	private $round = null;
	private $status = null;
	private $homeTeamId = null;
	private $homeTeamName = null;
	private $awayTeamId = null;
	private $awayTeamName = null;
	private $date = null;
	private $homeGoals = null;
	private $awayGoals = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return match round
	 *
	 * @return Integer
	 */
	public function getRound()
	{
		if(!isset($this->round) || $this->round === null)
		{
			$this->round = $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
		}
		return $this->round;
	}

	/**
	 * Return home team id
	 *
	 * @return Integer
	 */
	public function getHomeTeamId()
	{
		if(!isset($this->homeTeamId) || $this->homeTeamId === null)
		{
			$this->homeTeamId = $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
		}
		return $this->homeTeamId;
	}

	/**
	 * Return home team name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if(!isset($this->homeTeamName) || $this->homeTeamName === null)
		{
			$this->homeTeamName = $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
		}
		return $this->homeTeamName;
	}

	/**
	 * Return home goals
	 *
	 * @return Integer
	 */
	public function getHomeGoals()
	{
		if(!isset($this->homeGoals) || $this->homeGoals === null)
		{
			$this->homeGoals = $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
		}
		return $this->homeGoals;
	}

	/**
	 * Return away team id
	 *
	 * @return Integer
	 */
	public function getAwayTeamId()
	{
		if(!isset($this->awayTeamId) || $this->awayTeamId === null)
		{
			$this->awayTeamId = $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
		}
		return $this->awayTeamId;
	}

	/**
	 * Return away team name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if(!isset($this->awayTeamName) || $this->awayTeamName === null)
		{
			$this->awayTeamName = $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
		}
		return $this->awayTeamName;
	}

	/**
	 * Return away goals
	 *
	 * @return Integer
	 */
	public function getAwayGoals()
	{
		if(!isset($this->awayGoals) || $this->awayGoals === null)
		{
			$this->awayGoals = $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
		}
		return $this->awayGoals;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return match status : UPCOMING, ONGOING, FINISHED
	 *
	 * @return String
	 */
	public function getStatus()
	{
		if(!isset($this->status) || $this->status === null)
		{
			$this->status = $this->getXml()->getElementsByTagName('Status')->item(0)->nodeValue;
		}
		return $this->status;
	}
}
class HTOldLeagueTeam
{
	private $id = null;
	private $name = null;
	private $goalsFor = null;
	private $goalsAgainst = null;
	private $won = null;
	private $draw = null;
	private $lost = null;
	private $points = null;
	private $played = null;

	/**
	 * @param Integer $id
	 * @param Array $data
	 */
	public function __construct($id, $data)
	{
		$this->id = $id;
		$this->name = $data['name'];
		$this->points = $data['points'];
		$this->goalsFor = $data['goalsfor'];
		$this->goalsAgainst = $data['goalsagainst'];
		$this->won = $data['won'];
		$this->draw = $data['draw'];
		$this->lost = $data['lost'];
		$this->played = $data['played'];
	}

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Return points number
	 *
	 * @return Integer
	 */
	public function getPoints()
	{
		return $this->points;
	}

	/**
	 * Return goals for team
	 *
	 * @return Integer
	 */
	public function getGoalsFor()
	{
		return $this->goalsFor;
	}

	/**
	 * Return goals against team
	 *
	 * @return Integer
	 */
	public function getGoalsAgainst()
	{
		return $this->goalsAgainst;
	}

	/**
	 * Return goal average
	 *
	 * @return Integer
	 */
	public function getGoalAverage()
	{
		return $this->getGoalsFor()-$this->getGoalsAgainst();
	}

	/**
	 * Return number of won matches
	 *
	 * @return Integer
	 */
	public function getWon()
	{
		return $this->won;
	}

	/**
	 * Return number of draw
	 *
	 * @return Integer
	 */
	public function getDraw()
	{
		return $this->draw;
	}

	/**
	 * Return number of lost matches
	 *
	 * @return Integer
	 */
	public function getLost()
	{
		return $this->lost;
	}

	/**
	 * Return number of played matches
	 *
	 * @return Integer
	 */
	public function getNumberPlayedMatches()
	{
		return $this->played;
	}
}
class HTLeagueRound extends HTXml
{
	private $matches = array();

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return HTLeagueMatch object
	 *
	 * @param Integer $index
	 * @return HTLeagueMatch
	 */
	public function getMatch($index)
	{
		$index = round($index);
		if($index > 0 && $index < 5)
		{
			--$index;
			if(!isset($this->matches[$index]) || $this->matches[$index] === null)
			{
				$match = new DOMDocument('1.0', 'UTF-8');
				$match->appendChild($match->importNode($this->getXml()->getElementsByTagName('Match')->item($index), true));
				$this->matches[$index] = new HTLeagueMatch($match);
			}
			return $this->matches[$index];
		}
		return null;
	}

	/**
	 * Return total goal of the round
	 *
	 * @return Integer
	 */
	public function getTotalGoals()
	{
		$total = 0;
		for($i=1; $i<5; $i++)
		{
			$total += $this->getMatch($i)->getTotalGoals();
		}
		return $total;
	}
}
class HTLeagueMatch extends HTXml
{
	private $matchId = null;
	private $matchRound = null;
	private $homeTeamId = null;
	private $homeTeamName = null;
	private $awayTeamId = null;
	private $awayTeamName = null;
	private $matchDate = null;
	private $homeGoals = null;
	private $awayGoals = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getMatchId()
	{
		if(!isset($this->matchId) || $this->matchId === null)
		{
			$this->matchId = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->matchId;
	}

	/**
	 * Return match round
	 *
	 * @return Integer
	 */
	public function getMatchRound()
	{
		if(!isset($this->matchRound) || $this->matchRound === null)
		{
			$this->matchRound = $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
		}
		return $this->matchRound;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->matchDate[$format]) || $this->matchDate[$format] === null)
		{
			$this->matchDate[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->matchDate[$format] = HTFunction::convertDate($this->matchDate[$format], $format);
			}
		}
		return $this->matchDate[$format];
	}

	/**
	 * Return home team id
	 *
	 * @return Integer
	 */
	public function getHomeTeamId()
	{
		if(!isset($this->homeTeamId) || $this->homeTeamId === null)
		{
			$this->homeTeamId = $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
		}
		return $this->homeTeamId;
	}

	/**
	 * Return home team name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if(!isset($this->homeTeamName) || $this->homeTeamName === null)
		{
			$this->homeTeamName = $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
		}
		return $this->homeTeamName;
	}

	/**
	 * Return away team id
	 *
	 * @return Integer
	 */
	public function getAwayTeamId()
	{
		if(!isset($this->awayTeamId) || $this->awayTeamId === null)
		{
			$this->awayTeamId = $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
		}
		return $this->awayTeamId;
	}

	/**
	 * Return away team name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if(!isset($this->awayTeamName) || $this->awayTeamName === null)
		{
			$this->awayTeamName = $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
		}
		return $this->awayTeamName;
	}

	/**
	 * Return number of home goals
	 *
	 * @return Integer
	 */
	public function getHomeGoals()
	{
		if(!isset($this->homeGoals) || $this->homeGoals === null)
		{
			$node = $this->getXml()->getElementsByTagName('HomeGoals');
			if($node !== null && $node->length)
			{
				$this->homeGoals = $node->item(0)->nodeValue;
			}
		}
		return $this->homeGoals;
	}

	/**
	 * Return number of away goals
	 *
	 * @return Integer
	 */
	public function getAwayGoals()
	{
		if(!isset($this->awayGoals) || $this->awayGoals === null)
		{
			$node = $this->getXml()->getElementsByTagName('AwayGoals');
			if($node !== null && $node->length)
			{
				$this->awayGoals = $node->item(0)->nodeValue;
			}
		}
		return $this->awayGoals;
	}

	/**
	 * Return total goal of the match
	 *
	 * @return Integer
	 */
	public function getTotalGoals()
	{
		return $this->getHomeGoals() + $this->getAwayGoals();
	}
}
class HTWorldDetails extends HTGlobal
{
	private $leagueCount = null;
	private $leagues = array();
	private $leaguesById = array();
	private $leaguesByCountryId = array();
	private $leaguesByName = array();

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		parent::__construct($xml);
	}

	/**
	 * Return number of league
	 *
	 * @return Integer
	 */
	public function getLeagueNumber()
	{
		if(!isset($this->leagueCount) || $this->leagueCount === null)
		{
			$this->leagueCount = $this->getXml()->getElementsByTagName('League')->length;
		}
		return $this->leagueCount;
	}

	/**
	 * Return HTWorldLeague object
	 *
	 * @param Integer $number
	 * @return HTWorldLeague
	 */
	public function getLeague($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getLeagueNumber())
		{
			--$number;
			if(!isset($this->leagues[$number]) || $this->leagues[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//League');
				$league = new DOMDocument('1.0', 'UTF-8');
				$league->appendChild($league->importNode($nodeList->item($number), true));
				$this->leagues[$number] = new HTWorldLeague($league);
			}
			return $this->leagues[$number];
		}
		return null;
	}

	/**
	 * Return HTWorldLeague object
	 *
	 * @param Integer $leagueId
	 * @return HTWorldLeague
	 */
	public function getLeagueById($leagueId)
	{
		if(!isset($this->leaguesById[$leagueId]) || $this->leaguesById[$leagueId] === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//LeagueID[.="'.$leagueId.'"]');
			$league = new DOMDocument('1.0', 'UTF-8');
			$league->appendChild($league->importNode($nodeList->item(0)->parentNode, true));
			$this->leaguesById[$leagueId] = new HTWorldLeague($league);
		}
		return $this->leaguesById[$leagueId];
	}

	/**
	 * Return HTWorldLeague object
	 *
	 * @param Integer $leagueId
	 * @return HTWorldLeague
	 */
	public function getLeagueByCountryId($countryId)
	{
		if(!isset($this->leaguesByCountryId[$countryId]) || $this->leaguesByCountryId[$countryId] === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//CountryID[.="'.$countryId.'"]');
			$league = new DOMDocument('1.0', 'UTF-8');
			$league->appendChild($league->importNode($nodeList->item(0)->parentNode->parentNode, true));
			$this->leaguesByCountryId[$countryId] = new HTWorldLeague($league);
		}
		return $this->leaguesByCountryId[$countryId];
	}

	/**
	 * Return HTWorldLeague object
	 *
	 * @param String $leagueName
	 * @return HTWorldLeague
	 */
	public function getLeagueByName($leagueName)
	{
		if(!isset($this->leaguesByName[$leagueName]) || $this->leaguesByName[$leagueName] === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//LeagueName[.="'.$leagueName.'"]');
			$league = new DOMDocument('1.0', 'UTF-8');
			$league->appendChild($league->importNode($nodeList->item(0)->parentNode, true));
			$this->leaguesByName[$leagueName] = new HTWorldLeague($league);
		}
		return $this->leaguesByName[$leagueName];
	}
}
class HTWorldLanguages extends HTGlobal
{
	private $langueCount = null;
	private $langues = array();

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		parent::__construct($xml);
	}
	/**
	 * Return number of languages
	 *
	 * @return Integer
	 */
	public function getTotalLanguages()
	{
		if(!isset($this->langueCount) || $this->langueCount === null)
		{
			$this->langueCount = $this->getXml()->getElementsByTagName('Language')->length;
		}
		return $this->langueCount;
	}

	/**
	 * Return HTWorldLanguage object
	 *
	 * @param Integer $number
	 * @return HTWorldLanguage
	 */
	public function getLanguage($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getTotalLanguages())
		{
			--$number;
			if(!isset($this->langues[$number]) || $this->langues[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Language');
				$langue = new DOMDocument('1.0', 'UTF-8');
				$langue->appendChild($langue->importNode($nodeList->item($number), true));
				$this->langues[$number] = new HTWorldLanguage($langue);
			}
			return $this->langues[$number];
		}
		return null;
	}
}
class HTWorldLanguage extends HTXml
{
	private $id = null;
	private $name = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return language id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('LanguageID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return language name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('LanguageName')->item(0)->nodeValue;
		}
		return $this->name;
	}
}
class HTWorldLeague extends HTXml
{
	private $id = null;
	private $name = null;
	private $season = null;
	private $seasonOffset = null;
	private $matchRound = null;
	private $shortName = null;
	private $continent = null;
	private $zoneName = null;
	private $englishName = null;
	private $countryId = null;
	private $countryName = null;
	private $countryDate = null;
	private $countryTime = null;
	private $currencyName = null;
	private $currencyRate = null;
	private $cupId = null;
	private $cupName = null;
	private $numberActiveTeams = null;
	private $numberActiveUsers = null;
	private $numberWaitingUsers = null;
	private $trainingDate = null;
	private $economyDate = null;
	private $cupMatchDate = null;
	private $seriesMatchDate = null;
	private $numberLevel = null;
	private $ntId = null;
	private $u20Id = null;
	private $regionsNumber = null;
	private $regions = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return current season number
	 *
	 * @return Integer
	 */
	public function getSeasonNumber()
	{
		if(!isset($this->season) || $this->season === null)
		{
			$this->season = $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
		}
		return $this->season;
	}

	/**
	 * Return season offset to swedish's season
	 *
	 * @return Integer
	 */
	public function getSeasonOffset()
	{
		if(!isset($this->seasonOffset) || $this->seasonOffset === null)
		{
			$this->seasonOffset = $this->getXml()->getElementsByTagName('SeasonOffset')->item(0)->nodeValue;
		}
		return $this->seasonOffset;
	}

	/**
	 * Return match round number
	 *
	 * @return Integer
	 */
	public function getMatchRound()
	{
		if(!isset($this->matchRound) || $this->matchRound === null)
		{
			$this->matchRound = $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
		}
		return $this->matchRound;
	}

	/**
	 * Return league short name
	 *
	 * @return String
	 */
	public function getShortName()
	{
		if(!isset($this->shortName) || $this->shortName === null)
		{
			$this->shortName = $this->getXml()->getElementsByTagName('ShortName')->item(0)->nodeValue;
		}
		return $this->shortName;
	}

	/**
	 * Return continent
	 *
	 * @return String
	 */
	public function getContinent()
	{
		if(!isset($this->continent) || $this->continent === null)
		{
			$this->continent = $this->getXml()->getElementsByTagName('Continent')->item(0)->nodeValue;
		}
		return $this->continent;
	}

	/**
	 * Return world zone
	 *
	 * @return String
	 */
	public function getZoneName()
	{
		if(!isset($this->zoneName) || $this->zoneName === null)
		{
			$this->zoneName = $this->getXml()->getElementsByTagName('ZoneName')->item(0)->nodeValue;
		}
		return $this->zoneName;
	}

	/**
	 * Return english name
	 *
	 * @return String
	 */
	public function getEnglishName()
	{
		if(!isset($this->englishName) || $this->englishName === null)
		{
			$this->englishName = $this->getXml()->getElementsByTagName('EnglishName')->item(0)->nodeValue;
		}
		return $this->englishName;
	}

	/**
	 * Return country id
	 *
	 * @return Integer
	 */
	public function getCountryId()
	{
		if(!isset($this->countryId) || $this->countryId === null)
		{
			$this->countryId = $this->getXml()->getElementsByTagName('CountryID')->item(0)->nodeValue;
		}
		return $this->countryId;
	}

	/**
	 * Return country name
	 *
	 * @return String
	 */
	public function getCountryName()
	{
		if(!isset($this->countryName) || $this->countryName === null)
		{
			$this->countryName = $this->getXml()->getElementsByTagName('CountryName')->item(0)->nodeValue;
		}
		return $this->countryName;
	}

	/**
	 * Return country date format
	 *
	 * @return String
	 */
	public function getCountryDateFormat()
	{
		if(!isset($this->countryDate) || $this->countryDate === null)
		{
			$this->countryDate = $this->getXml()->getElementsByTagName('DateFormat')->item(0)->nodeValue;
		}
		return $this->countryDate;
	}

	/**
	 * Return country time format
	 *
	 * @return String
	 */
	public function getCountryTimeFormat()
	{
		if(!isset($this->countryTime) || $this->countryTime === null)
		{
			$this->countryTime = $this->getXml()->getElementsByTagName('TimeFormat')->item(0)->nodeValue;
		}
		return $this->countryTime;
	}

	/**
	 * Return currency name
	 *
	 * @return String
	 */
	public function getCurrencyName()
	{
		if(!isset($this->currencyName) || $this->currencyName === null)
		{
			$this->currencyName = $this->getXml()->getElementsByTagName('CurrencyName')->item(0)->nodeValue;
		}
		return $this->currencyName;
	}

	/**
	 * Return currency rate
	 *
	 * @return Integer
	 */
	public function getCurrencyRate()
	{
		if(!isset($this->currencyRate) || $this->currencyRate === null)
		{
			$this->currencyRate = $this->getXml()->getElementsByTagName('CurrencyRate')->item(0)->nodeValue;
		}
		return $this->currencyRate;
	}

	/**
	 * Return cup id
	 *
	 * @return Integer
	 */
	public function getCupId()
	{
		if(!isset($this->cupId) || $this->cupId === null)
		{
			$this->cupId = $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
		}
		return $this->cupId;
	}

	/**
	 * Return cup name
	 *
	 * @return String
	 */
	public function getCupName()
	{
		if(!isset($this->cupName) || $this->cupName === null)
		{
			$this->cupName = $this->getXml()->getElementsByTagName('CupName')->item(0)->nodeValue;
		}
		return $this->cupName;
	}

	/**
	 * Return number of active teams
	 *
	 * @return Integer
	 */
	public function getNumberActiveTeams()
	{
		if(!isset($this->numberActiveTeams) || $this->numberActiveTeams === null)
		{
			$this->numberActiveTeams = $this->getXml()->getElementsByTagName('ActiveTeams')->item(0)->nodeValue;
		}
		return $this->numberActiveTeams;
	}

	/**
	 * Return number of active users
	 *
	 * @return Integer
	 */
	public function getNumberActiveUsers()
	{
		if(!isset($this->numberActiveUsers) || $this->numberActiveUsers === null)
		{
			$this->numberActiveUsers = $this->getXml()->getElementsByTagName('ActiveUsers')->item(0)->nodeValue;
		}
		return $this->numberActiveUsers;
	}

	/**
	 * Return number of waiting users
	 *
	 * @return Integer
	 */
	public function getNumberWaitingUsers()
	{
		if(!isset($this->numberWaitingUsers) || $this->numberWaitingUsers === null)
		{
			$this->numberWaitingUsers = $this->getXml()->getElementsByTagName('WaitingUsers')->item(0)->nodeValue;
		}
		return $this->numberWaitingUsers;
	}

	/**
	 * Return training update date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getTrainingDate($format = null)
	{
		if(!isset($this->trainingDate[$format]) || $this->trainingDate[$format] === null)
		{
			$this->trainingDate[$format] = $this->getXml()->getElementsByTagName('TrainingDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->trainingDate[$format] = HTFunction::convertDate($this->trainingDate[$format], $format);
			}
		}
		return $this->trainingDate[$format];
	}

	/**
	 * Return economy update date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getEconomyDate($format = null)
	{
		if(!isset($this->economyDate[$format]) || $this->economyDate[$format] === null)
		{
			$this->economyDate[$format] = $this->getXml()->getElementsByTagName('EconomyDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->economyDate[$format] = HTFunction::convertDate($this->economyDate[$format], $format);
			}
		}
		return $this->economyDate[$format];
	}

	/**
	 * Return cup match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getCupMatchDate($format = null)
	{
		if(!isset($this->cupMatchDate[$format]) || $this->cupMatchDate[$format] === null)
		{
			$this->cupMatchDate[$format] = $this->getXml()->getElementsByTagName('CupMatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->cupMatchDate[$format] = HTFunction::convertDate($this->cupMatchDate[$format], $format);
			}
		}
		return $this->cupMatchDate[$format];
	}

	/**
	 * Return series match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getSeriesMatchDate($format = null)
	{
		if(!isset($this->seriesMatchDate[$format]) || $this->seriesMatchDate[$format] === null)
		{
			$this->seriesMatchDate[$format] = $this->getXml()->getElementsByTagName('SeriesMatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->seriesMatchDate[$format] = HTFunction::convertDate($this->seriesMatchDate[$format], $format);
			}
		}
		return $this->seriesMatchDate[$format];
	}

	/**
	 * Return max league level
	 *
	 * @return Integer
	 */
	public function getNumberLevel()
	{
		if(!isset($this->numberLevel) || $this->numberLevel === null)
		{
			$this->numberLevel = $this->getXml()->getElementsByTagName('NumberOfLevels')->item(0)->nodeValue;
		}
		return $this->numberLevel;
	}

	/**
	 * Return national team id
	 *
	 * @return Integer
	 */
	public function getNationalTeamId()
	{
		if(!isset($this->ntId) || $this->ntId === null)
		{
			$this->ntId = $this->getXml()->getElementsByTagName('NationalTeamId')->item(0)->nodeValue;
		}
		return $this->ntId;
	}

	/**
	 * Return U20 team id
	 *
	 * @return Integer
	 */
	public function getU20TeamId()
	{
		if(!isset($this->u20Id) || $this->u20Id === null)
		{
			$this->u20Id = $this->getXml()->getElementsByTagName('U20TeamId')->item(0)->nodeValue;
		}
		return $this->u20Id;
	}

	/**
	 * Return number of regions
	 *
	 * @return Integer
	 */
	public function getRegionsNumber()
	{
		if(!isset($this->regionsNumber) || $this->regionsNumber === null)
		{
			$this->regionsNumber = $this->getXml()->getElementsByTagName('Region')->length;
		}
		return $this->regionsNumber;
	}

	/**
	 * Return HTWorldRegion object
	 *
	 * @param Integer $index
	 * @return HTWorldRegion
	 */
	public function getRegion($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getRegionsNumber())
		{
			--$index;
			if(!isset($this->regions[$index]) || $this->regions[$index] === null)
			{
				$nodeList = $this->getXml()->getElementsByTagName('Region');
				if($nodeList->length)
				{
					$region = new DOMDocument('1.0', 'UTF-8');
					$region->appendChild($region->importNode($nodeList->item($index), true));
					$this->regions[$index] = new HTWorldRegion($region);
				}
			}
			return $this->regions[$index];
		}
		return null;
	}
}
class HTWorldRegion extends HTXml
{
	private $id = null;
	private $name = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return region id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return region name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
		}
		return $this->name;
	}
}
class HTTraining extends HTCommonSubscriber
{
	private $trainingLevel = null;
	private $newTrainingLevelAvailable = null;
	private $newTrainingLevel = null;
	private $trainingType = null;
	private $staminaTrainingPart = null;
	private $trainerId = null;
	private $trainerName = null;
	private $trainerArrivalDate = null;
	private $moraleAvailable = null;
	private $morale = null;
	private $selfConfidenceAvailable = null;
	private $selfConfidence = null;
	private $exprience442 = null;
	private $exprience433 = null;
	private $exprience451 = null;
	private $exprience352 = null;
	private $exprience532 = null;
	private $exprience343 = null;
	private $exprience541 = null;
	private $exprience523 = null;
	private $exprience550 = null;
	private $exprience253 = null;
	private $specialTrainingPlayers = null;
	private $specialTrainingPlayersNumber = null;
	private $lastTrainingTrainingType = null;
	private $lastTrainingTrainingLevel = null;
	private $lastTrainingStaminaTrainingPart = null;

	/**
	 * Return training level
	 *
	 * @return Integer
	 */
	public function getTrainingLevel()
	{
		if(!isset($this->trainingLevel) || $this->trainingLevel === null)
		{
			$this->trainingLevel = $this->getXml()->getElementsByTagName('TrainingLevel')->item(0)->nodeValue;
		}
		return $this->trainingLevel;
	}

	/**
	 * Return new training level if available
	 *
	 * @return Integer
	 */
	public function getNewTrainingLevel()
	{
		if(!isset($this->newTrainingLevelAvailable) || $this->newTrainingLevelAvailable === null)
		{
			$this->supportersPopularityAvailable = $this->getXml()->getElementsByTagName('NewTrainingLevel')->item(0)->getAttribute('Available');
		}
		if($this->newTrainingLevelAvailable == true)
		{
			if(!isset($this->newTrainingLevel) || $this->newTrainingLevel === null)
			{
				$this->newTrainingLevel = $this->getXml()->getElementsByTagName('NewTrainingLevel')->item(0)->nodeValue;
			}
			return $this->newTrainingLevel;
		}
		return null;
	}

	/**
	 * Return training type
	 *
	 * @return Integer
	 */
	public function getTrainingType()
	{
		if(!isset($this->trainingType) || $this->trainingType === null)
		{
			$this->trainingType = $this->getXml()->getElementsByTagName('TrainingType')->item(0)->nodeValue;
		}
		return $this->trainingType;
	}

	/**
	 * Return training stamina part
	 *
	 * @return Integer
	 */
	public function getStaminaTrainingPart()
	{
		if(!isset($this->staminaTrainingPart) || $this->staminaTrainingPart === null)
		{
			$this->staminaTrainingPart = $this->getXml()->getElementsByTagName('StaminaTrainingPart')->item(0)->nodeValue;
		}
		return $this->staminaTrainingPart;
	}

	/**
	 * Return trainer id
	 *
	 * @return Integer
	 */
	public function getTrainerId()
	{
		if(!isset($this->trainerId) || $this->trainerId === null)
		{
			$this->trainerId = $this->getXml()->getElementsByTagName('TrainerID')->item(0)->nodeValue;
		}
		return $this->trainerId;
	}

	/**
	 * Return trainer name
	 *
	 * @return String
	 */
	public function getTrainerName()
	{
		if(!isset($this->trainerName) || $this->trainerName === null)
		{
			$this->trainerName = $this->getXml()->getElementsByTagName('TrainerName')->item(0)->nodeValue;
		}
		return $this->trainerName;
	}

	/**
	 * Return trainer arrival date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getTrainerArrivalDate($format = null)
	{
		if(!isset($this->trainerArrivalDate[$format]) || $this->trainerArrivalDate[$format] === null)
		{
			$this->trainerArrivalDate[$format] = $this->getXml()->getElementsByTagName('ArrivalDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->trainerArrivalDate[$format] = HTFunction::convertDate($this->trainerArrivalDate[$format], $format);
			}
		}
		return $this->trainerArrivalDate[$format];
	}

	/**
	 * Return team spirit level
	 *
	 * @return Integer
	 */
	public function getTeamSpirit()
	{
		if(!isset($this->moraleAvailable) || $this->moraleAvailable === null)
		{
			$this->moraleAvailable = strtolower($this->getXml()->getElementsByTagName('Morale')->item(0)->getAttribute('Available')) == "true";
		}
		if($this->moraleAvailable == true)
		{
			if(!isset($this->morale) || $this->morale === null)
			{
				$this->morale = $this->getXml()->getElementsByTagName('Morale')->item(0)->nodeValue;
			}
			return $this->morale;
		}
		return null;
	}

	/**
	 * Return self confidence level
	 *
	 * @return Integer
	 */
	public function getSelfConfidence()
	{
		if(!isset($this->selfConfidenceAvailable) || $this->selfConfidenceAvailable === null)
		{
			$this->selfConfidenceAvailable = strtolower($this->getXml()->getElementsByTagName('SelfConfidence')->item(0)->getAttribute('Available')) == "true";
		}
		if($this->selfConfidenceAvailable == true)
		{
			if(!isset($this->selfConfidence) || $this->selfConfidence === null)
			{
				$this->selfConfidence = $this->getXml()->getElementsByTagName('SelfConfidence')->item(0)->nodeValue;
			}
			return $this->selfConfidence;
		}
		return null;
	}

	/**
	 * Return 442 experience level
	 *
	 * @return Integer
	 */
	public function get442Experience()
	{
		if(!isset($this->exprience442) || $this->exprience442 === null)
		{
			$this->exprience442 = $this->getXml()->getElementsByTagName('Experience442')->item(0)->nodeValue;
		}
		return $this->exprience442;
	}

	/**
	 * Return 343 experience level
	 *
	 * @return Integer
	 */
	public function get433Experience()
	{
		if(!isset($this->exprience433) || $this->exprience433 === null)
		{
			$this->exprience433 = $this->getXml()->getElementsByTagName('Experience433')->item(0)->nodeValue;
		}
		return $this->exprience433;
	}

	/**
	 * Return 451 experience level
	 *
	 * @return Integer
	 */
	public function get451Experience()
	{
		if(!isset($this->exprience451) || $this->exprience451 === null)
		{
			$this->exprience451 = $this->getXml()->getElementsByTagName('Experience451')->item(0)->nodeValue;
		}
		return $this->exprience451;
	}

	/**
	 * Return 352 experience level
	 *
	 * @return Integer
	 */
	public function get352Experience()
	{
		if(!isset($this->exprience352) || $this->exprience352 === null)
		{
			$this->exprience352 = $this->getXml()->getElementsByTagName('Experience352')->item(0)->nodeValue;
		}
		return $this->exprience352;
	}

	/**
	 * Return 532 experience level
	 *
	 * @return Integer
	 */
	public function get532Experience()
	{
		if(!isset($this->exprience532) || $this->exprience532 === null)
		{
			$this->exprience532 = $this->getXml()->getElementsByTagName('Experience532')->item(0)->nodeValue;
		}
		return $this->exprience532;
	}

	/**
	 * Return 343 experience level
	 *
	 * @return Integer
	 */
	public function get343Experience()
	{
		if(!isset($this->exprience343) || $this->exprience343 === null)
		{
			$this->exprience343 = $this->getXml()->getElementsByTagName('Experience343')->item(0)->nodeValue;
		}
		return $this->exprience343;
	}

	/**
	 * Return 541 experience level
	 *
	 * @return Integer
	 */
	public function get541Experience()
	{
		if(!isset($this->exprience541) || $this->exprience541 === null)
		{
			$this->exprience541 = $this->getXml()->getElementsByTagName('Experience541')->item(0)->nodeValue;
		}
		return $this->exprience541;
	}

	/**
	 * Return 523 experience level
	 *
	 * @return Integer
	 */
	public function get523Experience()
	{
		if(!isset($this->exprience523) || $this->exprience523 === null)
		{
			$this->exprience523 = $this->getXml()->getElementsByTagName('Experience523')->item(0)->nodeValue;
		}
		return $this->exprience523;
	}

	/**
	 * Return 550 experience level
	 *
	 * @return Integer
	 */
	public function get550Experience()
	{
		if(!isset($this->exprience550) || $this->exprience550 === null)
		{
			$this->exprience550 = $this->getXml()->getElementsByTagName('Experience550')->item(0)->nodeValue;
		}
		return $this->exprience550;
	}

	/**
	 * Return 253 experience level
	 *
	 * @return Integer
	 */
	public function get253Experience()
	{
		if(!isset($this->exprience253) || $this->exprience253 === null)
		{
			$this->exprience253 = $this->getXml()->getElementsByTagName('Experience253')->item(0)->nodeValue;
		}
		return $this->exprience253;
	}

	/**
	 * Return number of player with special training
	 *
	 * @return Integer
	 */
	public function getSpecialTrainingPlayerNumber()
	{
		if(!isset($this->specialTrainingPlayersNumber) || $this->specialTrainingPlayersNumber === null)
		{
			$this->specialTrainingPlayersNumber = $this->getXml()->getElementsByTagName('Player')->length;
		}
		return $this->specialTrainingPlayersNumber;
	}

	/**
	 * Return HTSpecialTrainingPlayer object
	 *
	 * @param Integer $index
	 * @return HTSpecialTrainingPlayer
	 */
	public function getSpecialTrainingPlayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getSpecialTrainingPlayerNumber())
		{
			--$index;
			if(!isset($this->specialTrainingPlayers[$index]) || $this->specialTrainingPlayers[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Player');
				$player = new DOMDocument('1.0', 'UTF-8');
				$player->appendChild($player->importNode($nodeList->item($index)->parentNode, true));
				$this->specialTrainingPlayers[$index] = new HTSpecialTrainingPlayer($player);
			}
			return $this->specialTrainingPlayers[$index];
		}
		return null;
	}

	/**
	 * Return last training type
	 *
	 * @return Integer
	 */
	public function getLastTrainingTrainingType()
	{
		if(!isset($this->lastTrainingTrainingType) || $this->lastTrainingTrainingType === null)
		{
			$this->lastTrainingTrainingType = $this->getXml()->getElementsByTagName('LastTrainingTrainingType')->item(0)->nodeValue;
		}
		return $this->lastTrainingTrainingType;
	}

	/**
	 * Return last training level
	 *
	 * @return Integer
	 */
	public function getLastTrainingTrainingLevel()
	{
		if(!isset($this->lastTrainingTrainingLevel) || $this->lastTrainingTrainingLevel === null)
		{
			$this->lastTrainingTrainingLevel = $this->getXml()->getElementsByTagName('LastTrainingTrainingLevel')->item(0)->nodeValue;
		}
		return $this->lastTrainingTrainingLevel;
	}

	/**
	 * Return last training stamina part level
	 *
	 * @return Integer
	 */
	public function getLastTrainingStaminaTrainingPart()
	{
		if(!isset($this->lastTrainingStaminaTrainingPart) || $this->lastTrainingStaminaTrainingPart === null)
		{
			$this->lastTrainingStaminaTrainingPart = $this->getXml()->getElementsByTagName('LastTrainingStaminaTrainingPart')->item(0)->nodeValue;
		}
		return $this->lastTrainingStaminaTrainingPart;
	}
}
class HTSpecialTrainingPlayer extends HTXml
{
	private $playerId = null;
	private $trainingId = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getTrainingId()
	{
		if(!isset($this->trainingId) || $this->trainingId === null)
		{
			$this->trainingId = $this->getXml()->getElementsByTagName('SpecialTrainingTypeID')->item(0)->nodeValue;
		}
		return $this->trainingId;
	}
}
class HTTrainingStats extends HTGlobal
{
	private $leagueId = null;
	private $numberTrainingType = null;
	private $trainingType = array();

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Return number of training types
	 *
	 * @return Integer
	 */
	public function getNumberTrainingType()
	{
		if(!isset($this->numberTrainingType) || $this->numberTrainingType === null)
		{
			$this->numberTrainingType = $this->getXml()->getElementsByTagName('TrainingStat')->length;
		}
		return $this->numberTrainingType;
	}

	/**
	 * Return HTTrainingType object
	 *
	 * @param Integer $index
	 * @return HTTrainingType
	 */
	public function getTrainingType($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberTrainingType())
		{
			--$index;
			if(!isset($this->trainingType[$index]) || $this->trainingType[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//TrainingType');
				$training = new DOMDocument('1.0', 'UTF-8');
				$training->appendChild($training->importNode($nodeList->item($index)->parentNode, true));
				$this->trainingType[$index] = new HTTrainingType($training);
			}
			return $this->trainingType[$index];
		}
		return null;
	}
}
class HTTrainingType extends HTXml
{
	private $type = null;
	private $numberTeams = null;
	private $percentTeams = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return type of training
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('TrainingType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return number of teams training this type of training
	 *
	 * @return Integer
	 */
	public function getNumberOfTeams()
	{
		if(!isset($this->numberTeams) || $this->numberTeams === null)
		{
			$this->numberTeams = $this->getXml()->getElementsByTagName('NumberOfTeams')->item(0)->nodeValue;
		}
		return $this->numberTeams;
	}

	/**
	 * Return percentage of teams training this type of training
	 *
	 * @return Integer
	 */
	public function getPercentageOfTeams()
	{
		if(!isset($this->percentTeams) || $this->percentTeams === null)
		{
			$this->percentTeams = $this->getXml()->getElementsByTagName('FractionOfTeams')->item(0)->nodeValue;
		}
		return $this->percentTeams;
	}
}
class HTTeamTransferHistory extends HTCommonTeam
{
	private $activatedDate = null;
	private $sumBuys = null;
	private $sumSales = null;
	private $numberBuys = null;
	private $numberSales = null;
	private $numberTransfers = null;
	private $transfers = array();
	private $startDate = null;
	private $endDate = null;

	/**
	 * Return team activated date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getTeamActivatedDate($format = null)
	{
		if(!isset($this->activatedDate[$format]) || $this->activatedDate[$format] === null)
		{
			$this->activatedDate[$format] = $this->getXml()->getElementsByTagName('ActivatedDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->activatedDate[$format] = HTFunction::convertDate($this->activatedDate[$format], $format);
			}
		}
		return $this->activatedDate[$format];
	}

	/**
	 * Return total sum of teams buys
	 *
	 * @return Integer
	 */
	public function getSumOfBuys()
	{
		if(!isset($this->sumBuys) || $this->sumBuys === null)
		{
			$this->sumBuys = $this->getXml()->getElementsByTagName('TotalSumOfBuys')->item(0)->nodeValue;
		}
		return $this->sumBuys;
	}

	/**
	 * Return total sum of teams sales
	 *
	 * @return Integer
	 */
	public function getSumOfSales()
	{
		if(!isset($this->sumSales) || $this->sumSales === null)
		{
			$this->sumSales = $this->getXml()->getElementsByTagName('TotalSumOfSales')->item(0)->nodeValue;
		}
		return $this->sumSales;
	}

	/**
	 * Return number of teams buys
	 *
	 * @return Integer
	 */
	public function getNumberOfBuys()
	{
		if(!isset($this->numberBuys) || $this->numberBuys === null)
		{
			$this->numberBuys = $this->getXml()->getElementsByTagName('NumberOfBuys')->item(0)->nodeValue;
		}
		return $this->numberBuys;
	}

	/**
	 * Return number of teams sales
	 *
	 * @return Integer
	 */
	public function getNumberOfSales()
	{
		if(!isset($this->numberSales) || $this->numberSales === null)
		{
			$this->numberSales = $this->getXml()->getElementsByTagName('NumberOfSales')->item(0)->nodeValue;
		}
		return $this->numberSales;
	}

	/**
	 * Return number of transfers listed by request
	 * Default period is past 7 days, if you specify a date
	 * period is one month before the date
	 *
	 * @return Integer
	 */
	public function getNumberTransfers()
	{
		if(!isset($this->numberTransfers) || $this->numberTransfers === null)
		{
			$this->numberTransfers = $this->getXml()->getElementsByTagName('Transfer')->length;
		}
		return $this->numberTransfers;
	}

	/**
	 * Return HTTransfer object
	 *
	 * @param Integer $index (between 0 and value returned by getNumberTransfersDuringPeriod() method)
	 * @return HTTransfer
	 */
	public function getTransfer($index)
	{
		$index = round($index);
		if($index >=0 && $index < $this->getNumberTransfers())
		{
			if(!isset($this->transfers[$index]) || $this->transfers[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Transfer');
				$transfer = new DOMDocument('1.0', 'UTF-8');
				$transfer->appendChild($transfer->importNode($nodeList->item($index), true));
				$this->transfers[$index] = new HTTransfer($transfer);
			}
			return $this->transfers[$index];
		}
		return null;
	}

	/**
	 * Return start date of transfers list
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getStartDate($format = null)
	{
		if(!isset($this->startDate[$format]) || $this->startDate[$format] === null)
		{
			$this->startDate[$format] = $this->getXml()->getElementsByTagName('StartDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->startDate[$format] = HTFunction::convertDate($this->startDate[$format], $format);
			}
		}
		return $this->startDate[$format];
	}

	/**
	 * Return end date of transfers list
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getEndDate($format = null)
	{
		if(!isset($this->endDate[$format]) || $this->endDate[$format] === null)
		{
			$this->endDate[$format] = $this->getXml()->getElementsByTagName('EndDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->endDate[$format] = HTFunction::convertDate($this->endDate[$format], $format);
			}
		}
		return $this->endDate[$format];
	}
}
class HTPlayerTransferHistory extends HTGlobal
{
	private $startDate = null;
	private $endDate = null;
	private $numberTransfers = null;
	private $playerId = null;
	private $playerName = null;
	private $transfers = null;

	/**
	 * Return start date of transfers list
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getStartDate($format = null)
	{
		if(!isset($this->startDate[$format]) || $this->startDate[$format] === null)
		{
			$this->startDate[$format] = $this->getXml()->getElementsByTagName('StartDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->startDate[$format] = HTFunction::convertDate($this->startDate[$format], $format);
			}
		}
		return $this->startDate[$format];
	}

	/**
	 * Return end date of transfers list
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getEndDate($format = null)
	{
		if(!isset($this->endDate[$format]) || $this->endDate[$format] === null)
		{
			$this->endDate[$format] = $this->getXml()->getElementsByTagName('EndDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->endDate[$format] = HTFunction::convertDate($this->endDate[$format], $format);
			}
		}
		return $this->endDate[$format];
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Return player name
	 *
	 * @return String
	 */
	public function getPlayerName()
	{
		if(!isset($this->playerName) || $this->playerName === null)
		{
			$this->playerName = $this->getXml()->getElementsByTagName('PlayerName')->item(0)->nodeValue;
		}
		return $this->playerName;
	}

	/**
	 * Return transfers number
	 *
	 * @return Integer
	 */
	public function getNumberTransfers()
	{
		if(!isset($this->numberTransfers) || $this->numberTransfers === null)
		{
			$this->numberTransfers = $this->getXml()->getElementsByTagName('Transfer')->length;
		}
		return $this->numberTransfers;
	}

	/**
	 * Return HTTransfer object
	 *
	 * @param Integer $index (between 1 and value returned by getNumberTransfers() method)
	 * @return HTTransfer
	 */
	public function getTransfer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberTransfers())
		{
			--$index;
			if(!isset($this->transfers[$index]) || $this->transfers[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Transfer');
				$player = $xpath->query('//Player');
				$transfer = new DOMDocument('1.0', 'UTF-8');
				$transfer->appendChild($transfer->importNode($nodeList->item($index), true));
				$transfer->appendChild($transfer->importNode($player->item(0), true));
				$this->transfers[$index] = new HTTransfer($transfer);
			}
			return $this->transfers[$index];
		}
		return null;
	}
}
class HTTransfer extends HTXml
{
	private $transferId = null;
	private $deadline = null;
	private $transferType = null;
	private $playerId = null;
	private $playerName = null;
	private $buyerId = null;
	private $buyerName = null;
	private $sellerId = null;
	private $sellerName = null;
	private $price = null;
	private $tsi = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return transfer id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->transferId) || $this->transferId === null)
		{
			$this->transferId = $this->getXml()->getElementsByTagName('TransferID')->item(0)->nodeValue;
		}
		return $this->transferId;
	}

	/**
	 * Return deadline date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDeadline($format = null)
	{
		if(!isset($this->deadline[$format]) || $this->deadline[$format] === null)
		{
			$this->deadline[$format] = $this->getXml()->getElementsByTagName('Deadline')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->deadline[$format] = HTFunction::convertDate($this->deadline[$format], $format);
			}
		}
		return $this->deadline[$format];
	}

	/**
	 * Return transfer type : S for sale, B for buy
	 *
	 * @return String
	 */
	public function getType()
	{
		if(!isset($this->transferType) || $this->transferType === null)
		{
			$node = $this->getXml()->getElementsByTagName('TransferType');
			if($node !== null && $node->length)
			{
				$this->transferType = $node->item(0)->nodeValue;
			}
		}
		return $this->transferType;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Return player name
	 *
	 * @return String
	 */
	public function getPlayerName()
	{
		if(!isset($this->playerName) || $this->playerName === null)
		{
			$this->playerName = $this->getXml()->getElementsByTagName('PlayerName')->item(0)->nodeValue;
		}
		return $this->playerName;
	}

	/**
	 * Return buyer team id
	 *
	 * @return Integer
	 */
	public function getBuyerTeamId()
	{
		if(!isset($this->buyerId) || $this->buyerId === null)
		{
			$this->buyerId = $this->getXml()->getElementsByTagName('BuyerTeamID')->item(0)->nodeValue;
		}
		return $this->buyerId;
	}

	/**
	 * Return buyer team name
	 *
	 * @return String
	 */
	public function getBuyerTeamName()
	{
		if(!isset($this->buyerName) || $this->buyerName === null)
		{
			$this->buyerName = $this->getXml()->getElementsByTagName('BuyerTeamName')->item(0)->nodeValue;
		}
		return $this->buyerName;
	}

	/**
	 * Return seller team id
	 *
	 * @return Integer
	 */
	public function getSellerTeamId()
	{
		if(!isset($this->sellerId) || $this->sellerId === null)
		{
			$this->sellerId = $this->getXml()->getElementsByTagName('SellerTeamID')->item(0)->nodeValue;
		}
		return $this->sellerId;
	}

	/**
	 * Return seller team name
	 *
	 * @return String
	 */
	public function getSellerTeamName()
	{
		if(!isset($this->sellerName) || $this->sellerName === null)
		{
			$this->sellerName = $this->getXml()->getElementsByTagName('SellerTeamName')->item(0)->nodeValue;
		}
		return $this->sellerName;
	}

	/**
	 * Return transfer price
	 *
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 * @return Integer
	 */
	public function getPrice($countryCurrency = null)
	{
		if(!isset($this->price[$countryCurrency]) || $this->price[$countryCurrency] === null)
		{
			$this->price[$countryCurrency] = HTMoney::convert($this->getXml()->getElementsByTagName('Price')->item(0)->nodeValue, $countryCurrency);
		}
		return $this->price[$countryCurrency];
	}

	/**
	 * Return player tsi at transfer date
	 *
	 * @return Integer
	 */
	public function getTsi()
	{
		if(!isset($this->tsi) || $this->tsi === null)
		{
			$this->tsi = $this->getXml()->getElementsByTagName('TSI')->item(0)->nodeValue;
		}
		return $this->tsi;
	}
}
class HTTeamPlayers extends HTCommonSubscriber
{
	private $numberPlayers = null;
	protected $players = null;
	private $isPlaying = null;

	/**
	 * Return number players of team
	 *
	 * @return Integer
	 */
	public function getNumberPlayers()
	{
		if(!isset($this->numberPlayers) || $this->numberPlayers === null)
		{
			$this->numberPlayers = $this->getXml()->getElementsByTagName('Player')->length;
		}
		return $this->numberPlayers;
	}

	/**
	 * Return HTTeamPlayer player object
	 *
	 * @param Integer $index
	 * @return HTTeamPlayer
	 */
	public function getPlayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberPlayers())
		{
			--$index;
			if(!isset($this->players[$index]) || $this->players[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Player');
				$player = new DOMDocument('1.0', 'UTF-8');
				$player->appendChild($player->importNode($nodeList->item($index), true));
				$this->players[$index] = new HTTeamPlayer($player);
			}
			return $this->players[$index];
		}
		return null;
	}

	/**
	 * Is team playing a match?
	 *
	 * @return Boolean
	 */
	public function isPlayingMatch()
	{
		if(!isset($this->isPlaying) || $this->isPlaying === null)
		{
			$this->isPlaying = strtolower($this->getXml()->getElementsByTagName('IsPlayingMatch')->item(0)->nodeValue) == "true";
		}
		return $this->isPlaying;
	}
}
class HTTeamOldCoaches extends HTTeamPlayers
{
	/**
	 * Return HTTeamOldCoach player object
	 *
	 * @param Integer $index
	 * @return HTTeamOldCoach
	 */
	public function getPlayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberPlayers())
		{
			--$index;
			if(!isset($this->players[$index]) || $this->players[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Player');
				$player = new DOMDocument('1.0', 'UTF-8');
				$player->appendChild($player->importNode($nodeList->item($index), true));
				$this->players[$index] = new HTTeamOldCoach($player);
			}
			return $this->players[$index];
		}
		return null;
	}
}
class HTTeamOldPlayers extends HTTeamPlayers
{
	/**
	 * Return HTTeamOldPlayer player object
	 *
	 * @param Integer $index
	 * @return HTTeamOldPlayer
	 */
	public function getPlayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberPlayers())
		{
			--$index;
			if(!isset($this->players[$index]) || $this->players[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Player');
				$player = new DOMDocument('1.0', 'UTF-8');
				$player->appendChild($player->importNode($nodeList->item($index), true));
				$this->players[$index] = new HTTeamOldPlayer($player);
			}
			return $this->players[$index];
		}
		return null;
	}
}
class HTTeamPlayer extends HTXml
{
	private $playerId = null;
	private $playerFirstName = null;
	private $playerLastName = null;
	private $playerNickName = null;
	private $playerNumber = null;
	private $age = null;
	private $days = null;
	private $tsi = null;
	private $form = null;
	private $statement = null;
	private $experience = null;
	private $leadership = null;
	private $salary = null;
	private $isAbroad = null;
	private $agreeability = null;
	private $aggressiveness = null;
	private $honesty = null;
	private $leagueGoals = null;
	private $cupGoals = null;
	private $friendlyGoals = null;
	private $carrerGoals = null;
	private $carrerHattricks = null;
	private $specialityCode = null;
	private $transferListed = null;
	private $nationalTeamId = null;
	private $countryId = null;
	private $caps = null;
	private $capsU20 = null;
	private $cards = null;
	private $injuryLevel = null;
	private $trainerType = null;
	private $trainerSkill = null;
	private $isTrainer = null;
	private $birthday = null;
	private $stamina = null;
	private $keeper = null;
	private $playmaker = null;
	private $scorer = null;
	private $passing = null;
	private $winger = null;
	private $defender = null;
	private $setpieces = null;
	private $categoryId = null;
	private $lastmatch = null;
	private $loyalty = null;
	private $bonus = null;

	const UNAVAILABLE = 'NOT AVAILABLE';
	const DAYSINYEAR = 112;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Return player first name
	 *
	 * @return String
	 */
	public function getFirstName()
	{
		if(!isset($this->playerFirstName) || $this->playerFirstName === null)
		{
			$this->playerFirstName = $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
		}
		return $this->playerFirstName;
	}

	/**
	 * Return player last name
	 *
	 * @return String
	 */
	public function getLastName()
	{
		if(!isset($this->playerLastName) || $this->playerLastName === null)
		{
			$this->playerLastName = $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
		}
		return $this->playerLastName;
	}

	/**
	 * Return player nickname
	 *
	 * @return String
	 */
	public function getNickName()
	{
		if(!isset($this->playerNickName) || $this->playerNickName === null)
		{
			$this->playerNickName = $this->getXml()->getElementsByTagName('NickName')->item(0)->nodeValue;
		}
		return $this->playerNickName;
	}

	/**
	 * Return player full name
	 *
	 * @return String
	 */
	public function getName()
	{
		$name = $this->getFirstName().' ';
		if($this->getNickName() !== null && $this->getNickName() !== '')
		{
			$name .= $this->getNickName().' ';
		}
		$name .= $this->getLastName();
		return $name;
	}

	/**
	 * Return player shirt number if team is hattrick supporter
	 *
	 * @return Integer
	 */
	public function getShirtNumber()
	{
		if(!isset($this->playerNumber) || $this->playerNumber === null)
		{
			$node = $this->getXml()->getElementsByTagName('PlayerNumber');
			if($node->length)
			{
				$this->playerNumber = $node->item(0)->nodeValue;
			}
		}
		if($this->playerNumber == 100)
		{
			return null;
		}
		return $this->playerNumber;
	}

	/**
	 * Return player age
	 *
	 * @return Integer
	 */
	public function getAge()
	{
		if(!isset($this->age) || $this->age === null)
		{
			$this->age = $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
		}
		return $this->age;
	}

	/**
	 * Return player age days
	 *
	 * @return Integer
	 */
	public function getDays()
	{
		if(!isset($this->days) || $this->days === null)
		{
			$this->days = $this->getXml()->getElementsByTagName('AgeDays')->item(0)->nodeValue;
		}
		return $this->days;
	}

	/**
	 * Return player TSI
	 *
	 * @return Integer
	 */
	public function getTsi()
	{
		if(!isset($this->tsi) || $this->tsi === null)
		{
			$this->tsi = $this->getXml()->getElementsByTagName('TSI')->item(0)->nodeValue;
		}
		return $this->tsi;
	}

	/**
	 * Return player form level
	 *
	 * @return Integer
	 */
	public function getForm()
	{
		if(!isset($this->form) || $this->form === null)
		{
			$this->form = $this->getXml()->getElementsByTagName('PlayerForm')->item(0)->nodeValue;
		}
		return $this->form;
	}

	/**
	 * Return player statement
	 *
	 * @return Unknow
	 */
	public function getStatement()
	{
		if(!isset($this->statement) || $this->statement === null)
		{
			$node = $this->getXml()->getElementsByTagName('Statement');
			if($node->length)
			{
				$this->statement = $node->item(0)->nodeValue;
			}
		}
		return $this->statement;
	}

	/**
	 * Return player experience level
	 *
	 * @return Integer
	 */
	public function getExperience()
	{
		if(!isset($this->experience) || $this->experience === null)
		{
			$this->experience = $this->getXml()->getElementsByTagName('Experience')->item(0)->nodeValue;
		}
		return $this->experience;
	}

	/**
	 * Return player leadership level
	 *
	 * @return Integer
	 */
	public function getLeadership()
	{
		if(!isset($this->leadership) || $this->leadership === null)
		{
			$this->leadership = $this->getXml()->getElementsByTagName('Leadership')->item(0)->nodeValue;
		}
		return $this->leadership;
	}

	/**
	 * Return player salary, in currency if specify
	 *
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 * @return Integer
	 */
	public function getSalary($countryCurrency = null)
	{
		if(!isset($this->salary[$countryCurrency]) || $this->salary[$countryCurrency] === null)
		{
			$this->salary[$countryCurrency] = HTMoney::convert($this->getXml()->getElementsByTagName('Salary')->item(0)->nodeValue, $countryCurrency);
		}
		return $this->salary[$countryCurrency];
	}

	/**
	 * Is player abroad ?
	 *
	 * @return Boolean
	 */
	public function isAbroad()
	{
		if(!isset($this->isAbroad) || $this->isAbroad === null)
		{
			$this->isAbroad = $this->getXml()->getElementsByTagName('IsAbroad')->item(0)->nodeValue == "1";
		}
		return $this->isAbroad;
	}

	/**
	 * Return player agreeability level
	 *
	 * @return Integer
	 */
	public function getAgreeability()
	{
		if(!isset($this->agreeability) || $this->agreeability === null)
		{
			$this->agreeability = $this->getXml()->getElementsByTagName('Agreeability')->item(0)->nodeValue;
		}
		return $this->agreeability;
	}

	/**
	 * Return player aggressiveness level
	 *
	 * @return Integer
	 */
	public function getAggressiveness()
	{
		if(!isset($this->aggressiveness) || $this->aggressiveness === null)
		{
			$this->aggressiveness = $this->getXml()->getElementsByTagName('Aggressiveness')->item(0)->nodeValue;
		}
		return $this->aggressiveness;
	}

	/**
	 * Return player honesty level
	 *
	 * @return Integer
	 */
	public function getHonesty()
	{
		if(!isset($this->honesty) || $this->honesty === null)
		{
			$this->honesty = $this->getXml()->getElementsByTagName('Honesty')->item(0)->nodeValue;
		}
		return $this->honesty;
	}

	/**
	 * Return player number goals in league
	 *
	 * @return Integer
	 */
	public function getGoalsInLeague()
	{
		if(!isset($this->leagueGoals) || $this->leagueGoals === null)
		{
			$this->leagueGoals = $this->getXml()->getElementsByTagName('LeagueGoals')->item(0)->nodeValue;
		}
		if($this->leagueGoals === self::UNAVAILABLE )
		{
			return null;
		}
		return $this->leagueGoals;
	}

	/**
	 * Return player number goals in cup
	 *
	 * @return Integer
	 */
	public function getGoalsInCup()
	{
		if(!isset($this->cupGoals) || $this->cupGoals === null)
		{
			$this->cupGoals = $this->getXml()->getElementsByTagName('CupGoals')->item(0)->nodeValue;
		}
		if($this->cupGoals === self::UNAVAILABLE )
		{
			return null;
		}
		return $this->cupGoals;
	}

	/**
	 * Return player number goals in friendly
	 *
	 * @return Integer
	 */
	public function getGoalsInFriendly()
	{
		if(!isset($this->friendlyGoals) || $this->friendlyGoals === null)
		{
			$this->friendlyGoals = $this->getXml()->getElementsByTagName('FriendliesGoals')->item(0)->nodeValue;
		}
		if($this->friendlyGoals === self::UNAVAILABLE )
		{
			return null;
		}
		return $this->friendlyGoals;
	}

	/**
	 * Return player number goals in his career
	 *
	 * @return Integer
	 */
	public function getGoalsInCareer()
	{
		if(!isset($this->carrerGoals) || $this->carrerGoals === null)
		{
			$this->carrerGoals = $this->getXml()->getElementsByTagName('CareerGoals')->item(0)->nodeValue;
		}
		if($this->carrerGoals === self::UNAVAILABLE )
		{
			return null;
		}
		return $this->carrerGoals;
	}

	/**
	 * Return player number hattricks in his career
	 *
	 * @return Integer
	 */
	public function getHattricksInCareer()
	{
		if(!isset($this->carrerHattricks) || $this->carrerHattricks === null)
		{
			$this->carrerHattricks = $this->getXml()->getElementsByTagName('CareerHattricks')->item(0)->nodeValue;
		}
		if($this->carrerHattricks === self::UNAVAILABLE )
		{
			return null;
		}
		return $this->carrerHattricks;
	}

	/**
	 * Return player speciality code
	 *
	 * @return Integer
	 */
	public function getSpeciality()
	{
		if(!isset($this->specialityCode) || $this->specialityCode === null)
		{
			$this->specialityCode = $this->getXml()->getElementsByTagName('Specialty')->item(0)->nodeValue;
		}
		return $this->specialityCode;
	}

	/**
	 * Is player transfer listed ?
	 *
	 * @return Boolean
	 */
	public function isTransferListed()
	{
		if(!isset($this->transferListed) || $this->transferListed === null)
		{
			$this->transferListed = $this->getXml()->getElementsByTagName('TransferListed')->item(0)->nodeValue == "1";
		}
		return $this->transferListed;
	}

	/**
	 * Return national team id if player selected
	 *
	 * @return Integer
	 */
	public function getNationalTeamId()
	{
		if(!isset($this->nationalTeamId) || $this->nationalTeamId === null)
		{
			$this->nationalTeamId = $this->getXml()->getElementsByTagName('NationalTeamID')->item(0)->nodeValue;
		}
		if($this->nationalTeamId == 0)
		{
			return null;
		}
		return $this->nationalTeamId;
	}

	/**
	 * Return player country id
	 *
	 * @return Integer
	 */
	public function getCountryId()
	{
		if(!isset($this->countryId) || $this->countryId === null)
		{
			$this->countryId = $this->getXml()->getElementsByTagName('CountryID')->item(0)->nodeValue;
		}
		return $this->countryId;
	}

	/**
	 * Return player number caps in A country team
	 *
	 * @return Integer
	 */
	public function getACaps()
	{
		if(!isset($this->caps) || $this->caps === null)
		{
			$this->caps = $this->getXml()->getElementsByTagName('Caps')->item(0)->nodeValue;
		}
		return $this->caps;
	}

	/**
	 * Return player number caps in U20 country team
	 *
	 * @return Integer
	 */
	public function getU20Caps()
	{
		if(!isset($this->capsU20) || $this->capsU20 === null)
		{
			$this->capsU20 = $this->getXml()->getElementsByTagName('CapsU20')->item(0)->nodeValue;
		}
		return $this->capsU20;
	}

	/**
	 * Return player cards number
	 *
	 * @return Integer
	 */
	public function getCards()
	{
		if(!isset($this->cards) || $this->cards === null)
		{
			$this->cards = $this->getXml()->getElementsByTagName('Cards')->item(0)->nodeValue;
		}
		if($this->cards === self::UNAVAILABLE )
		{
			return null;
		}
		return $this->cards;
	}

	/**
	 * Return player injury level, -1 if not injured
	 *
	 * @return Integer
	 */
	public function getInjury()
	{
		if(!isset($this->injuryLevel) || $this->injuryLevel === null)
		{
			$this->injuryLevel = $this->getXml()->getElementsByTagName('InjuryLevel')->item(0)->nodeValue;
		}
		if($this->injuryLevel === self::UNAVAILABLE )
		{
			return null;
		}
		return $this->injuryLevel;
	}

	/**
	 * Is player a trainer ?
	 *
	 * @return Boolean
	 */
	public function isTrainer()
	{
		if(!isset($this->isTrainer) || $this->isTrainer === null)
		{
			$this->isTrainer = $this->getXml()->getElementsByTagName('TrainerData')->length > 0;
		}
		return $this->isTrainer;
	}

	/**
	 * Return player trainer type
	 *
	 * @return Integer
	 */
	public function getTrainerType()
	{
		if($this->isTrainer())
		{
			if(!isset($this->trainerType) || $this->trainerType === null)
			{
				$this->trainerType = $this->getXml()->getElementsByTagName('TrainerType')->item(0)->nodeValue;
			}
			return $this->trainerType;
		}
		return null;
	}

	/**
	 * Return player trainer skill
	 *
	 * @return Integer
	 */
	public function getTrainerSkill()
	{
		if($this->isTrainer())
		{
			if(!isset($this->trainerSkill) || $this->trainerSkill === null)
			{
				$this->trainerSkill = $this->getXml()->getElementsByTagName('TrainerSkill')->item(0)->nodeValue;
			}
			return $this->trainerSkill;
		}
		return null;
	}

	/**
	 * Return player next birthday
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getNextBirthDay($format = null)
	{
		if(!isset($this->birthday[$format]) || $this->birthday[$format] === null)
		{
			$this->birthday[$format] = date('Y-m-d', time()+((self::DAYSINYEAR-$this->getDays())*24*3600));
			if($format !== null)
			{
				$this->birthday[$format] = HTFunction::convertDate($this->birthday[$format], $format);
			}
		}
		return $this->birthday[$format];
	}

	/**
	 * Return player stamina level if available
	 *
	 * @return Integer
	 */
	public function getStamina()
	{
		if(!isset($this->stamina) || $this->stamina === null)
		{
			if($this->getXml()->getElementsByTagName('StaminaSkill')->length)
			{
				$this->stamina = $this->getXml()->getElementsByTagName('StaminaSkill')->item(0)->nodeValue;
			}
		}
		return $this->stamina;
	}

	/**
	 * Return player keeper level if available
	 *
	 * @return Integer
	 */
	public function getKeeper()
	{
		if(!isset($this->keeper) || $this->keeper === null)
		{
			if($this->getXml()->getElementsByTagName('KeeperSkill')->length)
			{
				$this->keeper = $this->getXml()->getElementsByTagName('KeeperSkill')->item(0)->nodeValue;
			}
		}
		return $this->keeper;
	}

	/**
	 * Return player playmaker level if available
	 *
	 * @return Integer
	 */
	public function getPlaymaker()
	{
		if(!isset($this->playmaker) || $this->playmaker === null)
		{
			if($this->getXml()->getElementsByTagName('PlaymakerSkill')->length)
			{
				$this->playmaker = $this->getXml()->getElementsByTagName('PlaymakerSkill')->item(0)->nodeValue;
			}
		}
		return $this->playmaker;
	}

	/**
	 * Return player scorer level if available
	 *
	 * @return Integer
	 */
	public function getScorer()
	{
		if(!isset($this->scorer) || $this->scorer === null)
		{
			if($this->getXml()->getElementsByTagName('ScorerSkill')->length)
			{
				$this->scorer = $this->getXml()->getElementsByTagName('ScorerSkill')->item(0)->nodeValue;
			}
		}
		return $this->scorer;
	}

	/**
	 * Return player passing level if available
	 *
	 * @return Integer
	 */
	public function getPassing()
	{
		if(!isset($this->passing) || $this->passing === null)
		{
			if($this->getXml()->getElementsByTagName('PassingSkill')->length)
			{
				$this->passing = $this->getXml()->getElementsByTagName('PassingSkill')->item(0)->nodeValue;
			}
		}
		return $this->passing;
	}

	/**
	 * Return player winger level if available
	 *
	 * @return Integer
	 */
	public function getWinger()
	{
		if(!isset($this->winger) || $this->winger === null)
		{
			if($this->getXml()->getElementsByTagName('WingerSkill')->length)
			{
				$this->winger = $this->getXml()->getElementsByTagName('WingerSkill')->item(0)->nodeValue;
			}
		}
		return $this->winger;
	}

	/**
	 * Return player defender level if available
	 *
	 * @return Integer
	 */
	public function getDefender()
	{
		if(!isset($this->defender) || $this->defender === null)
		{
			if($this->getXml()->getElementsByTagName('DefenderSkill')->length)
			{
				$this->defender = $this->getXml()->getElementsByTagName('DefenderSkill')->item(0)->nodeValue;
			}
		}
		return $this->defender;
	}

	/**
	 * Return player set pieces level if available
	 *
	 * @return Integer
	 */
	public function getSetPieces()
	{
		if(!isset($this->setpieces) || $this->setpieces === null)
		{
			if($this->getXml()->getElementsByTagName('SetPiecesSkill')->length)
			{
				$this->setpieces = $this->getXml()->getElementsByTagName('SetPiecesSkill')->item(0)->nodeValue;
			}
		}
		return $this->setpieces;
	}

	/**
	 * Return player category id
	 *
	 * @return Integer
	 */
	public function getCategoryId()
	{
		if(!isset($this->categoryId) || $this->categoryId === null)
		{
			$this->categoryId = $this->getXml()->getElementsByTagName('PlayerCategoryId')->item(0)->nodeValue;
		}
		return $this->categoryId;
	}

	/**
	 * Return HTPlayerMatch object
	 *
	 * @return HTPlayerMatch
	 */
	public function getLastMatch()
	{
		if(!isset($this->lastmatch) || $this->lastmatch === null)
		{
			$node = $this->getXml()->getElementsByTagName('LastMatch')->item(0);
			if($node !== null && $node->hasChildNodes())
			{
				$lastmatch = new DOMDocument('1.0', 'UTF-8');
				$lastmatch->appendChild($lastmatch->importNode($node, true));
				$this->lastmatch = new HTPlayerMatch($lastmatch);
			}
		}
		return $this->lastmatch;
	}

	/**
	 * Return player loyalty level
	 *
	 * @return Integer
	 */
	public function getLoyalty()
	{
		if(!isset($this->loyalty) || $this->loyalty === null)
		{
			$node = $this->getXml()->getElementsByTagName('Loyalty');
			if($node->length)
			{
				$this->loyalty = $node->item(0)->nodeValue;
			}
		}
		return $this->loyalty;
	}

	/**
	 * Return if player has mother club bonus
	 *
	 * @return Boolean
	 */
	public function hasMotherClubBonus()
	{
		if(!isset($this->bonus) || $this->bonus === null)
		{
			$node = $this->getXml()->getElementsByTagName('MotherClubBonus');
			if($node->length)
			{
				$this->bonus = strtolower($node->item(0)->nodeValue) == 'true';
			}
		}
		return $this->bonus;
	}
}
class HTTeamOldCoach extends HTTeamPlayer
{
	private $trainerType = null;
	private $trainerSkill = null;

	/**
	 * Return trainer type :  0 = Defensive ;  1 = Offensive ;  2 = Balanced
	 *
	 * @return Integer
	 */
	public function getTrainerType()
	{
		if(!isset($this->trainerType) || $this->trainerType === null)
		{
			$this->trainerType = $this->getXml()->getElementsByTagName('TrainerType')->item(0)->nodeValue;
		}
		return $this->trainerType;
	}

	/**
	 * Return trainer skill value
	 *
	 * @return Integer
	 */
	public function getTrainerSkill()
	{
		if(!isset($this->trainerSkill) || $this->trainerSkill === null)
		{
			$this->trainerSkill = $this->getXml()->getElementsByTagName('TrainerSkill')->item(0)->nodeValue;
		}
		return $this->trainerSkill;
	}
}
class HTTeamOldPlayer extends HTTeamPlayer
{
	private $teamId = null;
	private $teamName = null;
	private $leagueName = null;

	/**
	 * Return current team id of old player
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//OwningTeam/TeamID');
			$this->teamId = $nodeList->item(0)->nodeValue;
		}
		return $this->teamId;
	}

	/**
	 * Return current team name of old player
	 *
	 * @return String
	 */
	public function getTeamName()
	{
		if(!isset($this->teamName) || $this->teamName === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//OwningTeam/TeamName');
			$this->teamName = $nodeList->item(0)->nodeValue;
		}
		return $this->teamName;
	}

	/**
	 * Return current league name of old player
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//OwningTeam/LeagueName');
			$this->leagueName = $nodeList->item(0)->nodeValue;
		}
		return $this->leagueName;
	}
}
class HTPlayer extends HTCommonSubscriber
{
	private $playerId = null;
	private $playerFirstName = null;
	private $playerLastName = null;
	private $playerNickName = null;
	private $playerNumber = null;
	private $age = null;
	private $days = null;
	private $form = null;
	private $cards = null;
	private $injuryLevel = null;
	private $statementAvailable = null;
	private $statement = null;
	private $playerLanguageAvailable = null;
	private $playerLanguage = null;
	private $playerLanguageIdAvailable = null;
	private $playerLanguageId = null;
	private $agreeability = null;
	private $aggressiveness = null;
	private $honesty = null;
	private $experience = null;
	private $leadership = null;
	private $specialityCode = null;
	private $nativeLeagueId = null;
	private $nativeLeagueName = null;
	private $nativeCountryId = null;
	private $tsi = null;
	private $leagueId = null;
	private $salary = null;
	private $isAbroad = null;
	private $caps = null;
	private $capsU20 = null;
	private $leagueGoals = null;
	private $cupGoals = null;
	private $carrerGoals = null;
	private $carrerHattricks = null;
	private $friendlyGoals = null;
	private $transferListed = null;
	private $askingPrice = null;
	private $deadline = null;
	private $highestbid = null;
	private $bidderTeamId = null;
	private $bidderTeamName = null;
	private $skillsAvailable = null;
	private $stamina = null;
	private $keeper = null;
	private $playmaker = null;
	private $scorer = null;
	private $passing = null;
	private $winger = null;
	private $defender = null;
	private $setPieces = null;
	private $trainerType = null;
	private $trainerSkill = null;
	private $isTrainer = null;
	private $birthday = null;
	private $lastmatch = null;
	private $ownerNotes = null;
	private $categoryId = null;
	private $loyalty = null;
	private $bonus = null;
	private $nationalTeamId = null;
	private $nationalTeamName = null;

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Return player first name
	 *
	 * @return String
	 */
	public function getFirstName()
	{
		if(!isset($this->playerFirstName) || $this->playerFirstName === null)
		{
			$this->playerFirstName = $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
		}
		return $this->playerFirstName;
	}

	/**
	 * Return player last name
	 *
	 * @return String
	 */
	public function getLastName()
	{
		if(!isset($this->playerLastName) || $this->playerLastName === null)
		{
			$this->playerLastName = $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
		}
		return $this->playerLastName;
	}

	/**
	 * Return player nickname
	 *
	 * @return String
	 */
	public function getNickName()
	{
		if(!isset($this->playerNickName) || $this->playerNickName === null)
		{
			$this->playerNickName = $this->getXml()->getElementsByTagName('NickName')->item(0)->nodeValue;
		}
		return $this->playerNickName;
	}

	/**
	 * Return player full name
	 *
	 * @return String
	 */
	public function getName()
	{
		$name = $this->getFirstName().' ';
		if($this->getNickName() !== null && $this->getNickName() !== '')
		{
			$name .= $this->getNickName().' ';
		}
		$name .= $this->getLastName();
		return $name;
	}

	/**
	 * Return player shirt number if team is hattrick supporter
	 *
	 * @return Integer
	 */
	public function getShirtNumber()
	{
		if(!isset($this->playerNumber) || $this->playerNumber === null)
		{
			$node = $this->getXml()->getElementsByTagName('PlayerNumber');
			if($node->length)
			{
				$this->playerNumber = $node->item(0)->nodeValue;
			}
			if($this->playerNumber > 99)
			{
				$this->playerNumber = null;
			}
		}
		return $this->playerNumber;
	}

	/**
	 * Return player age
	 *
	 * @return Integer
	 */
	public function getAge()
	{
		if(!isset($this->age) || $this->age === null)
		{
			$this->age = $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
		}
		return $this->age;
	}

	/**
	 * Return player age days
	 *
	 * @return Integer
	 */
	public function getDays()
	{
		if(!isset($this->days) || $this->days === null)
		{
			$this->days = $this->getXml()->getElementsByTagName('AgeDays')->item(0)->nodeValue;
		}
		return $this->days;
	}

	/**
	 * Return player TSI
	 *
	 * @return Integer
	 */
	public function getTsi()
	{
		if(!isset($this->tsi) || $this->tsi === null)
		{
			$this->tsi = $this->getXml()->getElementsByTagName('TSI')->item(0)->nodeValue;
		}
		return $this->tsi;
	}

	/**
	 * Return player league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Return player form level
	 *
	 * @return Integer
	 */
	public function getForm()
	{
		if(!isset($this->form) || $this->form === null)
		{
			$this->form = $this->getXml()->getElementsByTagName('PlayerForm')->item(0)->nodeValue;
		}
		return $this->form;
	}

	/**
	 * Return player cards number
	 *
	 * @return Integer
	 */
	public function getCards()
	{
		if(!isset($this->cards) || $this->cards === null)
		{
			$this->cards = $this->getXml()->getElementsByTagName('Cards')->item(0)->nodeValue;
		}
		return $this->cards;
	}

	/**
	 * Return player injury level, -1 if not injured
	 *
	 * @return Integer
	 */
	public function getInjury()
	{
		if(!isset($this->injuryLevel) || $this->injuryLevel === null)
		{
			$this->injuryLevel = $this->getXml()->getElementsByTagName('InjuryLevel')->item(0)->nodeValue;
		}
		return $this->injuryLevel;
	}

	/**
	 * Return player statement
	 *
	 * @return Integer
	 */
	public function getStatement()
	{
		if(!isset($this->statementAvailable) || $this->statementAvailable === null)
		{
			$this->statementAvailable = $this->getXml()->getElementsByTagName('Statement')->length;
		}
		if($this->statementAvailable)
		{
			if(!isset($this->statement) || $this->statement === null)
			{
				$this->statement = $this->getXml()->getElementsByTagName('Statement')->item(0)->nodeValue;
			}
			return $this->statement;
		}
		return null;
	}

	/**
	 * Return player language
	 *
	 * @return Integer
	 */
	public function getLanguage()
	{
		if(!isset($this->playerLanguageAvailable) || $this->playerLanguageAvailable === null)
		{
			$this->playerLanguageAvailable = $this->getXml()->getElementsByTagName('PlayerLanguage')->length;
		}
		if($this->playerLanguageAvailable)
		{
			if(!isset($this->playerLanguage) || $this->playerLanguage === null)
			{
				$this->playerLanguage = $this->getXml()->getElementsByTagName('PlayerLanguage')->item(0)->nodeValue;
			}
			return $this->playerLanguage;
		}
		return null;
	}

	/**
	 * Return player language id
	 *
	 * @return Integer
	 */
	public function getLanguageId()
	{
		if(!isset($this->playerLanguageIdAvailable) || $this->playerLanguageIdAvailable === null)
		{
			$this->playerLanguageIdAvailable = $this->getXml()->getElementsByTagName('PlayerLanguageID')->length;
		}
		if($this->playerLanguageIdAvailable)
		{
			if(!isset($this->playerLanguageId) || $this->playerLanguageId === null)
			{
				$this->playerLanguageId = $this->getXml()->getElementsByTagName('PlayerLanguageID')->item(0)->nodeValue;
			}
			return $this->playerLanguageId;
		}
		return null;
	}

	/**
	 * Return player experience level
	 *
	 * @return Integer
	 */
	public function getExperience()
	{
		if(!isset($this->experience) || $this->experience === null)
		{
			$this->experience = $this->getXml()->getElementsByTagName('Experience')->item(0)->nodeValue;
		}
		return $this->experience;
	}

	/**
	 * Return player leadership level
	 *
	 * @return Integer
	 */
	public function getLeadership()
	{
		if(!isset($this->leadership) || $this->leadership === null)
		{
			$this->leadership = $this->getXml()->getElementsByTagName('Leadership')->item(0)->nodeValue;
		}
		return $this->leadership;
	}

	/**
	 * Return player salary, in currency if specify
	 *
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 * @return Integer
	 */
	public function getSalary($countryCurrency = null)
	{
		if(!isset($this->salary[$countryCurrency]) || $this->salary[$countryCurrency] === null)
		{
			$this->salary[$countryCurrency] = HTMoney::convert($this->getXml()->getElementsByTagName('Salary')->item(0)->nodeValue, $countryCurrency);
		}
		return $this->salary[$countryCurrency];
	}

	/**
	 * Is player abroad ?
	 *
	 * @return Boolean
	 */
	public function isAbroad()
	{
		if(!isset($this->isAbroad) || $this->isAbroad === null)
		{
			$this->isAbroad = strtolower($this->getXml()->getElementsByTagName('IsAbroad')->item(0)->nodeValue) == "true";
		}
		return $this->isAbroad;
	}

	/**
	 * Return player speciality code
	 *
	 * @return Integer
	 */
	public function getSpeciality()
	{
		if(!isset($this->specialityCode) || $this->specialityCode === null)
		{
			$this->specialityCode = $this->getXml()->getElementsByTagName('Specialty')->item(0)->nodeValue;
		}
		return $this->specialityCode;
	}

	/**
	 * Return player native league id
	 *
	 * @return Integer
	 */
	public function getNativeLeagueId()
	{
		if(!isset($this->nativeLeagueId) || $this->nativeLeagueId === null)
		{
			$this->nativeLeagueId = $this->getXml()->getElementsByTagName('NativeLeagueID')->item(0)->nodeValue;
		}
		return $this->nativeLeagueId;
	}

	/**
	 * Return player native league name
	 *
	 * @return Integer
	 */
	public function getNativeLeagueName()
	{
		if(!isset($this->nativeLeagueName) || $this->nativeLeagueName === null)
		{
			$this->nativeLeagueName = $this->getXml()->getElementsByTagName('NativeLeagueName')->item(0)->nodeValue;
		}
		return $this->nativeLeagueName;
	}

	/**
	 * Return player native country id
	 *
	 * @return Integer
	 */
	public function getNativeCountryId()
	{
		if(!isset($this->nativeCountryId) || $this->nativeCountryId === null)
		{
			$this->nativeCountryId = $this->getXml()->getElementsByTagName('NativeCountryID')->item(0)->nodeValue;
		}
		return $this->nativeCountryId;
	}

	/**
	 * Is player transfer listed ?
	 *
	 * @return Boolean
	 */
	public function isTransferListed()
	{
		if(!isset($this->transferListed) || $this->transferListed === null)
		{
			$this->transferListed = strtolower($this->getXml()->getElementsByTagName('TransferListed')->item(0)->nodeValue) == "true";
		}
		return $this->transferListed;
	}

	/**
	 * Return asking price
	 *
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 * @return Integer
	 */
	public function getAskingPrice($countryCurrency = null)
	{
		if($this->isTransferListed())
		{
			if(!isset($this->askingPrice[$countryCurrency]) || $this->askingPrice[$countryCurrency] === null)
			{
				$this->askingPrice[$countryCurrency] = HTMoney::convert($this->getXml()->getElementsByTagName('AskingPrice')->item(0)->nodeValue, $countryCurrency);
			}
			return $this->askingPrice[$countryCurrency];
		}
		return null;
	}

	/**
	 * Return transfer deadline
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDeadline($format = null)
	{
		if($this->isTransferListed())
		{
			if(!isset($this->deadline) || $this->deadline === null)
			{
				$this->deadline = $this->getXml()->getElementsByTagName('Deadline')->item(0)->nodeValue;
				if($format !== null)
				{
					$this->deadline = HTFunction::convertDate($this->deadline, $format);
				}
			}
			return $this->deadline;
		}
		return null;
	}

	/**
	 * Return highest bid
	 *
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 * @return Integer
	 */
	public function getHighestBid($countryCurrency = null)
	{
		if($this->isTransferListed())
		{
			if(!isset($this->highestbid[$countryCurrency]) || $this->highestbid[$countryCurrency] === null)
			{
				$this->highestbid[$countryCurrency] = HTMoney::convert($this->getXml()->getElementsByTagName('HighestBid')->item(0)->nodeValue, $countryCurrency);
			}
			return $this->highestbid[$countryCurrency];
		}
		return null;
	}

	/**
	 * Return bidder team id
	 *
	 * @return Integer
	 */
	public function getBidderTeamId()
	{
		if($this->isTransferListed() && $this->getHighestBid()>0)
		{
			if(!isset($this->bidderTeamId) || $this->bidderTeamId === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$this->bidderTeamId = $xpath->query('//BidderTeam/TeamID')->item(0)->nodeValue;
			}
			return $this->bidderTeamId;
		}
		return null;
	}

	/**
	 * Return bidder team name
	 *
	 * @return String
	 */
	public function getBidderTeamName()
	{
		if($this->isTransferListed() && $this->getHighestBid()>0)
		{
			if(!isset($this->bidderTeamName) || $this->bidderTeamName === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$this->bidderTeamName = $xpath->query('//BidderTeam/TeamName')->item(0)->nodeValue;
			}
			return $this->bidderTeamName;
		}
		return null;
	}

	/**
	 * Return player number caps in A country team
	 *
	 * @return Integer
	 */
	public function getACaps()
	{
		if(!isset($this->caps) || $this->caps === null)
		{
			$this->caps = $this->getXml()->getElementsByTagName('Caps')->item(0)->nodeValue;
		}
		return $this->caps;
	}

	/**
	 * Return player number caps in U20 country team
	 *
	 * @return Integer
	 */
	public function getU20Caps()
	{
		if(!isset($this->capsU20) || $this->capsU20 === null)
		{
			$this->capsU20 = $this->getXml()->getElementsByTagName('CapsU20')->item(0)->nodeValue;
		}
		return $this->capsU20;
	}

	/**
	 * Does player skills available ?
	 *
	 * @return Boolean
	 */
	public function isSkillsAvailable()
	{
		if(!isset($this->skillsAvailable) || $this->skillsAvailable === null)
		{
			$this->skillsAvailable = $this->getXml()->getElementsByTagName('PlayerSkills')->item(0)->hasChildNodes();
		}
		return $this->skillsAvailable;
	}

	/**
	 * Return player stamina level
	 *
	 * @return Integer
	 */
	public function getStamina()
	{
		if(!isset($this->stamina) || $this->stamina === null)
		{
			$this->stamina = $this->getXml()->getElementsByTagName('StaminaSkill')->item(0)->nodeValue;
		}
		return $this->stamina;
	}

	/**
	 * Return player keeper level
	 *
	 * @return Integer
	 */
	public function getKeeper()
	{
		if($this->isSkillsAvailable())
		{
			if(!isset($this->keeper) || $this->keeper === null)
			{
				$this->keeper = $this->getXml()->getElementsByTagName('KeeperSkill')->item(0)->nodeValue;
			}
			return $this->keeper;
		}
		return null;
	}

	/**
	 * Return player playmaker level
	 *
	 * @return Integer
	 */
	public function getPlaymaker()
	{
		if($this->isSkillsAvailable())
		{
			if(!isset($this->playmaker) || $this->playmaker === null)
			{
				$this->playmaker = $this->getXml()->getElementsByTagName('PlaymakerSkill')->item(0)->nodeValue;
			}
			return $this->playmaker;
		}
		return null;
	}

	/**
	 * Return player scorer level
	 *
	 * @return Integer
	 */
	public function getScorer()
	{
		if($this->isSkillsAvailable())
		{
			if(!isset($this->scorer) || $this->scorer === null)
			{
				$this->scorer = $this->getXml()->getElementsByTagName('ScorerSkill')->item(0)->nodeValue;
			}
			return $this->scorer;
		}
		return null;
	}

	/**
	 * Return player passing level
	 *
	 * @return Integer
	 */
	public function getPassing()
	{
		if($this->isSkillsAvailable())
		{
			if(!isset($this->passing) || $this->passing === null)
			{
				$this->passing = $this->getXml()->getElementsByTagName('PassingSkill')->item(0)->nodeValue;
			}
			return $this->passing;
		}
		return null;
	}

	/**
	 * Return player winger level
	 *
	 * @return Integer
	 */
	public function getWinger()
	{
		if($this->isSkillsAvailable())
		{
			if(!isset($this->winger) || $this->winger === null)
			{
				$this->winger = $this->getXml()->getElementsByTagName('WingerSkill')->item(0)->nodeValue;
			}
			return $this->winger;
		}
		return null;
	}

	/**
	 * Return player defender level
	 *
	 * @return Integer
	 */
	public function getDefender()
	{
		if($this->isSkillsAvailable())
		{
			if(!isset($this->defender) || $this->defender === null)
			{
				$this->defender = $this->getXml()->getElementsByTagName('DefenderSkill')->item(0)->nodeValue;
			}
			return $this->defender;
		}
		return null;
	}

	/**
	 * Return player set pieces level
	 *
	 * @return Integer
	 */
	public function getSetPieces()
	{
		if($this->isSkillsAvailable())
		{
			if(!isset($this->setPieces) || $this->setPieces === null)
			{
				$this->setPieces = $this->getXml()->getElementsByTagName('SetPiecesSkill')->item(0)->nodeValue;
			}
			return $this->setPieces;
		}
		return null;
	}

	/**
	 * Return player agreeability level
	 *
	 * @return Integer
	 */
	public function getAgreeability()
	{
		if(!isset($this->agreeability) || $this->agreeability === null)
		{
			$this->agreeability = $this->getXml()->getElementsByTagName('Agreeability')->item(0)->nodeValue;
		}
		return $this->agreeability;
	}

	/**
	 * Return player aggressiveness level
	 *
	 * @return Integer
	 */
	public function getAggressiveness()
	{
		if(!isset($this->aggressiveness) || $this->aggressiveness === null)
		{
			$this->aggressiveness = $this->getXml()->getElementsByTagName('Aggressiveness')->item(0)->nodeValue;
		}
		return $this->aggressiveness;
	}

	/**
	 * Return player honesty level
	 *
	 * @return Integer
	 */
	public function getHonesty()
	{
		if(!isset($this->honesty) || $this->honesty === null)
		{
			$this->honesty = $this->getXml()->getElementsByTagName('Honesty')->item(0)->nodeValue;
		}
		return $this->honesty;
	}

	/**
	 * Is player a trainer ?
	 *
	 * @return Boolean
	 */
	public function isTrainer()
	{
		if(!isset($this->isTrainer) || $this->isTrainer === null)
		{
			$this->isTrainer = $this->getXml()->getElementsByTagName('TrainerData')->item(0)->hasChildNodes();
		}
		return $this->isTrainer;
	}

	/**
	 * Return player trainer type
	 *
	 * @return Integer
	 */
	public function getTrainerType()
	{
		if($this->isTrainer())
		{
			if(!isset($this->trainerType) || $this->trainerType === null)
			{
				$this->trainerType = $this->getXml()->getElementsByTagName('TrainerType')->item(0)->nodeValue;
			}
			return $this->trainerType;
		}
		return null;
	}

	/**
	 * Return player trainer skill
	 *
	 * @return Integer
	 */
	public function getTrainerSkill()
	{
		if($this->isTrainer())
		{
			if(!isset($this->trainerSkill) || $this->trainerSkill === null)
			{
				$this->trainerSkill = $this->getXml()->getElementsByTagName('TrainerSkill')->item(0)->nodeValue;
			}
			return $this->trainerSkill;
		}
		return null;
	}

	/**
	 * Return player next birthday
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getNextBirthDay($format = null)
	{
		if(!isset($this->birthday[$format]) || $this->birthday[$format] === null)
		{
			$this->birthday[$format] = $this->getXml()->getElementsByTagName('NextBirthDay')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->birthday[$format] = HTFunction::convertDate($this->birthday[$format], $format);
			}
		}
		return $this->birthday[$format];
	}

	/**
	 * Return owner category id set for the player
	 *
	 * @return Integer
	 */
	public function getCategoryId()
	{
		if(!isset($this->categoryId) || $this->categoryId === null)
		{
			$node = $this->getXml()->getElementsByTagName('PlayerCategoryID');
			if($node->length)
			{
				$this->categoryId = $node->item(0)->nodeValue;
			}
		}
		return $this->categoryId;
	}

	/**
	 * Return owner private notes
	 *
	 * @return String
	 */
	public function getOwnerNotes()
	{
		if(!isset($this->ownerNotes) || $this->ownerNotes === null)
		{
			$node = $this->getXml()->getElementsByTagName('OwnerNotes');
			if($node->length)
			{
				$this->ownerNotes = $node->item(0)->nodeValue;
			}
		}
		return $this->ownerNotes;
	}

	/**
	 * Return HTPlayerMatch object
	 *
	 * @return HTPlayerMatch
	 */
	public function getLastMatch()
	{
		if(!isset($this->lastmatch) || $this->lastmatch === null)
		{
			$node = $this->getXml()->getElementsByTagName('LastMatch')->item(0);
			if($node !== null && $node->hasChildNodes())
			{
				$lastmatch = new DOMDocument('1.0', 'UTF-8');
				$lastmatch->appendChild($lastmatch->importNode($node, true));
				$this->lastmatch = new HTPlayerMatch($lastmatch);
			}
		}
		return $this->lastmatch;
	}

	/**
	 * Return player loyalty level
	 *
	 * @return Integer
	 */
	public function getLoyalty()
	{
		if(!isset($this->loyalty) || $this->loyalty === null)
		{
			$node = $this->getXml()->getElementsByTagName('Loyalty');
			if($node->length)
			{
				$this->loyalty = $node->item(0)->nodeValue;
			}
		}
		return $this->loyalty;
	}

	/**
	 * Return if player has mother club bonus
	 *
	 * @return Boolean
	 */
	public function hasMotherClubBonus()
	{
		if(!isset($this->bonus) || $this->bonus === null)
		{
			$node = $this->getXml()->getElementsByTagName('MotherClubBonus');
			if($node->length)
			{
				$this->bonus = strtolower($node->item(0)->nodeValue) == 'true';
			}
		}
		return $this->bonus;
	}

	/**
	 * Return player number goals in his career
	 *
	 * @return Integer
	 */
	public function getGoalsInCareer()
	{
		if(!isset($this->carrerGoals) || $this->carrerGoals === null)
		{
			$this->carrerGoals = $this->getXml()->getElementsByTagName('CareerGoals')->item(0)->nodeValue;
		}
		return $this->carrerGoals;
	}

	/**
	 * Return player number goals in cup
	 *
	 * @return Integer
	 */
	public function getGoalsInCup()
	{
		if(!isset($this->cupGoals) || $this->cupGoals === null)
		{
			$this->cupGoals = $this->getXml()->getElementsByTagName('CupGoals')->item(0)->nodeValue;
		}
		return $this->cupGoals;
	}

	/**
	 * Return player number goals in league
	 *
	 * @return Integer
	 */
	public function getGoalsInLeague()
	{
		if(!isset($this->leagueGoals) || $this->leagueGoals === null)
		{
			$this->leagueGoals = $this->getXml()->getElementsByTagName('LeagueGoals')->item(0)->nodeValue;
		}
		return $this->leagueGoals;
	}

	/**
	 * Return player number hattricks in his career
	 *
	 * @return Integer
	 */
	public function getHattricksInCareer()
	{
		if(!isset($this->carrerHattricks) || $this->carrerHattricks === null)
		{
			$this->carrerHattricks = $this->getXml()->getElementsByTagName('CareerHattricks')->item(0)->nodeValue;
		}
		return $this->carrerHattricks;
	}

	/**
	 * Return player number goals in friendly
	 *
	 * @return Integer
	 */
	public function getGoalsInFriendly()
	{
		if(!isset($this->friendlyGoals) || $this->friendlyGoals === null)
		{
			$this->friendlyGoals = $this->getXml()->getElementsByTagName('FriendliesGoals')->item(0)->nodeValue;
		}
		return $this->friendlyGoals;
	}

	/**
	 * Return national team id if enrolled
	 *
	 * @return Integer
	 */
	public function getNationalTeamId()
	{
		if(!isset($this->nationalTeamId) || $this->nationalTeamId === null)
		{
			$node = $this->getXml()->getElementsByTagName('NationalTeamID');
			if($node->length)
			{
				$this->nationalTeamId = $node->item(0)->nodeValue;
			}
		}
		return $this->nationalTeamId;
	}

	/**
	 * Return national team name if enrolled
	 *
	 * @return String
	 */
	public function getNationalTeamName()
	{
		if(!isset($this->nationalTeamName) || $this->nationalTeamName === null)
		{
			$node = $this->getXml()->getElementsByTagName('NationalTeamName');
			if($node->length)
			{
				$this->nationalTeamName = $node->item(0)->nodeValue;
			}
		}
		return $this->nationalTeamName;
	}
}
class HTPlayerMatch extends HTXml
{
	private $matchid = null;
	private $date = null;
	private $code = null;
	private $minutes = null;
	private $rating = null;
	private $ratingendofgame = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getMatchId()
	{
		if(!isset($this->matchid) || $this->matchid === null)
		{
			$this->matchid = $this->getXml()->getElementsByTagName('MatchId')->item(0)->nodeValue;
		}
		return $this->matchid;
	}

	/**
	 * Return position code
	 *
	 * @return Integer
	 */
	public function getPositionCode()
	{
		if(!isset($this->code) || $this->code === null)
		{
			$this->code = $this->getXml()->getElementsByTagName('PositionCode')->item(0)->nodeValue;
		}
		return $this->code;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('Date')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return rating
	 *
	 * @return Integer
	 */
	public function getRating()
	{
		if(!isset($this->rating) || $this->rating === null)
		{
			$this->rating = $this->getXml()->getElementsByTagName('Rating')->item(0)->nodeValue;
		}
		return $this->rating;
	}

	/**
	 * Return number of played minutes
	 *
	 * @return Integer
	 */
	public function getPlayedMinutes()
	{
		if(!isset($this->minutes) || $this->minutes === null)
		{
			$this->minutes = $this->getXml()->getElementsByTagName('PlayedMinutes')->item(0)->nodeValue;
		}
		return $this->minutes;
	}

	/**
	 * Return rating at end of game
	 *
	 * @return Integer
	 */
	public function getRatingEndOfGame()
	{
		if(!isset($this->ratingendofgame) || $this->ratingendofgame === null)
		{
			$this->ratingendofgame = $this->getXml()->getElementsByTagName('RatingEndOfGame')->item(0)->nodeValue;
		}
		return $this->ratingendofgame;
	}
}
class HTTeamMatches extends HTCommonTeam
{
	private $numberMatches = null;
	private $matches = null;
	private $nextMatch = null;
	private $nextMatches = null;
	private $lastMatch = null;
	private $lastMatches = null;
	private $playingMatches = null;
	private $isYouth = null;
	private $leagueId = null;
	private $leagueName = null;
	private $leagueLevelUnitId = null;
	private $leagueLevelUnitName = null;
	private $leagueLevel = null;
	const NOT_PLAYED = 'UPCOMING';
	const PLAYED = 'FINISHED';
	const PLAYING = 'ONGOING';

	/**
	 * Return if it's youth matches
	 *
	 * @return Boolean
	 */
	public function isYouth()
	{
		if(!isset($this->isYouth) || $this->isYouth === null)
		{
			$this->isYouth = strtolower($this->getXml()->getElementsByTagName('IsYouth')->item(0)->nodeValue) == 'true';
		}
		return $this->isYouth;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!$this->isYouth())
		{
			if(!isset($this->leagueId) || $this->leagueId === null)
			{
				$this->leagueId = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
			}
			return $this->leagueId;
		}
		return null;
	}

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!$this->isYouth())
		{
			if(!isset($this->leagueName) || $this->leagueName === null)
			{
				$this->leagueName = $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
			}
			return $this->leagueName;
		}
		return null;
	}

	/**
	 * Return league level unit id
	 *
	 * @return Integer
	 */
	public function getLeagueLevelUnitId()
	{
		if(!$this->isYouth())
		{
			if(!isset($this->leagueLevelUnitId) || $this->leagueLevelUnitId === null)
			{
				$this->leagueLevelUnitId = $this->getXml()->getElementsByTagName('LeagueLevelUnitID')->item(0)->nodeValue;
			}
			return $this->leagueLevelUnitId;
		}
		return null;
	}

	/**
	 * Return league level unit name
	 *
	 * @return String
	 */
	public function getLeagueLevelUnitName()
	{
		if(!$this->isYouth())
		{
			if(!isset($this->leagueLevelUnitName) || $this->leagueLevelUnitName === null)
			{
				$this->leagueLevelUnitName = $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
			}
			return $this->leagueLevelUnitName;
		}
		return null;
	}

	/**
	 * Return league level
	 *
	 * @return Integer
	 */
	public function getLeagueLevel()
	{
		if(!$this->isYouth())
		{
			if(!isset($this->leagueLevel) || $this->leagueLevel === null)
			{
				$this->leagueLevel = $this->getXml()->getElementsByTagName('LeagueLevel')->item(0)->nodeValue;
			}
			return $this->leagueLevel;
		}
		return null;
	}

	/**
	 * Return number of matches in list
	 *
	 * @return Integer
	 */
	public function getNumberMatches()
	{
		if(!isset($this->numberMatches) || $this->numberMatches === null)
		{
			$this->numberMatches = $this->getXml()->getElementsByTagName('Match')->length;
		}
		return $this->numberMatches;
	}

	/**
	 * Return HTTeamMatch object
	 *
	 * @param Integer $index
	 * @return HTTeamMatch
	 */
	public function getMatch($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberMatches())
		{
			--$index;
			if(!isset($this->matches[$index]) || $this->matches[$index] === null)
			{
				$nodeList = $this->getXml()->getElementsByTagName('Match');
				$xmlMatch = new DOMDocument('1.0', 'UTF-8');
				$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($index), true));
				$this->matches[$index] = new HTTeamMatch($xmlMatch);
			}
			return $this->matches[$index];
		}
		return null;
	}

	/**
	 * Return next match
	 *
	 * @return HTTeamMatch
	 */
	public function getNextMatch()
	{
		if(!isset($this->nextMatch) || $this->nextMatch === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Status[.="'.self::NOT_PLAYED.'"]');
			$xmlMatch = new DOMDocument('1.0', 'UTF-8');
			$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item(0)->parentNode, true));
			$this->nextMatch = new HTTeamMatch($xmlMatch);
		}
		return $this->nextMatch;
	}

	/**
	 * Return next matches
	 *
	 * @return Array<HTTeamMatch>
	 */
	public function getNextMatches()
	{
		if(!isset($this->nextMatches) || $this->nextMatches === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Status[.="'.self::NOT_PLAYED.'"]');
			for($i=0; $i<$nodeList->length; $i++)
			{
				$xmlMatch = new DOMDocument('1.0', 'UTF-8');
				$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($i)->parentNode, true));
				$this->nextMatches[$i] = new HTTeamMatch($xmlMatch);
			}
		}
		return $this->nextMatches;
	}

	/**
	 * Return last match
	 *
	 * @return HTTeamMatch
	 */
	public function getLastMatch()
	{
		if(!isset($this->lastMatch) || $this->lastMatch === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Status[.="'.self::PLAYED.'"]');
			$xmlMatch = new DOMDocument('1.0', 'UTF-8');
			$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($nodeList->length - 1)->parentNode, true));
			$this->lastMatch = new HTTeamMatch($xmlMatch);
		}
		return $this->lastMatch;
	}

	/**
	 * Return last matches
	 *
	 * @return Array<HTTeamMatch>
	 */
	public function getLastMatches()
	{
		if(!isset($this->lastMatches) || $this->lastMatches === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Status[.="'.self::PLAYED.'"]');
			for($i=1; $i<=$nodeList->length; $i++)
			{
				$xmlMatch = new DOMDocument('1.0', 'UTF-8');
				$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($nodeList->length - $i)->parentNode, true));
				$this->lastMatches[$i] = new HTTeamMatch($xmlMatch);
			}
		}
		return $this->lastMatches;
	}

	/**
	 * Return playing matches
	 *
	 * @return Array<HTTeamMatch>
	 */
	public function getPlayingMatches()
	{
		if(!isset($this->playingMatches) || $this->playingMatches === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Status[.="'.self::PLAYING.'"]');
			for($i=0; $i<$nodeList->length; $i++)
			{
				$xmlMatch = new DOMDocument('1.0', 'UTF-8');
				$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($i)->parentNode, true));
				$this->playingMatches[$i] = new HTTeamMatch($xmlMatch);
			}
		}
		return $this->playingMatches;
	}
}
class HTTeamMatch extends HTXml
{
	private $id = null;
	private $homeTeamId = null;
	private $homeTeamName = null;
	private $awayTeamId = null;
	private $awayTeamName = null;
	private $date = null;
	private $type = null;
	private $context = null;
	private $ordersGiven = null;
	private $status = null;
	private $isTournament = null;
	private $isYouth = null;
	const TOURNAMENT = 'htointegrated';
	const YOUTH = 'youth';
	const SENIOR = 'hattrick';

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return if it's youth match
	 *
	 * @return Boolean
	 */
	public function isYouth()
	{
		if(!isset($this->isYouth) || $this->isYouth === null)
		{
			$this->isYouth = strtolower($this->getXml()->getElementsByTagName('SourceSystem')->item(0)->nodeValue) == self::YOUTH;
		}
		return $this->isYouth;
	}

	/**
	 * Return if it's tournament match
	 *
	 * @return Boolean
	 */
	public function isTournament()
	{
		if(!isset($this->isTournament) || $this->isTournament === null)
		{
			$this->isTournament = strtolower($this->getXml()->getElementsByTagName('SourceSystem')->item(0)->nodeValue) == self::TOURNAMENT;
		}
		return $this->isTournament;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return home team id
	 *
	 * @return Integer
	 */
	public function getHomeTeamId()
	{
		if(!isset($this->homeTeamId) || $this->homeTeamId === null)
		{
			$this->homeTeamId = $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
		}
		return $this->homeTeamId;
	}

	/**
	 * Return home team name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if(!isset($this->homeTeamName) || $this->homeTeamName === null)
		{
			$this->homeTeamName = $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
		}
		return $this->homeTeamName;
	}

	/**
	 * Return away team id
	 *
	 * @return Integer
	 */
	public function getAwayTeamId()
	{
		if(!isset($this->awayTeamId) || $this->awayTeamId === null)
		{
			$this->awayTeamId = $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
		}
		return $this->awayTeamId;
	}

	/**
	 * Return away team name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if(!isset($this->awayTeamName) || $this->awayTeamName === null)
		{
			$this->awayTeamName = $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
		}
		return $this->awayTeamName;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return match type code
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return match context id
	 *
	 * @return Integer
	 */
	public function getContextId()
	{
		if(!isset($this->context) || $this->context === null)
		{
			$this->context = $this->getXml()->getElementsByTagName('MatchContextId')->item(0)->nodeValue;
		}
		return $this->context;
	}

	/**
	 * Is orders already given for this match ?
	 *
	 * @return Boolean
	 */
	public function isOrdersGiven()
	{
		if($this->getStatus() == HTTeamMatches::NOT_PLAYED )
		{
			if(!isset($this->ordersGiven) || $this->ordersGiven === null)
			{
				$node = $this->getXml()->getElementsByTagName('OrdersGiven');
				if($node !== null && $node->length)
				{
					$this->ordersGiven = strtolower($node->item(0)->nodeValue) == "true";
				}
				else
				{
					$this->ordersGiven = false;
				}
			}
			return $this->ordersGiven;
		}
		return null;
	}

	/**
	 * Return match status : UPCOMING, ONGOING, FINISHED
	 *
	 * @return String
	 */
	public function getStatus()
	{
		if(!isset($this->status) || $this->status === null)
		{
			$this->status = $this->getXml()->getElementsByTagName('Status')->item(0)->nodeValue;
		}
		return $this->status;
	}
}
class HTMatch extends HTGlobal
{
	private $id = null;
	private $type = null;
	private $startDate = null;
	private $endDate = null;
	private $homeTeam = null;
	private $awayTeam = null;
	private $arena = null;
	private $score = null;
	private $goals = null;
	private $totalGoals = null;
	private $cards = null;
	private $totalYellowCards = null;
	private $yellowCards = null;
	private $totalRedCards = null;
	private $redCards = null;
	private $possesionHomeTeamFirstHalf = null;
	private $possesionHomeTeamSecondHalf = null;
	private $possesionAwayTeamFirstHalf = null;
	private $possesionAwayTeamSecondHalf = null;
	private $fullText = null;
	private $firstHalf = null;
	private $secondHalf = null;
	private $eventNumber = null;
	private $events = null;
	private $injuriesNumber = null;
	private $injuries = null;
	private $isYouth = null;
	private $isTournament = null;
	private $referee = null;
	private $referee1 = null;
	private $referee2 = null;
	const YOUTH = 'youth';
	const TOURNAMENT = 'htointegrated';
	const SENIOR = 'hattrick';

	/**
	 * Return if it's a youth match
	 *
	 * @return Boolean
	 */
	public function isYouth()
	{
		if(!isset($this->isYouth) || $this->isYouth === null)
		{
			$this->isYouth = strtolower($this->getXml()->getElementsByTagName('SourceSystem')->item(0)->nodeValue) == self::YOUTH;
		}
		return $this->isYouth;
	}

	/**
	 * Return if it's a tournament match
	 *
	 * @return Boolean
	 */
	public function isTournament()
	{
		if(!isset($this->isTournament) || $this->isTournament === null)
		{
			$this->isTournament = strtolower($this->getXml()->getElementsByTagName('SourceSystem')->item(0)->nodeValue) == self::TOURNAMENT;
		}
		return $this->isTournament;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return match type
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return match start datetime
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getStartDate($format = null)
	{
		if(!isset($this->startDate[$format]) || $this->startDate[$format] === null)
		{
			$this->startDate[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->startDate[$format] = HTFunction::convertDate($this->startDate[$format], $format);
			}
		}
		return $this->startDate[$format];
	}

	/**
	 * Return match end datetime
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getEndDate($format = null)
	{
		if(!isset($this->endDate[$format]) || $this->endDate[$format] === null)
    {
      $this->endDate[$format] = null;
      $node =  $this->getXml()->getElementsByTagName('FinishedDate');
      if($node->length)
      {
        $this->endDate[$format] = $node->item(0)->nodeValue;
        if($format !== null)
        {
          $this->endDate[$format] = HTFunction::convertDate($this->endDate[$format], $format);
        }
      }
    }
		return $this->endDate[$format];
	}

	/**
	 * Return home team HTMatchTeam object
	 *
	 * @return HTMatchTeam
	 */
	public function getHomeTeam()
	{
		if(!isset($this->homeTeam) || $this->homeTeam === null)
		{
			$this->homeTeam = $this->getTeam('Home');
		}
		return $this->homeTeam;
	}

	/**
	 * Return away team HTMatchTeam object
	 *
	 * @return HTMatchTeam
	 */
	public function getAwayTeam()
	{
		if(!isset($this->awayTeam) || $this->awayTeam === null)
		{
			$this->awayTeam = $this->getTeam('Away');
		}
		return $this->awayTeam;
	}

	/**
	 * @param String $type
	 * @return HTMatchTeam
	 */
	private function getTeam($type)
	{
		$xpath = new DOMXPath($this->getXml());
		$nodeList = $xpath->query('//'.$type.'Team');
		$team = new DOMDocument('1.0', 'UTF-8');
		$team->appendChild($team->importNode($nodeList->item(0), true));
		return new HTMatchTeam($team, $type);
	}

	/**
	 * Return HTMatchArena object
	 *
	 * @return HTMatchArena
	 */
	public function getArena()
	{
		if(!isset($this->arena) || $this->arena === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Arena');
			$arena = new DOMDocument('1.0', 'UTF-8');
			$arena->appendChild($arena->importNode($nodeList->item(0), true));
			$this->arena = new HTMatchArena($arena);
		}
		return $this->arena;
	}

	/**
	 * Return match score
	 *
	 * @return String
	 */
	public function getScore()
	{
		if(!isset($this->score) || $this->score === null)
		{
			$home = $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
			$away = $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
			$this->score = $home.'-'.$away;
		}
		return $this->score;
	}

	/**
	 * Return number of goals in the match
	 *
	 * @return Integer
	 */
	public function getTotalGoals()
	{
		if(!isset($this->totalGoals) || $this->totalGoals === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Goal');
			$this->totalGoals = $nodeList->length;
		}
		return $this->totalGoals;
	}

	/**
	 * Return HTMatchGoal object
	 *
	 * @param Integer $number
	 * @return HTMatchGoal
	 */
	public function getGoal($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getTotalGoals())
		{
			--$number;
			if(!isset($this->goals[$number]) || $this->goals[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Goal');
				$goal = new DOMDocument('1.0', 'UTF-8');
				$goal->appendChild($goal->importNode($nodeList->item($number), true));
				$this->goals[$number] = new HTMatchGoal($goal);
			}
			return $this->goals[$number];
		}
		return null;
	}

	/**
	 * Return total of yellow Cards
	 *
	 * @return Integer
	 */
	public function getTotalYellowCards()
	{
		if(!isset($this->totalYellowCards) || $this->totalYellowCards === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//BookingType[.="1"]');
			$this->totalYellowCards = $nodeList->length;
		}
		return $this->totalYellowCards;
	}

	/**
	 * Return total of red Cards
	 *
	 * @return Integer
	 */
	public function getTotalRedCards()
	{
		if(!isset($this->totalRedCards) || $this->totalRedCards === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//BookingType[.="2"]');
			$this->totalRedCards = $nodeList->length;
		}
		return $this->totalRedCards;
	}

	/**
	 * Return total of Cards
	 *
	 * @return Integer
	 */
	public function getTotalCards()
	{
		return $this->getTotalRedCards()+$this->getTotalYellowCards();
	}

	/**
	 * Return HTMatchCard object
	 *
	 * @return HTMatchCard
	 */
	public function getCard($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getTotalCards())
		{
			--$number;
			if(!isset($this->cards[$number]) || $this->cards[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Booking');
				$card = new DOMDocument('1.0', 'UTF-8');
				$card->appendChild($card->importNode($nodeList->item($number), true));
				$this->cards[$number] = new HTMatchCard($card);
			}
			return $this->cards[$number];
		}
		return null;
	}

	/**
	 * Return yellow HTMatchCard object
	 *
	 * @return HTMatchCard
	 */
	public function getYellowCard($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getTotalYellowCards())
		{
			if(!isset($this->yellowCards[$number]) || $this->yellowCards[$number] === null)
			{
				$this->yellowCards[$number] = $this->getColorCard(1, $number);
			}
			return $this->yellowCards[$number];
		}
		return null;
	}

	/**
	 * Return red HTMatchCard object
	 *
	 * @return HTMatchCard
	 */
	public function getRedCard($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getTotalRedCards())
		{
			if(!isset($this->redCards[$number]) || $this->redCards[$number] === null)
			{
				$this->redCards[$number] = $this->getColorCard(2, $number);
			}
			return $this->redCards[$number];
		}
		return null;
	}

	/**
	 * @return HTMatchCard
	 */
	private function getColorCard($colorId, $number)
	{
		$xpath = new DOMXPath($this->getXml());
		$nodeList = $xpath->query('//BookingType[.="'.$colorId.'"]');
		$card = new DOMDocument('1.0', 'UTF-8');
		$card->appendChild($card->importNode($nodeList->item((--$number))->parentNode, true));
		return  new HTMatchCard($card);
	}

	/**
	 * Return home team possession in firt halftime
	 *
	 * @return Integer
	 */
	public function getHomeTeamPossessionFirstHalf()
	{
		if(!isset($this->possesionHomeTeamFirstHalf) || $this->possesionHomeTeamFirstHalf === null)
		{
			$this->possesionHomeTeamFirstHalf = $this->getXml()->getElementsByTagName('PossessionFirstHalfHome')->item(0)->nodeValue;
		}
		return $this->possesionHomeTeamFirstHalf;
	}

	/**
	 * Return home team possession in second halftime
	 *
	 * @return Integer
	 */
	public function getHomeTeamPossessionSecondHalf()
	{
		if(!isset($this->possesionHomeTeamSecondHalf) || $this->possesionHomeTeamSecondHalf === null)
		{
			$this->possesionHomeTeamSecondHalf = $this->getXml()->getElementsByTagName('PossessionSecondHalfHome')->item(0)->nodeValue;
		}
		return $this->possesionHomeTeamSecondHalf;
	}

	/**
	 * Return away team possession in firt halftime
	 *
	 * @return Integer
	 */
	public function getAwayTeamPossessionFirstHalf()
	{
		if(!isset($this->possesionAwayTeamFirstHalf) || $this->possesionAwayTeamFirstHalf === null)
		{
			$this->possesionAwayTeamFirstHalf = $this->getXml()->getElementsByTagName('PossessionFirstHalfAway')->item(0)->nodeValue;
		}
		return $this->possesionAwayTeamFirstHalf;
	}

	/**
	 * Return away team possession in second halftime
	 *
	 * @return Integer
	 */
	public function getAwayTeamPossessionSecondHalf()
	{
		if(!isset($this->possesionAwayTeamSecondHalf) || $this->possesionAwayTeamSecondHalf === null)
		{
			$this->possesionAwayTeamSecondHalf = $this->getXml()->getElementsByTagName('PossessionSecondHalfAway')->item(0)->nodeValue;
		}
		return $this->possesionAwayTeamSecondHalf;
	}

	/**
	 * Return whole text match
	 *
	 * @param String $playerUrlReplacement (given url is concat with : PlayerID=xxxxxxx )
	 * @return String
	 */
	public function getFullText($playerUrlReplacement = null)
	{
		if(!isset($this->fullText) || $this->fullText === null)
		{
			$this->fullText = $this->getFirstHalfTime($playerUrlReplacement).$this->getSecondHalfTime($playerUrlReplacement);
		}
		return $this->fullText;
	}

	/**
	 * Return text of first half time
	 *
	 * @param String $playerUrlReplacement (given url is concat with : PlayerID=xxxxxxx )
	 * @return String
	 */
	public function getFirstHalfTime($playerUrlReplacement = null)
	{
		if(!isset($this->firstHalf) || $this->firstHalf === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Minute[.<46]');
			$text = '';
			foreach ($nodeList as $event)
			{
				$event = $event->parentNode;
				$text .= $event->getElementsByTagName('EventText')->item(0)->nodeValue;
			}
			$this->firstHalf = $this->replaceUrl($text, $playerUrlReplacement);
		}
		return $this->firstHalf;
	}

	/**
	 * Return text of second half time
	 *
	 * @param String $playerUrlReplacement (given url is concat with : PlayerID=xxxxxxx )
	 * @return String
	 */
	public function getSecondHalfTime($playerUrlReplacement = null)
	{
		if(!isset($this->secondHalf) || $this->secondHalf === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Minute[.>45]');
			$text = '';
			foreach ($nodeList as $event)
			{
				$event = $event->parentNode;
				$text .= $event->getElementsByTagName('EventText')->item(0)->nodeValue;
			}
			$this->secondHalf = $this->replaceUrl($text, $playerUrlReplacement);
		}
		return $this->secondHalf;
	}

	/**
	 * @param String $text
	 * @param String $playerUrlReplacement
	 * @return String
	 */
	private function replaceUrl($text, $playerUrlReplacement)
	{
		if($playerUrlReplacement !== null)
		{
			$text = HTFunction::updatePlayerUrl($text, $playerUrlReplacement);
		}
		else
		{
			$text = HTFunction::updatePlayerUrl($text, 'http://www.hattrick.org' . HTFunction::PLAYERURL);
		}
		return $text;
	}

	/**
	 * Return number event
	 *
	 * @return Integer
	 */
	public function getEventNumber()
	{
		if(!isset($this->eventNumber) || $this->eventNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Event');
			$this->eventNumber = $nodeList->length;
		}
		return $this->eventNumber;
	}

	/**
	 * Return HTLiveMatchEvent object
	 *
	 * @param Integer $number
	 * @return HTLiveMatchEvent
	 */
	public function getEvent($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getEventNumber())
		{
			--$number;
			if(!isset($this->events[$number]) || $this->events[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Event');
				$event = new DOMDocument('1.0', 'UTF-8');
				$event->appendChild($event->importNode($nodeList->item($number), true));
				$this->events[$number] = new HTLiveMatchEvent($event);
			}
			return $this->events[$number];
		}
		return null;
	}

	/**
	 * Return number of injuries in the match
	 *
	 * @return Integer
	 */
	public function getTotalInjuries()
	{
		if(!isset($this->injuriesNumber) || $this->injuriesNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Injury');
			$this->injuriesNumber = $nodeList->length;
		}
		return $this->injuriesNumber;
	}

	/**
	 * Return HTMatchInjury object
	 *
	 * @param Integer $number
	 * @return HTMatchInjury
	 */
	public function getInjury($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getTotalInjuries())
		{
			--$number;
			if(!isset($this->injuries[$number]) || $this->injuries[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Injury');
				$injury = new DOMDocument('1.0', 'UTF-8');
				$injury->appendChild($injury->importNode($nodeList->item($number), true));
				$this->injuries[$number] = new HTMatchInjury($injury);
			}
			return $this->injuries[$number];
		}
		return null;
	}

	/**
	 * Return HTReferee object
	 *
	 * @return HTReferee
	 */
	public function getReferee()
	{
		if(!isset($this->referee) || $this->referee === null)
		{
			$node = $this->xml->getElementsByTagName('Referee');
			if($node->length)
			{
				$ref = new DOMDocument('1.0', 'UTF-8');
				$ref->appendChild($ref->importNode($node->item(0), true));
				$this->referee = new HTReferee($ref);
			}
		}
		return $this->referee;
	}

	/**
	 * Return HTReferee object
	 *
	 * @return HTReferee
	 */
	public function getFirstRefereeAssistant()
	{
		if(!isset($this->referee1) || $this->referee1 === null)
		{
			$node = $this->xml->getElementsByTagName('RefereeAssistant1');
			if($node->length)
			{
				$ref = new DOMDocument('1.0', 'UTF-8');
				$ref->appendChild($ref->importNode($node->item(0), true));
				$this->referee1 = new HTReferee($ref);
			}
		}
		return $this->referee1;
	}

	/**
	 * Return HTReferee object
	 *
	 * @return HTReferee
	 */
	public function getSecondRefereeAssistant()
	{
		if(!isset($this->referee2) || $this->referee2 === null)
		{
			$node = $this->xml->getElementsByTagName('RefereeAssistant2');
			if($node->length)
			{
				$ref = new DOMDocument('1.0', 'UTF-8');
				$ref->appendChild($ref->importNode($node->item(0), true));
				$this->referee2 = new HTReferee($ref);
			}
		}
		return $this->referee2;
	}
}
class HTTournaments extends HTGlobal
{
	private $tournamentNumber = null;
	private $tournament = array();

	/**
	 * Return number of tournaments
	 *
	 * @return Integer
	 */
	public function getTournamentNumber()
	{
		if(!isset($this->tournamentNumber) || $this->tournamentNumber === null)
		{
			$this->tournamentNumber = $this->getXml()->getElementsByTagName('Tournament')->length;
		}
		return $this->tournamentNumber;
	}

	/**
	 * Return HTTournament object
	 *
	 * @param Integer $index
	 * @return HTTournament
	 */
	public function getTournament($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getTournamentNumber())
		{
			--$index;
			if(!isset($this->tournament[$index]) || $this->tournament[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Tournament');
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->tournament[$index] = new HTTournament($node->saveXML());
			}
			return $this->tournament[$index];
		}
		return null;
	}
}
class HTTournament extends HTGlobal
{
	private $tournamentId = null;
	private $name = null;
	private $type = null;
	private $season = null;
	private $logo = null;
	private $trophy = null;
	private $numberTeams = null;
	private $numberGroups = null;
	private $lastRound = null;
	private $firstRoundDate = null;
	private $nextRoundDate = null;
	private $matchOnGoing = null;
	private $creatorId = null;
	private $creatorName = null;

	/**
	 * Return tournament id
	 *
	 * @return Integer
	 */
	public function getTournamentId()
	{
		if(!isset($this->tournamentId) || $this->tournamentId === null)
		{
			$this->tournamentId = $this->getXml()->getElementsByTagName('TournamentId')->item(0)->nodeValue;
		}
		return $this->tournamentId;
	}

	/**
	 * Return tournament name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('Name')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return tournament type
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('TournamentType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return season number
	 *
	 * @return Integer
	 */
	public function getSeason()
	{
		if(!isset($this->season) || $this->season === null)
		{
			$this->season = $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
		}
		return $this->season;
	}

	/**
	 * Return logo url
	 *
	 * @return String
	 */
	public function getLogoUrl()
	{
		if(!isset($this->logo) || $this->logo === null)
		{
			$this->logo = $this->getXml()->getElementsByTagName('LogoUrl')->item(0)->nodeValue;
		}
		return $this->logo;
	}

	/**
	 * Return trophy
	 *
	 * @return Integer
	 */
	public function getTrophy()
	{
		if(!isset($this->trophy) || $this->trophy === null)
		{
			$this->trophy = $this->getXml()->getElementsByTagName('TrophyType')->item(0)->nodeValue;
		}
		return $this->trophy;
	}

	/**
	 * Return number of teams
	 *
	 * @return Integer
	 */
	public function getTeamNumber()
	{
		if(!isset($this->numberTeams) || $this->numberTeams === null)
		{
			$this->numberTeams = $this->getXml()->getElementsByTagName('NumberOfTeams')->item(0)->nodeValue;
		}
		return $this->numberTeams;
	}

	/**
	 * Return number of groups
	 *
	 * @return Integer
	 */
	public function getGroupNumber()
	{
		if(!isset($this->numberGroups) || $this->numberGroups === null)
		{
			$this->numberGroups = $this->getXml()->getElementsByTagName('NumberOfGroups')->item(0)->nodeValue;
		}
		return $this->numberGroups;
	}

	/**
	 * Return last finished match round number
	 *
	 * @return Integer
	 */
	public function getLastMatchRound()
	{
		if(!isset($this->lastRound) || $this->lastRound === null)
		{
			$this->lastRound = $this->getXml()->getElementsByTagName('LastMatchRound')->item(0)->nodeValue;
		}
		return $this->lastRound;
	}

	/**
	 * Return first match round date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getFirstMatchRoundDate($format = null)
	{
		if(!isset($this->firstRoundDate[$format]) || $this->firstRoundDate[$format] === null)
		{
			$this->firstRoundDate[$format] = $this->getXml()->getElementsByTagName('FirstMatchRoundDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->firstRoundDate[$format] = HTFunction::convertDate($this->firstRoundDate[$format], $format);
			}
		}
		return $this->firstRoundDate[$format];
	}

	/**
	 * Return next match round date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getNextMatchRoundDate($format = null)
	{
		if(!isset($this->nextRoundDate[$format]) || $this->nextRoundDate[$format] === null)
		{
			$this->nextRoundDate[$format] = $this->getXml()->getElementsByTagName('NextMatchRoundDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->nextRoundDate[$format] = HTFunction::convertDate($this->nextRoundDate[$format], $format);
			}
		}
		return $this->nextRoundDate[$format];
	}

	/**
	 * Return if a matches are ongoing
	 *
	 * @return Boolean
	 */
	public function isMatchOnGoing()
	{
		if(!isset($this->matchOnGoing) || $this->matchOnGoing === null)
		{
			$this->matchOnGoing = (bool)$this->getXml()->getElementsByTagName('IsMatchesOngoing')->item(0)->nodeValue;
		}
		return $this->matchOnGoing;
	}

	/**
	 * Return creator id
	 *
	 * @return Integer
	 */
	public function getCreatorId()
	{
		if(!isset($this->creatorId) || $this->creatorId === null)
		{
			$this->creatorId = $this->getXml()->getElementsByTagName('UserId')->item(0)->nodeValue;
		}
		return $this->creatorId;
	}

	/**
	 * Return creator name
	 *
	 * @return Integer
	 */
	public function getCreatorName()
	{
		if(!isset($this->creatorName) || $this->creatorName === null)
		{
			$this->creatorName = $this->getXml()->getElementsByTagName('Loginname')->item(0)->nodeValue;
		}
		return $this->creatorName;
	}
}
class HTTournamentLeagueTable extends HTGlobal
{
	private $groupNumber = null;
	private $group = array();

	/**
	 * Return number of groups
	 *
	 * @return Integer
	 */
	public function getGroupNumber()
	{
		if(!isset($this->groupNumber) || $this->groupNumber === null)
		{
			$this->groupNumber = $this->getXml()->getElementsByTagName('TournamentLeagueTable')->length;
		}
		return $this->groupNumber;
	}

	/**
	 * Return HTTournamentGroup object
	 *
	 * @param Integer $index
	 * @return HTTournamentGroup
	 */
	public function getGroup($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getGroupNumber())
		{
			--$index;
			if(!isset($this->group[$index]) || $this->group[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//TournamentLeagueTable');
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->group[$index] = new HTTournamentGroup($node);
			}
			return $this->group[$index];
		}
		return null;
	}
}
class HTTournamentGroup extends HTXml
{
	private $groupId = null;
	private $teamNumber = null;
	private $team = array();

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return group id
	 *
	 * @return Integer
	 */
	public function getGroupId()
	{
		if(!isset($this->groupId) || $this->groupId === null)
		{
			$this->groupId = $this->getXml()->getElementsByTagName('GroupId')->item(0)->nodeValue;
		}
		return $this->groupId;
	}

	/**
	 * Return number of teams
	 *
	 * @return Integer
	 */
	public function getTeamNumber()
	{
		if(!isset($this->teamNumber) || $this->teamNumber === null)
		{
			$this->teamNumber = $this->getXml()->getElementsByTagName('Team')->length;
		}
		return $this->teamNumber;
	}

	/**
	 * Return HTLeagueTeam object
	 *
	 * @param Integer $index
	 * @return HTLeagueTeam
	 */
	public function getTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getTeamNumber())
		{
			--$index;
			if(!isset($this->team[$index]) || $this->team[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Team');
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->team[$index] = new HTLeagueTeam($node);
			}
			return $this->team[$index];
		}
		return null;
	}
}
class HTTournamentMatches extends HTGlobal
{
	private $matchNumber = null;
	private $match = array();

	/**
	 * Return number of matches
	 *
	 * @return Integer
	 */
	public function getMatchNumber()
	{
		if(!isset($this->matchNumber) || $this->matchNumber === null)
		{
			$this->matchNumber = $this->getXml()->getElementsByTagName('Match')->length;
		}
		return $this->matchNumber;
	}

	/**
	 * Return HTTournamentMatch object
	 *
	 * @param Integer $index
	 * @return HTTournamentMatch
	 */
	public function getMatch($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getMatchNumber())
		{
			--$index;
			if(!isset($this->match[$index]) || $this->match[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Match');
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->match[$index] = new HTTournamentMatch($node);
			}
			return $this->match[$index];
		}
		return null;
	}
}
class HTTournamentMatch extends HTXml
{
	private $id = null;
	private $homeTeamId = null;
	private $homeTeamName = null;
	private $homeTeamShortName = null;
	private $awayTeamId = null;
	private $awayTeamName = null;
	private $awayTeamShortName = null;
	private $date = null;
	private $type = null;
	private $round = null;
	private $group = null;
	private $status = null;
	private $homeGoals = null;
	private $awayGoals = null;
	private $homeStatement = null;
	private $awayStatement = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('MatchId')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return home team id
	 *
	 * @return Integer
	 */
	public function getHomeTeamId()
	{
		if(!isset($this->homeTeamId) || $this->homeTeamId === null)
		{
			$this->homeTeamId = $this->getXml()->getElementsByTagName('HomeTeamId')->item(0)->nodeValue;
		}
		return $this->homeTeamId;
	}

	/**
	 * Return home team name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if(!isset($this->homeTeamName) || $this->homeTeamName === null)
		{
			$this->homeTeamName = $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
		}
		return $this->homeTeamName;
	}

	/**
	 * Return home team short  name
	 *
	 * @return String
	 */
	public function getHomeTeamShortName()
	{
		if(!isset($this->homeTeamShortName) || $this->homeTeamShortName === null)
		{
			$this->homeTeamShortName = $this->getXml()->getElementsByTagName('HomeShortTeamName')->item(0)->nodeValue;
		}
		return $this->homeTeamShortName;
	}

	/**
	 * Return away team id
	 *
	 * @return Integer
	 */
	public function getAwayTeamId()
	{
		if(!isset($this->awayTeamId) || $this->awayTeamId === null)
		{
			$this->awayTeamId = $this->getXml()->getElementsByTagName('AwayTeamId')->item(0)->nodeValue;
		}
		return $this->awayTeamId;
	}

	/**
	 * Return away team name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if(!isset($this->awayTeamName) || $this->awayTeamName === null)
		{
			$this->awayTeamName = $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
		}
		return $this->awayTeamName;
	}

	/**
	 * Return away team short  name
	 *
	 * @return String
	 */
	public function getAwayTeamShortName()
	{
		if(!isset($this->awayTeamShortName) || $this->awayTeamShortName === null)
		{
			$this->awayTeamShortName = $this->getXml()->getElementsByTagName('AwayShortTeamName')->item(0)->nodeValue;
		}
		return $this->awayTeamShortName;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return match type
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return match round
	 *
	 * @return Integer
	 */
	public function getRound()
	{
		if(!isset($this->round) || $this->round === null)
		{
			$this->round = $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
		}
		return $this->round;
	}

	/**
	 * Return match group
	 *
	 * @return Integer
	 */
	public function getGroup()
	{
		if(!isset($this->group) || $this->group === null)
		{
			$this->group = $this->getXml()->getElementsByTagName('Group')->item(0)->nodeValue;
		}
		return $this->group;
	}

	/**
	 * Return match status
	 *
	 * @return Integer
	 */
	public function getStatus()
	{
		if(!isset($this->status) || $this->status === null)
		{
			$this->status = $this->getXml()->getElementsByTagName('Status')->item(0)->nodeValue;
		}
		return $this->status;
	}

	/**
	 * Return number of home goals
	 *
	 * @return Integer
	 */
	public function getHomeGoals()
	{
		if(!isset($this->homeGoals) || $this->homeGoals === null)
		{
			$this->homeGoals = $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
		}
		return $this->homeGoals;
	}

	/**
	 * Return number of home goals
	 *
	 * @return Integer
	 */
	public function getAwayGoals()
	{
		if(!isset($this->awayGoals) || $this->awayGoals === null)
		{
			$this->awayGoals = $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
		}
		return $this->awayGoals;
	}

	/**
	 * Return home statement
	 *
	 * @return String
	 */
	public function getHomeStatement()
	{
		if(!isset($this->homeStatement) || $this->homeStatement === null)
		{
			$this->homeStatement = $this->getXml()->getElementsByTagName('HomeStatement')->item(0)->nodeValue;
		}
		return $this->homeStatement;
	}

	/**
	 * Return away statement
	 *
	 * @return String
	 */
	public function getAwayStatement()
	{
		if(!isset($this->awayStatement) || $this->awayStatement === null)
		{
			$this->awayStatement = $this->getXml()->getElementsByTagName('AwayStatement')->item(0)->nodeValue;
		}
		return $this->awayStatement;
	}
}
class HTReferee extends HTXml
{
	private $id = null;
	private $name = null;
	private $countryId = null;
	private $countryName = null;
	private $teamId = null;
	private $teamName = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return referee id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('RefereeId')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return referee name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('RefereeName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return referee country id
	 *
	 * @return Integer
	 */
	public function getCountryId()
	{
		if(!isset($this->countryId) || $this->countryId === null)
		{
			$this->countryId = $this->getXml()->getElementsByTagName('RefereeCountryId')->item(0)->nodeValue;
		}
		return $this->countryId;
	}

	/**
	 * Return referee country name
	 *
	 * @return String
	 */
	public function getCountryName()
	{
		if(!isset($this->countryName) || $this->countryName === null)
		{
			$this->countryName = $this->getXml()->getElementsByTagName('RefereeCountryName')->item(0)->nodeValue;
		}
		return $this->countryName;
	}

	/**
	 * Return referee team id
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$this->teamId = $this->getXml()->getElementsByTagName('RefereeTeamId')->item(0)->nodeValue;
		}
		return $this->teamId;
	}

	/**
	 * Return referee team name
	 *
	 * @return String
	 */
	public function getTeamName()
	{
		if(!isset($this->teamName) || $this->teamName === null)
		{
			$this->teamName = $this->getXml()->getElementsByTagName('RefereeTeamname')->item(0)->nodeValue;
		}
		return $this->teamName;
	}
}
class HTMatchInjury extends HTXml
{
	private $playerId = null;
	private $playerName = null;
	private $teamId = null;
	private $type = null;
	private $minute = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('InjuryPlayerID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Return player name
	 *
	 * @return String
	 */
	public function getPlayerName()
	{
		if(!isset($this->playerName) || $this->playerName === null)
		{
			$this->playerName = $this->getXml()->getElementsByTagName('InjuryPlayerName')->item(0)->nodeValue;
		}
		return $this->playerName;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$this->teamId = $this->getXml()->getElementsByTagName('InjuryTeamID')->item(0)->nodeValue;
		}
		return $this->teamId;
	}

	/**
	 * Return injury type
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('InjuryType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return injury minute
	 *
	 * @return Integer
	 */
	public function getMinute()
	{
		if(!isset($this->minute) || $this->minute === null)
		{
			$this->minute = $this->getXml()->getElementsByTagName('InjuryMinute')->item(0)->nodeValue;
		}
		return $this->minute;
	}
}
class HTMatchTeam extends HTXml
{
	private $type;
	private $id = null;
	private $name = null;
	private $dressCode = null;
	private $goals = null;
	private $tacticType = null;
	private $tacticSkill = null;
	private $midfield = null;
	private $rightDef = null;
	private $midDef = null;
	private $leftDef = null;
	private $rightAtt = null;
	private $midAtt = null;
	private $leftAtt = null;
	private $setPiecesDef = null;
	private $setPiecesAtt = null;
	private $attitude = null;
	private $formation = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml, $type)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
		$this->type = $type;
	}

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName($this->type.'TeamID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName($this->type.'TeamName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return dress URI
	 *
	 * @return String
	 */
	public function getDressURI()
	{
		if(!isset($this->dressCode) || $this->dressCode === null)
		{
			$this->dressCode = false;
			$uri = $this->getXml()->getElementsByTagName('DressURI');
			if($uri->length)
			{
				$this->dressCode = $uri->item(0)->nodeValue;
			}
		}
		return $this->dressCode;
	}


	/**
	 * Return goals number
	 *
	 * @return Integer
	 */
	public function getGoals()
	{
		if(!isset($this->goals) || $this->goals === null)
		{
			$this->goals = $this->getXml()->getElementsByTagName($this->type.'Goals')->item(0)->nodeValue;
		}
		return $this->goals;
	}

	/**
	 * Return tactic type
	 *
	 * @return Integer
	 */
	public function getTacticType()
	{
		if(!isset($this->tacticType) || $this->tacticType === null)
		{
			$this->tacticType = $this->getXml()->getElementsByTagName('TacticType')->item(0)->nodeValue;
		}
		return $this->tacticType;
	}

	/**
	 * Return tactic skill
	 *
	 * @return Integer
	 */
	public function getTacticSkill()
	{
		if(!isset($this->tacticSkill) || $this->tacticSkill === null)
		{
			$this->tacticSkill = $this->getXml()->getElementsByTagName('TacticSkill')->item(0)->nodeValue;
		}
		return $this->tacticSkill;
	}

	/**
	 * Return miedfield rating
	 *
	 * @return Integer
	 */
	public function getMidfieldRating()
	{
		if(!isset($this->midfield) || $this->midfield === null)
		{
			$this->midfield = $this->getXml()->getElementsByTagName('RatingMidfield')->item(0)->nodeValue;
		}
		return $this->midfield;
	}

	/**
	 * Return right defense rating
	 *
	 * @return Integer
	 */
	public function getRightDefenseRating()
	{
		if(!isset($this->rightDef) || $this->rightDef === null)
		{
			$this->rightDef = $this->getXml()->getElementsByTagName('RatingRightDef')->item(0)->nodeValue;
		}
		return $this->rightDef;
	}

	/**
	 * Return central defense rating
	 *
	 * @return Integer
	 */
	public function getCentralDefenseRating()
	{
		if(!isset($this->midDef) || $this->midDef === null)
		{
			$this->midDef = $this->getXml()->getElementsByTagName('RatingMidDef')->item(0)->nodeValue;
		}
		return $this->midDef;
	}

	/**
	 * Return left defense rating
	 *
	 * @return Integer
	 */
	public function getLeftDefenseRating()
	{
		if(!isset($this->leftDef) || $this->leftDef === null)
		{
			$this->leftDef = $this->getXml()->getElementsByTagName('RatingLeftDef')->item(0)->nodeValue;
		}
		return $this->leftDef;
	}

	/**
	 * Return right attack rating
	 *
	 * @return Integer
	 */
	public function getRightAttackRating()
	{
		if(!isset($this->rightAtt) || $this->rightAtt === null)
		{
			$this->rightAtt = $this->getXml()->getElementsByTagName('RatingRightAtt')->item(0)->nodeValue;
		}
		return $this->rightAtt;
	}

	/**
	 * Return central attack rating
	 *
	 * @return Integer
	 */
	public function getCentralAttackRating()
	{
		if(!isset($this->midAtt) || $this->midAtt === null)
		{
			$this->midAtt = $this->getXml()->getElementsByTagName('RatingMidAtt')->item(0)->nodeValue;
		}
		return $this->midAtt;
	}

	/**
	 * Return left attack rating
	 *
	 * @return Integer
	 */
	public function getLeftAttackRating()
	{
		if(!isset($this->leftAtt) || $this->leftAtt === null)
		{
			$this->leftAtt = $this->getXml()->getElementsByTagName('RatingLeftAtt')->item(0)->nodeValue;
		}
		return $this->leftAtt;
	}

	/**
	 * Return defensive indirect set pieces rating
	 *
	 * @return Integer
	 */
	public function getIndirectSetPiecesDefenseRating()
	{
		if(!isset($this->setPiecesDef) || $this->setPiecesDef === null)
		{
			$this->setPiecesDef = $this->getXml()->getElementsByTagName('RatingIndirectSetPiecesDef')->item(0)->nodeValue;
		}
		return $this->setPiecesDef;
	}

	/**
	 * Return attacking indirect set pieces rating
	 *
	 * @return Integer
	 */
	public function getIndirectSetPiecesAttackRating()
	{
		if(!isset($this->setPiecesAtt) || $this->setPiecesAtt === null)
		{
			$this->setPiecesAtt = $this->getXml()->getElementsByTagName('RatingIndirectSetPiecesAtt')->item(0)->nodeValue;
		}
		return $this->setPiecesAtt;
	}

	/**
	 * Return HatStats note
	 *
	 * @return Integer
	 */
	public function getHatStats()
	{
		return ($this->getMidfieldRating()*3)+
						$this->getRightDefenseRating()+
						$this->getLeftDefenseRating()+
						$this->getCentralDefenseRating()+
						$this->getRightAttackRating()+
						$this->getLeftAttackRating()+
						$this->getCentralAttackRating();
	}

	/**
	 * Return match attitude if given
	 *
	 * @return Integer
	 */
	public function getAttitude()
	{
		if(!isset($this->attitude) || $this->attitude === null)
		{
			$node = $this->getXml()->getElementsByTagName('TeamAttitude');
			if($node !== null && $node->length)
			{
				$this->attitude = $node->item(0)->nodeValue;
			}
		}
		return $this->attitude;
	}

	/**
	 * Return team formation
	 *
	 * @return String
	 */
	public function getFormation()
	{
		if(!isset($this->formation) || $this->formation === null)
		{
			$this->formation = $this->getXml()->getElementsByTagName('Formation')->item(0)->nodeValue;
		}
		return $this->formation;
	}
}
class HTMatchArena extends HTXml
{
	private $id = null;
	private $name = null;
	private $weatherId = null;
	private $spectators = null;
	private $total = null;
	private $terraces = null;
	private $basic = null;
	private $roof = null;
	private $vip = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return arena id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return arena name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return weather id
	 *
	 * @return Integer
	 */
	public function getWeatherId()
	{
		if(!isset($this->weatherId) || $this->weatherId === null)
		{
			$this->weatherId = $this->getXml()->getElementsByTagName('WeatherID')->item(0)->nodeValue;
		}
		return $this->weatherId;
	}

	/**
	 * Return spectators number
	 *
	 * @return Integer
	 */
	public function getSpectators()
	{
		if(!isset($this->spectators) || $this->spectators === null)
		{
			$this->spectators = $this->getXml()->getElementsByTagName('SoldTotal')->item(0)->nodeValue;
		}
		return $this->spectators;
	}

	/**
	 * Return number of sold terraces
	 *
	 * @return Integer
	 */
	public function getSoldTerraces()
	{
		if(!isset($this->terraces) || $this->terraces === null)
		{
			$node = $this->getXml()->getElementsByTagName('SoldTerraces');
			if($node->length)
			{
				$this->terraces = $node->item(0)->nodeValue;
			}
		}
		return $this->terraces;
	}

	/**
	 * Return number of sold terraces
	 *
	 * @return Integer
	 */
	public function getSoldBasic()
	{
		if(!isset($this->basic) || $this->basic === null)
		{
			$node = $this->getXml()->getElementsByTagName('SoldBasic');
			if($node->length)
			{
				$this->basic = $node->item(0)->nodeValue;
			}
		}
		return $this->basic;
	}

	/**
	 * Return number of sold roof
	 *
	 * @return Integer
	 */
	public function getSoldRoof()
	{
		if(!isset($this->roof) || $this->roof === null)
		{
			$node = $this->getXml()->getElementsByTagName('SoldRoof');
			if($node->length)
			{
				$this->roof = $node->item(0)->nodeValue;
			}
		}
		return $this->roof;
	}

	/**
	 * Return number of sold VIP
	 *
	 * @return Integer
	 */
	public function getSoldVip()
	{
		if(!isset($this->vip) || $this->vip === null)
		{
			$node = $this->getXml()->getElementsByTagName('SoldVIP');
			if($node->length)
			{
				$this->vip = $node->item(0)->nodeValue;
			}
		}
		return $this->vip;
	}
}
class HTMatchGoal extends HTXml
{
	private $scorerId = null;
	private $scorerName = null;
	private $scorerTeamId = null;
	private $scoreHomeTeam = null;
	private $scoreAwayTeam = null;
	private $minute = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return scorer player id
	 *
	 * @return Integer
	 */
	public function getScorerId()
	{
		if(!isset($this->scorerId) || $this->scorerId === null)
		{
			$this->scorerId = $this->getXml()->getElementsByTagName('ScorerPlayerID')->item(0)->nodeValue;
		}
		return $this->scorerId;
	}

	/**
	 * Return scorer player name
	 *
	 * @return String
	 */
	public function getScorerName()
	{
		if(!isset($this->scorerName) || $this->scorerName === null)
		{
			$this->scorerName = $this->getXml()->getElementsByTagName('ScorerPlayerName')->item(0)->nodeValue;
		}
		return $this->scorerName;
	}

	/**
	 * Return scorer team id
	 *
	 * @return Integer
	 */
	public function getScorerTeamId()
	{
		if(!isset($this->scorerTeamId) || $this->scorerTeamId === null)
		{
			$this->scorerTeamId = $this->getXml()->getElementsByTagName('ScorerTeamID')->item(0)->nodeValue;
		}
		return $this->scorerTeamId;
	}

	/**
	 * Return home team score
	 *
	 * @return Integer
	 */
	public function getHomeTeamScore()
	{
		if(!isset($this->scoreHomeTeam) || $this->scoreHomeTeam === null)
		{
			$this->scoreHomeTeam = $this->getXml()->getElementsByTagName('ScorerHomeGoals')->item(0)->nodeValue;
		}
		return $this->scoreHomeTeam;
	}

	/**
	 * Return away team score
	 *
	 * @return Integer
	 */
	public function getAwayTeamScore()
	{
		if(!isset($this->scoreAwayTeam) || $this->scoreAwayTeam === null)
		{
			$this->scoreAwayTeam = $this->getXml()->getElementsByTagName('ScorerAwayGoals')->item(0)->nodeValue;
		}
		return $this->scoreAwayTeam;
	}

	/**
	 * Return goal minute
	 *
	 * @return Integer
	 */
	public function getMinute()
	{
		if(!isset($this->minute) || $this->minute === null)
		{
			$this->minute = $this->getXml()->getElementsByTagName('ScorerMinute')->item(0)->nodeValue;
		}
		return $this->minute;
	}
}
class HTMatchCard extends HTXml
{
	private $playerId = null;
	private $playerName = null;
	private $teamId = null;
	private $type = null;
	private $minute = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return card's player id
	 *
	 * @return Integer
	 */
	public function getPlayerid()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('BookingPlayerID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Return card's player name
	 *
	 * @return String
	 */
	public function getPlayerName()
	{
		if(!isset($this->playerName) || $this->playerName === null)
		{
			$this->playerName = $this->getXml()->getElementsByTagName('BookingPlayerName')->item(0)->nodeValue;
		}
		return $this->playerName;
	}

	/**
	 * Return card's team id
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$this->teamId = $this->getXml()->getElementsByTagName('BookingTeamID')->item(0)->nodeValue;
		}
		return $this->teamId;
	}

	/**
	 * Return card type
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('BookingType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return card minute
	 *
	 * @return Integer
	 */
	public function getMinute()
	{
		if(!isset($this->minute) || $this->minute === null)
		{
			$this->minute = $this->getXml()->getElementsByTagName('BookingMinute')->item(0)->nodeValue;
		}
		return $this->minute;
	}
}
class HTMatchArchive extends HTCommonTeam
{
	private $matchNumber = null;
	private $matches = null;

	/**
	 * Return number of match in list
	 *
	 * @return Integer
	 */
	public function getMatchNumber()
	{
		if(!isset($this->matchNumber) || $this->matchNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Match');
			$this->matchNumber = $nodeList->length;
		}
		return $this->matchNumber;
	}

	/**
	 * Return HTMatchArchiveMatch object
	 *
	 * @param Integer $index
	 * @return HTMatchArchiveMatch
	 */
	public function getMatch($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getMatchNumber())
		{
			--$index;
			if(!isset($this->matches[$index]) || $this->matches[$index] === null)
			{
				$nodeList = $this->getXml()->getElementsByTagName('Match');
				$xmlMatch = new DOMDocument('1.0', 'UTF-8');
				$xmlMatch->appendChild($xmlMatch->importNode($nodeList->item($index), true));
				$this->matches[$index] = new HTMatchArchiveMatch($xmlMatch);
			}
			return $this->matches[$index];
		}
		return null;
	}
}
class HTMatchArchiveMatch extends HTXml
{
	private $id = null;
	private $homeTeamId = null;
	private $homeTeamName = null;
	private $awayTeamId = null;
	private $awayTeamName = null;
	private $date = null;
	private $type = null;
	private $context = null;
	private $homeGoals = null;
	private $awayGoals = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return home team id
	 *
	 * @return Integer
	 */
	public function getHomeTeamId()
	{
		if(!isset($this->homeTeamId) || $this->homeTeamId === null)
		{
			$this->homeTeamId = $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
		}
		return $this->homeTeamId;
	}

	/**
	 * Return home team name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if(!isset($this->homeTeamName) || $this->homeTeamName === null)
		{
			$this->homeTeamName = $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
		}
		return $this->homeTeamName;
	}

	/**
	 * Return away team id
	 *
	 * @return Integer
	 */
	public function getAwayTeamId()
	{
		if(!isset($this->awayTeamId) || $this->awayTeamId === null)
		{
			$this->awayTeamId = $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
		}
		return $this->awayTeamId;
	}

	/**
	 * Return away team name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if(!isset($this->awayTeamName) || $this->awayTeamName === null)
		{
			$this->awayTeamName = $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
		}
		return $this->awayTeamName;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return match type
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return match context id
	 *
	 * @return Integer
	 */
	public function getContextId()
	{
		if(!isset($this->context) || $this->context === null)
		{
			$this->context = $this->getXml()->getElementsByTagName('MatchContextId')->item(0)->nodeValue;
		}
		return $this->context;
	}

	/**
	 * Return home goals
	 *
	 * @return Integer
	 */
	public function getHomeGoals()
	{
		if(!isset($this->homeGoals) || $this->homeGoals === null)
		{
			$this->homeGoals = $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
		}
		return $this->homeGoals;
	}

	/**
	 * Return away goals
	 *
	 * @return Integer
	 */
	public function getAwayGoals()
	{
		if(!isset($this->awayGoals) || $this->awayGoals === null)
		{
			$this->awayGoals = $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
		}
		return $this->awayGoals;
	}

	/**
	 * Return score
	 *
	 * @return String
	 */
	public function getScore()
	{
		return $this->getHomeGoals().'-'.$this->getAwayGoals();
	}
}
class HTLineup extends HTCommonTeam
{
	private $matchId = null;
	private $matchType = null;
	private $matchDate = null;
	private $homeTeamId = null;
	private $homeTeamName = null;
	private $awayTeamId = null;
	private $awayTeamName = null;
	private $arenaId = null;
	private $arenaName = null;
	private $teamExperienceLevel = null;
	private $startingLineup = null;
	private $finalLineup = null;
	private $substitutionsNumber = null;
	private $substitutions = null;

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getMatchId()
	{
		if(!isset($this->matchId) || $this->matchId === null)
		{
			$this->matchId = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->matchId;
	}

	/**
	 * Return home team id
	 *
	 * @return Integer
	 */
	public function getHomeTeamId()
	{
		if(!isset($this->homeTeamId) || $this->homeTeamId === null)
		{
			$this->homeTeamId = $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
		}
		return $this->homeTeamId;
	}

	/**
	 * Return home team name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if(!isset($this->homeTeamName) || $this->homeTeamName === null)
		{
			$this->homeTeamName = $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
		}
		return $this->homeTeamName;
	}

	/**
	 * Return away team id
	 *
	 * @return Integer
	 */
	public function getAwayTeamId()
	{
		if(!isset($this->awayTeamId) || $this->awayTeamId === null)
		{
			$this->awayTeamId = $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
		}
		return $this->awayTeamId;
	}

	/**
	 * Return away team name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if(!isset($this->awayTeamName) || $this->awayTeamName === null)
		{
			$this->awayTeamName = $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
		}
		return $this->awayTeamName;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getMatchDate($format = null)
	{
		if(!isset($this->matchDate[$format]) || $this->matchDate[$format] === null)
		{
			$this->matchDate[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->matchDate[$format] = HTFunction::convertDate($this->matchDate[$format], $format);
			}
		}
		return $this->matchDate[$format];
	}

	/**
	 * Return match type
	 *
	 * @return Integer
	 */
	public function getMatchType()
	{
		if(!isset($this->matchType) || $this->matchType === null)
		{
			$this->matchType = $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
		}
		return $this->matchType;
	}

	/**
	 * Return arena id
	 *
	 * @return Integer
	 */
	public function getArenaId()
	{
		if(!isset($this->arenaId) || $this->arenaId === null)
		{
			$this->arenaId = $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
		}
		return $this->arenaId;
	}

	/**
	 * Return arena name
	 *
	 * @return String
	 */
	public function getArenaName()
	{
		if(!isset($this->arenaName) || $this->arenaName === null)
		{
			$this->arenaName = $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
		}
		return $this->arenaName;
	}

	/**
	 * Return team experience level
	 *
	 * @return Integer
	 */
	public function getTeamExperience()
	{
		if(!isset($this->teamExperienceLevel) || $this->teamExperienceLevel === null)
		{
			$this->teamExperienceLevel = $this->getXml()->getElementsByTagName('ExperienceLevel')->item(0)->nodeValue;
		}
		return $this->teamExperienceLevel;
	}

	/**
	 * Returns HTStartingLineup object
	 *
	 * @return HTStartingLineup
	 */
	public function getStartingLineup()
	{
		if(!isset($this->startingLineup) || $this->startingLineup === null)
		{
			$nodeList = $this->getXml()->getElementsByTagName('StartingLineup');
			$lineup = new DOMDocument('1.0', 'UTF-8');
			$lineup->appendChild($lineup->importNode($nodeList->item(0), true));
			$this->startingLineup = new HTStartingLineup($lineup);
		}
		return $this->startingLineup;
	}

	/**
	 * Returns HTFinalLineup object
	 *
	 * @return HTFinalLineup
	 */
	public function getFinalLineup()
	{
		if(!isset($this->finalLineup) || $this->finalLineup === null)
		{
			$nodeList = $this->getXml()->getElementsByTagName('Lineup');
			$lineup = new DOMDocument('1.0', 'UTF-8');
			$lineup->appendChild($lineup->importNode($nodeList->item(0), true));
			$this->finalLineup = new HTFinalLineup($lineup);
		}
		return $this->finalLineup;
	}

	/**
	 * Return number of substitutions
	 *
	 * @return Integer
	 */
	public function getSubstitutionNumber()
	{
		if(!isset($this->substitutionsNumber) || $this->substitutionsNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Substitution');
			$this->substitutionsNumber = $nodeList->length;
		}
		return $this->substitutionsNumber;
	}

	/**
	 * Return HTSubstitutionLineup object
	 *
	 * @param Integer $index
	 * @return HTSubstitutionLineup
	 */
	public function getSubstitution($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getSubstitutionNumber())
		{
			--$index;
			if(!isset($this->substitutions[$index]) || $this->substitutions[$index] === null)
			{
				$nodeList = $this->getXml()->getElementsByTagName('Substitution');
				$sub = new DOMDocument('1.0', 'UTF-8');
				$sub->appendChild($sub->importNode($nodeList->item($index), true));
				$this->substitutions[$index] = new HTSubstitutionLineup($sub);
			}
			return $this->substitutions[$index];
		}
		return null;
	}
}
class HTSubstitutionLineup extends HTXml
{
	private $teamId = null;
	private $subject = null;
	private $object = null;
	private $ordertype = null;
	private $newposition = null;
	private $newbehaviour = null;
	private $minute = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$this->teamId = $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
		}
		return $this->teamId;
	}

	/**
	 * Return player id, leaving player or player changing behaviour or player swap
	 *
	 * @return Integer
	 */
	public function getPlayerOutId()
	{
		if(!isset($this->subject) || $this->subject === null)
		{
			$this->subject = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subject;
	}

	/**
	 * Return player id, entering player or player changing behaviour or player swap
	 *
	 * @return Integer
	 */
	public function getPlayerInId()
	{
		if(!isset($this->object) || $this->object === null)
		{
			$this->object = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->object;
	}

	/**
	 * Return order type
	 *
	 * @return Integer
	 */
	public function getOrderType()
	{
		if(!isset($this->ordertype) || $this->ordertype === null)
		{
			$this->ordertype = $this->getXml()->getElementsByTagName('OrderType')->item(0)->nodeValue;
		}
		return $this->ordertype;
	}

	/**
	 * Return new position id
	 *
	 * @return Integer
	 */
	public function getNewPositionId()
	{
		if(!isset($this->newposition) || $this->newposition === null)
		{
			$this->newposition = $this->getXml()->getElementsByTagName('NewPositionId')->item(0)->nodeValue;
		}
		return $this->newposition;
	}

	/**
	 * Return new position behaviour
	 *
	 * @return Integer
	 */
	public function getNewPositionBehaviour()
	{
		if(!isset($this->newbehaviour) || $this->newbehaviour === null)
		{
			$this->newbehaviour = $this->getXml()->getElementsByTagName('NewPositionBehaviour')->item(0)->nodeValue;
		}
		return $this->newbehaviour;
	}

	/**
	 * Return minute of substitution
	 *
	 * @return Integer
	 */
	public function getMinute()
	{
		if(!isset($this->minute) || $this->minute === null)
		{
			$this->minute = $this->getXml()->getElementsByTagName('MatchMinute')->item(0)->nodeValue;
		}
		return $this->minute;
	}
}
class HTStartingLineup extends HTXml
{
	private $playersNumber = null;
	private $players = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return number of players on lineup
	 *
	 * @return Integer
	 */
	public function getPlayersNumber()
	{
		if(!isset($this->playersNumber) || $this->playersNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Player');
			$this->playersNumber = $nodeList->length;
		}
		return $this->playersNumber;
	}

	/**
	 * Return HTLineupPlayer object
	 *
	 * @param Integer $index
	 * @return HTLineupPlayer
	 */
	public function getPlayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getPlayersNumber())
		{
			--$index;
			if(!isset($this->players[$index]) || $this->players[$index] === null)
			{
				$nodeList = $this->getXml()->getElementsByTagName('Player');
				$player = new DOMDocument('1.0', 'UTF-8');
				$player->appendChild($player->importNode($nodeList->item($index), true));
				$this->players[$index] = new HTLineupPlayer($player);
			}
			return $this->players[$index];
		}
		return null;
	}
}
class HTFinalLineup extends HTXml
{
	private $playersNumber = null;
	private $players = null;
	private $totalStars = null;
	private $totalStarsEndOfMatch = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return number of players on lineup
	 *
	 * @return Integer
	 */
	public function getPlayersNumber()
	{
		if(!isset($this->playersNumber) || $this->playersNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Player');
			$this->playersNumber = $nodeList->length;
		}
		return $this->playersNumber;
	}

	/**
	 * Return HTLineupPlayer object
	 *
	 * @param Integer $index
	 * @return HTLineupPlayer
	 */
	public function getPlayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getPlayersNumber())
		{
			--$index;
			if(!isset($this->players[$index]) || $this->players[$index] === null)
			{
				$nodeList = $this->getXml()->getElementsByTagName('Player');
				$player = new DOMDocument('1.0', 'UTF-8');
				$player->appendChild($player->importNode($nodeList->item($index), true));
				$this->players[$index] = new HTLineupPlayer($player);
			}
			return $this->players[$index];
		}
		return null;
	}

	/**
	 * Return team total stars
	 *
	 * @param $includeSubstitutes Boolean
	 * @return Float
	 */
	public function getTotalStars($includeSubstitutes = true)
	{
		if(!isset($this->totalStars[$includeSubstitutes]) || $this->totalStars[$includeSubstitutes] === null)
		{
			$total = 0;
			for($i=1; $i<=$this->getPlayersNumber(); $i++)
			{
				$player = $this->getPlayer($i);
				if($includeSubstitutes === true || ($includeSubstitutes === false && $player->getRole()>=100 && $player->getRole()<=113))
				{
					$total += $player->getRatingStars();
				}
			}
			$this->totalStars[$includeSubstitutes] = $total;
		}
		return $this->totalStars[$includeSubstitutes];
	}

	/**
	 * Return team total stars at end of match
	 *
	 * @param $includeSubstitutes Boolean
	 * @return Float
	 */
	public function getTotalStarsAtEndOfMatch($includeSubstitutes = true)
	{
		if(!isset($this->totalStarsEndOfMatch[$includeSubstitutes]) || $this->totalStarsEndOfMatch[$includeSubstitutes] === null)
		{
			$total = 0;
			for($i=1; $i<=$this->getPlayersNumber(); $i++)
			{
				$player = $this->getPlayer($i);
				if($includeSubstitutes === true || ($includeSubstitutes === false && $player->getRole()>=100 && $player->getRole()<=113))
				{
					$total += $player->getRatingStarsAtEndOfMatch();
				}
			}
			$this->totalStarsEndOfMatch[$includeSubstitutes] = $total;
		}
		return $this->totalStarsEndOfMatch[$includeSubstitutes];
	}
}
class HTLineupPlayer extends HTXml
{
	private $id = null;
	private $roleId = null;
	private $firstName = null;
	private $lastName = null;
	private $nickName = null;
	private $ratingStars = null;
	private $ratingStarsEnd = null;
	private $individualOrder = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return player role id
	 *
	 * @return Integer
	 */
	public function getRole()
	{
		if(!isset($this->roleId) || $this->roleId === null)
		{
			$this->roleId = $this->getXml()->getElementsByTagName('RoleID')->item(0)->nodeValue;
		}
		return $this->roleId;
	}

	/**
	 * Return player name
	 *
	 * @return String
	 */
	public function getName()
	{
		$name = $this->getFirstName();
		if($this->getNickName() !== null && $this->getNickName() !== '')
		{
			$name .= ' '.$this->getNickName();
		}
		$name .= ' '.$this->getLastName();
		return $name;
	}

	/**
	 * Return player first name
	 *
	 * @return String
	 */
	public function getFirstName()
	{
		if(!isset($this->firstName) || $this->firstName === null)
		{
			$this->firstName = $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
		}
		return $this->firstName;
	}

	/**
	 * Return player last name
	 *
	 * @return String
	 */
	public function getLastName()
	{
		if(!isset($this->lastName) || $this->lastName === null)
		{
			$this->lastName = $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
		}
		return $this->lastName;
	}

	/**
	 * Return player nick name
	 *
	 * @return String
	 */
	public function getNickName()
	{
		if(!isset($this->nickName) || $this->nickName === null)
		{
			$this->nickName = $this->getXml()->getElementsByTagName('NickName')->item(0)->nodeValue;
		}
		return $this->nickName;
	}

	/**
	 * Return player rating stars
	 *
	 * @return Float
	 */
	public function getRatingStars()
	{
		if(!isset($this->ratingStars) || $this->ratingStars === null)
		{
			$tmp = $this->getXml()->getElementsByTagName('RatingStars');
			if($tmp->length)
			{
				$this->ratingStars = $tmp->item(0)->nodeValue;
			}
		}
		return $this->ratingStars;
	}

	/**
	 * Return player rating stars at end of game
	 *
	 * @return Float
	 */
	public function getRatingStarsAtEndOfMatch()
	{
		if(!isset($this->ratingStarsEnd) || $this->ratingStarsEnd === null)
		{
			$tmp = $this->getXml()->getElementsByTagName('RatingStarsEndOfMatch');
			if($tmp->length)
			{
				$this->ratingStarsEnd = $tmp->item(0)->nodeValue;
			}
		}
		return $this->ratingStarsEnd;
	}

	/**
	 * Return player individual order code
	 *
	 * @return Integer
	 */
	public function getIndividualOrder()
	{
		if(!isset($this->individualOrder) || $this->individualOrder === null)
		{
			$tmp = $this->getXml()->getElementsByTagName('Behaviour');
			if($tmp->length)
			{
				$this->individualOrder = $tmp->item(0)->nodeValue;
			}
		}
		return $this->individualOrder;
	}
}
class HTLive extends HTGlobal
{
	private $matchNumber = null;
	private $matches = null;

	/**
	 * Return number of match in list
	 *
	 * @return Integer
	 */
	public function getMatchNumber()
	{
		if(!isset($this->matchNumber) || $this->matchNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Match');
			$this->matchNumber = $nodeList->length;
		}
		return $this->matchNumber;
	}

	/**
	 * Return HTLiveMatch object
	 *
	 * @param Integer $number
	 * @return HTLiveMatch
	 */
	public function getMatch($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getMatchNumber())
		{
			--$number;
			if(!isset($this->matches[$number]) || $this->matches[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Match');
				$match = new DOMDocument('1.0', 'UTF-8');
				$match->appendChild($match->importNode($nodeList->item($number), true));
				$this->matches[$number] = new HTLiveMatch($match);
			}
			return $this->matches[$number];
		}
		return null;
	}
}
class HTLiveMatch extends HTXml
{
	private $sourceSystem = null;
	private $id = null;
	private $date	= null;
	private $homeTeamId = null;
	private $homeTeamName = null;
	private $awayTeamId = null;
	private $awayTeamName = null;
	private $homeGoals = null;
	private $awayGoals = null;
	private $eventNumber = null;
	private $events = null;
	private $eventsIndex = null;
	private $substitutionsNumber = null;
	private $substitutions = null;
	private $homeStartingLineup = null;
	private $awayStartingLineup = null;
	private $lastIndex = null;
	private $nextMinute = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match source system
	 *
	 * @return String
	 */
	public function getSourceSystem()
	{
		if(!isset($this->sourceSystem) || $this->sourceSystem === null)
		{
			$this->sourceSystem = $this->getXml()->getElementsByTagName('SourceSystem')->item(0)->nodeValue;
		}
		return $this->sourceSystem;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return home team id
	 *
	 * @return Integer
	 */
	public function getHomeTeamId()
	{
		if(!isset($this->homeTeamId) || $this->homeTeamId === null)
		{
			$this->homeTeamId = $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
		}
		return $this->homeTeamId;
	}

	/**
	 * Return home team name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if(!isset($this->homeTeamName) || $this->homeTeamName === null)
		{
			$this->homeTeamName = $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
		}
		return $this->homeTeamName;
	}

	/**
	 * Return away team id
	 *
	 * @return Integer
	 */
	public function getAwayTeamId()
	{
		if(!isset($this->awayTeamId) || $this->awayTeamId === null)
		{
			$this->awayTeamId = $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
		}
		return $this->awayTeamId;
	}

	/**
	 * Return away team name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if(!isset($this->awayTeamName) || $this->awayTeamName === null)
		{
			$this->awayTeamName = $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
		}
		return $this->awayTeamName;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return home goals
	 *
	 * @return Integer
	 */
	public function getHomeGoals()
	{
		if(!isset($this->homeGoals) || $this->homeGoals === null)
		{
			$this->homeGoals = $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
		}
		return $this->homeGoals;
	}

	/**
	 * Return away goals
	 *
	 * @return Integer
	 */
	public function getAwayGoals()
	{
		if(!isset($this->awayGoals) || $this->awayGoals === null)
		{
			$this->awayGoals = $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
		}
		return $this->awayGoals;
	}

	/**
	 * Return number event
	 *
	 * @return Integer
	 */
	public function getEventNumber()
	{
		if(!isset($this->eventNumber) || $this->eventNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Event');
			$this->eventNumber = $nodeList->length;
		}
		return $this->eventNumber;
	}

	/**
	 * Return HTLiveMatchEvent object
	 *
	 * @param Integer $number
	 * @return HTLiveMatchEvent
	 */
	public function getEvent($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getEventNumber())
		{
			--$number;
			if(!isset($this->events[$number]) || $this->events[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Event');
				$event = new DOMDocument('1.0', 'UTF-8');
				$event->appendChild($event->importNode($nodeList->item($number), true));
				$this->events[$number] = new HTLiveMatchEvent($event);
			}
			return $this->events[$number];
		}
		return null;
	}

	/**
	 * Return HTLiveMatchEvent object
	 *
	 * @param Integer $index
	 * @return HTLiveMatchEvent
	 */
	public function getEventByOrder($number)
	{
		$number = round($number);
		if($number > 0 && $number <= $this->getEventNumber())
		{
			if(!isset($this->eventsIndex[$number]) || $this->eventsIndex[$number] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Event[@Index='.$number.']');
				$event = new DOMDocument('1.0', 'UTF-8');
				$event->appendChild($event->importNode($nodeList->item(0), true));
				$this->eventsIndex[$number] = new HTLiveMatchEvent($event);
			}
			return $this->eventsIndex[$number];
		}
		return null;
	}

	/**
	 * Return number of substitutions
	 *
	 * @return Integer
	 */
	public function getSubstitutionNumber()
	{
		if(!isset($this->substitutionsNumber) || $this->substitutionsNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Substitution');
			$this->substitutionsNumber = $nodeList->length;
		}
		return $this->substitutionsNumber;
	}

	/**
	 * Return HTSubstitutionLineup object
	 *
	 * @param Integer $index
	 * @return HTSubstitutionLineup
	 */
	public function getSubstitution($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getSubstitutionNumber())
		{
			--$index;
			if(!isset($this->substitutions[$index]) || $this->substitutions[$index] === null)
			{
				$nodeList = $this->getXml()->getElementsByTagName('Substitution');
				$sub = new DOMDocument('1.0', 'UTF-8');
				$sub->appendChild($sub->importNode($nodeList->item($index), true));
				$this->substitutions[$index] = new HTSubstitutionLineup($sub);
			}
			return $this->substitutions[$index];
		}
		return null;
	}

	/**
	 * Returns HTStartingLineup object
	 *
	 * @return HTStartingLineup
	 */
	public function getHomeStartingLineup()
	{
		if(!isset($this->homeStartingLineup) || $this->homeStartingLineup === null)
		{
			$nodeList = $this->getXml()->getElementsByTagName('StartingLineup');
			if($nodeList->length)
			{
				$lineup = new DOMDocument('1.0', 'UTF-8');
				$lineup->appendChild($lineup->importNode($nodeList->item(0), true));
				$this->homeStartingLineup = new HTStartingLineup($lineup);
			}
		}
		return $this->homeStartingLineup;
	}

	/**
	 * Returns HTStartingLineup object
	 *
	 * @return HTStartingLineup
	 */
	public function getAwayStartingLineup()
	{
		if(!isset($this->awayStartingLineup) || $this->awayStartingLineup === null)
		{
			$nodeList = $this->getXml()->getElementsByTagName('StartingLineup');
			if($nodeList->length)
			{
				$lineup = new DOMDocument('1.0', 'UTF-8');
				$lineup->appendChild($lineup->importNode($nodeList->item(1), true));
				$this->awayStartingLineup = new HTStartingLineup($lineup);
			}
		}
		return $this->awayStartingLineup;
	}

	/**
	 * Return last match event index shown
	 *
	 * @return Integer
	 */
	public function getLastEventIndexShown()
	{
		if(!isset($this->lastIndex) || $this->lastIndex === null)
		{
			$node = $this->getXml()->getElementsByTagName('LastShownEventIndex');
			if($node->length)
			{
				$this->lastIndex = $node->item(0)->nodeValue;
			}
		}
		return $this->lastIndex;
	}

	/**
	 * Return the minute for next event
	 *
	 * @return Integer
	 */
	public function getNextMinuteEvent()
	{
		if(!isset($this->nextMinute) || $this->nextMinute === null)
		{
			$node = $this->getXml()->getElementsByTagName('NextEventMinute');
			if($node->length)
			{
				$this->nextMinute = $node->item(0)->nodeValue;
			}
		}
		return $this->nextMinute;
	}
}
class HTLiveMatchEvent extends HTXml
{
	private $minute = null;
	private $subjectPlayerId = null;
	private $subjectTeamId = null;
	private $objectPlayerId = null;
	private $key = null;
	private $keyId = null;
	private $variation = null;
	private $text = null;
	private $moreInfos = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return event minute
	 *
	 * @return Integer
	 */
	public function getMinute()
	{
		if(!isset($this->minute) || $this->minute === null)
		{
			$this->minute = $this->getXml()->getElementsByTagName('Minute')->item(0)->nodeValue;
		}
		return $this->minute;
	}

	/**
	 * Return event subject player id
	 *
	 * @return Integer
	 */
	public function getSubjectPlayerId()
	{
		if(!isset($this->subjectPlayerId) || $this->subjectPlayerId === null)
		{
			$this->subjectPlayerId = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerId;
	}

	/**
	 * Return event subject team id
	 *
	 * @return Integer
	 */
	public function getSubjectTeamId()
	{
		if(!isset($this->subjectTeamId) || $this->subjectTeamId === null)
		{
			$this->subjectTeamId = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamId;
	}

	/**
	 * Return event object player id
	 *
	 * @return Integer
	 */
	public function getObjectPlayerId()
	{
		if(!isset($this->objectPlayerId) || $this->objectPlayerId === null)
		{
			$this->objectPlayerId = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerId;
	}

	/**
	 * Return event key
	 *
	 * @return String
	 */
	public function getKey()
	{
		if(!isset($this->key) || $this->key === null)
		{
			$node = $this->getXml()->getElementsByTagName('EventKey');
			if($node->length)
			{
				$this->key = $node->item(0)->nodeValue;
			}
			else
			{
				$this->key = $this->getKeyId().'_'.$this->getKeyVariation();
			}
		}
		return $this->key;
	}

	/**
	 * Return event key id
	 *
	 * @return Integer
	 */
	public function getKeyId()
	{
		if(!isset($this->keyId) || $this->keyId === null)
		{
			$node = $this->getXml()->getElementsByTagName('EventTypeID');
			if($node->length)
			{
				$this->keyId = $node->item(0)->nodeValue;
			}
			else
			{
				$key = explode('_', $this->getKey());
				$this->keyId = $key[0];
			}
		}
		return $this->keyId;
	}

	/**
	 * Return event key variation
	 *
	 * @return Integer
	 */
	public function getKeyVariation()
	{
		if(!isset($this->variation) || $this->variation === null)
		{
			$node =  $this->getXml()->getElementsByTagName('EventVariation');
			if($node->length)
			{
				$this->variation = $node->item(0)->nodeValue;
			}
			else
			{
				$key = explode('_', $this->getKey());
				$this->variation = $key[1];
			}
		}
		return $this->variation;
	}

	/**
	 * Return event text
	 *
	 * @param String $playerUrlReplacement (given url is concat with : PlayerID=xxxxxxx )
	 * @return String
	 */
	public function getText($playerUrlReplacement = null)
	{
		if(!isset($this->text) || $this->text === null)
		{
			$this->text = $this->getXml()->getElementsByTagName('EventText')->item(0)->nodeValue;
			if($playerUrlReplacement !== null)
			{
				$this->text = HTFunction::updatePlayerUrl($this->text, $playerUrlReplacement);
			}
			else
			{
				$this->text = HTFunction::updatePlayerUrl($this->text, 'http://www.hattrick.org' . HTFunction::PLAYERURL);
			}
		}
		return $this->text;
	}

	/**
	 * Event has more infos ?
	 *
	 * @return Boolean
	 */
	public function hasMoreInfos()
	{
		if(!isset($this->moreInfos) || $this->moreInfos === null)
		{
			$this->moreInfos = class_exists($this->getEventClassName());
		}
		return $this->moreInfos;
	}

	/**
	 * @return String
	 */
	private function getEventClassName()
	{
		return 'HTEvent'.$this->getMainKey();
	}

	/**
	 * @return Integer
	 */
	public function getMainKey()
	{
		$key = explode('_', $this->getKey());
		return $key[0];
	}

	/**
	 * Return HTEvent* object, depends on event ID
	 *
	 * @return HTEvent
	 */
	public function getInfos()
	{
		if($this->hasMoreInfos())
		{
			$class = $this->getEventClassName();
			return new $class($this->getXml());
		}
		return null;
	}
}
class HTNationalTeams extends HTGlobal
{
	private $leagueOfficeTypeID = null;
	private $numberNationalTeams = null;
	private $nationalTeams = null;
	private $nationalTeamsByName = null;
	private $cup = null;

	/**
	 * Return league office type id (2 = A, 4 = U20)
	 *
	 * @return Integer
	 */
	public function getLeagueOfficeTypeId()
	{
		if(!isset($this->leagueOfficeTypeID) || $this->leagueOfficeTypeID === null)
		{
			$this->leagueOfficeTypeID = $this->getXml()->getElementsByTagName('LeagueOfficeTypeID')->item(0)->nodeValue;
		}
		return $this->leagueOfficeTypeID;
	}

	/**
	 * Return number of national teams
	 *
	 * @return Integer
	 */
	public function getNumberNationalTeams()
	{
		if(!isset($this->numberNationalTeams) || $this->numberNationalTeams === null)
		{
			$this->numberNationalTeams = $this->getXml()->getElementsByTagName('NationalTeam')->length;
		}
		return $this->numberNationalTeams;
	}

	/**
	 * Return HTNationalTeamsTeam object
	 *
	 * @param Integer $index
	 * @return HTNationalTeamsTeam
	 */
	public function getTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberNationalTeams())
		{
			--$index;
			if(!isset($this->nationalTeams[$index]) || $this->nationalTeams[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//NationalTeam');
				$team = new DOMDocument('1.0', 'UTF-8');
				$team->appendChild($team->importNode($nodeList->item($index), true));
				$this->nationalTeams[$index] = new HTNationalTeamsTeam($team);
			}
			return $this->nationalTeams[$index];
		}
		return null;
	}

	/**
	 * Return HTNationalTeamsTeam object
	 *
	 * @param String $name
	 * @return HTNationalTeamsTeam
	 */
	public function getTeamByLocalName($name)
	{
		if(!isset($this->nationalTeamsByName[$name]) || $this->nationalTeamsByName[$name] === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//NationalTeamName[.="'.$name.'"]');
			$team = new DOMDocument('1.0', 'UTF-8');
			$team->appendChild($team->importNode($nodeList->item(0)->parentNode, true));
			$this->nationalTeamsByName[$name] = new HTNationalTeamsTeam($team);
		}
		return $this->nationalTeamsByName[$name];
	}

	/**
	 * Return HTNationalTeamsCup object
	 *
	 * @return HTNationalTeamsCup
	 */
	public function getWorldCup()
	{
		if(!isset($this->cup) || $this->cup === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Cup');
			$cup = new DOMDocument('1.0', 'UTF-8');
			$cup->appendChild($cup->importNode($nodeList->item(0), true));
			$this->cup = new HTNationalTeamsCup($cup);
		}
		return $this->cup;
	}
}
class HTNationalTeamsTeam extends HTXml
{
	private $id = null;
	private $name = null;
	private $dress = null;
	private $dressAlternate = null;
	private $ratingScore = null;
	private $leagueId = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return national team id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('NationalTeamID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return national team name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('NationalTeamName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return national team dress url
	 *
	 * @return String
	 */
	public function getDressURI()
	{
		if(!isset($this->dress) || $this->dress === null)
		{
			$this->dress = $this->getXml()->getElementsByTagName('DressURI')->item(0)->nodeValue;
		}
		return $this->dress;
	}

	/**
	 * Return national team dress alternate url
	 *
	 * @return String
	 */
	public function getDressAlternateURI()
	{
		if(!isset($this->dressAlternate) || $this->dressAlternate === null)
		{
			$this->dressAlternate = $this->getXml()->getElementsByTagName('DressAlternateURI')->item(0)->nodeValue;
		}
		return $this->dressAlternate;
	}

	/**
	 * Return national team score rating
	 *
	 * @return Integer
	 */
	public function getRatingScore()
	{
		if(!isset($this->ratingScore) || $this->ratingScore === null)
		{
			$this->ratingScore = $this->getXml()->getElementsByTagName('RatingScore')->item(0)->nodeValue;
		}
		return $this->ratingScore;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueId')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}
}
class HTNationalTeamsCup extends HTXml
{
	private $id = null;
	private $teamsListAvailable = null;
	private $numberTeams = null;
	private $teams = null;
	private $teamsLocalName = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return current worldcup id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Is list of teams still in cup available ?
	 *
	 * @return Boolean
	 */
	public function isTeamsListAvailable()
	{
		if(!isset($this->teamsListAvailable) || $this->teamsListAvailable === null)
		{
			$this->teamsListAvailable = strtolower($this->getXml()->getElementsByTagName('CupTeams')->item(0)->getAttribute('Available')) == 'true';
		}
		return $this->teamsListAvailable;
	}

	/**
	 * Return number of teams still in world cup
	 *
	 * @return Integer
	 */
	public function getNumberTeamsStillInCup()
	{
		if(!isset($this->numberTeams) || $this->numberTeams === null)
		{
			$this->numberTeams = $this->getXml()->getElementsByTagName('CupTeam')->length;
		}
		return $this->numberTeams;
	}

	/**
	 * Return HTNationalTeamsCupTeam object
	 *
	 * @param Integer $index
	 * @return HTNationalTeamsCupTeam
	 */
	public function getTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberTeamsStillInCup())
		{
			if(!isset($this->teams[$index]) || $this->teams[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//CupTeam');
				$team = new DOMDocument('1.0', 'UTF-8');
				$team->appendChild($team->importNode($nodeList->item($index), true));
				$this->teams[$index] = new HTNationalTeamsCupTeam($team);
			}
			return $this->teams[$index];
		}
		return null;
	}

	/**
	 * Return HTNationalTeamsCupTeam object
	 *
	 * @param String $name
	 * @return HTNationalTeamsCupTeam
	 */
	public function getTeamByLocalName($name)
	{
		if(!isset($this->teamsLocalName[$name]) || $this->teamsLocalName[$name] === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//CupNationalTeamName[.="'.$name.'"]');
			$team = new DOMDocument('1.0', 'UTF-8');
			$team->appendChild($team->importNode($nodeList->item(0)->parentNode, true));
			$this->teamsLocalName[$name] = new HTNationalTeamsCupTeam($team);
		}
		return $this->teamsLocalName[$name];
	}
}
class HTNationalTeamsCupTeam extends HTXml
{
	private $id = null;
	private $name = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('CupNationalTeamID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('CupNationalTeamName')->item(0)->nodeValue;
		}
		return $this->name;
	}
}
class HTNationalTeamDetail extends HTCommonTeam
{
	private $coachUserId = null;
	private $coachUserName = null;
	private $shortTeamName = null;
	private $leagueId = null;
	private $leagueName = null;
	private $trainerId = null;
	private $trainerName = null;
	private $homepage = null;
	private $dress = null;
	private $dressAlternate = null;
	private $experience442 = null;
	private $experience433 = null;
	private $experience451 = null;
	private $experience352 = null;
	private $experience532 = null;
	private $experience343 = null;
	private $experience541 = null;
	private $experience523 = null;
	private $experience550 = null;
	private $experience253 = null;
	private $morale = null;
	private $selfConfidence = null;
	private $supportersPopularity = null;
	private $ratingScore = null;
	private $fanClubSize = null;
	private $rank = null;
	private $isPlaying = null;
	private $logo = null;

	/**
	 * Return coach user id
	 *
	 * @return Integer
	 */
	public function getCoachUserId()
	{
		if(!isset($this->coachUserId) || $this->coachUserId === null)
		{
			$this->coachUserId = $this->getXml()->getElementsByTagName('NationalCoachUserID')->item(0)->nodeValue;
		}
		return $this->coachUserId;
	}

	/**
	 * Return coach user name
	 *
	 * @return String
	 */
	public function getCoachUserName()
	{
		if(!isset($this->coachUserName) || $this->coachUserName === null)
		{
			$this->coachUserName = $this->getXml()->getElementsByTagName('NationalCoachLoginname')->item(0)->nodeValue;
		}
		return $this->coachUserName;
	}

	/**
	 * Return short team name
	 *
	 * @return String
	 */
	public function getShortTeamName()
	{
		if(!isset($this->shortTeamName) || $this->shortTeamName === null)
		{
			$this->shortTeamName = $this->getXml()->getElementsByTagName('ShortTeamName')->item(0)->nodeValue;
		}
		return $this->shortTeamName;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$this->leagueName = $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
		}
		return $this->leagueName;
	}

	/**
	 * Return trainer player id
	 *
	 * @return Integer
	 */
	public function getTrainerId()
	{
		if(!isset($this->trainerId) || $this->trainerId === null)
		{
			$this->trainerId = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->trainerId;
	}

	/**
	 * Return trainer player name
	 *
	 * @return String
	 */
	public function getTrainerName()
	{
		if(!isset($this->trainerName) || $this->trainerName === null)
		{
			$this->trainerName = $this->getXml()->getElementsByTagName('PlayerName')->item(0)->nodeValue;
		}
		return $this->trainerName;
	}

	/**
	 * Return home page url
	 *
	 * @return String
	 */
	public function getHomepageUrl()
	{
		if(!isset($this->homepage) || $this->homepage === null)
		{
			$this->homepage = $this->getXml()->getElementsByTagName('HomePage')->item(0)->nodeValue;
		}
		return $this->homepage;
	}

	/**
	 * Return dress uri
	 *
	 * @return String
	 */
	public function getDressURI()
	{
		if(!isset($this->dress) || $this->dress === null)
		{
			$this->dress = $this->getXml()->getElementsByTagName('DressURI')->item(0)->nodeValue;
		}
		return $this->dress;
	}

	/**
	 * Return dress alternate uri
	 *
	 * @return String
	 */
	public function getDressAlternateURI()
	{
		if(!isset($this->dressAlternate) || $this->dressAlternate === null)
		{
			$this->dressAlternate = $this->getXml()->getElementsByTagName('DressAlternateURI')->item(0)->nodeValue;
		}
		return $this->dressAlternate;
	}

	/**
	 * Return 442 experience level
	 *
	 * @return Integer
	 */
	public function get442Experience()
	{
		if(!isset($this->experience442) || $this->experience442 === null)
		{
			$this->experience442 = $this->getXml()->getElementsByTagName('Experience442')->item(0)->nodeValue;
		}
		return $this->experience442;
	}

	/**
	 * Return 433 experience level
	 *
	 * @return Integer
	 */
	public function get433Experience()
	{
		if(!isset($this->experience433) || $this->experience433 === null)
		{
			$this->experience433 = $this->getXml()->getElementsByTagName('Experience433')->item(0)->nodeValue;
		}
		return $this->experience433;
	}

	/**
	 * Return 451 experience level
	 *
	 * @return Integer
	 */
	public function get451Experience()
	{
		if(!isset($this->experience451) || $this->experience451 === null)
		{
			$this->experience451 = $this->getXml()->getElementsByTagName('Experience451')->item(0)->nodeValue;
		}
		return $this->experience451;
	}

	/**
	 * Return 352 experience level
	 *
	 * @return Integer
	 */
	public function get352Experience()
	{
		if(!isset($this->experience352) || $this->experience352 === null)
		{
			$this->experience352 = $this->getXml()->getElementsByTagName('Experience352')->item(0)->nodeValue;
		}
		return $this->experience352;
	}

	/**
	 * Return 532 experience level
	 *
	 * @return Integer
	 */
	public function get532Experience()
	{
		if(!isset($this->experience532) || $this->experience532 === null)
		{
			$this->experience532 = $this->getXml()->getElementsByTagName('Experience532')->item(0)->nodeValue;
		}
		return $this->experience532;
	}

	/**
	 * Return 343 experience level
	 *
	 * @return Integer
	 */
	public function get343Experience()
	{
		if(!isset($this->experience343) || $this->experience343 === null)
		{
			$this->experience343 = $this->getXml()->getElementsByTagName('Experience343')->item(0)->nodeValue;
		}
		return $this->experience343;
	}

	/**
	 * Return 451 experience level
	 *
	 * @return Integer
	 */
	public function get541Experience()
	{
		if(!isset($this->experience541) || $this->experience541 === null)
		{
			$this->experience541 = $this->getXml()->getElementsByTagName('Experience541')->item(0)->nodeValue;
		}
		return $this->experience541;
	}

	/**
	 * Return 523 experience level
	 *
	 * @return Integer
	 */
	public function get523Experience()
	{
		if(!isset($this->experience523) || $this->experience523 === null)
		{
			$this->experience523 = $this->getXml()->getElementsByTagName('Experience523')->item(0)->nodeValue;
		}
		return $this->experience523;
	}

	/**
	 * Return 550 experience level
	 *
	 * @return Integer
	 */
	public function get550Experience()
	{
		if(!isset($this->experience550) || $this->experience550 === null)
		{
			$this->experience550 = $this->getXml()->getElementsByTagName('Experience550')->item(0)->nodeValue;
		}
		return $this->experience550;
	}

	/**
	 * Return 253 experience level
	 *
	 * @return Integer
	 */
	public function get253Experience()
	{
		if(!isset($this->experience253) || $this->experience253 === null)
		{
			$this->experience253 = $this->getXml()->getElementsByTagName('Experience253')->item(0)->nodeValue;
		}
		return $this->experience253;
	}

	/**
	 * Return team spirit level
	 *
	 * @return Integer
	 */
	public function getTeamSpirit()
	{
		if(!isset($this->morale) || $this->morale === null)
		{
			$this->morale = $this->getXml()->getElementsByTagName('Morale')->item(0)->nodeValue;
		}
		return $this->morale;
	}

	/**
	 * Return self confidence level
	 *
	 * @return Integer
	 */
	public function getSelfConfidence()
	{
		if(!isset($this->selfConfidence) || $this->selfConfidence === null)
		{
			$this->selfConfidence = $this->getXml()->getElementsByTagName('SelfConfidence')->item(0)->nodeValue;
		}
		return $this->selfConfidence;
	}

	/**
	 * Return self confidence level
	 *
	 * @return Integer
	 */
	public function getSupportersPopularity()
	{
		if(!isset($this->supportersPopularity) || $this->supportersPopularity === null)
		{
			$this->supportersPopularity = $this->getXml()->getElementsByTagName('SupportersPopularity')->item(0)->nodeValue;
		}
		return $this->supportersPopularity;
	}

	/**
	 * Return rating score
	 *
	 * @return Integer
	 */
	public function getRatingScore()
	{
		if(!isset($this->ratingScore) || $this->ratingScore === null)
		{
			$this->ratingScore = $this->getXml()->getElementsByTagName('RatingScore')->item(0)->nodeValue;
		}
		return $this->ratingScore;
	}

	/**
	 * Return fan club size
	 *
	 * @return Integer
	 */
	public function getFanClubSize()
	{
		if(!isset($this->fanClubSize) || $this->fanClubSize === null)
		{
			$this->fanClubSize = $this->getXml()->getElementsByTagName('FanClubSize')->item(0)->nodeValue;
		}
		return $this->fanClubSize;
	}

	/**
	 * Return team rank
	 *
	 * @return Integer
	 */
	public function getRank()
	{
		if(!isset($this->rank) || $this->rank === null)
		{
			$this->rank = $this->getXml()->getElementsByTagName('Rank')->item(0)->nodeValue;
		}
		return $this->rank;
	}

	/**
	 * Return if team is playing a match
	 *
	 * @return Boolean
	 */
	public function isPlayingMatch()
	{
		if(!isset($this->isPlaying) || $this->isPlaying === null)
		{
			$this->isPlaying = strtolower($this->getXml()->getElementsByTagName('IsPlayingMatch')->item(0)->nodeValue) == "true";
		}
		return $this->isPlaying;
	}

	/**
	 * Return team logo url
	 *
	 * @return String
	 */
	public function getLogoUrl()
	{
		if(!isset($this->logo) || $this->logo === null)
		{
			$this->logo = $this->getXml()->getElementsByTagName('Logo')->item(0)->nodeValue;
		}
		return $this->logo;
	}
}
class HTNationalPlayers extends HTCommonSubscriber
{
	private $numberPlayers = null;
	private $players = null;

	/**
	 * Return number of players in team
	 *
	 * @return Integer
	 */
	public function getNumberPlayers()
	{
		if(!isset($this->numberPlayers) || $this->numberPlayers === null)
		{
			$this->numberPlayers = $this->getXml()->getElementsByTagName('Player')->length;
		}
		return $this->numberPlayers;
	}

	/**
	 * Return HTNationalPlayer object
	 *
	 * @param Integer $index
	 * @return HTNationalPlayer
	 */
	public function getPlayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberPlayers())
		{
			--$index;
			if(!isset($this->players[$index]) || $this->players[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Player');
				$player = new DOMDocument('1.0', 'UTF-8');
				$player->appendChild($player->importNode($nodeList->item($index), true));
				$this->players[$index] = new HTNationalPlayer($player);
			}
			return $this->players[$index];
		}
		return null;
	}
}
class HTNationalPlayer extends HTXml
{
	private $id = null;
	private $name = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return player name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('PlayerName')->item(0)->nodeValue;
		}
		return $this->name;
	}
}
class HTWorldCupGroup extends HTGlobal
{
	private $cupId = null;
	private $season = null;
	private $matchRound = null;
	private $numberTeams = null;
	private $teams = null;
	private $teamsByName = null;
	private $numberRounds = null;
	private $rounds = null;

	/**
	 * Return world cup id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->cupId) || $this->cupId === null)
		{
			$this->cupId = $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
		}
		return $this->cupId;
	}

	/**
	 * Return world cup season
	 *
	 * @return Integer
	 */
	public function getSeason()
	{
		if(!isset($this->season) || $this->season === null)
		{
			$this->season = $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
		}
		return $this->season;
	}

	/**
	 * Return world cup match round
	 *
	 * @return Integer
	 */
	public function getMatchRound()
	{
		if(!isset($this->matchRound) || $this->matchRound === null)
		{
			$this->matchRound = $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
		}
		return $this->matchRound;
	}

	/**
	 * Return number of teams
	 *
	 * @return Integer
	 */
	public function getNumberTeams()
	{
		if(!isset($this->numberTeams) || $this->numberTeams === null)
		{
			$node = $this->getXml()->getElementsByTagName('CupSeriesScores');
			if($node->length)
			{
				$this->numberTeams = $node->item(0)->getAttribute('Count');
			}
		}
		return $this->numberTeams;
	}

	/**
	 * Return HTWorldCupGroupTeam object
	 *
	 * @param Integer $index
	 * @return HTWorldCupGroupTeam
	 */
	public function getTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberTeams())
		{
			--$index;
			if(!isset($this->teams[$index]) || $this->teams[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Team');
				$team = new DOMDocument('1.0', 'UTF-8');
				$team->appendChild($team->importNode($nodeList->item($index), true));
				$this->teams[$index] = new HTWorldCupGroupTeam($team);
			}
			return $this->teams[$index];
		}
		return null;
	}

	/**
	 * Return HTWorldCupGroupTeam object
	 *
	 * @param Integer $index
	 * @return HTWorldCupGroupTeam
	 */
	public function getTeamByLocalName($name)
	{
		if(!isset($this->teamsByName[$name]) || $this->teamsByName[$name] === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//TeamName[.="'.$name.'"]');
			$team = new DOMDocument('1.0', 'UTF-8');
			$team->appendChild($team->importNode($nodeList->item(0)->parentNode, true));
			$this->teamsByName[$name] = new HTWorldCupGroupTeam($team);
		}
		return $this->teamsByName[$name];
	}

	/**
	 * Return number of rounds
	 *
	 * @return Integer
	 */
	public function getNumberRounds()
	{
		if(!isset($this->numberRounds) || $this->numberRounds === null)
		{
			$this->numberRounds = $this->getXml()->getElementsByTagName('Round')->length;
		}
		return $this->numberRounds;
	}

	/**
	 * Return HTWorldCupGroupRound object
	 *
	 * @param Integer $index
	 * @return HTWorldCupGroupRound
	 */
	public function getRound($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberRounds())
		{
			--$index;
			if(!isset($this->rounds[$index]) || $this->rounds[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Round');
				$round = new DOMDocument('1.0', 'UTF-8');
				$round->appendChild($round->importNode($nodeList->item($index), true));
				$this->rounds[$index] = new HTWorldCupGroupRound($round);
			}
			return $this->rounds[$index];
		}
		return null;
	}
}
class HTWorldCupGroupTeam extends HTXml
{
	private $id = null;
	private $name = null;
	private $place = null;
	private $cupSeriesUnitId = null;
	private $cupSeriesUnitName = null;
	private $matchesPlayed = null;
	private $goalsFor = null;
	private $goalsAgainst = null;
	private $points = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return team place
	 *
	 * @return Integer
	 */
	public function getPlace()
	{
		if(!isset($this->place) || $this->place === null)
		{
			$this->place = $this->getXml()->getElementsByTagName('Place')->item(0)->nodeValue;
		}
		return $this->place;
	}

	/**
	 * Return cup series unit id
	 *
	 * @return Integer
	 */
	public function getCupSeriesUnitId()
	{
		if(!isset($this->cupSeriesUnitId) || $this->cupSeriesUnitId === null)
		{
			$this->cupSeriesUnitId = $this->getXml()->getElementsByTagName('CupSeriesUnitID')->item(0)->nodeValue;
		}
		return $this->cupSeriesUnitId;
	}

	/**
	 * Return cup series unit name
	 *
	 * @return String
	 */
	public function getCupSeriesUnitName()
	{
		if(!isset($this->cupSeriesUnitName) || $this->cupSeriesUnitName === null)
		{
			$this->cupSeriesUnitName = $this->getXml()->getElementsByTagName('CupSeriesUnitName')->item(0)->nodeValue;
		}
		return $this->cupSeriesUnitName;
	}

	/**
	 * Return number of played matches
	 *
	 * @return Integer
	 */
	public function getMatchesPlayedNumber()
	{
		if(!isset($this->matchesPlayed) || $this->matchesPlayed === null)
		{
			$this->matchesPlayed = $this->getXml()->getElementsByTagName('MatchesPlayed')->item(0)->nodeValue;
		}
		return $this->matchesPlayed;
	}

	/**
	 * Return number of goal for team
	 *
	 * @return Integer
	 */
	public function getGoalsFor()
	{
		if(!isset($this->goalsFor) || $this->goalsFor === null)
		{
			$this->goalsFor = $this->getXml()->getElementsByTagName('GoalsFor')->item(0)->nodeValue;
		}
		return $this->goalsFor;
	}

	/**
	 * Return number of goal against team
	 *
	 * @return Integer
	 */
	public function getGoalsAgainst()
	{
		if(!isset($this->goalsAgainst) || $this->goalsAgainst === null)
		{
			$this->goalsAgainst = $this->getXml()->getElementsByTagName('GoalsAgainst')->item(0)->nodeValue;
		}
		return $this->goalsAgainst;
	}

	/**
	 * Return points number
	 *
	 * @return Integer
	 */
	public function getPoints()
	{
		if(!isset($this->points) || $this->points === null)
		{
			$this->points = $this->getXml()->getElementsByTagName('Points')->item(0)->nodeValue;
		}
		return $this->points;
	}
}
class HTWorldCupGroupRound extends HTXml
{
	private $matchRound = null;
	private $date = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match round
	 *
	 * @return Integer
	 */
	public function getMatchRound()
	{
		if(!isset($this->matchRound) || $this->matchRound === null)
		{
			$this->matchRound = $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
		}
		return $this->matchRound;
	}

	/**
	 * Return round date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('StartDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}
}
class HTAlliances extends HTGlobal
{
	private $numberAlliances = null;
	private $alliances = null;
	private $pageIndex = null;
	private $pages = null;

	/**
	 * Return number of alliances
	 *
	 * @return Integer
	 */
	public function getNumberAlliances()
	{
		if(!isset($this->numberAlliances) || $this->numberAlliances === null)
		{
			$this->numberAlliances = $this->getXml()->getElementsByTagName('Alliance')->length;
		}
		return $this->numberAlliances;
	}

	/**
	 * Return HTAlliance object
	 *
	 * @param Integer $index
	 * @return HTAlliance
	 */
	public function getAlliance($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberAlliances())
		{
			--$index;
			if(!isset($this->alliances[$index]) || $this->alliances[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Alliance');
				$alliance = new DOMDocument('1.0', 'UTF-8');
				$alliance->appendChild($alliance->importNode($nodeList->item($index), true));
				$this->alliances[$index] = new HTAlliance($alliance);
			}
			return $this->alliances[$index];
		}
		return null;
	}

	/**
	 * Return page index
	 *
	 * @return Integer
	 */
	public function getPageIndex()
	{
		if(!isset($this->pageIndex) || $this->pageIndex === null)
		{
			$this->pageIndex = $this->getXml()->getElementsByTagName('PageIndex')->item(0)->nodeValue;
		}
		return $this->pageIndex;
	}

	/**
	 * Return page number
	 *
	 * @return Integer
	 */
	public function getPageNumber()
	{
		if(!isset($this->pages) || $this->pages === null)
		{
			$this->pages = $this->getXml()->getElementsByTagName('Pages')->item(0)->nodeValue;
		}
		return $this->pages;
	}
}
class HTAlliance extends HTXml
{
	private $id = null;
	private $name = null;
	private $description = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return alliance id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('AllianceID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return alliance name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('AllianceName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return alliance description
	 *
	 * @return String
	 */
	public function getDescription()
	{
		if(!isset($this->description) || $this->description === null)
		{
			$this->description = $this->getXml()->getElementsByTagName('AllianceDescription')->item(0)->nodeValue;
		}
		return $this->description;
	}
}
class HTArena extends HTCommonTeam
{
	private $id = null;
	private $name = null;
	private $leagueId = null;
	private $leagueName = null;
	private $regionId = null;
	private $regionName = null;
	private $currentCapacity = null;
	private $futureCapacity = null;
	private $futureCapacityAvailable = null;

	/**
	 * Return arena id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return arena name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return arena league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Return arena league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$this->leagueName = $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
		}
		return $this->leagueName;
	}

	/**
	 * Return arena region id
	 *
	 * @return Integer
	 */
	public function getRegionId()
	{
		if(!isset($this->regionId) || $this->regionId === null)
		{
			$this->regionId = $this->getXml()->getElementsByTagName('RegionID')->item(0)->nodeValue;
		}
		return $this->regionId;
	}

	/**
	 * Return arena region name
	 *
	 * @return String
	 */
	public function getRegionName()
	{
		if(!isset($this->regionName) || $this->regionName === null)
		{
			$this->regionName = $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
		}
		return $this->regionName;
	}

	/**
	 * Return HTArenaCapacity object (current capacity)
	 *
	 * @return HTArenaCapacity
	 */
	public function getCurrentCapacity()
	{
		if(!isset($this->currentCapacity) || $this->currentCapacity === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//CurrentCapacity');
			$capacity = new DOMDocument('1.0', 'UTF-8');
			$capacity->appendChild($capacity->importNode($nodeList->item(0), true));
			$this->currentCapacity = new HTArenaCapacity($capacity);
		}
		return $this->currentCapacity;
	}

	/**
	 * Is future capacity available ?
	 *
	 * @return Boolean
	 */
	public function isFutureCapacityAvailable()
	{
		if(!isset($this->futureCapacityAvailable) || $this->futureCapacityAvailable === null)
		{
			$this->futureCapacityAvailable = strtolower($this->getXml()->getElementsByTagName('ExpandedCapacity')->item(0)->getAttribute('Available')) == 'true';
		}
		return $this->futureCapacityAvailable;
	}

	/**
	 * Return HTArenaCapacity object (expanded capacity)
	 *
	 * @return HTArenaCapacity
	 */
	public function getExpandedCapacity()
	{
		if($this->isFutureCapacityAvailable())
		{
			if(!isset($this->futureCapacity) || $this->futureCapacity === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//ExpandedCapacity');
				$capacity = new DOMDocument('1.0', 'UTF-8');
				$capacity->appendChild($capacity->importNode($nodeList->item(0), true));
				$this->futureCapacity = new HTArenaCapacity($capacity);
			}
			return $this->futureCapacity;
		}
		return null;
	}

	/**
	 * Return future arena capacity
	 *
	 * @return Integer
	 */
	public function getFutureCapacity()
	{
		if($this->isFutureCapacityAvailable())
		{
			return $this->getCurrentCapacity()+$this->getExpandedCapacity();
		}
		return $this->getCurrentCapacity();
	}
}
class HTArenaCapacity extends HTXml
{
	private $lastRebuildDateAvailable = null;
	private $lastRebuildDate = null;
	private $expansionDate = null;
	private $terraces = null;
	private $basic = null;
	private $roof = null;
	private $vip = null;
	private $total = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Is last rebuild arena date available ?
	 *
	 * @return Boolean
	 */
	public function isLastRebuildDateAvailable()
	{
		if(!isset($this->lastRebuildDateAvailable) || $this->lastRebuildDateAvailable === null)
		{
			$node = $this->getXml()->getElementsByTagName('RebuiltDate');
			if($node !== null && $node->length)
			{
				$this->lastRebuildDateAvailable = strtolower($this->getXml()->getElementsByTagName('RebuiltDate')->item(0)->getAttribute('Available')) == 'true';
			}
			else
			{
				$this->lastRebuildDateAvailable = false;
			}
		}
		return $this->lastRebuildDateAvailable;
	}

	/**
	 * Return last rebuild arena date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getLastRebuildDate($format = null)
	{
		if($this->isLastRebuildDateAvailable())
		{
			if(!isset($this->lastRebuildDate[$format]) || $this->lastRebuildDate[$format] === null)
			{
				$this->lastRebuildDate[$format] = $this->getXml()->getElementsByTagName('RebuiltDate')->item(0)->nodeValue;
				if($format !== null)
				{
					$this->lastRebuildDate[$format] = HTFunction::convertDate($this->lastRebuildDate[$format], $format);
				}
			}
			return $this->lastRebuildDate[$format];
		}
		return null;
	}

	/**
	 * Return arena expansion date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getExpansionDate($format = null)
	{
		if(!isset($this->expansionDate[$format]) || $this->expansionDate[$format] === null)
		{
			$node = $this->getXml()->getElementsByTagName('ExpansionDate');
			if($node !== null && $node->length)
			{
				$this->expansionDate[$format] = $this->getXml()->getElementsByTagName('ExpansionDate')->item(0)->nodeValue;
				if($format !== null)
				{
					$this->expansionDate[$format] = HTFunction::convertDate($this->expansionDate[$format], $format);
				}
			}
		}
		return $this->expansionDate[$format];
	}

	/**
	 * Return terraces seats number
	 *
	 * @return Integer
	 */
	public function getTerraces()
	{
		if(!isset($this->terraces) || $this->terraces === null)
		{
			$this->terraces = $this->getXml()->getElementsByTagName('Terraces')->item(0)->nodeValue;
		}
		return $this->terraces;
	}

	/**
	 * Return basic seats number
	 *
	 * @return Integer
	 */
	public function getBasic()
	{
		if(!isset($this->basic) || $this->basic === null)
		{
			$this->basic = $this->getXml()->getElementsByTagName('Basic')->item(0)->nodeValue;
		}
		return $this->basic;
	}

	/**
	 * Return roof seats number
	 *
	 * @return Integer
	 */
	public function getRoof()
	{
		if(!isset($this->roof) || $this->roof === null)
		{
			$this->roof = $this->getXml()->getElementsByTagName('Roof')->item(0)->nodeValue;
		}
		return $this->roof;
	}

	/**
	 * Return vip seats number
	 *
	 * @return Integer
	 */
	public function getVip()
	{
		if(!isset($this->vip) || $this->vip === null)
		{
			$this->vip = $this->getXml()->getElementsByTagName('VIP')->item(0)->nodeValue;
		}
		return $this->vip;
	}

	/**
	 * Return total seats number
	 *
	 * @return Integer
	 */
	public function getTotal()
	{
		if(!isset($this->total) || $this->total === null)
		{
			$node = $this->getXml()->getElementsByTagName('Total');
			if($node !== null && $node->length)
			{
				$this->total = $node->item(0)->nodeValue;
			}
			else
			{
				$this->total = $this->getTerraces()+$this->getBasic()+$this->getRoof()+$this->getVip();
			}
		}
		return $this->total;
	}
}
class HTMatchOrders extends HTGlobal
{
	private $dataAvailable = null;
	private $homeTeamId = null;
	private $homeTeamName = null;
	private $awayTeamId = null;
	private $awayTeamName = null;
	private $arenaId = null;
	private $arenaName = null;
	private $date = null;
	private $type = null;
	private $attitude = null;
	private $attitudeAvailable = null;
	private $tactic = null;
	private $players = null;
	private $playersByRole = null;
	private $playersNumber = null;
	private $ordersNumber = null;
	private $orders = null;
	private $ordersByPlayerId = null;
	private $ordersNumberByPlayerId = null;

	/**
	 * Are orders available ?
	 *
	 * @return Boolean
	 */
	public function isDataAvailable()
	{
		if(!isset($this->dataAvailable) || $this->dataAvailable === null)
		{
			$this->dataAvailable = strtolower($this->getXml()->getElementsByTagName('MatchData')->item(0)->getAttribute('Available')) == 'true';
		}
		return $this->dataAvailable;
	}

	/**
	 * Return home team id
	 *
	 * @return Integer
	 */
	public function getHomeTeamId()
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->homeTeamId) || $this->homeTeamId === null)
			{
				$this->homeTeamId = $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
			}
			return $this->homeTeamId;
		}
		return null;
	}

	/**
	 * Return home team name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->homeTeamName) || $this->homeTeamName === null)
			{
				$this->homeTeamName = $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
			}
			return $this->homeTeamName;
		}
		return null;
	}

	/**
	 * Return away team id
	 *
	 * @return Integer
	 */
	public function getAwayTeamId()
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->awayTeamId) || $this->awayTeamId === null)
			{
				$this->awayTeamId = $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
			}
			return $this->awayTeamId;
		}
		return null;
	}

	/**
	 * Return away team name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->awayTeamName) || $this->awayTeamName === null)
			{
				$this->awayTeamName = $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
			}
			return $this->awayTeamName;
		}
		return null;
	}

	/**
	 * Return arena id
	 *
	 * @return Integer
	 */
	public function getArenaId()
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->arenaId) || $this->arenaId === null)
			{
				$this->arenaId = $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
			}
			return $this->arenaId;
		}
		return null;
	}

	/**
	 * Return arena name
	 *
	 * @return String
	 */
	public function getArenaName()
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->arenaName) || $this->arenaName === null)
			{
				$this->arenaName = $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
			}
			return $this->arenaName;
		}
		return null;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->date[$format]) || $this->date[$format] === null)
			{
				$this->date[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
				if($format !== null)
				{
					$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
				}
			}
			return $this->date[$format];
		}
		return null;
	}

	/**
	 * Return match type
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->type) || $this->type === null)
			{
				$this->type = $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
			}
			return $this->type;
		}
		return null;
	}

	/**
	 * Is attitude value available ?
	 *
	 * @return Boolean
	 */
	public function isAttituteAvailable()
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->attitudeAvailable) || $this->attitudeAvailable === null)
			{
				$this->attitudeAvailable = strtolower($this->getXml()->getElementsByTagName('Attitude')->item(0)->getAttribute('Available')) == 'true';
			}
			return $this->attitudeAvailable;
		}
		return null;
	}

	/**
	 * Return attitude value
	 *
	 * @return Integer
	 */
	public function getAttitude()
	{
		if($this->isDataAvailable())
		{
			if($this->isAttituteAvailable())
			{
				if(!isset($this->attitude) || $this->attitude === null)
				{
					$this->attitude = $this->getXml()->getElementsByTagName('Attitude')->item(0)->nodeValue;
				}
				return $this->attitude;
			}
		}
		return null;
	}

	/**
	 * Return tactic value
	 *
	 * @return Integer
	 */
	public function getTactic()
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->tactic) || $this->tactic === null)
			{
				$this->tactic = $this->getXml()->getElementsByTagName('TacticType')->item(0)->nodeValue;
			}
			return $this->tactic;
		}
		return null;
	}

	/**
	 * Return number players
	 *
	 * @return Integer
	 */
	public function getPlayersNumber()
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->playersNumber) || $this->playersNumber === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Player');
				$this->playersNumber = $nodeList->length;
			}
			return $this->playersNumber;
		}
		return null;
	}

	/**
	 * Return HTMatchOrdersPlayer object
	 *
	 * @param Integer $index
	 * @return HTMatchOrdersPlayer
	 */
	public function getPlayer($index)
	{
		if($this->isDataAvailable())
		{
			$index = round($index);
			if($index > 0 && $index <= $this->getPlayersNumber())
			{
				--$index;
				if(!isset($this->players[$index]) || $this->players[$index] === null)
				{
					$xpath = new DOMXPath($this->getXml());
					$nodeList = $xpath->query('//Player');
					$player = new DOMDocument('1.0', 'UTF-8');
					$player->appendChild($player->importNode($nodeList->item($index), true));
					$this->players[$index] = new HTMatchOrdersPlayer($player);
				}
				return $this->players[$index];
			}
		}
		return null;
	}

	/**
	 * Return HTMatchOrdersPlayer object
	 *
	 * @param Integer $index
	 * @return HTMatchOrdersPlayer
	 */
	public function getPlayerByRoleId($id)
	{
		if($this->isDataAvailable())
		{
			$id = round($id);
			if(($id >= 100 && $id < 119) || ($id == 17 || $id == 18))
			{
				if(!isset($this->playersByRole[$id]) || $this->playersByRole[$id] === null)
				{
					$xpath = new DOMXPath($this->getXml());
					$nodeList = $xpath->query('//RoleID[.="'.$id.'"]');
					$player = new DOMDocument('1.0', 'UTF-8');
					$player->appendChild($player->importNode($nodeList->item(0)->parentNode, true));
					$this->playersByRole[$id] = new HTMatchOrdersPlayer($player);
				}
				return $this->playersByRole[$id];
			}
		}
		return null;
	}

	/**
	 * Return number player orders
	 *
	 * @return Integer
	 */
	public function getPlayerOrdersNumber()
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->ordersNumber) || $this->ordersNumber === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//PlayerOrder');
				$this->ordersNumber = $nodeList->length;
			}
			return $this->ordersNumber;
		}
		return null;
	}

	/**
	 * Return HTMatchOrdersPlayerOrder object
	 *
	 * @param Integer $index
	 * @return HTMatchOrdersPlayerOrder
	 */
	public function getPlayerOrder($index)
	{
		if($this->isDataAvailable())
		{
			$index = round($index);
			if($index > 0 && $index <= $this->getPlayerOrdersNumber())
			{
				--$index;
				if(!isset($this->orders[$index]) || $this->orders[$index] === null)
				{
					$xpath = new DOMXPath($this->getXml());
					$nodeList = $xpath->query('//PlayerOrder');
					$order = new DOMDocument('1.0', 'UTF-8');
					$order->appendChild($order->importNode($nodeList->item($index), true));
					$this->orders[$index] = new HTMatchOrdersPlayerOrder($order);
				}
				return $this->orders[$index];
			}
		}
		return null;
	}

	/**
	 * Return number of orders for a player
	 *
	 * @param Integer $playerId
	 * @return Integer
	 */
	public function getPlayerOrderNumberByPlayerId($playerId)
	{
		if($this->isDataAvailable())
		{
			if(!isset($this->ordersNumberByPlayerId[$playerId]) || $this->ordersNumberByPlayerId[$playerId] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//SubjectPlayerID[.="'.$playerId.'"]');
				$this->ordersNumberByPlayerId[$playerId] = $nodeList->length;
			}
			return $this->ordersNumberByPlayerId[$playerId];
		}
		return 0;
	}

	/**
	 * Return HTMatchOrdersPlayerOrder object
	 *
	 * @param Integer $playerId
	 * @param Integer $number
	 * @return HTMatchOrdersPlayerOrder
	 */
	public function getPlayerOrderByPlayerId($playerId, $number = 1)
	{
		if($this->isDataAvailable())
		{
			if($number > 0 && $number <= $this->getPlayerOrderNumberByPlayerId($playerId))
			{
				--$number;
				if(!isset($this->ordersByPlayerId[$playerId]) || $this->ordersByPlayerId[$playerId] === null)
				{
					$xpath = new DOMXPath($this->getXml());
					$nodeList = $xpath->query('//SubjectPlayerID[.="'.$playerId.'"]');
					$order = new DOMDocument('1.0', 'UTF-8');
					$order->appendChild($order->importNode($nodeList->item($number)->parentNode, true));
					$this->ordersByPlayerId[$playerId] = new HTMatchOrdersPlayerOrder($order);
				}
				return $this->ordersByPlayerId[$playerId];
			}
		}
		return null;
	}
}
class HTMatchOrdersPlayer extends HTXml
{
	private $id = null;
	private $position = null;
	private $firstname = null;
	private $lastname = null;
	private $nickname = null;
	private $individualOrder = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return player full name
	 *
	 * @return String
	 */
	public function getName()
	{
		$name = $this->getFirstName();
		if($this->getNickName())
		{
			$name .= ' '.$this->getNickName();
		}
		return $name.' '.$this->getLastName();
	}

	/**
	 * Return player first name
	 *
	 * @return String
	 */
	public function getFirstName()
	{
		if(!isset($this->firstname) || $this->firstname === null)
		{
			$this->firstname = $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
		}
		return $this->firstname;
	}

	/**
	 * Return player first name
	 *
	 * @return String
	 */
	public function getLastName()
	{
		if(!isset($this->lastname) || $this->lastname === null)
		{
			$this->lastname = $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
		}
		return $this->lastname;
	}

	/**
	 * Return player nick name
	 *
	 * @return String
	 */
	public function getNickName()
	{
		if(!isset($this->nickname) || $this->nickname === null)
		{
			$this->nickname = $this->getXml()->getElementsByTagName('NickName')->item(0)->nodeValue;
		}
		return $this->nickname;
	}

	/**
	 * Return position code
	 *
	 * @return Integer
	 */
	public function getPosition()
	{
		if(!isset($this->position) || $this->position === null)
		{
			$this->position = $this->getXml()->getElementsByTagName('RoleID')->item(0)->nodeValue;
		}
		return $this->position;
	}

	/**
	 * Return player individual order code
	 *
	 * @return Integer
	 */
	public function getIndividualOrder()
	{
		if(!isset($this->individualOrder) || $this->individualOrder === null)
		{
			$node = $this->getXml()->getElementsByTagName('Behaviour');
			if($node !== null && $node->length)
			{
				$this->individualOrder = $node->item(0)->nodeValue;
			}
		}
		return $this->individualOrder;
	}
}
class HTMatchOrdersPlayerOrder extends HTXml
{
	private $orderId = null;
	private $minuteCriteria = null;
	private $goalDiffCriteria = null;
	private $redCardCriteria = null;
	private $subjectPlayerId = null;
	private $objectPlayerId = null;
	private $newPositionBehaviour = null;
	private $newPositionId = null;
	private $orderType = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return player order id
	 *
	 * @return Integer
	 */
	public function getOrderId()
	{
		if(!isset($this->orderId) || $this->orderId === null)
		{
			$this->orderId = $this->getXml()->getElementsByTagName('PlayerOrderID')->item(0)->nodeValue;
		}
		return $this->orderId;
	}

	/**
	 * Return player order id
	 *
	 * @return Integer
	 */
	public function getOrderType()
	{
		if(!isset($this->orderType) || $this->orderType === null)
		{
			$this->orderType = $this->getXml()->getElementsByTagName('OrderType')->item(0)->nodeValue;
		}
		return $this->orderType;
	}

	/**
	 * Return minute criteria
	 *
	 * @return Integer
	 */
	public function getMinuteCriteria()
	{
		if(!isset($this->minuteCriteria) || $this->minuteCriteria === null)
		{
			$this->minuteCriteria = $this->getXml()->getElementsByTagName('MatchMinuteCriteria')->item(0)->nodeValue;
		}
		return $this->minuteCriteria;
	}

	/**
	 * Return goal difference criteria
	 *
	 * @return Integer
	 */
	public function getGoalDifferenceCriteria()
	{
		if(!isset($this->goalDiffCriteria) || $this->goalDiffCriteria === null)
		{
			$this->goalDiffCriteria = $this->getXml()->getElementsByTagName('GoalDiffCriteria')->item(0)->nodeValue;
		}
		return $this->goalDiffCriteria;
	}

	/**
	 * Return red card criteria
	 *
	 * @return Integer
	 */
	public function getRedCardCriteria()
	{
		if(!isset($this->redCardCriteria) || $this->redCardCriteria === null)
		{
			$this->redCardCriteria = $this->getXml()->getElementsByTagName('RedCardCriteria')->item(0)->nodeValue;
		}
		return $this->redCardCriteria;
	}

	/**
	 * Return player id out
	 *
	 * @return Integer
	 */
	public function getPlayerIdOut()
	{
		if(!isset($this->subjectPlayerId) || $this->subjectPlayerId === null)
		{
			$this->subjectPlayerId = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerId;
	}

	/**
	 * Return player id in
	 *
	 * @return Integer
	 */
	public function getPlayerIdIn()
	{
		if(!isset($this->objectPlayerId) || $this->objectPlayerId === null)
		{
			$this->objectPlayerId = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerId;
	}

	/**
	 * Return new player behaviour
	 *
	 * @return Integer
	 */
	public function getNewBehaviour()
	{
		if(!isset($this->newPositionBehaviour) || $this->newPositionBehaviour === null)
		{
			$this->newPositionBehaviour = $this->getXml()->getElementsByTagName('NewPositionBehaviour')->item(0)->nodeValue;
		}
		return $this->newPositionBehaviour;
	}

	/**
	 * Return new player position
	 *
	 * @return Integer
	 */
	public function getNewPosition()
	{
		if(!isset($this->newPositionId) || $this->newPositionId === null)
		{
			$this->newPositionId = $this->getXml()->getElementsByTagName('NewPositionId')->item(0)->nodeValue;
		}
		return $this->newPositionId;
	}
}
class HTArenaMyStats extends HTGlobal
{
	private $arenaId = null;
	private $arenaName = null;
	private $matchType = null;
	private $startDate = null;
	private $endDate = null;
	private $matchesNumber = null;
	private $average = null;
	private $most = null;
	private $least = null;
	const ALL = 'All';
	const COMP = 'CompOnly';
	const LEAGUE = 'LeagueOnly';
	const FRIENDLY = 'FriendlyOnly';

	/**
	 * Return arena id
	 *
	 * @return Integer
	 */
	public function getArenaId()
	{
		if(!isset($this->arenaId) || $this->arenaId === null)
		{
			$this->arenaId = $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
		}
		return $this->arenaId;
	}

	/**
	 * Return arena name
	 *
	 * @return String
	 */
	public function getArenaName()
	{
		if(!isset($this->arenaName) || $this->arenaName === null)
		{
			$this->arenaName = $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
		}
		return $this->arenaName;
	}

	/**
	 * Return match type
	 *
	 * @return String
	 */
	public function getMatchType()
	{
		if(!isset($this->matchType) || $this->matchType === null)
		{
			$this->matchType = $this->getXml()->getElementsByTagName('MatchTypes')->item(0)->nodeValue;
		}
		return $this->matchType;
	}

	/**
	 * Return start date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getStartDate($format = null)
	{
		if(!isset($this->startDate[$format]) || $this->startDate[$format] === null)
		{
			$this->startDate[$format] = $this->getXml()->getElementsByTagName('FirstDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->startDate[$format] = HTFunction::convertDate($this->startDate[$format], $format);
			}
		}
		return $this->startDate[$format];
	}

	/**
	 * Return end date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getEndDate($format = null)
	{
		if(!isset($this->endDate[$format]) || $this->endDate[$format] === null)
		{
			$this->endDate[$format] = $this->getXml()->getElementsByTagName('LastDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->endDate[$format] = HTFunction::convertDate($this->endDate[$format], $format);
			}
		}
		return $this->endDate[$format];
	}

	/**
	 * Return matches number
	 *
	 * @return String
	 */
	public function getMatchesNumber()
	{
		if(!isset($this->matchesNumber) || $this->matchesNumber === null)
		{
			$this->matchesNumber = $this->getXml()->getElementsByTagName('NumberOfMatches')->item(0)->nodeValue;
		}
		return $this->matchesNumber;
	}

	/**
	 * Return HTArenaMyStatsVisitors object
	 *
	 * @return HTArenaMyStatsVisitors
	 */
	public function getAverageVisitors()
	{
		if(!isset($this->average) || $this->average === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//VisitorsAverage');
			$visitor = new DOMDocument('1.0', 'UTF-8');
			$visitor->appendChild($visitor->importNode($nodeList->item(0), true));
			$this->average = new HTArenaMyStatsVisitors($visitor);
		}
		return $this->average;
	}

	/**
	 * Return HTArenaMyStatsVisitors object
	 *
	 * @return HTArenaMyStatsVisitors
	 */
	public function getMostVisitors()
	{
		if(!isset($this->most) || $this->most === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//VisitorsMost');
			$visitor = new DOMDocument('1.0', 'UTF-8');
			$visitor->appendChild($visitor->importNode($nodeList->item(0), true));
			$this->most = new HTArenaMyStatsVisitors($visitor);
		}
		return $this->most;
	}

	/**
	 * Return HTArenaMyStatsVisitors object
	 *
	 * @return HTArenaMyStatsVisitors
	 */
	public function getLeastVisitors()
	{
		if(!isset($this->least) || $this->least === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//VisitorsLeast');
			$visitor = new DOMDocument('1.0', 'UTF-8');
			$visitor->appendChild($visitor->importNode($nodeList->item(0), true));
			$this->least = new HTArenaMyStatsVisitors($visitor);
		}
		return $this->least;
	}
}
class HTArenaMyStatsVisitors extends HTXml
{
	private $terraces = null;
	private $basic = null;
	private $roof = null;
	private $vip = null;
	private $total = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return terraces seats number
	 *
	 * @return Integer
	 */
	public function getTerraces()
	{
		if(!isset($this->terraces) || $this->terraces === null)
		{
			$this->terraces = $this->getXml()->getElementsByTagName('Terraces')->item(0)->nodeValue;
		}
		return $this->terraces;
	}

	/**
	 * Return basic seats number
	 *
	 * @return Integer
	 */
	public function getBasic()
	{
		if(!isset($this->basic) || $this->basic === null)
		{
			$this->basic = $this->getXml()->getElementsByTagName('Basic')->item(0)->nodeValue;
		}
		return $this->basic;
	}

	/**
	 * Return roof seats number
	 *
	 * @return Integer
	 */
	public function getRoof()
	{
		if(!isset($this->roof) || $this->roof === null)
		{
			$this->roof = $this->getXml()->getElementsByTagName('Roof')->item(0)->nodeValue;
		}
		return $this->roof;
	}

	/**
	 * Return vip seats number
	 *
	 * @return Integer
	 */
	public function getVip()
	{
		if(!isset($this->vip) || $this->vip === null)
		{
			$this->vip = $this->getXml()->getElementsByTagName('VIP')->item(0)->nodeValue;
		}
		return $this->vip;
	}

	/**
	 * Return total seats number
	 *
	 * @return Integer
	 */
	public function getTotal()
	{
		if(!isset($this->total) || $this->total === null)
		{
			$this->total = $this->getXml()->getElementsByTagName('Total')->item(0)->nodeValue;
		}
		return $this->total;
	}
}
class HTArenasStats extends HTGlobal
{
	private $leagueId = null;
	private $leagueName = null;
	private $createDate = null;
	private $arenaNumber = null;
	private $arenas = null;

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$node = $this->getXml()->getElementsByTagName('LeagueName');
			if($node !== null && $node->length)
			{
				$this->leagueName = $node->item(0)->nodeValue;
			}
		}
		return $this->leagueName;
	}

	/**
	 * Return stats created date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getStatsDate($format = null)
	{
		if(!isset($this->createDate[$format]) || $this->createDate[$format] === null)
		{
			$this->createDate[$format] = $this->getXml()->getElementsByTagName('CreatedDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->createDate[$format] = HTFunction::convertDate($this->createDate[$format], $format);
			}
		}
		return $this->createDate[$format];
	}

	/**
	 * Return number of arenas
	 *
	 * @return Integer
	 */
	public function getArenaNumber()
	{
		if(!isset($this->arenaNumber) || $this->arenaNumber === null)
		{
			$this->arenaNumber = $this->getXml()->getElementsByTagName('ArenaStat')->length;
		}
		return $this->arenaNumber;
	}

	/**
	 * Return HTArenasStatsArena object
	 *
	 * @param Integer $index
	 * @return HTArenasStatsArena
	 */
	public function getArena($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getArenaNumber())
		{
			--$index;
			if(!isset($this->arenas[$index]) || $this->arenas[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//ArenaStat');
				$arena = new DOMDocument('1.0', 'UTF-8');
				$arena->appendChild($arena->importNode($nodeList->item($index), true));
				$this->arenas[$index] = new HTArenasStatsArena($arena);
			}
			return $this->arenas[$index];
		}
		return null;
	}
}
class HTArenasStatsArena extends HTXml
{
	private $id = null;
	private $name = null;
	private $size = null;
	private $regionId = null;
	private $regionName = null;
	private $leagueId = null;
	private $leagueName = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return arena id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return arena name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return arena size
	 *
	 * @return Integer
	 */
	public function getSize()
	{
		if(!isset($this->size) || $this->size === null)
		{
			$this->size = $this->getXml()->getElementsByTagName('ArenaSize')->item(0)->nodeValue;
		}
		return $this->size;
	}

	/**
	 * Return arena region id
	 *
	 * @return Integer
	 */
	public function getRegionId()
	{
		if(!isset($this->regionId) || $this->regionId === null)
		{
			$this->regionId = $this->getXml()->getElementsByTagName('ArenaRegionId')->item(0)->nodeValue;
		}
		return $this->regionId;
	}

	/**
	 * Return arena region name
	 *
	 * @return String
	 */
	public function getRegionName()
	{
		if(!isset($this->regionName) || $this->regionName === null)
		{
			$this->regionName = $this->getXml()->getElementsByTagName('ArenaRegionName')->item(0)->nodeValue;
		}
		return $this->regionName;
	}

	/**
	 * Return arena league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('ArenaLeagueID')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Return arena league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$this->leagueName = $this->getXml()->getElementsByTagName('ArenaLeagueName')->item(0)->nodeValue;
		}
		return $this->leagueName;
	}
}
class HTChallenges extends HTCommonTeam
{
	private $mine = null;
	private $mineNumber = null;
	private $offersNumber = null;
	private $offers = null;

	const NORMAL = 0;
	const CUP	= 1;
	const HOME = 0;
	const AWAY = 1;
	const NEUTRAL = 2;

	/**
	 * Return my challenges number
	 *
	 * @return Integer
	 */
	public function getMineNumber()
	{
		if(!isset($this->mineNumber) || $this->mineNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Challenge');
			$this->mineNumber = $nodeList->length;
		}
		return $this->mineNumber;
	}

	/**
	 * Return HTChallenge object
	 *
	 * @param Integer $index
	 * @return HTChallenge
	 */
	public function getMine($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getMineNumber())
		{
			--$index;
			if(!isset($this->mine[$index]) || $this->mine[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Challenge');
				$challenge = new DOMDocument('1.0', 'UTF-8');
				$challenge->appendChild($challenge->importNode($nodeList->item($index), true));
				$this->mine[$index] = new HTChallenge($challenge);
			}
			return $this->mine[$index];
		}
		return null;
	}

	/**
	 * Return offers challenges number
	 *
	 * @return Integer
	 */
	public function getOfferNumber()
	{
		if(!isset($this->offersNumber) || $this->offersNumber === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query('//Offer');
			$this->offersNumber = $nodeList->length;
		}
		return $this->offersNumber;
	}

	/**
	 * Return HTChallenge object
	 *
	 * @param Integer $index
	 * @return HTChallenge
	 */
	public function getOffer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getOfferNumber())
		{
			--$index;
			if(!isset($this->offers[$index]) || $this->offers[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Offer');
				$challenge = new DOMDocument('1.0', 'UTF-8');
				$challenge->appendChild($challenge->importNode($nodeList->item($index), true));
				$this->offers[$index] = new HTChallenge($challenge);
			}
			return $this->offers[$index];
		}
		return null;
	}
}
class HTChallenge extends HTXml
{
	private $id = null;
	private $matchId = null;
	private $date = null;
	private $type = null;
	private $opponentTeamId = null;
	private $opponentTeamName = null;
	private $arenaId = null;
	private $arenaName = null;
	private $leagueId = null;
	private $leagueName = null;
	private $isAgreed = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return challenge id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('TrainingMatchID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return challenge match id
	 *
	 * @return Integer
	 */
	public function getMatchId()
	{
		if(!isset($this->matchId) || $this->matchId === null)
		{
			if($this->getXml()->getElementsByTagName('MatchId')->length)
			{
				$this->matchId = $this->getXml()->getElementsByTagName('MatchId')->item(0)->nodeValue;
			}
		}
		return $this->matchId;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getMatchDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('MatchTime')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return match type
	 *
	 * @return Integer
	 */
	public function getMatchType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('FriendlyType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return opponent team id
	 *
	 * @return Integer
	 */
	public function getOpponentTeamId()
	{
		if(!isset($this->opponentTeamId) || $this->opponentTeamId === null)
		{
			$this->opponentTeamId = $this->getXml()->getElementsByTagName('TeamID')->item(0)->nodeValue;
		}
		return $this->opponentTeamId;
	}

	/**
	 * Return opponent team name
	 *
	 * @return Integer
	 */
	public function getOpponentTeamName()
	{
		if(!isset($this->opponentTeamName) || $this->opponentTeamName === null)
		{
			$this->opponentTeamName = $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
		}
		return $this->opponentTeamName;
	}

	/**
	 * Return arena id
	 *
	 * @return Integer
	 */
	public function getArenaId()
	{
		if(!isset($this->arenaId) || $this->arenaId === null)
		{
			$this->arenaId = $this->getXml()->getElementsByTagName('ArenaID')->item(0)->nodeValue;
		}
		return $this->arenaId;
	}

	/**
	 * Return arena name
	 *
	 * @return String
	 */
	public function getArenaName()
	{
		if(!isset($this->arenaName) || $this->arenaName === null)
		{
			$this->arenaName = $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
		}
		return $this->arenaName;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueID')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$this->leagueName = $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
		}
		return $this->leagueName;
	}

	/**
	 * Is this challenge accepted ?
	 *
	 * @return Boolean
	 */
	public function isAccepted()
	{
		if(!isset($this->isAgreed) || $this->isAgreed === null)
		{
			$this->isAgreed = strtolower($this->getXml()->getElementsByTagName('IsAgreed')->item(0)->nodeValue) == "true";
		}
		return $this->isAgreed;
	}
}
class HTChallengeableTeams extends HTCommonTeam
{
	private $teams = null;
	private $teamsNumber = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->loadXML($xml);
		$xpath = new DOMXPath($dom);
		$nodeList = $xpath->query('//ChallengeableResult');
		$teams = new DOMDocument('1.0', 'UTF-8');
		$teams->appendChild($teams->importNode($nodeList->item(0), true));
		parent::__construct($teams->saveXML());
	}

	/**
	 * Return number of teams
	 *
	 * @return Integer
	 */
	public function getTeamsNumber()
	{
		if(!isset($this->teamsNumber) || $this->teamsNumber === null)
		{
			$this->teamsNumber = $this->getXml()->getElementsByTagName('Opponent')->length;
		}
		return $this->teamsNumber;
	}

	/**
	 * Return HTChallengeableTeam object
	 *
	 * @param Integer $index
	 * @return HTChallengeableTeam
	 */
	public function getTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getTeamsNumber())
		{
			--$index;
			if(!isset($this->teams[$index]) || $this->teams[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Opponent');
				$team = new DOMDocument('1.0', 'UTF-8');
				$team->appendChild($team->importNode($nodeList->item($index), true));
				$this->teams[$index] = new HTChallengeableTeam($team);
			}
			return $this->teams[$index];
		}
		return null;
	}
}
class HTChallengeableTeam extends HTXml
{
	private $ischallengeable = null;
	private $userid = null;
	private $teamid = null;
	private $teamname = null;
	private $logourl = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return if team is chanllengeable
	 *
	 * @return Boolean
	 */
	public function isTeamChallengeable()
	{
		if(!isset($this->ischallengeable) || $this->ischallengeable === null)
		{
			$this->ischallengeable = $this->getXml()->getElementsByTagName('IsChallengeable')->item(0)->nodeValue == 'True';
		}
		return $this->ischallengeable;
	}

	/**
	 * Return team's userid
	 *
	 * @return Integer
	 */
	public function getUserId()
	{
		if(!isset($this->userid) || $this->userid === null)
		{
			$this->userid = $this->getXml()->getElementsByTagName('UserId')->item(0)->nodeValue;
		}
		return $this->userid;
	}

	/**
	 * Return teamid
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamid) || $this->teamid === null)
		{
			$this->teamid = $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
		}
		return $this->teamid;
	}

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getTeamName()
	{
		if(!isset($this->teamname) || $this->teamname === null)
		{
			$this->teamname = $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
		}
		return $this->teamname;
	}

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getLogoUrl()
	{
		if(!isset($this->logourl) || $this->logourl === null)
		{
			$this->logourl = $this->getXml()->getElementsByTagName('LogoURL')->item(0)->nodeValue;
		}
		return $this->logourl;
	}
}
class HTCup extends HTGlobal
{
	private $id = null;
	private $season = null;
	private $round = null;
	private $name = null;
	private $matchNumber = null;
	private $matches = null;

	/**
	 * Return cup id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return cup season
	 *
	 * @return Integer
	 */
	public function getSeason()
	{
		if(!isset($this->season) || $this->season === null)
		{
			$this->season = $this->getXml()->getElementsByTagName('CupSeason')->item(0)->nodeValue;
		}
		return $this->season;
	}

	/**
	 * Return cup round
	 *
	 * @return Integer
	 */
	public function getRound()
	{
		if(!isset($this->round) || $this->round === null)
		{
			$this->round = $this->getXml()->getElementsByTagName('CupRound')->item(0)->nodeVale;
		}
		return $this->round;
	}

	/**
	 * Return cup name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('CupName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return match number
	 *
	 * @return Integer
	 */
	public function getMatchNumber()
	{
		if(!isset($this->matchNumber) || $this->matchNumber === null)
		{
			$this->matchNumber = $this->getXml()->getElementsByTagName('Match')->length;
		}
		return $this->matchNumber;
	}

	/**
	 * Return HTCupMatch object
	 *
	 * @param Integer $index
	 * @return HTCupMatch
	 */
	public function getMatch($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getMatchNumber())
		{
			--$index;
			if(!isset($this->matches[$index]) || $this->matches[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Match');
				$match = new DOMDocument('1.0', 'UTF-8');
				$match->appendChild($match->importNode($nodeList->item($index), true));
				$this->matches[$index] = new HTCupMatch($match);
			}
			return $this->matches[$index];
		}
		return null;
	}
}
class HTCupMatch extends HTXml
{
	private $id = null;
	private $date = null;
	private $homeTeamId = null;
	private $homeTeamName = null;
	private $awayTeamId = null;
	private $awayTeamName = null;
	private $matchResultsAvailable = null;
	private $homeGoals = null;
	private $awayGoals = null;
	private $leagueInfoAvailable = null;
	private $homeLeagueId = null;
	private $homeLeagueName = null;
	private $awayLeagueId = null;
	private $awayLeagueName = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return match date
	 *
	 * @param String $date (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return home team id
	 *
	 * @return Integer
	 */
	public function getHomeTeamId()
	{
		if(!isset($this->homeTeamId) || $this->homeTeamId === null)
		{
			$this->homeTeamId = $this->getXml()->getElementsByTagName('HomeTeam')->item(0)->getElementsByTagName('TeamId')->item(0)->nodeValue;
		}
		return $this->homeTeamId;
	}

	/**
	 * Return home team name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if(!isset($this->homeTeamName) || $this->homeTeamName === null)
		{
			$this->homeTeamName = $this->getXml()->getElementsByTagName('HomeTeam')->item(0)->getElementsByTagName('TeamName')->item(0)->nodeValue;
		}
		return $this->homeTeamName;
	}

	/**
	 * Return away team id
	 *
	 * @return Integer
	 */
	public function getAwayTeamId()
	{
		if(!isset($this->awayTeamId) || $this->awayTeamId === null)
		{
			$this->awayTeamId = $this->getXml()->getElementsByTagName('AwayTeam')->item(0)->getElementsByTagName('TeamId')->item(0)->nodeValue;
		}
		return $this->awayTeamId;
	}

	/**
	 * Return away team name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if(!isset($this->awayTeamName) || $this->awayTeamName === null)
		{
			$this->awayTeamName = $this->getXml()->getElementsByTagName('AwayTeam')->item(0)->getElementsByTagName('TeamName')->item(0)->nodeValue;
		}
		return $this->awayTeamName;
	}

	/**
	 * Is match result available ?
	 *
	 * @return Boolean
	 */
	public function isMatchResultAvailable()
	{
		if(!isset($this->matchResultsAvailable) || $this->matchResultsAvailable === null)
		{
			$this->matchResultsAvailable = $this->getXml()->getElementsByTagName('MatchResult')->item(0)->getAttribute('Available') == 'true';
		}
		return $this->matchResultsAvailable;
	}

	/**
	 * Return home goals
	 *
	 * @return Integer
	 */
	public function getHomeGoals()
	{
		if($this->isMatchResultAvailable())
		{
			if(!isset($this->homeGoals) || $this->homeGoals === null)
			{
				$this->homeGoals = $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
			}
			return $this->homeGoals;
		}
		return null;
	}

	/**
	 * Return away goals
	 *
	 * @return Integer
	 */
	public function getAwayGoals()
	{
		if($this->isMatchResultAvailable())
		{
			if(!isset($this->awayGoals) || $this->awayGoals === null)
			{
				$this->awayGoals = $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
			}
			return $this->awayGoals;
		}
		return null;
	}

	/**
	 * Is league info available ?
	 *
	 * @return Boolean
	 */
	public function isLeagueInfoAvailable()
	{
		if(!isset($this->leagueInfoAvailable) || $this->leagueInfoAvailable === null)
		{
			$this->leagueInfoAvailable = strtolower($this->getXml()->getElementsByTagName('LeagueInfo')->item(0)->getAttribute('Available')) == 'true';
		}
		return $this->leagueInfoAvailable;
	}

	/**
	 * Return home league id
	 *
	 * @return Integer
	 */
	public function getHomeLeagueId()
	{
		if($this->isLeagueInfoAvailable())
		{
			if(!isset($this->homeLeagueId) || $this->homeLeagueId === null)
			{
				$this->homeLeagueId = $this->getXml()->getElementsByTagName('HomeLeagueID')->item(0)->nodeValue;
			}
			return $this->homeLeagueId;
		}
		return null;
	}

	/**
	 * Return away league id
	 *
	 * @return Integer
	 */
	public function getAwayLeagueId()
	{
		if($this->isLeagueInfoAvailable())
		{
			if(!isset($this->awayLeagueId) || $this->awayLeagueId === null)
			{
				$this->awayLeagueId = $this->getXml()->getElementsByTagName('AwayLeagueID')->item(0)->nodeValue;
			}
			return $this->awayLeagueId;
		}
		return null;
	}

	/**
	 * Return home league name
	 *
	 * @return String
	 */
	public function getHomeLeagueName()
	{
		if($this->isLeagueInfoAvailable())
		{
			if(!isset($this->homeLeagueName) || $this->homeLeagueName === null)
			{
				$this->homeLeagueName = $this->getXml()->getElementsByTagName('HomeLeagueName')->item(0)->nodeValue;
			}
			return $this->homeLeagueName;
		}
		return null;
	}

	/**
	 * Return away league name
	 *
	 * @return String
	 */
	public function getAwayLeagueName()
	{
		if($this->isLeagueInfoAvailable())
		{
			if(!isset($this->awayLeagueName) || $this->awayLeagueName === null)
			{
				$this->awayLeagueName = $this->getXml()->getElementsByTagName('AwayLeagueName')->item(0)->nodeValue;
			}
			return $this->awayLeagueName;
		}
		return null;
	}
}
class HTSearch extends HTGlobal
{
	private $searchString = null;
	private $searchString2 = null;
	private $searchId = null;
	private $searchLeagueId = null;
	private $type = null;
	private $resultNumber = null;
	private $results = null;
	private $pageIndex = null;
	private $pages = null;

	/**
	 * Return search string
	 *
	 * @return String
	 */
	public function getSearchString()
	{
		if(!isset($this->searchString) || $this->searchString === null)
		{
			$this->searchString = $this->getXml()->getElementsByTagName('SearchString')->item(0)->nodeValue;
		}
		return $this->searchString;
	}

	/**
	 * Return search string 2
	 *
	 * @return String
	 */
	public function getSearchString2()
	{
		if(!isset($this->searchString2) || $this->searchString2 === null)
		{
			$this->searchString2 = $this->getXml()->getElementsByTagName('SearchString2')->item(0)->nodeValue;
		}
		return $this->searchString2;
	}

	/**
	 * Return what type of search was performed
	 *
	 * @return Integer
	 */
	public function getSearchType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('SearchType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return search id
	 *
	 * @return Integer
	 */
	public function getSearchId()
	{
		if(!isset($this->searchId) || $this->searchId === null)
		{
			$this->searchId = $this->getXml()->getElementsByTagName('SearchID')->item(0)->nodeValue;
		}
		return $this->searchId;
	}

	/**
	 * Return search league id
	 *
	 * @return Integer
	 */
	public function getSearchLeagueId()
	{
		if(!isset($this->searchLeagueId) || $this->searchLeagueId === null)
		{
			$this->searchLeagueId = $this->getXml()->getElementsByTagName('SearchLeagueID')->item(0)->nodeValue;
		}
		return $this->searchLeagueId;
	}

	/**
	 * Return page index
	 *
	 * @return Integer
	 */
	public function getPageIndex()
	{
		if(!isset($this->pageIndex) || $this->pageIndex === null)
		{
			$this->pageIndex = $this->getXml()->getElementsByTagName('PageIndex')->item(0)->nodeValue;
		}
		return $this->pageIndex;
	}

	/**
	 * Return number page of results
	 *
	 * @return Integer
	 */
	public function getPageNumber()
	{
		if(!isset($this->pages) || $this->pages === null)
		{
			$this->pages = $this->getXml()->getElementsByTagName('Pages')->item(0)->nodeValue;
		}
		return $this->pages;
	}

	/**
	 * Return number of results
	 *
	 * @return String
	 */
	public function getResultNumber()
	{
		if(!isset($this->resultNumber) || $this->resultNumber === null)
		{
			$this->resultNumber = $this->getXml()->getElementsByTagName('Result')->length;
		}
		return $this->resultNumber;
	}

	/**
	 * Return HTSearchResult object
	 *
	 * @param Integer $index
	 * @return HTSearchResult
	 */
	public function getResult($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getResultNumber())
		{
			--$index;
			if(!isset($this->results[$index]) || $this->results[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Result');
				$result = new DOMDocument('1.0', 'UTF-8');
				$result->appendChild($result->importNode($nodeList->item($index), true));
				$this->results[$index] = new HTSearchResult($result);
			}
			return $this->results[$index];
		}
		return null;
	}
}
class HTSearchResult extends HTXml
{
	private $id = null;
	private $name = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return result id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('ResultID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return result value
	 *
	 * @return String
	 */
	public function getValue()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('ResultName')->item(0)->nodeValue;
		}
		return $this->name;
	}
}
class HTBookmarksGlobal extends HTGlobal
{
	const ALL = 0;
	const TEAMS = 1;
	const PLAYERS = 2;
	const MATCHES = 3;
	const CONFUSERS = 4;
	const LEAGUES = 5;
	const YOUTHTEAMS = 6;
	const YOUTHPLAYERS = 7;
	const YOUTHMATCHS = 8;
	const YOUTHLEAGUES = 9;
	const CONFPOSTS = 10;
	const CONFTHREADS = 11;

	/**
	 * Return number of bookmarks
	 *
	 * @return Integer
	 */
	public function getBookarksNumber()
	{
		if(!isset($this->bookmarksNumber) || $this->bookmarksNumber === null)
		{
			$this->bookmarksNumber = $this->getXml()->getElementsByTagName('Bookmark')->length;
		}
		return $this->bookmarksNumber;
	}
}
class HTBookmarks extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmark object
	 *
	 * @param Integer $index
	 * @return HTBookmark
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmark($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkGlobal extends HTXml
{
	protected $id = null;
	protected $type = null;
	protected $comment = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return bookmark id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('BookmarkID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return bookmark type
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('BookmarkTypeID')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return bookmark comment
	 *
	 * @return String
	 */
	public function getComment()
	{
		if(!isset($this->comment) || $this->comment === null)
		{
			$this->comment = $this->getXml()->getElementsByTagName('Comment')->item(0)->nodeValue;
		}
		return $this->comment;
	}
}
class HTBookmark extends HTBookmarkGlobal
{
	private $text = null;
	private $text2 = null;
	private $objectId = null;
	private $objectId2 = null;
	private $objectId3 = null;

	/**
	 * Return bookmark text
	 *
	 * @return String
	 */
	public function getText()
	{
		if(!isset($this->text) || $this->text === null)
		{
			$this->text = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->text;
	}

	/**
	 * Return bookmark text2
	 *
	 * @return String
	 */
	public function getText2()
	{
		if(!isset($this->text2) || $this->text2 === null)
		{
			$this->text2 = $this->getXml()->getElementsByTagName('Text2')->item(0)->nodeValue;
		}
		return $this->text2;
	}

	/**
	 * Return bookmark objectId
	 *
	 * @return Integer
	 */
	public function getObjectId()
	{
		if(!isset($this->objectId) || $this->objectId === null)
		{
			$this->objectId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->objectId;
	}

	/**
	 * Return bookmark objectId2
	 *
	 * @return Integer
	 */
	public function getObjectId2()
	{
		if(!isset($this->objectId2) || $this->objectId2 === null)
		{
			$this->objectId2 = $this->getXml()->getElementsByTagName('ObjectID2')->item(0)->nodeValue;
		}
		return $this->objectId2;
	}

	/**
	 * Return bookmark objectId3
	 *
	 * @return Integer
	 */
	public function getObjectId3()
	{
		if(!isset($this->objectId3) || $this->objectId3 === null)
		{
			$this->objectId3 = $this->getXml()->getElementsByTagName('ObjectID3')->item(0)->nodeValue;
		}
		return $this->objectId3;
	}
}
class HTBookmarksTeams extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmarkTeam object
	 *
	 * @param Integer $index
	 * @return HTBookmarkTeam
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmarkTeam($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkTeam extends HTBookmarkGlobal
{
	private $teamName = null;
	private $teamId = null;
	private $alias = null;

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getTeamName()
	{
		if(!isset($this->teamName) || $this->teamName === null)
		{
			$this->teamName = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->teamName;
	}

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$this->teamId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->teamId;
	}

	/**
	 * Return team user's alias
	 *
	 * @return String
	 */
	public function getAlias()
	{
		if(!isset($this->alias) || $this->alias === null)
		{
			$this->alias = $this->getXml()->getElementsByTagName('Text2')->item(0)->nodeValue;
		}
		return $this->alias;
	}
}
class HTBookmarksPlayers extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmarkPlayer object
	 *
	 * @param Integer $index
	 * @return HTBookmarkPlayer
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmarkPlayer($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkPlayer extends HTBookmarkGlobal
{
	private $playerName = null;
	private $playerId = null;
	private $deadline = null;

	/**
	 * Return player name
	 *
	 * @return String
	 */
	public function getPlayerName()
	{
		if(!isset($this->playerName) || $this->playerName === null)
		{
			$this->playerName = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->playerName;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Return player deadline
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDeadline($format = null)
	{
		if(!isset($this->deadline[$format]) || $this->deadline[$format] === null)
		{
			$this->deadline[$format] = $this->getXml()->getElementsByTagName('Text2')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->deadline[$format] = HTFunction::convertDate($this->deadline[$format], $format);
			}
		}
		return $this->deadline[$format];
	}
}
class HTBookmarksMatches extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmarkMatch object
	 *
	 * @param Integer $index
	 * @return HTBookmarkMatch
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmarkMatch($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkMatch extends HTBookmarkGlobal
{
	private $teams = null;
	private $matchDate = null;
	private $matchId = null;

	/**
	 * Return teams names
	 *
	 * @return String
	 */
	public function getTeams()
	{
		if(!isset($this->teams) || $this->teams === null)
		{
			$this->teams = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->teams;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getMatchId()
	{
		if(!isset($this->matchId) || $this->matchId === null)
		{
			$this->matchId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->matchId;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getMatchDate($format = null)
	{
		if(!isset($this->matchDate[$format]) || $this->matchDate[$format] === null)
		{
			$this->matchDate[$format] = $this->getXml()->getElementsByTagName('Text2')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->matchDate[$format] = HTFunction::convertDate($this->matchDate[$format], $format);
			}
		}
		return $this->matchDate[$format];
	}
}
class HTBookmarksConfUsers extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmarkConfUser object
	 *
	 * @param Integer $index
	 * @return HTBookmarkConfUser
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmarkConfUser($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkConfUser extends HTBookmarkGlobal
{
	private $alias = null;
	private $userId = null;

	/**
	 * Return user alias
	 *
	 * @return String
	 */
	public function getAlias()
	{
		if(!isset($this->alias) || $this->alias === null)
		{
			$this->alias = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->alias;
	}

	/**
	 * Return user id
	 *
	 * @return Integer
	 */
	public function getUserId()
	{
		if(!isset($this->userId) || $this->userId === null)
		{
			$this->userId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->userId;
	}
}
class HTBookmarksLeagues extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmarkLeague object
	 *
	 * @param Integer $index
	 * @return HTBookmarkLeague
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmarkLeague($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkLeague extends HTBookmarkGlobal
{
	private $leagueName = null;
	private $leagueId = null;
	private $leagueLevelId = null;

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$this->leagueName = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->leagueName;
	}

	/**
	 * Return league level id
	 *
	 * @return Integer
	 */
	public function getLeagueLevelId()
	{
		if(!isset($this->leagueLevelId) || $this->leagueLevelId === null)
		{
			$this->leagueLevelId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->leagueLevelId;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('ObjectID2')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}
}
class HTBookmarksYouthTeams extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmarkYouthTeam object
	 *
	 * @param Integer $index
	 * @return HTBookmarkYouthTeam
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmarkYouthTeam($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkYouthTeam extends HTBookmarkGlobal
{
	private $teamName = null;
	private $teamId = null;

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getYouthTeamName()
	{
		if(!isset($this->teamName) || $this->teamName === null)
		{
			$this->teamName = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->teamName;
	}

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getYouthTeamId()
	{
		if(!isset($this->teamId) || $this->teamId === null)
		{
			$this->teamId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->teamId;
	}
}
class HTBookmarksYouthPlayers extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmarkYouthPlayer object
	 *
	 * @param Integer $index
	 * @return HTBookmarkYouthPlayer
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmarkYouthPlayer($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkYouthPlayer extends HTBookmarkGlobal
{
	private $playerName = null;
	private $playerId = null;

	/**
	 * Return player name
	 *
	 * @return String
	 */
	public function getYouthPlayerName()
	{
		if(!isset($this->playerName) || $this->playerName === null)
		{
			$this->playerName = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->playerName;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getYouthPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}
}
class HTBookmarksYouthMatches extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmarkYouthMatch object
	 *
	 * @param Integer $index
	 * @return HTBookmarkYouthMatch
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmarkYouthMatch($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkYouthMatch extends HTBookmarkGlobal
{
	private $teams = null;
	private $matchDate = null;
	private $matchId = null;

	/**
	 * Return youth teams names
	 *
	 * @return String
	 */
	public function getYouthTeams()
	{
		if(!isset($this->teams) || $this->teams === null)
		{
			$this->teams = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->teams;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getYouthMatchId()
	{
		if(!isset($this->matchId) || $this->matchId === null)
		{
			$this->matchId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->matchId;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getYouthMatchDate($format = null)
	{
		if(!isset($this->matchDate[$format]) || $this->matchDate[$format] === null)
		{
			$this->matchDate[$format] = $this->getXml()->getElementsByTagName('Text2')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->matchDate[$format] = HTFunction::convertDate($this->matchDate[$format], $format);
			}
		}
		return $this->matchDate[$format];
	}
}
class HTBookmarksYouthLeagues extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmarkYouthLeague object
	 *
	 * @param Integer $index
	 * @return HTBookmarkYouthLeague
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmarkYouthLeague($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkYouthLeague extends HTBookmarkGlobal
{
	private $leagueName = null;
	private $leagueId = null;

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getYouthLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$this->leagueName = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->leagueName;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getYouthLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}
}
class HTBookmarksConfPosts extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmarkConfPost object
	 *
	 * @param Integer $index
	 * @return HTBookmarkConfPost
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmarkConfPost($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkConfPost extends HTBookmarkGlobal
{
	private $subject = null;
	private $poster = null;
	private $threadId = null;
	private $userId = null;

	/**
	 * Return post subject
	 *
	 * @return String
	 */
	public function getSubject()
	{
		if(!isset($this->subject) || $this->subject === null)
		{
			$this->subject = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->subject;
	}

	/**
	 * Return poster name
	 *
	 * @return String
	 */
	public function getPoster()
	{
		if(!isset($this->poster) || $this->poster === null)
		{
			$this->poster = $this->getXml()->getElementsByTagName('Text2')->item(0)->nodeValue;
		}
		return $this->poster;
	}

	/**
	 * Return thread id
	 *
	 * @return Integer
	 */
	public function getThreadId()
	{
		if(!isset($this->threadId) || $this->threadId === null)
		{
			$this->threadId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->threadId;
	}

	/**
	 * Return poster user id
	 *
	 * @return Integer
	 */
	public function getUserId()
	{
		if(!isset($this->userId) || $this->userId === null)
		{
			$this->userId = $this->getXml()->getElementsByTagName('ObjectID2')->item(0)->nodeValue;
		}
		return $this->userId;
	}
}
class HTBookmarksConfThreads extends HTBookmarksGlobal
{
	protected $bookmarksNumber = null;
	private $bookmarks = null;

	/**
	 * Return HTBookmarkConfThread object
	 *
	 * @param Integer $index
	 * @return HTBookmarkConfThread
	 */
	public function getBookmark($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getBookarksNumber())
		{
			--$index;
			if(!isset($this->bookmarks[$index]) || $this->bookmarks[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Bookmark');
				$bookmark = new DOMDocument('1.0', 'UTF-8');
				$bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
				$this->bookmarks[$index] = new HTBookmarkConfThread($bookmark);
			}
			return $this->bookmarks[$index];
		}
		return null;
	}
}
class HTBookmarkConfThread extends HTBookmarkGlobal
{
	private $subject = null;
	private $poster = null;
	private $threadId = null;
	private $userId = null;
	private $messageId = null;

	/**
	 * Return post subject
	 *
	 * @return String
	 */
	public function getSubject()
	{
		if(!isset($this->subject) || $this->subject === null)
		{
			$this->subject = $this->getXml()->getElementsByTagName('Text')->item(0)->nodeValue;
		}
		return $this->subject;
	}

	/**
	 * Return poster name
	 *
	 * @return String
	 */
	public function getPoster()
	{
		if(!isset($this->poster) || $this->poster === null)
		{
			$this->poster = $this->getXml()->getElementsByTagName('Text2')->item(0)->nodeValue;
		}
		return $this->poster;
	}

	/**
	 * Return thread id
	 *
	 * @return Integer
	 */
	public function getThreadId()
	{
		if(!isset($this->threadId) || $this->threadId === null)
		{
			$this->threadId = $this->getXml()->getElementsByTagName('ObjectID')->item(0)->nodeValue;
		}
		return $this->threadId;
	}

	/**
	 * Return message id
	 *
	 * @return Integer
	 */
	public function getMessageId()
	{
		if(!isset($this->messageId) || $this->messageId === null)
		{
			$this->messageId = $this->getXml()->getElementsByTagName('ObjectID2')->item(0)->nodeValue;
		}
		return $this->messageId;
	}

	/**
	 * Return poster user id
	 *
	 * @return Integer
	 */
	public function getUserId()
	{
		if(!isset($this->userId) || $this->userId === null)
		{
			$this->userId = $this->getXml()->getElementsByTagName('ObjectID3')->item(0)->nodeValue;
		}
		return $this->userId;
	}
}
class HTTrainingEvents extends HTGlobal
{
	private $playerId = null;
	private $eventsNumber = null;
	private $eventsAvailable = null;
	private $events = null;

	/**
	 * Is events available for this player ?
	 *
	 * @return Boolean
	 */
	public function isEventsAvailable()
	{
		if(!isset($this->eventsAvailable) || $this->eventsAvailable === null)
		{
			$this->eventsAvailable = strtolower($this->getXml()->getElementsByTagName('TrainingEvents')->item(0)->getAttribute('Available')) == 'true';
		}
		return $this->eventsAvailable;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Return number of training events
	 *
	 * @return Integer
	 */
	public function getEventsNumber()
	{
		if($this->isEventsAvailable())
		{
			if(!isset($this->eventsNumber) || $this->eventsNumber === null)
			{
				$this->eventsNumber = $this->getXml()->getElementsByTagName('TrainingEvent')->length;
			}
			return $this->eventsNumber;
		}
		return null;
	}

	/**
	 * Return HTTrainingEvent object
	 *
	 * @param Integer $index
	 * @return HTTrainingEvent
	 */
	public function getEvent($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getEventsNumber())
		{
			--$index;
			if(!isset($this->events[$index]) || $this->events[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//TrainingEvent');
				$event = new DOMDocument('1.0', 'UTF-8');
				$event->appendChild($event->importNode($nodeList->item($index), true));
				$this->events[$index] = new HTTrainingEvent($event);
			}
			return $this->events[$index];
		}
		return null;
	}
}
class HTTrainingEvent extends HTXml
{
	private $date = null;
	private $skillId = null;
	private $oldLevel = null;
	private $newLevel = null;
	private $season = null;
	private $matchRound = null;
	private $day = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return event date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('EventDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return skill id
	 *
	 * @return Integer
	 */
	public function getSkillId()
	{
		if(!isset($this->skillId) || $this->skillId === null)
		{
			$this->skillId = $this->getXml()->getElementsByTagName('SkillID')->item(0)->nodeValue;
		}
		return $this->skillId;
	}

	/**
	 * Return old level
	 *
	 * @return Integer
	 */
	public function getOldLevel()
	{
		if(!isset($this->oldLevel) || $this->oldLevel === null)
		{
			$this->oldLevel = $this->getXml()->getElementsByTagName('OldLevel')->item(0)->nodeValue;
		}
		return $this->oldLevel;
	}

	/**
	 * Return new level
	 *
	 * @return Integer
	 */
	public function getNewLevel()
	{
		if(!isset($this->newLevel) || $this->newLevel === null)
		{
			$this->newLevel = $this->getXml()->getElementsByTagName('NewLevel')->item(0)->nodeValue;
		}
		return $this->newLevel;
	}

	/**
	 * Return season number
	 *
	 * @return Integer
	 */
	public function getSeason()
	{
		if(!isset($this->season) || $this->season === null)
		{
			$this->season = $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
		}
		return $this->season;
	}

	/**
	 * Return match round
	 *
	 * @return Integer
	 */
	public function getMatchRound()
	{
		if(!isset($this->matchRound) || $this->matchRound === null)
		{
			$this->matchRound = $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
		}
		return $this->matchRound;
	}

	/**
	 * Return day number
	 *
	 * @return Integer
	 */
	public function getDay()
	{
		if(!isset($this->day) || $this->day === null)
		{
			$this->day = $this->getXml()->getElementsByTagName('DayNumber')->item(0)->nodeValue;
		}
		return $this->day;
	}
}
class HTPlayerEvents extends HTGlobal
{
	private $playerId = null;
	private $eventsNumber = null;
	private $eventsAvailable = null;
	private $events = null;

	/**
	 * Is events available for this player ?
	 *
	 * @return Boolean
	 */
	public function isEventsAvailable()
	{
		if(!isset($this->eventsAvailable) || $this->eventsAvailable === null)
		{
			$this->eventsAvailable = strtolower($this->getXml()->getElementsByTagName('PlayerEvents')->item(0)->getAttribute('Available')) == 'true';
		}
		return $this->eventsAvailable;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Return number of training events
	 *
	 * @return Integer
	 */
	public function getEventsNumber()
	{
		if($this->isEventsAvailable())
		{
			if(!isset($this->eventsNumber) || $this->eventsNumber === null)
			{
				$this->eventsNumber = $this->getXml()->getElementsByTagName('PlayerEvent')->length;
			}
			return $this->eventsNumber;
		}
		return null;
	}

	/**
	 * Return HTPlayerEvent object
	 *
	 * @param Integer $index
	 * @return HTTrainingEvent
	 */
	public function getEvent($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getEventsNumber())
		{
			--$index;
			if(!isset($this->events[$index]) || $this->events[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//PlayerEvent');
				$event = new DOMDocument('1.0', 'UTF-8');
				$event->appendChild($event->importNode($nodeList->item($index), true));
				$this->events[$index] = new HTPlayerEvent($event);
			}
			return $this->events[$index];
		}
		return null;
	}
}
class HTPlayerEvent extends HTXml
{
	private $date = null;
	private $typeId = null;
	private $text = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return event date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('EventDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return event type id
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->typeId) || $this->typeId === null)
		{
			$this->typeId = $this->getXml()->getElementsByTagName('PlayerEventTypeID')->item(0)->nodeValue;
		}
		return $this->typeId;
	}

	/**
	 * Return event text
	 *
	 * @return String
	 */
	public function getText()
	{
		if(!isset($this->text) || $this->text === null)
		{
			$this->text = $this->getXml()->getElementsByTagName('EventText')->item(0)->nodeValue;
		}
		return $this->text;
	}
}
class HTAllianceCommon extends HTGlobal
{
	protected $id = null;
	protected $name = null;

	/**
	 * Return alliance id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('AllianceID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return alliance name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('AllianceName')->item(0)->nodeValue;
		}
		return $this->name;
	}
}
class HTAllianceDetails extends HTAllianceCommon
{
	private $abbreviation = null;
	private $description = null;
	private $logoUrl = null;
	private $topRole = null;
	private $topUserId = null;
	private $topLoginName = null;
	private $creationDate = null;
	private $homepageUrl = null;
	private $numberMembers = null;
	private $numberLanguages = null;
	private $languages = null;
	private $message = null;
	private $numberUsersLogged = null;
	private $usersLogged = null;
	private $rules = null;
	private $roleId = null;
	private $roleName = null;
	private $awaitingRequests = null;
	private $hasRightToAcceptRequests = null;
	private $hasRightToSendNewsLetter = null;
	private $hasRightToCreatePolls = null;
	private $hasRightToEditPublicProperties = null;
	private $hasRightToExcludeMembers = null;
	private $hasRightToCreateRoles = null;
	private $hasRightToEditRoles = null;
	private $hasRightToModerate = null;
	private $hasRightToEditRules = null;
	private $dissolutionEndDate = null;

	/**
	 * Return alliance abbreviation
	 *
	 * @return String
	 */
	public function getAbbreviation()
	{
		if(!isset($this->abbreviation) || $this->abbreviation === null)
		{
			$node = $this->getXml()->getElementsByTagName('Abbreviation');
			if($node !== null && $node->length)
			{
				$this->abbreviation = $node->item(0)->nodeValue;
			}
		}
		return $this->abbreviation;
	}

	/**
	 * Return alliance description
	 *
	 * @return String
	 */
	public function getDescription()
	{
		if(!isset($this->description) || $this->description === null)
		{
			$this->description = $this->getXml()->getElementsByTagName('Description')->item(0)->nodeValue;
		}
		return $this->description;
	}

	/**
	 * Return alliance top role name
	 *
	 * @return String
	 */
	public function getTopRole()
	{
		if(!isset($this->topRole) || $this->topRole === null)
		{
			$this->topRole = $this->getXml()->getElementsByTagName('TopRole')->item(0)->nodeValue;
		}
		return $this->topRole;
	}

	/**
	 * Return alliance top user id
	 *
	 * @return Integer
	 */
	public function getTopUserId()
	{
		if(!isset($this->topUserId) || $this->topUserId === null)
		{
			$this->topUserId = $this->getXml()->getElementsByTagName('TopUserID')->item(0)->nodeValue;
		}
		return $this->topUserId;
	}

	/**
	 * Return alliance top user loginname
	 *
	 * @return String
	 */
	public function getTopLoginName()
	{
		if(!isset($this->topLoginName) || $this->topLoginName === null)
		{
			$this->topLoginName = $this->getXml()->getElementsByTagName('TopLoginname')->item(0)->nodeValue;
		}
		return $this->topLoginName;
	}

	/**
	 * Return alliance creation date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getCreationDate($format = null)
	{
		if(!isset($this->creationDate[$format]) || $this->creationDate[$format] === null)
		{
			$this->creationDate[$format] = $this->getXml()->getElementsByTagName('CreationDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->creationDate[$format] = HTFunction::convertDate($this->creationDate[$format], $format);
			}
		}
		return $this->creationDate[$format];
	}

	/**
	 * Return alliance homepage url
	 *
	 * @return String
	 */
	public function getHomepageUrl()
	{
		if(!isset($this->homepageUrl) || $this->homepageUrl === null)
		{
			$node = $this->getXml()->getElementsByTagName('HomePageURL');
			if($node !== null && $node->length)
			{
				$this->homepageUrl = $node->item(0)->nodeValue;
			}
		}
		return $this->homepageUrl;
	}

	/**
	 * Return alliance message
	 *
	 * @return String
	 */
	public function getMessage()
	{
		if(!isset($this->message) || $this->message === null)
		{
			$node = $this->getXml()->getElementsByTagName('Message');
			if($node !== null && $node->length)
			{
				$this->message = $node->item(0)->nodeValue;
			}
		}
		return $this->message;
	}

	/**
	 * Return alliance logo url
	 *
	 * @return String
	 */
	public function getLogoUrl()
	{
		if(!isset($this->logoUrl) || $this->logoUrl === null)
		{
			$node = $this->getXml()->getElementsByTagName('LogoURL');
			if($node !== null && $node->length)
			{
				$this->logoUrl = $node->item(0)->nodeValue;
			}
		}
		return $this->logoUrl;
	}

	/**
	 * Return alliance members number
	 *
	 * @return Integer
	 */
	public function getNumberMembers()
	{
		if(!isset($this->numberMembers) || $this->numberMembers === null)
		{
			$this->numberMembers = $this->getXml()->getElementsByTagName('NumberOfMembers')->item(0)->nodeValue;
		}
		return $this->numberMembers;
	}

	/**
	 * Return alliance languages number
	 *
	 * @return Integer
	 */
	public function getNumberLanguages()
	{
		if(!isset($this->numberLanguages) || $this->numberLanguages === null)
		{
			$this->numberLanguages = $this->getXml()->getElementsByTagName('Language')->length;
		}
		return $this->numberLanguages;
	}

	/**
	 * Return HTWorldLanguage object
	 *
	 * @param Integer $index
	 * @return HTWorldLanguage
	 */
	public function getLanguage($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberLanguages())
		{
			--$index;
			if(!isset($this->languages[$index]) || $this->languages[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Language');
				$lang = new DOMDocument('1.0', 'UTF-8');
				$lang->appendChild($lang->importNode($nodeList->item($index), true));
				$this->languages[$index] = new HTWorldLanguage($lang);
			}
			return $this->languages[$index];
		}
		return null;
	}

	/**
	 * Return alliance languages number
	 *
	 * @return Integer
	 */
	public function getNumberUsersLogged()
	{
		if(!isset($this->numberUsersLogged) || $this->numberUsersLogged === null)
		{
			$this->numberUsersLogged = $this->getXml()->getElementsByTagName('LoggedInUsers')->item(0)->getAttribute('Count');
		}
		return $this->numberUsersLogged;
	}

	/**
	 * Return HTWorldLanguage object
	 *
	 * @param Integer $index
	 * @return HTWorldLanguage
	 */
	public function getUserLogged($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberUsersLogged())
		{
			--$index;
			if(!isset($this->usersLogged[$index]) || $this->usersLogged[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//LoggedInUser');
				$user = new DOMDocument('1.0', 'UTF-8');
				$user->appendChild($user->importNode($nodeList->item($index), true));
				$this->usersLogged[$index] = new HTUser($user);
			}
			return $this->usersLogged[$index];
		}
		return null;
	}

	/**
	 * Return alliance rules
	 *
	 * @return String
	 */
	public function getRules()
	{
		if(!isset($this->rules) || $this->rules === null)
		{
			$this->rules = $this->getXml()->getElementsByTagName('Rules')->item(0)->nodeValue;
		}
		return $this->rules;
	}

	/**
	 * Return user role id
	 *
	 * @return Integer
	 */
	public function getRoleId()
	{
		if(!isset($this->roleId) || $this->roleId === null)
		{
			$this->roleId = $this->getXml()->getElementsByTagName('RoleID')->item(0)->nodeValue;
		}
		return $this->roleId;
	}

	/**
	 * Return user role name
	 *
	 * @return String
	 */
	public function getRoleName()
	{
		if(!isset($this->roleName) || $this->roleName === null)
		{
			$this->roleName = $this->getXml()->getElementsByTagName('RoleName')->item(0)->nodeValue;
		}
		return $this->roleName;
	}

	/**
	 * Return number of awaiting requests
	 *
	 * @return Integer
	 */
	public function getNumberAwaitingRequests()
	{
		if(!isset($this->awaitingRequests) || $this->awaitingRequests === null)
		{
			$node = $this->getXml()->getElementsByTagName('AwaitingRequests');
			if($node !== null && $node->length)
			{
				$this->awaitingRequests = $node->item(0)->nodeValue;
			}
		}
		return $this->awaitingRequests;
	}

	/**
	 * Does user have right to accept requests ?
	 *
	 * @return Boolean
	 */
	public function userHasRightToAcceptRequests()
	{
		if(!isset($this->hasRightToAcceptRequests) || $this->hasRightToAcceptRequests === null)
		{
			$node = $this->getXml()->getElementsByTagName('HasRightToAcceptRequests');
			if($node !== null && $node->length)
			{
				$this->hasRightToAcceptRequests = strtolower($node->item(0)->nodeValue) == "true";
			}
			else
			{
				$this->hasRightToAcceptRequests = false;
			}
		}
		return $this->hasRightToAcceptRequests;
	}

	/**
	 * Does user have right to edit public properties ?
	 *
	 * @return Boolean
	 */
	public function userHasRightToEditPublicProperties()
	{
		if(!isset($this->hasRightToEditPublicProperties) || $this->hasRightToEditPublicProperties === null)
		{
			$node = $this->getXml()->getElementsByTagName('HasRightToEditPublicProperties');
			if($node !== null && $node->length)
			{
				$this->hasRightToEditPublicProperties = strtolower($node->item(0)->nodeValue) == "true";
			}
			else
			{
				$this->hasRightToEditPublicProperties = false;
			}
		}
		return $this->hasRightToEditPublicProperties;
	}

	/**
	 * Does user have right to exclude members ?
	 *
	 * @return Boolean
	 */
	public function userHasRightToExcludeMembers()
	{
		if(!isset($this->hasRightToExcludeMembers) || $this->hasRightToExcludeMembers === null)
		{
			$node = $this->getXml()->getElementsByTagName('HasRightToExcludeMembers');
			if($node !== null && $node->length)
			{
				$this->hasRightToExcludeMembers = strtolower($node->item(0)->nodeValue) == "true";
			}
			else
			{
				$this->hasRightToExcludeMembers = false;
			}
		}
		return $this->hasRightToExcludeMembers;
	}

	/**
	 * Does user have right to create roles ?
	 *
	 * @return Boolean
	 */
	public function userHasRightToCreateRoles()
	{
		if(!isset($this->hasRightToCreateRoles) || $this->hasRightToCreateRoles === null)
		{
			$node = $this->getXml()->getElementsByTagName('HasRightToCreateRoles');
			if($node !== null && $node->length)
			{
				$this->hasRightToCreateRoles = strtolower($node->item(0)->nodeValue) == "true";
			}
			else
			{
				$this->hasRightToCreateRoles = false;
			}
		}
		return $this->hasRightToCreateRoles;
	}

	/**
	 * Does user have right to edit roles ?
	 *
	 * @return Boolean
	 */
	public function userHasRightToEditRoles()
	{
		if(!isset($this->hasRightToEditRoles) || $this->hasRightToEditRoles === null)
		{
			$node = $this->getXml()->getElementsByTagName('HasRightToEditRoles');
			if($node !== null && $node->length)
			{
				$this->hasRightToEditRoles = strtolower($node->item(0)->nodeValue) == "true";
			}
			else
			{
				$this->hasRightToEditRoles = false;
			}
		}
		return $this->hasRightToEditRoles;
	}

	/**
	 * Does user have right to moderate ?
	 *
	 * @return Boolean
	 */
	public function userHasRightToModerate()
	{
		if(!isset($this->hasRightToModerate) || $this->hasRightToModerate === null)
		{
			$node = $this->getXml()->getElementsByTagName('HasRightToModerate');
			if($node !== null && $node->length)
			{
				$this->hasRightToModerate = strtolower($node->item(0)->nodeValue) == "true";
			}
			else
			{
				$this->hasRightToModerate = false;
			}
		}
		return $this->hasRightToModerate;
	}

	/**
	 * Does user have right to send newsletter ?
	 *
	 * @return Boolean
	 */
	public function userHasRightToSendNewsLetter()
	{
		if(!isset($this->hasRightToSendNewsLetter) || $this->hasRightToSendNewsLetter === null)
		{
			$node = $this->getXml()->getElementsByTagName('HasRightToSendNewsLetter');
			if($node !== null && $node->length)
			{
				$this->hasRightToSendNewsLetter = strtolower($node->item(0)->nodeValue) == "true";
			}
			else
			{
				$this->hasRightToSendNewsLetter = false;
			}
		}
		return $this->hasRightToSendNewsLetter;
	}

	/**
	 * Does user have right to create polls ?
	 *
	 * @return Boolean
	 */
	public function userHasRightToCreatePolls()
	{
		if(!isset($this->hasRightToCreatePolls) || $this->hasRightToCreatePolls === null)
		{
			$node = $this->getXml()->getElementsByTagName('HasRightToCreatePolls');
			if($node !== null && $node->length)
			{
				$this->hasRightToCreatePolls = strtolower($node->item(0)->nodeValue) == "true";
			}
			else
			{
				$this->hasRightToCreatePolls = false;
			}
		}
		return $this->hasRightToCreatePolls;
	}

	/**
	 * Does user have right to edit rules ?
	 *
	 * @return Boolean
	 */
	public function userHasRightToEditRules()
	{
		if(!isset($this->hasRightToEditRules) || $this->hasRightToEditRules === null)
		{
			$node = $this->getXml()->getElementsByTagName('HasRightToEditRules');
			if($node !== null && $node->length)
			{
				$this->hasRightToEditRules = strtolower($node->item(0)->nodeValue) == "true";
			}
			else
			{
				$this->hasRightToEditRules = false;
			}
		}
		return $this->hasRightToEditRules;
	}

	/**
	 * Return dissolution date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDissolutionDate($format = null)
	{
		if(!isset($this->dissolutionEndDate[$format]) || $this->dissolutionEndDate[$format] === null)
		{
			$node = $this->getXml()->getElementsByTagName('DissolutionEndDate');
			if($node !== null && $node->length)
			{
				$this->dissolutionEndDate[$format] = $node->item(0)->nodeValue;
				if($format !== null)
				{
					$this->dissolutionEndDate[$format] = HTFunction::convertDate($this->dissolutionEndDate[$format], $format);
				}
			}
		}
		return $this->dissolutionEndDate[$format];
	}
}
class HTUser extends HTXml
{
	protected $id = null;
	protected $loginName = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return user id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('UserID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return user alias
	 *
	 * @return String
	 */
	public function getAlias()
	{
		if(!isset($this->loginName) || $this->loginName === null)
		{
			$this->loginName = $this->getXml()->getElementsByTagName('Loginname')->item(0)->nodeValue;
		}
		return $this->loginName;
	}
}
class HTAllianceMembers extends HTAllianceCommon
{
	private $numberMembers = null;
	private $members = null;

	/**
	 * Return number of members
	 *
	 * @return Integer
	 */
	public function getNumberMembers()
	{
		if(!isset($this->numberMembers) || $this->numberMembers === null)
		{
			$this->numberMembers = $this->getXml()->getElementsByTagName('Member')->length;
		}
		return $this->numberMembers;
	}

	/**
	 * Return HTAllianceMember object
	 *
	 * @param Integer $index
	 * @return HTAllianceMember
	 */
	public function getMember($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberMembers())
		{
			--$index;
			if(!isset($this->members[$index]) || $this->members[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Member');
				$member = new DOMDocument('1.0', 'UTF-8');
				$member->appendChild($member->importNode($nodeList->item($index), true));
				$this->members[$index] = new HTAllianceMember($member);
			}
			return $this->members[$index];
		}
		return null;
	}
}
class HTAllianceMember extends HTUser
{
	private $roleId = null;
	private $roleName = null;
	private $shipDate = null;

	/**
	 * Return user role id
	 *
	 * @return String
	 */
	public function getRoleId()
	{
		if(!isset($this->roleId) || $this->roleId === null)
		{
			$this->roleId = $this->getXml()->getElementsByTagName('RoleID')->item(0)->nodeValue;
		}
		return $this->roleId;
	}

	/**
	 * Return user role name
	 *
	 * @return String
	 */
	public function getRoleName()
	{
		if(!isset($this->roleName) || $this->roleName === null)
		{
			$this->roleName = $this->getXml()->getElementsByTagName('RoleName')->item(0)->nodeValue;
		}
		return $this->roleName;
	}

	/**
	 * Return user ship date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getShipDate($format = null)
	{
		if(!isset($this->shipDate[$format]) || $this->shipDate[$format] === null)
		{
			$this->shipDate[$format] = $this->getXml()->getElementsByTagName('MemberShipDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->shipDate[$format] = HTFunction::convertDate($this->shipDate[$format], $format);
			}
		}
		return $this->shipDate[$format];
	}
}
class HTAllianceRoles extends HTAllianceCommon
{
	private $numberRoles = null;
	private $roles = null;

	/**
	 * Return number of roles
	 *
	 * @return Integer
	 */
	public function getNumberRoles()
	{
		if(!isset($this->numberRoles) || $this->numberRoles === null)
		{
			$this->numberRoles = $this->getXml()->getElementsByTagName('Role')->length;
		}
		return $this->numberRoles;
	}

	/**
	 * Return HTAllianceRole object
	 *
	 * @param Integer $index
	 * @return HTAllianceRole
	 */
	public function getRole($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberRoles())
		{
			--$index;
			if(!isset($this->roles[$index]) || $this->roles[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Role');
				$role = new DOMDocument('1.0', 'UTF-8');
				$role->appendChild($role->importNode($nodeList->item($index), true));
				$this->roles[$index] = new HTAllianceRole($role);
			}
			return $this->roles[$index];
		}
		return null;
	}
}
class HTAllianceRole extends HTXml
{
	private $id = null;
	private $name = null;
	private $rank = null;
	private $memberCount = null;
	private $maxMembers = null;
	private $request = null;
	private $description = null;

	/**
	 * Return role id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('RoleID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return role name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('RoleName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return role rank
	 *
	 * @return Integer
	 */
	public function getRank()
	{
		if(!isset($this->rank) || $this->rank === null)
		{
			$this->rank = $this->getXml()->getElementsByTagName('RoleRank')->item(0)->nodeValue;
		}
		return $this->rank;
	}

	/**
	 * Return number of member
	 *
	 * @return Integer
	 */
	public function getNumberMember()
	{
		if(!isset($this->memberCount) || $this->memberCount === null)
		{
			$this->memberCount = $this->getXml()->getElementsByTagName('RoleMemberCount')->item(0)->nodeValue;
		}
		return $this->memberCount;
	}

	/**
	 * Return maximum number of member
	 *
	 * @return Integer
	 */
	public function getMaximumMember()
	{
		if(!isset($this->maxMembers) || $this->maxMembers === null)
		{
			$this->maxMembers = $this->getXml()->getElementsByTagName('RoleMaxMembers')->item(0)->nodeValue;
		}
		return $this->maxMembers;
	}

	/**
	 * Return the way to become member
	 *
	 * @return Integer
	 */
	public function getRequestType()
	{
		if(!isset($this->request) || $this->request === null)
		{
			$this->request = $this->getXml()->getElementsByTagName('RoleRequestType')->item(0)->nodeValue;
		}
		return $this->request;
	}

	/**
	 * Return role description
	 *
	 * @return String
	 */
	public function getDescription()
	{
		if(!isset($this->description) || $this->description === null)
		{
			$this->description = $this->getXml()->getElementsByTagName('RoleDescription')->item(0)->nodeValue;
		}
		return $this->description;
	}
}
class HTFans extends HTGlobal
{
	private $mood = null;
	private $members = null;
	private $seasonExpectation = null;
	private $numberPlayedMatches = null;
	private $playedMatches = null;
	private $numberUpcomingMatches = null;
	private $upcomingMatches = null;
	private $fanclubid = null;
	private $fanclubname = null;

	/**
	 * Return fan club ib
	 *
	 * @return Integer
	 */
	public function getFanClubId()
	{
		if(!isset($this->fanclubid) || $this->fanclubid === null)
		{
			$this->fanclubid = $this->getXml()->getElementsByTagName('FanclubId')->item(0)->nodeValue;
		}
		return $this->fanclubid;
	}

	/**
	 * Return fan club name
	 *
	 * @return String
	 */
	public function getFanClubName()
	{
		if(!isset($this->fanclubname) || $this->fanclubname === null)
		{
			$this->fanclubname = $this->getXml()->getElementsByTagName('FanclubName')->item(0)->nodeValue;
		}
		return $this->fanclubname;
	}

	/**
	 * Return fan mood
	 *
	 * @return Integer
	 */
	public function getMood()
	{
		if(!isset($this->mood) || $this->mood === null)
		{
			$this->mood = $this->getXml()->getElementsByTagName('FanMood')->item(0)->nodeValue;
		}
		return $this->mood;
	}

	/**
	 * Return fan seasonExpectation
	 *
	 * @return Integer
	 */
	public function getSeasonExpectation()
	{
		if(!isset($this->seasonExpectation) || $this->seasonExpectation === null)
		{
			$this->seasonExpectation = $this->getXml()->getElementsByTagName('FanSeasonExpectation')->item(0)->nodeValue;
		}
		return $this->seasonExpectation;
	}

	/**
	 * Return fans number
	 *
	 * @return Integer
	 */
	public function getMembers()
	{
		if(!isset($this->members) || $this->members === null)
		{
			$this->members = $this->getXml()->getElementsByTagName('Members')->item(0)->nodeValue;
		}
		return $this->members;
	}

	/**
	 * Return number of played matches
	 *
	 * @return Integer
	 */
	public function getNumberPlayedMatches()
	{
		if(!isset($this->numberPlayedMatches) || $this->numberPlayedMatches === null)
		{
			$node = $this->getXml()->getElementsByTagName('PlayedMatches');
			$matchs = new DOMDocument('1.0', 'UTF-8');
			$matchs->appendChild($matchs->importNode($node->item(0), true));
			$this->numberPlayedMatches = $matchs->getElementsByTagName('Match')->length;
		}
		return $this->numberPlayedMatches;
	}

	/**
	 * Return HTFansPlayedMatch object
	 *
	 * @param Integer $index
	 * @return HTFansPlayedMatch
	 */
	public function getPlayedMatch($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberPlayedMatches())
		{
			--$index;
			if(!isset($this->playedMatches[$index]) || $this->playedMatches[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//PlayedMatches/Match');
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->playedMatches[$index] = new HTFansPlayedMatch($node);
			}
			return $this->playedMatches[$index];
		}
		return null;
	}

	/**
	 * Return number of upcoming matches
	 *
	 * @return Integer
	 */
	public function getNumberUpcomingMatches()
	{
		if(!isset($this->numberUpcomingMatches) || $this->numberUpcomingMatches === null)
		{
			$node = $this->getXml()->getElementsByTagName('UpcomingMatches');
			$matchs = new DOMDocument('1.0', 'UTF-8');
			$matchs->appendChild($matchs->importNode($node->item(0), true));
			$this->numberUpcomingMatches = $matchs->getElementsByTagName('Match')->length;
		}
		return $this->numberUpcomingMatches;
	}

	/**
	 * Return HTFansUpcomingMatch object
	 *
	 * @param Integer $index
	 * @return HTFansUpcomingMatch
	 */
	public function getUpcomingMatch($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberUpcomingMatches())
		{
			--$index;
			if(!isset($this->upcomingMatches[$index]) || $this->upcomingMatches[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//UpcomingMatches/Match');
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->upcomingMatches[$index] = new HTFansUpcomingMatch($node);
			}
			return $this->upcomingMatches[$index];
		}
		return null;
	}
}
class HTFansPlayedMatch extends HTFansUpcomingMatch
{
	private $homeGoals = null;
	private $awayGoals = null;
	private $fanMoodAfterMatch = null;
	private $weather = null;
	private $affluence = null;

	/**
	 * Return home goals number
	 *
	 * @return Integer
	 */
	public function getHomeGoals()
	{
		if(!isset($this->homeGoals) || $this->homeGoals === null)
		{
			$this->homeGoals = $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
		}
		return $this->homeGoals;
	}

	/**
	 * Return away goals number
	 *
	 * @return Integer
	 */
	public function getAwayGoals()
	{
		if(!isset($this->awayGoals) || $this->awayGoals === null)
		{
			$this->awayGoals = $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
		}
		return $this->awayGoals;
	}

	/**
	 * Return fan expectation
	 *
	 * @return Integer
	 */
	public function getFanMoodAfter()
	{
		if(!isset($this->fanMoodAfterMatch) || $this->fanMoodAfterMatch === null)
		{
			$this->fanMoodAfterMatch = $this->getXml()->getElementsByTagName('FanMoodAfterMatch')->item(0)->nodeValue;
		}
		return $this->fanMoodAfterMatch;
	}

	/**
	 * Return match weather
	 *
	 * @return Integer
	 */
	public function getWeather()
	{
		if(!isset($this->weather) || $this->weather === null)
		{
			$this->weather = $this->getXml()->getElementsByTagName('Weather')->item(0)->nodeValue;
		}
		return $this->weather;
	}

	/**
	 * Return match affluence
	 *
	 * @return Integer
	 */
	public function getAffluence()
	{
		if(!isset($this->affluence) || $this->affluence === null)
		{
			$this->affluence = $this->getXml()->getElementsByTagName('SoldSeats')->item(0)->nodeValue;
		}
		return $this->affluence;
	}

}
class HTFansUpcomingMatch extends HTXml
{
	private $matchId = null;
	private $homeTeamId = null;
	private $homeTeamName = null;
	private $awayTeamId = null;
	private $awayTeamName = null;
	private $matchDate = null;
	private $matchType = null;
	private $fanMatchExpectation = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->matchId) || $this->matchId === null)
		{
			$this->matchId = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->matchId;
	}

	/**
	 * Return home team id
	 *
	 * @return Integer
	 */
	public function getHomeTeamId()
	{
		if(!isset($this->homeTeamId) || $this->homeTeamId === null)
		{
			$this->homeTeamId = $this->getXml()->getElementsByTagName('HomeTeamID')->item(0)->nodeValue;
		}
		return $this->homeTeamId;
	}

	/**
	 * Return home team name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if(!isset($this->homeTeamName) || $this->homeTeamName === null)
		{
			$this->homeTeamName = $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
		}
		return $this->homeTeamName;
	}

	/**
	 * Return away team id
	 *
	 * @return Integer
	 */
	public function getAwayTeamId()
	{
		if(!isset($this->awayTeamId) || $this->awayTeamId === null)
		{
			$this->awayTeamId = $this->getXml()->getElementsByTagName('AwayTeamID')->item(0)->nodeValue;
		}
		return $this->awayTeamId;
	}

	/**
	 * Return away team name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if(!isset($this->awayTeamName) || $this->awayTeamName === null)
		{
			$this->awayTeamName = $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
		}
		return $this->awayTeamName;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->matchDate[$format]) || $this->matchDate[$format] === null)
		{
			$this->matchDate[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->matchDate[$format] = HTFunction::convertDate($this->matchDate[$format], $format);
			}
		}
		return $this->matchDate[$format];
	}

	/**
	 * Return match type
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->matchType) || $this->matchType === null)
		{
			$this->matchType = $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
		}
		return $this->matchType;
	}

	/**
	 * Return fan expectation
	 *
	 * @return Integer
	 */
	public function getFanExpectation()
	{
		if(!isset($this->fanMatchExpectation) || $this->fanMatchExpectation === null)
		{
			$this->fanMatchExpectation = $this->getXml()->getElementsByTagName('FanMatchExpectation')->item(0)->nodeValue;
		}
		return $this->fanMatchExpectation;
	}
}
class HTAchievements extends HTGlobal
{
	private $maxPoints = null;
	private $achievementsNumber = null;
	private $achievements = null;

	/**
	 * Return max points achievements
	 *
	 * @return Integer
	 */
	public function getMaxPoints()
	{
		if(!isset($this->maxPoints) || $this->maxPoints === null)
		{
			$this->maxPoints = $this->getXml()->getElementsByTagName('MaxPoints')->item(0)->nodeValue;
		}
		return $this->maxPoints;
	}

	/**
	 * Return achievements number
	 *
	 * @return Integer
	 */
	public function getNumberAchievements()
	{
		if(!isset($this->achievementsNumber) || $this->achievementsNumber === null)
		{
			$this->achievementsNumber = $this->getXml()->getElementsByTagName('Achievement')->length;
		}
		return $this->achievementsNumber;
	}

	/**
	 * Return HTAchievement object
	 *
	 * @param Integer $index
	 * @return HTAchievement
	 */
	public function getAchievement($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberAchievements())
		{
			--$index;
			if(!isset($this->achievements[$index]) || $this->achievements[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Achievement');
				$achievement = new DOMDocument('1.0', 'UTF-8');
				$achievement->appendChild($achievement->importNode($nodeList->item($index), true));
				$this->achievements[$index] = new HTAchievement($achievement);
			}
			return $this->achievements[$index];
		}
		return null;
	}
}
class HTAchievement extends HTXml
{
	private $type = null;
	private $text = null;
	private $category = null;
	private $date = null;
	private $points = null;
	private $multiLevel = null;
	private $numberOfEvents = null;
	private $rank = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return achievement type
	 *
	 * @return Integer
	 */
	public function getType()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('AchievementTypeID')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return achievement text
	 *
	 * @return String
	 */
	public function getText()
	{
		if(!isset($this->text) || $this->text === null)
		{
			$this->text = $this->getXml()->getElementsByTagName('AchievementText')->item(0)->nodeValue;
		}
		return $this->text;
	}

	/**
	 * Return achievement category
	 *
	 * @return Integer
	 */
	public function getCategory()
	{
		if(!isset($this->category) || $this->category === null)
		{
			$this->category = $this->getXml()->getElementsByTagName('CategoryID')->item(0)->nodeValue;
		}
		return $this->category;
	}

	/**
	 * Return achievement date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('EventDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return achievement points
	 *
	 * @return Integer
	 */
	public function getPoints()
	{
		if(!isset($this->points) || $this->points === null)
		{
			$this->points = $this->getXml()->getElementsByTagName('Points')->item(0)->nodeValue;
		}
		return $this->points;
	}

	/**
	 * Return if achievement is multi level
	 *
	 * @return Boolean
	 */
	public function isMultiLevel()
	{
		if(!isset($this->multiLevel) || $this->multiLevel === null)
		{
			$this->multiLevel = strtolower($this->getXml()->getElementsByTagName('MultiLevel')->item(0)->nodeValue) == "true";
		}
		return $this->multiLevel;
	}

	/**
	 * Return achievement number of events
	 *
	 * @return Integer
	 */
	public function getNumberOfEvents()
	{
		if(!isset($this->numberOfEvents) || $this->numberOfEvents === null)
		{
			$this->numberOfEvents = $this->getXml()->getElementsByTagName('NumberOfEvents')->item(0)->nodeValue;
		}
		return $this->numberOfEvents;
	}

	/**
	 * Return user's current rank
	 *
	 * @return Integer
	 */
	public function getRank()
	{
		if(!isset($this->rank) || $this->rank === null)
		{
			$this->rank = $this->getXml()->getElementsByTagName('Rank')->item(0)->nodeValue;
		}
		return $this->rank;
	}
}
class HTNationalPlayersStats extends HTCommonTeam
{
	private $matchType = null;
	private $moreExist = null;
	private $playerNumber = null;
	private $players = null;

	/**
	 * Return match type (NT for national team match, WC for worldcup match)
	 *
	 * @return String
	 */
	public function getMatchType()
	{
		if(!isset($this->matchType) || $this->matchType === null)
		{
			$this->matchType = $this->getXml()->getElementsByTagName('MatchTypeCategory')->item(0)->nodeValue;
		}
		return $this->matchType;
	}

	/**
	 * Return if more records available
	 *
	 * @return Integer
	 */
	public function hasMoreRecords()
	{
		if(!isset($this->moreExist) || $this->moreExist === null)
		{
			$this->moreExist = strtolower($this->getXml()->getElementsByTagName('MoreRecordsAvailable')->item(0)->nodeValue) == "true";
		}
		return $this->moreExist;
	}

	/**
	 * Return players number
	 *
	 * @return Integer
	 */
	public function getNumberPlayers()
	{
		if(!isset($this->playerNumber) || $this->playerNumber === null)
		{
			$this->playerNumber = $this->getXml()->getElementsByTagName('Player')->length;
		}
		return $this->playerNumber;
	}

	/**
	 * Return HTAchievement object
	 *
	 * @param Integer $index
	 * @return HTNationalPlayerStats
	 */
	public function getPlayer($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNumberPlayers())
		{
			--$index;
			if(!isset($this->players[$index]) || $this->players[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Achievement');
				$player = new DOMDocument('1.0', 'UTF-8');
				$player->appendChild($player->importNode($nodeList->item($index), true));
				$this->players[$index] = new HTNationalPlayerStats($player);
			}
			return $this->players[$index];
		}
		return null;
	}
}
class HTNationalPlayerStats extends HTXml
{
	private $id = null;
	private $name = null;
	private $nbMatches = null;

	/**
	 * Create an instance
	 *
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return player id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('PlayerID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return player name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('PlayerName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return number of matches
	 *
	 * @return Integer
	 */
	public function getNumberOfMatches()
	{
		if(!isset($this->nbMatches) || $this->nbMatches === null)
		{
			$this->nbMatches = $this->getXml()->getElementsByTagName('NrOfMatches')->item(0)->nodeValue;
		}
		return $this->nbMatches;
	}
}
class HTFlags
{
	private $flagArray = null;
	private $flagNamedArray = null;

	/**
	 * Create instance
	 *
	 * @param Array $flagArray
	 */
	public function __construct($flagArray)
	{
		$this->flagArray = array_values($flagArray);
		$this->flagNamedArray = $flagArray;
	}

	/**
	 * Return flag number
	 *
	 * @return Integer
	 */
	public function getFlagNumber()
	{
		return count($this->flagArray);
	}

	/**
	 * Return HTWorldLeague object
	 *
	 * @param Integer $index
	 * @return HTWorldLeague
	 */
	public function getLeagueOfFlagByNumber($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getFlagNumber())
		{
			return $this->flagArray[--$index];
		}
		return null;
	}

	/**
	 * Return HTWorldLeague object
	 *
	 * @param String $countryName
	 * @return HTWorldLeague
	 */
	public function getLeagueOfFlagByName($countryName)
	{
		if(key_exists($countryName, $this->flagNamedArray))
		{
			return $this->flagNamedArray[$countryName];
		}
		return null;
	}
}
class HTWorldCupMatches extends HTGlobal
{
	private $cupId = null;
	private $season = null;
	private $matchRound = null;
	private $cupSeriesUnitID = null;
	private $matchNumber = null;
	private $matches = null;
	private $roundNumber = null;
	private $rounds = null;

	/**
	 * Return cup id
	 *
	 * @return Integer
	 */
	public function getCupId()
	{
		if(!isset($this->cupId) || $this->cupId === null)
		{
			$this->cupId = $this->getXml()->getElementsByTagName('CupID')->item(0)->nodeValue;
		}
		return $this->cupId;
	}

	/**
	 * Return season number
	 *
	 * @return Integer
	 */
	public function getSeason()
	{
		if(!isset($this->season) || $this->season === null)
		{
			$this->season = $this->getXml()->getElementsByTagName('Season')->item(0)->nodeValue;
		}
		return $this->season;
	}

	/**
	 * Return match round
	 *
	 * @return Integer
	 */
	public function getMatchRound()
	{
		if(!isset($this->matchRound) || $this->matchRound === null)
		{
			$this->matchRound = $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
		}
		return $this->matchRound;
	}

	/**
	 * Return cup series unit id
	 *
	 * @return Integer
	 */
	public function getCupSeriesUnitId()
	{
		if(!isset($this->cupSeriesUnitID) || $this->cupSeriesUnitID === null)
		{
			$this->cupSeriesUnitID = $this->getXml()->getElementsByTagName('CupSeriesUnitID')->item(0)->nodeValue;
		}
		return $this->cupSeriesUnitID;
	}

	/**
	 * Return match number
	 *
	 * @return Integer
	 */
	public function getMatchNumber()
	{
		if(!isset($this->matchNumber) || $this->matchNumber === null)
		{
			$this->matchNumber = $this->getXml()->getElementsByTagName('Match')->length;
		}
		return $this->matchNumber;
	}

	/**
	 * Return HTWorldCupMatch object
	 *
	 * @param Integer $index
	 * @return HTWorldCupMatch
	 */
	public function getMatch($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getMatchNumber())
		{
			--$index;
			if(!isset($this->matches[$index]) || $this->matches[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Match');
				$match = new DOMDocument('1.0', 'UTF-8');
				$match->appendChild($match->importNode($nodeList->item($index), true));
				$this->matches[$index] = new HTWorldCupMatch($match);
			}
			return $this->matches[$index];
		}
		return null;
	}

	/**
	 * Return round number
	 *
	 * @return Integer
	 */
	public function getRoundNumber()
	{
		if(!isset($this->roundNumber) || $this->roundNumber === null)
		{
			$this->roundNumber = $this->getXml()->getElementsByTagName('Round')->length;
		}
		return $this->roundNumber;
	}

	/**
	 * Return HTWorldCupRound object
	 *
	 * @param Integer $index
	 * @return HTWorldCupRound
	 */
	public function getRound($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getRoundNumber())
		{
			--$index;
			if(!isset($this->rounds[$index]) || $this->rounds[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Round');
				$round = new DOMDocument('1.0', 'UTF-8');
				$round->appendChild($round->importNode($nodeList->item($index), true));
				$this->rounds[$index] = new HTWorldCupRound($round);
			}
			return $this->rounds[$index];
		}
		return null;
	}
}
class HTWorldCupMatch extends HTXml
{
	private $id = null;
	private $homeTeamId = null;
	private $homeTeamName = null;
	private $awayTeamId = null;
	private $awayTeamName = null;
	private $startDate = null;
	private $endDate = null;
	private $homeGoals = null;
	private $awayGoals = null;

	/**
	 * Create an instance
	 *
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getMatchId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return home team id
	 *
	 * @return Integer
	 */
	public function getHomeTeamId()
	{
		if(!isset($this->homeTeamId) || $this->homeTeamId === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//HomeTeam/TeamID");
			$this->homeTeamId = $nodeList->item(0)->nodeValue;
		}
		return $this->homeTeamId;
	}

	/**
	 * Return home team name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if(!isset($this->homeTeamName) || $this->homeTeamName === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//HomeTeam/TeamName");
			$this->homeTeamName = $nodeList->item(0)->nodeValue;
		}
		return $this->homeTeamName;
	}

	/**
	 * Return away team id
	 *
	 * @return Integer
	 */
	public function getAwayTeamId()
	{
		if(!isset($this->awayTeamId) || $this->awayTeamId === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//AwayTeam/TeamID");
			$this->awayTeamId = $nodeList->item(0)->nodeValue;
		}
		return $this->awayTeamId;
	}

	/**
	 * Return away team name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if(!isset($this->awayTeamName) || $this->awayTeamName === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//AwayTeam/TeamName");
			$this->awayTeamName = $nodeList->item(0)->nodeValue;
		}
		return $this->awayTeamName;
	}

	/**
	 * Return match start date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getStartDate($format = null)
	{
		if(!isset($this->startDate[$format]) || $this->startDate[$format] === null)
		{
			$this->startDate[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->startDate[$format] = HTFunction::convertDate($this->startDate[$format], $format);
			}
		}
		return $this->startDate[$format];
	}

	/**
	 * Return match end date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getEndDate($format = null)
	{
		if(!isset($this->endDate[$format]) || $this->endDate[$format] === null)
		{
			$this->endDate[$format] = $this->getXml()->getElementsByTagName('FinishedDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->endDate[$format] = HTFunction::convertDate($this->endDate[$format], $format);
			}
		}
		return $this->endDate[$format];
	}

	/**
	 * Return home team goals number
	 *
	 * @return Integer
	 */
	public function getHomeGoals()
	{
		if(!isset($this->homeGoals) || $this->homeGoals === null)
		{
			$this->homeGoals = $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
		}
		return $this->homeGoals;
	}

	/**
	 * Return away team goals number
	 *
	 * @return Integer
	 */
	public function getAwayGoals()
	{
		if(!isset($this->awayGoals) || $this->awayGoals === null)
		{
			$this->awayGoals = $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
		}
		return $this->awayGoals;
	}
}
class HTWorldCupRound extends HTXml
{
	private $matchRound = null;
	private $startDate = null;


	/**
	 * Create an instance
	 *
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return match round number
	 *
	 * @return Integer
	 */
	public function getMatchRound()
	{
		if(!isset($this->matchRound) || $this->matchRound === null)
		{
			$this->matchRound = $this->getXml()->getElementsByTagName('MatchRound')->item(0)->nodeValue;
		}
		return $this->matchRound;
	}

	/**
	 * Return match start date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getStartDate($format = null)
	{
		if(!isset($this->startDate[$format]) || $this->startDate[$format] === null)
		{
			$this->startDate[$format] = $this->getXml()->getElementsByTagName('StartDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->startDate[$format] = HTFunction::convertDate($this->startDate[$format], $format);
			}
		}
		return $this->startDate[$format];
	}
}
class HTNationalMatches extends HTGlobal
{
	private $leagueOfficeTypeID = null;
	private $matchNumber = null;
	private $matches = null;

	/**
	 * Return league office type id
	 *
	 * @return Integer
	 */
	public function getLeagueOfficeTypeID()
	{
		if(!isset($this->leagueOfficeTypeID) || $this->leagueOfficeTypeID === null)
		{
			$this->leagueOfficeTypeID = $this->getXml()->getElementsByTagName('LeagueOfficeTypeID')->item(0)->nodeValue;
		}
		return $this->leagueOfficeTypeID;
	}

	/**
	 * Return match number
	 *
	 * @return Integer
	 */
	public function getMatchNumber()
	{
		if(!isset($this->matchNumber) || $this->matchNumber === null)
		{
			$this->matchNumber = $this->getXml()->getElementsByTagName('Match')->length;
		}
		return $this->matchNumber;
	}

	/**
	 * Return HTNationalMatch object
	 *
	 * @param Integer $index
	 * @return HTNationalMatch
	 */
	public function getMatch($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getMatchNumber())
		{
			--$index;
			if(!isset($this->matches[$index]) || $this->matches[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Match');
				$match = new DOMDocument('1.0', 'UTF-8');
				$match->appendChild($match->importNode($nodeList->item($index), true));
				$this->matches[$index] = new HTNationalMatch($match);
			}
			return $this->matches[$index];
		}
		return null;
	}
}
class HTNationalMatch extends HTXml
{
	private $id = null;
	private $date = null;
	private $type = null;
	private $homeTeamName = null;
	private $awayTeamName = null;
	private $homeGoals = null;
	private $awayGoals = null;
	private $resultAvailable = null;

	/**
	 * Create an instance
	 *
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML;
		$this->xml = $xml;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getMatchId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDate($format = null)
	{
		if(!isset($this->date[$format]) || $this->date[$format] === null)
		{
			$this->date[$format] = $this->getXml()->getElementsByTagName('MatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->date[$format] = HTFunction::convertDate($this->date[$format], $format);
			}
		}
		return $this->date[$format];
	}

	/**
	 * Return match type
	 *
	 * @return Integer
	 */
	public function getTypeId()
	{
		if(!isset($this->type) || $this->type === null)
		{
			$this->type = $this->getXml()->getElementsByTagName('MatchType')->item(0)->nodeValue;
		}
		return $this->type;
	}

	/**
	 * Return home type name
	 *
	 * @return String
	 */
	public function getHomeTeamName()
	{
		if(!isset($this->homeTeamName) || $this->homeTeamName === null)
		{
			$this->homeTeamName = $this->getXml()->getElementsByTagName('HomeTeamName')->item(0)->nodeValue;
		}
		return $this->homeTeamName;
	}

	/**
	 * Return away type name
	 *
	 * @return String
	 */
	public function getAwayTeamName()
	{
		if(!isset($this->awayTeamName) || $this->awayTeamName === null)
		{
			$this->awayTeamName = $this->getXml()->getElementsByTagName('AwayTeamName')->item(0)->nodeValue;
		}
		return $this->awayTeamName;
	}

	/**
	 * Return if match is finished
	 *
	 * @return Boolean
	 */
	public function isResultAvailable()
	{
		if(!isset($this->resultAvailable) || $this->resultAvailable === null)
		{
			$this->resultAvailable = false;
			if($this->getXml()->getElementsByTagName('HomeGoals')->length && $this->getXml()->getElementsByTagName('AwayGoals')->length)
			{
				$this->resultAvailable = true;
			}
		}
		return $this->resultAvailable;
	}

	/**
	 * Return home goals number
	 *
	 * @return Integer
	 */
	public function getHomeGoals()
	{
		if($this->isResultAvailable())
		{
			if(!isset($this->homeGoals) || $this->homeGoals === null)
			{
				$this->homeGoals = $this->getXml()->getElementsByTagName('HomeGoals')->item(0)->nodeValue;
			}
			return $this->homeGoals;
		}
		return null;
	}

	/**
	 * Return away goals number
	 *
	 * @return Integer
	 */
	public function getAwayGoals()
	{
		if($this->isResultAvailable())
		{
			if(!isset($this->awayGoals) || $this->awayGoals === null)
			{
				$this->awayGoals = $this->getXml()->getElementsByTagName('AwayGoals')->item(0)->nodeValue;
			}
			return $this->awayGoals;
		}
		return null;
	}
}
class HTTeamFlag extends HTXml
{
	private $leagueId = null;
	private $leagueName = null;
	private $countryCode = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueId')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Return league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$this->leagueName = $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
		}
		return $this->leagueName;
	}

	/**
	 * Return country code
	 *
	 * @return String
	 */
	public function getCountryCode()
	{
		if(!isset($this->countryCode) || $this->countryCode === null)
		{
			$this->countryCode = $this->getXml()->getElementsByTagName('CountryCode')->item(0)->nodeValue;
		}
		return $this->countryCode;
	}
}
class HTTeamLadders extends HTGlobal
{
	private $ladderNumber = null;
	private $ladder = array();

	/**
	 * Return number of tournaments
	 *
	 * @return Integer
	 */
	public function getLadderNumber()
	{
		if(!isset($this->ladderNumber) || $this->ladderNumber === null)
		{
			$this->ladderNumber = $this->getXml()->getElementsByTagName('Ladder')->length;
		}
		return $this->ladderNumber;
	}

	/**
	 * Return HTTeamLadder object
	 *
	 * @param Integer $index
	 * @return HTTeamLadder
	 */
	public function getLadder($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getLadderNumber())
		{
			--$index;
			if(!isset($this->ladder[$index]) || $this->ladder[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Ladder');
				$node = new DOMDocument('1.0', 'UTF-8');
				$node->appendChild($node->importNode($nodeList->item($index), true));
				$this->ladder[$index] = new HTTeamLadder($node);
			}
			return $this->ladder[$index];
		}
		return null;
	}
}
class HTTeamLadder extends HTXml
{
	private $id = null;
	private $name = null;
	private $position = null;
	private $nextDate = null;
	private $wins = null;
	private $lost = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return ladder id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('LadderId')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return ladder name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('Name')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return team position
	 *
	 * @return Integer
	 */
	public function getPosition()
	{
		if(!isset($this->position) || $this->position === null)
		{
			$this->position = $this->getXml()->getElementsByTagName('Position')->item(0)->nodeValue;
		}
		return $this->position;
	}

	/**
	 * Return next match date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getNextMatchDate($format = null)
	{
		if(!isset($this->nextDate[$format]) || $this->nextDate[$format] === null)
		{
			$this->nextDate[$format] = $this->getXml()->getElementsByTagName('NextMatchDate')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->nextDate[$format] = HTFunction::convertDate($this->nextDate[$format], $format);
			}
		}
		return $this->nextDate[$format];
	}

	/**
	 * Return total won matches
	 *
	 * @return Integer
	 */
	public function getWonNumber()
	{
		if(!isset($this->wins) || $this->wins === null)
		{
			$this->wins = $this->getXml()->getElementsByTagName('Wins')->item(0)->nodeValue;
		}
		return $this->wins;
	}

	/**
	 * Return total lost matches
	 *
	 * @return Integer
	 */
	public function getLostNumber()
	{
		if(!isset($this->lost) || $this->lost === null)
		{
			$this->lost = $this->getXml()->getElementsByTagName('Lost')->item(0)->nodeValue;
		}
		return $this->lost;
	}
}
class HTLadder extends HTGlobal
{
	private $ladderId = null;
	private $ladderName = null;
	private $teamNumber = null;
	private $pageSize = null;
	private $pageIndex = null;
	private $kingId = null;
	private $kingName = null;
	private $kingDate = null;
	private $numberTeams = null;
	private $teams = array();

	/**
	 * Return ladder id
	 *
	 * @return Integer
	 */
	public function getLadderId()
	{
		if(!isset($this->ladderId) || $this->ladderId === null)
		{
			$this->ladderId = $this->getXml()->getElementsByTagName('LadderId')->item(0)->nodeValue;
		}
		return $this->ladderId;
	}

	/**
	 * Return ladder name
	 *
	 * @return String
	 */
	public function getLadderName()
	{
		if(!isset($this->ladderName) || $this->ladderName === null)
		{
			$this->ladderName = $this->getXml()->getElementsByTagName('Name')->item(0)->nodeValue;
		}
		return $this->ladderName;
	}

	/**
	 * Return number of teams in ladder
	 *
	 * @return Integer
	 */
	public function getNumberOfTeams()
	{
		if(!isset($this->numberTeams) || $this->numberTeams === null)
		{
			$this->numberTeams = $this->getXml()->getElementsByTagName('NumOfTeams')->item(0)->nodeValue;
		}
		return $this->numberTeams;
	}

	/**
	 * Return page size
	 *
	 * @return Integer
	 */
	public function getPageSize()
	{
		if(!isset($this->pageSize) || $this->pageSize === null)
		{
			$this->pageSize = $this->getXml()->getElementsByTagName('PageSize')->item(0)->nodeValue;
		}
		return $this->pageSize;
	}

	/**
	 * Return page index
	 *
	 * @return Integer
	 */
	public function getPageIndex()
	{
		if(!isset($this->pageIndex) || $this->pageIndex === null)
		{
			$this->pageIndex = $this->getXml()->getElementsByTagName('PageIndex')->item(0)->nodeValue;
		}
		return $this->pageIndex;
	}

	/**
	 * Return king team id
	 *
	 * @return String
	 */
	public function getKingTeamId()
	{
		if(!isset($this->kingId) || $this->kingId === null)
		{
			$this->kingId = $this->getXml()->getElementsByTagName('KingTeamId')->item(0)->nodeValue;
		}
		return $this->kingId;
	}

	/**
	 * Return king team name
	 *
	 * @return String
	 */
	public function getKingTeamName()
	{
		if(!isset($this->kingName) || $this->kingName === null)
		{
			$this->kingName = $this->getXml()->getElementsByTagName('KingTeamName')->item(0)->nodeValue;
		}
		return $this->kingName;
	}

	/**
	 * Return date since team is king
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getKingDate($format = null)
	{
		if(!isset($this->kingDate[$format]) || $this->kingDate[$format] === null)
		{
			$this->kingDate[$format] = $this->getXml()->getElementsByTagName('KingSince')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->kingDate[$format] = HTFunction::convertDate($this->kingDate[$format], $format);
			}
		}
		return $this->kingDate[$format];
	}

	/**
	 * Return team number
	 *
	 * @return Integer
	 */
	public function getTeamNumber()
	{
		if(!isset($this->teamNumber) || $this->teamNumber === null)
		{
			$this->teamNumber = $this->getXml()->getElementsByTagName('Team')->length;
		}
		return $this->teamNumber;
	}

	/**
	 * Return HTLadderTeam object
	 *
	 * @param Integer $index
	 * @return HTLadderTeam
	 */
	public function getTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getTeamNumber())
		{
			--$index;
			if(!isset($this->teams[$index]) || $this->teams[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Team');
				$team = new DOMDocument('1.0', 'UTF-8');
				$team->appendChild($team->importNode($nodeList->item($index), true));
				$this->teams[$index] = new HTLadderTeam($team);
			}
			return $this->teams[$index];
		}
		return null;
	}
}
class HTLadderTeam extends HTXml
{
	private $id = null;
	private $name = null;
	private $position = null;
	private $wins = null;
	private $lost = null;
	private $winsrow = null;
	private $lostrow = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Return team id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Return team name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Return team position
	 *
	 * @return Integer
	 */
	public function getPosition()
	{
		if(!isset($this->position) || $this->position === null)
		{
			$this->position = $this->getXml()->getElementsByTagName('Position')->item(0)->nodeValue;
		}
		return $this->position;
	}

	/**
	 * Return total won matches
	 *
	 * @return Integer
	 */
	public function getWonNumber()
	{
		if(!isset($this->wins) || $this->wins === null)
		{
			$this->wins = $this->getXml()->getElementsByTagName('Wins')->item(0)->nodeValue;
		}
		return $this->wins;
	}

	/**
	 * Return total lost matches
	 *
	 * @return Integer
	 */
	public function getLostNumber()
	{
		if(!isset($this->lost) || $this->lost === null)
		{
			$this->lost = $this->getXml()->getElementsByTagName('Lost')->item(0)->nodeValue;
		}
		return $this->lost;
	}

	/**
	 * Return total won matches in a row
	 *
	 * @return Integer
	 */
	public function getWonInARowNumber()
	{
		if(!isset($this->winsrow) || $this->winsrow === null)
		{
			$this->winsrow = $this->getXml()->getElementsByTagName('WinsInARow')->item(0)->nodeValue;
		}
		return $this->winsrow;
	}

	/**
	 * Return total lost matches
	 *
	 * @return Integer
	 */
	public function getLostInARowNumber()
	{
		if(!isset($this->lostrow) || $this->lostrow === null)
		{
			$this->lostrow = $this->getXml()->getElementsByTagName('LostInARow')->item(0)->nodeValue;
		}
		return $this->lostrow;
	}
}
class HTTranslation extends HTGlobal
{
	private $languageId = null;
	private $languageName = null;
	private $skillnames = array();
	private $skilllevels = array();
	private $skillsublevels = array();
	private $specialitieslabel = null;
	private $specialities = array();
	private $agreeabilitylabel = null;
	private $agreeability = array();
	private $agressivenesslabel = null;
	private $agressiveness = array();
	private $honestylabel = null;
	private $honesty = array();
	private $tactictypeslabel = null;
	private $tactictypes = array();
	private $matchpositionslabel = null;
	private $matchpositions = array();
	private $ratingsectorslabel = null;
	private $ratingsectors = array();
	private $teamattitudelabel = null;
	private $teamattitude = array();
	private $teamspiritlabel = null;
	private $teamspirit = array();
	private $confidencelabel = null;
	private $confidence = array();
	private $trainingtypeslabel = null;
	private $trainingtypes = array();
	private $sponsorslabel = null;
	private $sponsors = array();
	private $fanmoodlabel = null;
	private $fanmood = array();
	private $fanmatchlabel = null;
	private $fanmatch = array();
	private $fanseasonlabel = null;
	private $fanseason = array();

	/**
	 * Returns language id
	 *
	 * @return Integer
	 */
	public function getLanguageId()
	{
		if($this->languageId === null)
		{
			$this->languageId = $this->getXml()->getElementsByTagName('Language')->item(0)->getAttribute('Id');
		}
		return $this->languageId;
	}

	/**
	 * Returns language name
	 *
	 * @return String
	 */
	public function getLanguageName()
	{
		if($this->languageName === null)
		{
			$this->languageName = $this->getXml()->getElementsByTagName('Language')->item(0)->nodeValue;
		}
		return $this->languageName;
	}

	/**
	 * Return keeper skill name
	 *
	 * @return String
	 */
	public function getSkillNameKeeper()
	{
		if(!isset($this->skillnames['Keeper']))
		{
			$this->skillnames['Keeper'] = $this->getSkillName('Keeper');
		}
		return $this->skillnames['Keeper'];
	}

	/**
	 * Return stamina skill name
	 *
	 * @return String
	 */
	public function getSkillNameStamina()
	{
		if(!isset($this->skillnames['Stamina']))
		{
			$this->skillnames['Stamina'] = $this->getSkillName('Stamina');
		}
		return $this->skillnames['Stamina'];
	}

	/**
	 * Return defender skill name
	 *
	 * @return String
	 */
	public function getSkillNameDefender()
	{
		if(!isset($this->skillnames['Defender']))
		{
			$this->skillnames['Defender'] = $this->getSkillName('Defender');
		}
		return $this->skillnames['Defender'];
	}

	/**
	 * Return playmaker skill name
	 *
	 * @return String
	 */
	public function getSkillNamePlaymaker()
	{
		if(!isset($this->skillnames['Playmaker']))
		{
			$this->skillnames['Playmaker'] = $this->getSkillName('Playmaker');
		}
		return $this->skillnames['Playmaker'];
	}

	/**
	 * Return winger skill name
	 *
	 * @return String
	 */
	public function getSkillNameWinger()
	{
		if(!isset($this->skillnames['Winger']))
		{
			$this->skillnames['Winger'] = $this->getSkillName('Winger');
		}
		return $this->skillnames['Winger'];
	}

	/**
	 * Return scorer skill name
	 *
	 * @return String
	 */
	public function getSkillNameScorer()
	{
		if(!isset($this->skillnames['Scorer']))
		{
			$this->skillnames['Scorer'] = $this->getSkillName('Scorer');
		}
		return $this->skillnames['Scorer'];
	}

	/**
	 * Return kicker skill name
	 *
	 * @return String
	 */
	public function getSkillNameKicker()
	{
		if(!isset($this->skillnames['Kicker']))
		{
			$this->skillnames['Kicker'] = $this->getSkillName('Kicker');
		}
		return $this->skillnames['Kicker'];
	}

	/**
	 * Return passer skill name
	 *
	 * @return String
	 */
	public function getSkillNamePasser()
	{
		if(!isset($this->skillnames['Passer']))
		{
			$this->skillnames['Passer'] = $this->getSkillName('Passer');
		}
		return $this->skillnames['Passer'];
	}

	/**
	 * Return experience skill name
	 *
	 * @return String
	 */
	public function getSkillNameExperience()
	{
		if(!isset($this->skillnames['Experience']))
		{
			$this->skillnames['Experience'] = $this->getSkillName('Experience');
		}
		return $this->skillnames['Experience'];
	}

	/**
	 * Return leadership skill name
	 *
	 * @return String
	 */
	public function getSkillNameLeaderShip()
	{
		if(!isset($this->skillnames['LeaderShip']))
		{
			$this->skillnames['LeaderShip'] = $this->getSkillName('LeaderShip');
		}
		return $this->skillnames['LeaderShip'];
	}

	/**
	 * Return skill name
	 *
	 * @param String $type
	 * @return String
	 */
	private function getSkillName($type)
	{
		$xpath = new DOMXPath($this->getXml());
		$nodeList = $xpath->query("//SkillNames/Skill[@Type='".$type."']");
		if($nodeList->length)
		{
			return trim($nodeList->item(0)->nodeValue);
		}
		return '';
	}

	/**
	 * Return skill level
	 *
	 * @param Integer $level
	 * @return String
	 */
	public function getSkillLevel($level)
	{
		if(!isset($this->skilllevels[$level]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//SkillLevels/Level[@Value='".$level."']");
			if($nodeList->length)
			{
				$this->skilllevels[$level] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->skilllevels[$level] = '';
			}
		}
		return $this->skilllevels[$level];
	}

	/**
	 * Return skill sub level
	 *
	 * @param Float $sublevel
	 * @return String
	 */
	public function getSkillSubLevel($sublevel)
	{
		$sublevel = str_replace('.', ',', $sublevel);
		if(!isset($this->skillsublevels[$sublevel]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//SkillSubLevels/SubLevel[@Value='".$sublevel."']");
			if($nodeList->length)
			{
				$this->skillsublevels[$sublevel] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->skillsublevels[$sublevel] = '';
			}
		}
		return $this->skillsublevels[$sublevel];
	}

	/**
	 * Returns player specialties label
	 *
	 * @return String
	 */
	public function getPlayerSpecialitiesLabel()
	{
		if($this->specialitieslabel === null)
		{
			$this->specialitieslabel = trim($this->getXml()->getElementsByTagName('PlayerSpecialties')->item(0)->getAttribute('Label'));
		}
		return $this->specialitieslabel;
	}

	/**
	 * Returns player speciality name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getPlayerSpeciality($id)
	{
		if(!isset($this->specialities[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//PlayerSpecialties/Item[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->specialities[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->specialities[$id] = '';
			}
		}
		return $this->specialities[$id];
	}

	/**
	 * Returns player agreeability label
	 *
	 * @return String
	 */
	public function getPlayerAgreeabilityLabel()
	{
		if($this->agreeabilitylabel === null)
		{
			$this->agreeabilitylabel = trim($this->getXml()->getElementsByTagName('PlayerAgreeability')->item(0)->getAttribute('Label'));
		}
		return $this->agreeabilitylabel;
	}

	/**
	 * Returns player agreeability name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getPlayerAgreeability($id)
	{
		if(!isset($this->agreeability[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//PlayerAgreeability/Level[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->agreeability[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->agreeability[$id] = '';
			}
		}
		return $this->agreeability[$id];
	}

	/**
	 * Returns player agressiveness label
	 *
	 * @return String
	 */
	public function getPlayerAgressivenessLabel()
	{
		if($this->agressivenesslabel === null)
		{
			$this->agressivenesslabel = trim($this->getXml()->getElementsByTagName('PlayerAgressiveness')->item(0)->getAttribute('Label'));
		}
		return $this->agressivenesslabel;
	}

	/**
	 * Returns player agressiveness name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getPlayerAgressiveness($id)
	{
		if(!isset($this->agressiveness[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//PlayerAgressiveness/Level[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->agressiveness[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->agressiveness[$id] = '';
			}
		}
		return $this->agressiveness[$id];
	}

	/**
	 * Returns player honesty label
	 *
	 * @return String
	 */
	public function getPlayerHonestyLabel()
	{
		if($this->honestylabel === null)
		{
			$this->honestylabel = trim($this->getXml()->getElementsByTagName('PlayerHonesty')->item(0)->getAttribute('Label'));
		}
		return $this->honestylabel;
	}

	/**
	 * Returns player honesty name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getPlayerHonesty($id)
	{
		if(!isset($this->honesty[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//PlayerHonesty/Level[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->honesty[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->honesty[$id] = '';
			}
		}
		return $this->honesty[$id];
	}

	/**
	 * Returns tactics type label
	 *
	 * @return String
	 */
	public function getTacticsLabel()
	{
		if($this->tactictypeslabel === null)
		{
			$this->honestylabel = trim($this->getXml()->getElementsByTagName('TacticTypes')->item(0)->getAttribute('Label'));
		}
		return $this->honestylabel;
	}

	/**
	 * Returns tactic name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getTactic($id)
	{
		if(!isset($this->tactictypes[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//TacticTypes/Item[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->tactictypes[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->tactictypes[$id] = '';
			}
		}
		return $this->tactictypes[$id];
	}

	/**
	 * Returns match position label
	 *
	 * @return String
	 */
	public function getMatchPositionLabel()
	{
		if($this->matchpositionslabel === null)
		{
			$this->matchpositionslabel = trim($this->getXml()->getElementsByTagName('MatchPositions')->item(0)->getAttribute('Label'));
		}
		return $this->matchpositionslabel;
	}

	/**
	 * Returns match position name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getMatchPositions($id)
	{
		if(!isset($this->matchpositions[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//MatchPositions/Item[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->matchpositions[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->matchpositions[$id] = '';
			}
		}
		return $this->matchpositions[$id];
	}

	/**
	 * Returns match position label
	 *
	 * @return String
	 */
	public function getRatingSectorsLabel()
	{
		if($this->matchpositionslabel === null)
		{
			$this->matchpositionslabel = trim($this->getXml()->getElementsByTagName('RatingSectors')->item(0)->getAttribute('Label'));
		}
		return $this->matchpositionslabel;
	}

	/**
	 * Return midfield rating sector name
	 *
	 * @return String
	 */
	public function getRatingSectorsMidfield()
	{
		if(!isset($this->ratingsectors['Midfield']))
		{
			$this->ratingsectors['Midfield'] = $this->getRatingSectors('Midfield');
		}
		return $this->ratingsectors['Midfield'];
	}

	/**
	 * Return right defense rating sector name
	 *
	 * @return String
	 */
	public function getRatingSectorsRightDefense()
	{
		if(!isset($this->ratingsectors['RightDefense']))
		{
			$this->ratingsectors['RightDefense'] = $this->getRatingSectors('RightDefense');
		}
		return $this->ratingsectors['RightDefense'];
	}

	/**
	 * Return central defense rating sector name
	 *
	 * @return String
	 */
	public function getRatingSectorsCentralDefense()
	{
		if(!isset($this->ratingsectors['CentralDefense']))
		{
			$this->ratingsectors['CentralDefense'] = $this->getRatingSectors('CentralDefense');
		}
		return $this->ratingsectors['CentralDefense'];
	}

	/**
	 * Return left defense rating sector name
	 *
	 * @return String
	 */
	public function getRatingSectorsLeftDefense()
	{
		if(!isset($this->ratingsectors['LeftDefense']))
		{
			$this->ratingsectors['LeftDefense'] = $this->getRatingSectors('LeftDefense');
		}
		return $this->ratingsectors['LeftDefense'];
	}

	/**
	 * Return right attack rating sector name
	 *
	 * @return String
	 */
	public function getRatingSectorsRightAttack()
	{
		if(!isset($this->ratingsectors['RightAttack']))
		{
			$this->ratingsectors['RightAttack'] = $this->getRatingSectors('RightAttack');
		}
		return $this->ratingsectors['RightAttack'];
	}

	/**
	 * Return central attack rating sector name
	 *
	 * @return String
	 */
	public function getRatingSectorsCentralAttack()
	{
		if(!isset($this->ratingsectors['CentralAttack']))
		{
			$this->ratingsectors['CentralAttack'] = $this->getRatingSectors('CentralAttack');
		}
		return $this->ratingsectors['CentralAttack'];
	}

	/**
	 * Return left attack rating sector name
	 *
	 * @return String
	 */
	public function getRatingSectorsLeftAttack()
	{
		if(!isset($this->ratingsectors['LeftAttack']))
		{
			$this->ratingsectors['LeftAttack'] = $this->getRatingSectors('LeftAttack');
		}
		return $this->ratingsectors['LeftAttack'];
	}

	/**
	 * Returns rating sector name
	 *
	 * @param Integer $id
	 * @return String
	 */
	private function getRatingSectors($type)
	{
		$xpath = new DOMXPath($this->getXml());
		$nodeList = $xpath->query("//RatingSectors/Item[@Type='".$type."']");
		if($nodeList->length)
		{
			return trim($nodeList->item(0)->nodeValue);
		}
		return '';
	}

	/**
	 * Returns team attitude label
	 *
	 * @return String
	 */
	public function getTeamAttitudeLabel()
	{
		if($this->teamattitudelabel === null)
		{
			$this->teamattitudelabel = trim($this->getXml()->getElementsByTagName('TeamAttitude')->item(0)->getAttribute('Label'));
		}
		return $this->teamattitudelabel;
	}

	/**
	 * Returns team attitude name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getTeamAttitude($id)
	{
		if(!isset($this->teamattitude[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//TeamAttitude/Level[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->teamattitude[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->teamattitude[$id] = '';
			}
		}
		return $this->teamattitude[$id];
	}

	/**
	 * Returns team spirit label
	 *
	 * @return String
	 */
	public function getTeamSpiritLabel()
	{
		if($this->teamspiritlabel === null)
		{
			$this->teamspiritlabel = trim($this->getXml()->getElementsByTagName('TeamSpirit')->item(0)->getAttribute('Label'));
		}
		return $this->teamspiritlabel;
	}

	/**
	 * Returns team spirit name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getTeamSpirit($id)
	{
		if(!isset($this->teamspirit[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//TeamSpirit/Level[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->teamspirit[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->teamspirit[$id] = '';
			}
		}
		return $this->teamspirit[$id];
	}

	/**
	 * Returns team confidence label
	 *
	 * @return String
	 */
	public function getTeamConfidenceLabel()
	{
		if($this->confidencelabel === null)
		{
			$this->confidencelabel = trim($this->getXml()->getElementsByTagName('Confidence')->item(0)->getAttribute('Label'));
		}
		return $this->confidencelabel;
	}

	/**
	 * Returns team confidence name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getTeamConfidence($id)
	{
		if(!isset($this->confidence[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//Confidence/Level[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->confidence[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->confidence[$id] = '';
			}
		}
		return $this->confidence[$id];
	}

	/**
	 * Returns training types label
	 *
	 * @return String
	 */
	public function getTrainingTypesLabel()
	{
		if($this->trainingtypeslabel === null)
		{
			$this->trainingtypeslabel = trim($this->getXml()->getElementsByTagName('TrainingTypes')->item(0)->getAttribute('Label'));
		}
		return $this->trainingtypeslabel;
	}

	/**
	 * Returns training type name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getTrainingType($id)
	{
		if(!isset($this->trainingtypes[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//TrainingTypes/Item[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->trainingtypes[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->trainingtypes[$id] = '';
			}
		}
		return $this->trainingtypes[$id];
	}

	/**
	 * Returns sponsors label
	 *
	 * @return String
	 */
	public function getSponsorsLabel()
	{
		if($this->sponsorslabel === null)
		{
			$this->sponsorslabel = trim($this->getXml()->getElementsByTagName('Sponsors')->item(0)->getAttribute('Label'));
		}
		return $this->sponsorslabel;
	}

	/**
	 * Returns sponsor name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getSponsor($id)
	{
		if(!isset($this->sponsors[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//Sponsors/Level[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->sponsors[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->sponsors[$id] = '';
			}
		}
		return $this->sponsors[$id];
	}

	/**
	 * Returns fan mood label
	 *
	 * @return String
	 */
	public function getFanMoodLabel()
	{
		if($this->fanmoodlabel === null)
		{
			$this->fanmoodlabel = trim($this->getXml()->getElementsByTagName('FanMood')->item(0)->getAttribute('Label'));
		}
		return $this->fanmoodlabel;
	}

	/**
	 * Returns fan mood name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getFanMood($id)
	{
		if(!isset($this->fanmood[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//FanMood/Level[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->fanmood[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->fanmood[$id] = '';
			}
		}
		return $this->fanmood[$id];
	}

	/**
	 * Returns fan match expectations label
	 *
	 * @return String
	 */
	public function getFanMatchExpectationsLabel()
	{
		if($this->fanmatchlabel === null)
		{
			$this->fanmatchlabel = trim($this->getXml()->getElementsByTagName('FanMatchExpectations')->item(0)->getAttribute('Label'));
		}
		return $this->fanmatchlabel;
	}

	/**
	 * Returns fan match expectation name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getFanMatchExpectation($id)
	{
		if(!isset($this->fanmatch[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//FanMatchExpectations/Level[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->fanmatch[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->fanmatch[$id] = '';
			}
		}
		return $this->fanmatch[$id];
	}

	/**
	 * Returns fan season expectations label
	 *
	 * @return String
	 */
	public function getFanSeasonExpectationsLabel()
	{
		if($this->fanseasonlabel === null)
		{
			$this->fanseasonlabel = trim($this->getXml()->getElementsByTagName('FanSeasonExpectations')->item(0)->getAttribute('Label'));
		}
		return $this->fanseasonlabel;
	}

	/**
	 * Returns fan season expectation name
	 *
	 * @param Integer $id
	 * @return String
	 */
	public function getFanSeasonExpectation($id)
	{
		if(!isset($this->fanseason[$id]))
		{
			$xpath = new DOMXPath($this->getXml());
			$nodeList = $xpath->query("//FanSeasonExpectations/Level[@Value='".$id."']");
			if($nodeList->length)
			{
				$this->fanseason[$id] = trim($nodeList->item(0)->nodeValue);
			}
			else
			{
				$this->fanseason[$id] = '';
			}
		}
		return $this->fanseason[$id];
	}
}
class HTManagerCompendium extends HTGlobal
{
	private $managerId = null;
	private $login = null;
	private $isHtSupporter = null;
	private $isSilverSupporter = null;
	private $isHtSupporterGold = null;
	private $isHtSupporterPlatinum = null;
	private $htSupporter = null;
	private $langId = null;
	private $langName = null;
	private $countryId = null;
	private $countryName = null;
	private $teamNumber = null;
	private $teams = array();
	private $ntTeamNumber = null;
	private $ntTeams = array();

	/**
	 * Returns manager user id
	 *
	 * @return Integer
	 */
	public function getManagerId()
	{
		if(!isset($this->managerId) || $this->managerId === null)
		{
			$this->managerId = $this->getXml()->getElementsByTagName('UserId')->item(0)->nodeValue;
		}
		return $this->managerId;
	}

	/**
	 * Returns login name
	 *
	 * @return String
	 */
	public function getLoginName()
	{
		if(!isset($this->login) || $this->login === null)
		{
			$this->login = $this->getXml()->getElementsByTagName('Loginname')->item(0)->nodeValue;
		}
		return $this->login;
	}

	/**
	 * Is the user Hattrick Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporter()
	{
		if(!isset($this->isHtSupporter) || $this->isHtSupporter === null)
		{
			$this->isHtSupporter = $this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue != "";
		}
		return $this->isHtSupporter;
	}

	/**
	 * Is the user Silver Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporterSilver()
	{
		if(!isset($this->isSilverSupporter) || $this->isSilverSupporter === null)
		{
			$this->isSilverSupporter = strtolower($this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue) == "silver";
		}
		return $this->isSilverSupporter;
	}

	/**
	 * Is the user Gold Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporterGold()
	{
		if(!isset($this->isGoldSupporter) || $this->isGoldSupporter === null)
		{
			$this->isGoldSupporter = strtolower($this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue) == "gold";
		}
		return $this->isGoldSupporter;
	}

	/**
	 * Is the user Platinum Supporter ?
	 *
	 * @return Boolean
	 */
	public function isHtSupporterPlatinum()
	{
		if(!isset($this->isPlatinumSupporter) || $this->isPlatinumSupporter === null)
		{
			$this->isPlatinumSupporter = strtolower($this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue) == "platinum";
		}
		return $this->isPlatinumSupporter;
	}

	/**
	 * Get Supporter level
	 *
	 * @return String
	 */
	public function getHtSupporterLevel()
	{
		if(!isset($this->htSupporter) || $this->htSupporter === null)
		{
			$this->htSupporter = $this->getXml()->getElementsByTagName('SupporterTier')->item(0)->nodeValue;
		}
		return $this->htSupporter;
	}

	/**
	 * Returns language id
	 *
	 * @return Integer
	 */
	public function getLanguageId()
	{
		if(!isset($this->langId) || $this->langId === null)
		{
			$this->langId = $this->getXml()->getElementsByTagName('LanguageId')->item(0)->nodeValue;
		}
		return $this->langId;
	}

	/**
	 * Returns language name
	 *
	 * @return String
	 */
	public function getLanguageName()
	{
		if(!isset($this->langName) || $this->langName === null)
		{
			$this->langName = $this->getXml()->getElementsByTagName('LanguageName')->item(0)->nodeValue;
		}
		return $this->langName;
	}

	/**
	 * Returns country id
	 *
	 * @return Integer
	 */
	public function getCountryId()
	{
		if(!isset($this->countryId) || $this->countryId === null)
		{
			$this->countryId = $this->getXml()->getElementsByTagName('CountryId')->item(0)->nodeValue;
		}
		return $this->countryId;
	}

	/**
	 * Returns country name
	 *
	 * @return String
	 */
	public function getCountryName()
	{
		if(!isset($this->countryName) || $this->countryName === null)
		{
			$this->countryName = $this->getXml()->getElementsByTagName('CountryName')->item(0)->nodeValue;
		}
		return $this->countryName;
	}

	/**
	 * Return number of teams
	 *
	 * @return Integer
	 */
	public function getTeamNumber()
	{
		if(!isset($this->teamNumber) || $this->teamNumber === null)
		{
			$this->teamNumber = $this->getXml()->getElementsByTagName('Team')->length;
		}
		return $this->teamNumber;
	}

	/**
	 * Return HTCompendiumTeam object
	 *
	 * @param Integer $index
	 * @return HTCompendiumTeam
	 */
	public function getTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getTeamNumber())
		{
			--$index;
			if(!isset($this->teams[$index]) || $this->teams[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//Team');
				$arena = new DOMDocument('1.0', 'UTF-8');
				$arena->appendChild($arena->importNode($nodeList->item($index), true));
				$this->teams[$index] = new HTCompendiumTeam($arena);
			}
			return $this->teams[$index];
		}
		return null;
	}

	/**
	 * Return number of national teams
	 *
	 * @return Integer
	 */
	public function getNationalTeamNumber()
	{
		if(!isset($this->ntTeamNumber) || $this->ntTeamNumber === null)
		{
			$this->ntTeamNumber = $this->getXml()->getElementsByTagName('NationalTeam')->length;
		}
		return $this->ntTeamNumber;
	}

	/**
	 * Return HTCompendiumNationalTeam object
	 *
	 * @param Integer $index
	 * @return HTCompendiumNationalTeam
	 */
	public function getNationalTeam($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getNationalTeamNumber())
		{
			--$index;
			if(!isset($this->ntTeams[$index]) || $this->ntTeams[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//NationalTeam');
				$arena = new DOMDocument('1.0', 'UTF-8');
				$arena->appendChild($arena->importNode($nodeList->item($index), true));
				$this->ntTeams[$index] = new HTCompendiumNationalTeam($arena);
			}
			return $this->ntTeams[$index];
		}
		return null;
	}
}
class HTCompendiumTeam extends HTXml
{
	private $id = null;
	private $name = null;
	private $arenaId = null;
	private $arenaName = null;
	private $leagueId = null;
	private $leagueName = null;
	private $leagueLevelId = null;
	private $leagueLevelName = null;
	private $regionId = null;
	private $regionName = null;
	private $hasYouthTeam = null;
	private $youthTeamId = null;
	private $youthTeamName = null;
	private $youthTeamLeagueId = null;
	private $youthTeamLeagueName = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Returns team id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('TeamId')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Returns team name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('TeamName')->item(0)->nodeValue;
		}
		return $this->name;
	}

	/**
	 * Returns arena id
	 *
	 * @return Integer
	 */
	public function getArenaId()
	{
		if(!isset($this->arenaId) || $this->arenaId === null)
		{
			$this->arenaId = $this->getXml()->getElementsByTagName('ArenaId')->item(0)->nodeValue;
		}
		return $this->arenaId;
	}

	/**
	 * Returns arena name
	 *
	 * @return String
	 */
	public function getArenaName()
	{
		if(!isset($this->arenaName) || $this->arenaName === null)
		{
			$this->arenaName = $this->getXml()->getElementsByTagName('ArenaName')->item(0)->nodeValue;
		}
		return $this->arenaName;
	}

	/**
	 * Returns league id
	 *
	 * @return Integer
	 */
	public function getLeagueId()
	{
		if(!isset($this->leagueId) || $this->leagueId === null)
		{
			$this->leagueId = $this->getXml()->getElementsByTagName('LeagueId')->item(0)->nodeValue;
		}
		return $this->leagueId;
	}

	/**
	 * Returns league name
	 *
	 * @return String
	 */
	public function getLeagueName()
	{
		if(!isset($this->leagueName) || $this->leagueName === null)
		{
			$this->leagueName = $this->getXml()->getElementsByTagName('LeagueName')->item(0)->nodeValue;
		}
		return $this->leagueName;
	}

	/**
	 * Returns league level unit id
	 *
	 * @return Integer
	 */
	public function getLeagueLevelUnitId()
	{
		if(!isset($this->leagueLevelId) || $this->leagueLevelId === null)
		{
			$this->leagueLevelId = $this->getXml()->getElementsByTagName('LeagueLevelUnitId')->item(0)->nodeValue;
		}
		return $this->leagueLevelId;
	}

	/**
	 * Returns league level unit name
	 *
	 * @return String
	 */
	public function getLeagueLevelUnitName()
	{
		if(!isset($this->leagueLevelName) || $this->leagueLevelName === null)
		{
			$this->leagueLevelName = $this->getXml()->getElementsByTagName('LeagueLevelUnitName')->item(0)->nodeValue;
		}
		return $this->leagueLevelName;
	}

	/**
	 * Returns region id
	 *
	 * @return Integer
	 */
	public function getRegionId()
	{
		if(!isset($this->regionId) || $this->regionId === null)
		{
			$this->regionId = $this->getXml()->getElementsByTagName('RegionId')->item(0)->nodeValue;
		}
		return $this->regionId;
	}

	/**
	 * Returns region name
	 *
	 * @return String
	 */
	public function getRegionName()
	{
		if(!isset($this->regionName) || $this->regionName === null)
		{
			$this->regionName = $this->getXml()->getElementsByTagName('RegionName')->item(0)->nodeValue;
		}
		return $this->regionName;
	}

	/**
	 * Is youth team exist ?
	 *
	 * @return Boolean
	 */
	public function hasYouthTeam()
	{
		if(!isset($this->hasYouthTeam) || $this->hasYouthTeam === null)
		{
			$this->hasYouthTeam = $this->getXml()->getElementsByTagName('YouthTeam')->item(0)->hasChildNodes();
		}
		return $this->hasYouthTeam;
	}

	/**
	 * Returns youth team id
	 *
	 * @return Integer
	 */
	public function getYouthTeamId()
	{
		if($this->hasYouthTeam())
		{
			if(!isset($this->youthTeamId) || $this->youthTeamId === null)
			{
				$this->youthTeamId = $this->getXml()->getElementsByTagName('YouthTeamId')->item(0)->nodeValue;
			}
			return $this->youthTeamId;
		}
		return null;
	}

	/**
	 * Returns youth team name
	 *
	 * @return String
	 */
	public function getYouthTeamName()
	{
		if($this->hasYouthTeam())
		{
			if(!isset($this->youthTeamName) || $this->youthTeamName === null)
			{
				$this->youthTeamName = $this->getXml()->getElementsByTagName('YouthTeamName')->item(0)->nodeValue;
			}
			return $this->youthTeamName;
		}
		return null;
	}

	/**
	 * Returns youth team league id
	 *
	 * @return Integer
	 */
	public function getYouthTeamLeagueId()
	{
		if($this->hasYouthTeam())
		{
			if(!isset($this->youthTeamLeagueId) || $this->youthTeamLeagueId === null)
			{
				$this->youthTeamLeagueId = $this->getXml()->getElementsByTagName('YouthLeagueId')->item(0)->nodeValue;
			}
			return $this->youthTeamLeagueId;
		}
		return null;
	}

	/**
	 * Returns youth team league name
	 *
	 * @return String
	 */
	public function getYouthTeamLeagueName()
	{
		if($this->hasYouthTeam())
		{
			if(!isset($this->youthTeamLeagueName) || $this->youthTeamLeagueName === null)
			{
				$this->youthTeamLeagueName = $this->getXml()->getElementsByTagName('YouthLeagueName')->item(0)->nodeValue;
			}
			return $this->youthTeamLeagueName;
		}
		return null;
	}
}
class HTCompendiumNationalTeam extends HTXml
{
	private $id = null;
	private $name = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Returns national team id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		if(!isset($this->id) || $this->id === null)
		{
			$this->id = $this->getXml()->getElementsByTagName('NationalTeamId')->item(0)->nodeValue;
		}
		return $this->id;
	}

	/**
	 * Returns national team name
	 *
	 * @return String
	 */
	public function getName()
	{
		if(!isset($this->name) || $this->name === null)
		{
			$this->name = $this->getXml()->getElementsByTagName('NationalTeamName')->item(0)->nodeValue;
		}
		return $this->name;
	}
}
class HTSearchTransfer extends HTGlobal
{
	private $total = null;
	private $pageIndex = null;
	private $pageSize = null;
	private $transferNumber = null;
	private $transfer = array();

	/**
	 * Returns total number of transfer results, returns -1 if total greater than 100 and only 100 firsts are available
	 *
	 * @return Integer
	 */
	public function getTotalResults()
	{
		if(!isset($this->total) || $this->total === null)
		{
			$this->total = $this->getXml()->getElementsByTagName('ItemCount')->item(0)->nodeValue;
		}
		return $this->total;
	}

	/**
	 * Returns page size
	 *
	 * @return Integer
	 */
	public function getPageSize()
	{
		if(!isset($this->pageSize) || $this->pageSize === null)
		{
			$this->pageSize = $this->getXml()->getElementsByTagName('PageSize')->item(0)->nodeValue;
		}
		return $this->pageSize;
	}

	/**
	 * Returns page index
	 *
	 * @return Integer
	 */
	public function getPageIndex()
	{
		if(!isset($this->pageIndex) || $this->pageIndex === null)
		{
			$this->pageIndex = $this->getXml()->getElementsByTagName('PageIndex')->item(0)->nodeValue;
		}
		return $this->pageIndex;
	}

	/**
	 * Return number of result for current request
	 *
	 * @return Integer
	 */
	public function getResultNumber()
	{
		if(!isset($this->transferNumber) || $this->transferNumber === null)
		{
			$this->transferNumber = $this->getXml()->getElementsByTagName('TransferResult')->length;
		}
		return $this->transferNumber;
	}

	/**
	 * Return HTSearchTransfer object
	 *
	 * @param Integer $index
	 * @return HTSearchTransferResult
	 */
	public function getResult($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getResultNumber())
		{
			--$index;
			if(!isset($this->transfer[$index]) || $this->transfer[$index] === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$nodeList = $xpath->query('//TransferResult');
				$arena = new DOMDocument('1.0', 'UTF-8');
				$arena->appendChild($arena->importNode($nodeList->item($index), true));
				$this->transfer[$index] = new HTSearchTransferResult($arena);
			}
			return $this->transfer[$index];
		}
		return null;
	}
}
class HTSearchTransferResult extends HTXml
{
	private $playerId = null;
	private $firstName = null;
	private $lastName = null;
	private $nickName = null;
	private $countryId = null;
	private $price = null;
	private $deadline = null;
	private $highestbid = null;
	private $bidderTeamId = null;
	private $bidderTeamName = null;
	private $age = null;
	private $days = null;
	private $tsi = null;
	private $form = null;
	private $experience = null;
	private $leadership = null;
	private $speciality = null;
	private $cards = null;
	private $injury = null;
	private $stamina = null;
	private $keeper = null;
	private $defender = null;
	private $playmaker = null;
	private $winger = null;
	private $scorer = null;
	private $passing = null;
	private $setpieces = null;
	private $sellerTeamId = null;
	private $sellerTeamName = null;
	private $sellerLeagueId = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml->saveXML();
		$this->xml = $xml;
	}

	/**
	 * Returns player id
	 *
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->playerId) || $this->playerId === null)
		{
			$this->playerId = $this->getXml()->getElementsByTagName('PlayerId')->item(0)->nodeValue;
		}
		return $this->playerId;
	}

	/**
	 * Returns player firstname
	 *
	 * @return String
	 */
	public function getFirstName()
	{
		if(!isset($this->firstName) || $this->firstName === null)
		{
			$this->firstName = $this->getXml()->getElementsByTagName('FirstName')->item(0)->nodeValue;
		}
		return $this->firstName;
	}

	/**
	 * Returns player lastname
	 *
	 * @return String
	 */
	public function getLastName()
	{
		if(!isset($this->lastName) || $this->lastName === null)
		{
			$this->lastName = $this->getXml()->getElementsByTagName('LastName')->item(0)->nodeValue;
		}
		return $this->lastName;
	}

	/**
	 * Returns player nickname
	 *
	 * @return String
	 */
	public function getNickName()
	{
		if(!isset($this->nickName) || $this->nickName === null)
		{
			$this->nickName = $this->getXml()->getElementsByTagName('NickName')->item(0)->nodeValue;
		}
		return $this->nickName;
	}

	/**
	 * Returns player native country id
	 *
	 * @return Integer
	 */
	public function getCountryId()
	{
		if(!isset($this->countryId) || $this->countryId === null)
		{
			$this->countryId = $this->getXml()->getElementsByTagName('NativeCountryID')->item(0)->nodeValue;
		}
		return $this->countryId;
	}

	/**
	 * Returns asking price
	 *
	 * @return Integer
	 */
	public function getAskingPrice()
	{
		if(!isset($this->price) || $this->price === null)
		{
			$this->price = $this->getXml()->getElementsByTagName('AskingPrice')->item(0)->nodeValue;
		}
		return $this->price;
	}

	/**
	 * Return deadline date
	 *
	 * @param String $format (php date() function format)
	 * @return String
	 */
	public function getDeadline($format = null)
	{
		if(!isset($this->deadline[$format]) || $this->deadline[$format] === null)
		{
			$this->deadline[$format] = $this->getXml()->getElementsByTagName('Deadline')->item(0)->nodeValue;
			if($format !== null)
			{
				$this->deadline[$format] = HTFunction::convertDate($this->deadline[$format], $format);
			}
		}
		return $this->deadline[$format];
	}

	/**
	 * Return highest bid
	 *
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 * @return Integer
	 */
	public function getHighestBid($countryCurrency = null)
	{
		if(!isset($this->highestbid[$countryCurrency]) || $this->highestbid[$countryCurrency] === null)
		{
			$this->highestbid[$countryCurrency] = HTMoney::convert($this->getXml()->getElementsByTagName('HighestBid')->item(0)->nodeValue, $countryCurrency);
		}
		return $this->highestbid[$countryCurrency];
	}

	/**
	 * Return bidder team id
	 *
	 * @return Integer
	 */
	public function getBidderTeamId()
	{
		if($this->getHighestBid()>0)
		{
			if(!isset($this->bidderTeamId) || $this->bidderTeamId === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$this->bidderTeamId = $xpath->query('//BidderTeam/TeamID')->item(0)->nodeValue;
			}
			return $this->bidderTeamId;
		}
		return null;
	}

	/**
	 * Return bidder team name
	 *
	 * @return String
	 */
	public function getBidderTeamName()
	{
		if($this->getHighestBid()>0)
		{
			if(!isset($this->bidderTeamName) || $this->bidderTeamName === null)
			{
				$xpath = new DOMXPath($this->getXml());
				$this->bidderTeamName = $xpath->query('//BidderTeam/TeamName')->item(0)->nodeValue;
			}
			return $this->bidderTeamName;
		}
		return null;
	}

	/**
	 * Returns player age
	 *
	 * @return Integer
	 */
	public function getAge()
	{
		if(!isset($this->age) || $this->age === null)
		{
			$this->age = $this->getXml()->getElementsByTagName('Age')->item(0)->nodeValue;
		}
		return $this->age;
	}

	/**
	 * Returns player days
	 *
	 * @return Integer
	 */
	public function getDays()
	{
		if(!isset($this->days) || $this->days === null)
		{
			$this->days = $this->getXml()->getElementsByTagName('AgeDays')->item(0)->nodeValue;
		}
		return $this->days;
	}

	/**
	 * Returns player TSI
	 *
	 * @return Integer
	 */
	public function getTSI()
	{
		if(!isset($this->tsi) || $this->tsi === null)
		{
			$this->tsi = $this->getXml()->getElementsByTagName('TSI')->item(0)->nodeValue;
		}
		return $this->tsi;
	}

	/**
	 * Returns player form
	 *
	 * @return Integer
	 */
	public function getForm()
	{
		if(!isset($this->form) || $this->form === null)
		{
			$this->form = $this->getXml()->getElementsByTagName('PlayerForm')->item(0)->nodeValue;
		}
		return $this->form;
	}

	/**
	 * Returns player experience
	 *
	 * @return Integer
	 */
	public function getExperience()
	{
		if(!isset($this->experience) || $this->experience === null)
		{
			$this->experience = $this->getXml()->getElementsByTagName('Experience')->item(0)->nodeValue;
		}
		return $this->experience;
	}

	/**
	 * Returns player leadership
	 *
	 * @return Integer
	 */
	public function getLeadership()
	{
		if(!isset($this->leadership) || $this->leadership === null)
		{
			$this->leadership = $this->getXml()->getElementsByTagName('Leadership')->item(0)->nodeValue;
		}
		return $this->leadership;
	}

	/**
	 * Returns player speciality id
	 *
	 * @return Integer
	 */
	public function getSpeciality()
	{
		if(!isset($this->speciality) || $this->speciality === null)
		{
			$this->speciality = $this->getXml()->getElementsByTagName('Specialty')->item(0)->nodeValue;
		}
		return $this->speciality;
	}

	/**
	 * Returns number of cards, 3 means red card
	 *
	 * @return Integer
	 */
	public function getCards()
	{
		if(!isset($this->cards) || $this->cards === null)
		{
			$this->cards = $this->getXml()->getElementsByTagName('Cards')->item(0)->nodeValue;
		}
		return $this->cards;
	}

	/**
	 * Returns player injury level
	 *
	 * @return Integer
	 */
	public function getInjury()
	{
		if(!isset($this->injury) || $this->injury === null)
		{
			$this->injury = $this->getXml()->getElementsByTagName('InjuryLevel')->item(0)->nodeValue;
		}
		return $this->injury;
	}

	/**
	 * Returns player stamina level
	 *
	 * @return Integer
	 */
	public function getStamina()
	{
		if(!isset($this->stamina) || $this->stamina === null)
		{
			$this->stamina = $this->getXml()->getElementsByTagName('StaminaSkill')->item(0)->nodeValue;
		}
		return $this->stamina;
	}

	/**
	 * Returns player keeper level
	 *
	 * @return Integer
	 */
	public function getKeeper()
	{
		if(!isset($this->keeper) || $this->keeper === null)
		{
			$this->keeper = $this->getXml()->getElementsByTagName('KeeperSkill')->item(0)->nodeValue;
		}
		return $this->keeper;
	}

	/**
	 * Returns player playmaker level
	 *
	 * @return Integer
	 */
	public function getPlaymaker()
	{
		if(!isset($this->playmaker) || $this->playmaker === null)
		{
			$this->playmaker = $this->getXml()->getElementsByTagName('PlaymakerSkill')->item(0)->nodeValue;
		}
		return $this->playmaker;
	}

	/**
	 * Returns player scorer level
	 *
	 * @return Integer
	 */
	public function getScorer()
	{
		if(!isset($this->scorer) || $this->scorer === null)
		{
			$this->scorer = $this->getXml()->getElementsByTagName('ScorerSkill')->item(0)->nodeValue;
		}
		return $this->scorer;
	}

	/**
	 * Returns player passing level
	 *
	 * @return Integer
	 */
	public function getPassing()
	{
		if(!isset($this->passing) || $this->passing === null)
		{
			$this->passing = $this->getXml()->getElementsByTagName('PassingSkill')->item(0)->nodeValue;
		}
		return $this->passing;
	}

	/**
	 * Returns player winger level
	 *
	 * @return Integer
	 */
	public function getWinger()
	{
		if(!isset($this->winger) || $this->winger === null)
		{
			$this->winger = $this->getXml()->getElementsByTagName('WingerSkill')->item(0)->nodeValue;
		}
		return $this->winger;
	}

	/**
	 * Returns player defender level
	 *
	 * @return Integer
	 */
	public function getDefender()
	{
		if(!isset($this->defender) || $this->defender === null)
		{
			$this->defender = $this->getXml()->getElementsByTagName('DefenderSkill')->item(0)->nodeValue;
		}
		return $this->defender;
	}

	/**
	 * Returns player set pieces level
	 *
	 * @return Integer
	 */
	public function getSetPieces()
	{
		if(!isset($this->setpieces) || $this->setpieces === null)
		{
			$this->setpieces = $this->getXml()->getElementsByTagName('SetPiecesSkill')->item(0)->nodeValue;
		}
		return $this->setpieces;
	}

	/**
	 * Return seller team id
	 *
	 * @return Integer
	 */
	public function getSellerTeamId()
	{
		if(!isset($this->sellerTeamId) || $this->sellerTeamId === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$this->sellerTeamId = $xpath->query('//SellerTeam/TeamID')->item(0)->nodeValue;
		}
		return $this->sellerTeamId;
	}

	/**
	 * Return seller team name
	 *
	 * @return String
	 */
	public function getSellerTeamName()
	{
		if(!isset($this->sellerTeamName) || $this->sellerTeamName === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$this->sellerTeamName = $xpath->query('//SellerTeam/TeamName')->item(0)->nodeValue;
		}
		return $this->sellerTeamName;
	}

	/**
	 * Return seller team league id
	 *
	 * @return Integer
	 */
	public function getSellerLeagueId()
	{
		if(!isset($this->sellerLeagueId) || $this->sellerLeagueId === null)
		{
			$xpath = new DOMXPath($this->getXml());
			$this->sellerLeagueId = $xpath->query('//SellerTeam/LeagueID')->item(0)->nodeValue;
		}
		return $this->sellerLeagueId;
	}
}
class HTSearchTransferCriteria
{
	private $ageMin = null;
	private $dayMin = null;
	private $ageMax = null;
	private $dayMax = null;
	private $skillType1 = null;
	private $skillMin1 = null;
	private $skillMax1 = null;
	private $skillType2 = null;
	private $skillMin2 = null;
	private $skillMax2 = null;
	private $skillType3 = null;
	private $skillMin3 = null;
	private $skillMax3 = null;
	private $skillType4 = null;
	private $skillMin4 = null;
	private $skillMax4 = null;
	private $speciality = null;
	private $country = null;
	private $tsiMin = null;
	private $tsiMax = null;
	private $priceMin = null;
	private $priceMax = null;
	private $pageSize = null;
	private $pageIndex = null;

	const SKILL_GOALKEEPER = 1;
	const SKILL_STAMINA = 2;
	const SKILL_SETPIECES = 3;
	const SKILL_DEFENDING = 4;
	const SKILL_SCORING = 5;
	const SKILL_WINGER = 6;
	const SKILL_PASSING = 7;
	const SKILL_PLAYMAKING = 8;
	const SKILL_TRAINER = 9;
	const SKILL_LEADERSHIP = 10;
	const SKILL_EXPERIENCE = 11;

	/**
	 * Set min age (Can also set min days with float age parameter)
	 *
	 * @param Float $age
	 */
	public function setMinAge($age)
	{
		if(round($age)!=$age)
		{
			list($this->ageMin, $this->dayMin) = explode('.', (string)$age);
		}
		else
		{
			$this->ageMin = $age;
		}
	}

	/**
	 * Set max age (Can also set max days with float age parameter)
	 *
	 * @param Float $age
	 */
	public function setMaxAge($age)
	{
		if(round($age)!=$age)
		{
			list($this->ageMax, $this->dayMax) = explode('.', (string)$age);
		}
		else
		{
			$this->ageMax = $age;
		}
	}

	/**
	 * Set min days
	 *
	 * @param Integer $days
	 */
	public function setMinDays($days)
	{
		$this->dayMin = $days;
	}

	/**
	 * Set max days
	 *
	 * @param Integer $days
	 */
	public function setMaxDays($days)
	{
		$this->dayMax = $days;
	}

	/**
	 * Set first skill search
	 *
	 * @param Integer $type
	 * @param Integer $min
	 * @param Integer $max
	 */
	public function setFirstSkill($type, $min = null, $max = null)
	{
		$this->setSkill(1, $type, $min, $max);
	}

	/**
	 * Set second skill search
	 *
	 * @param Integer $type
	 * @param Integer $min
	 * @param Integer $max
	 */
	public function setSecondSkill($type, $min = null, $max = null)
	{
		$this->setSkill(2, $type, $min, $max);
	}

	/**
	 * Set third skill search
	 *
	 * @param Integer $type
	 * @param Integer $min
	 * @param Integer $max
	 */
	public function setThirdSkill($type, $min = null, $max = null)
	{
		$this->setSkill(3, $type, $min, $max);
	}

	/**
	 * Set fourth skill search
	 *
	 * @param Integer $type
	 * @param Integer $min
	 * @param Integer $max
	 */
	public function setFourthSkill($type, $min = null, $max = null)
	{
		$this->setSkill(4, $type, $min, $max);
	}

	/**
	 * @param Integer $num
	 * @param Integer $type
	 * @param Integer $min
	 * @param Integer $max
	 */
	private function setSkill($num, $type, $min, $max)
	{
		$this->{'skillType'.$num} = $type;
		if($min !== null)
		{
			$this->{'skillMin'.$num} = $min;
		}
		if($max !== null)
		{
			$this->{'skillMax'.$num} = $max;
		}
	}

	/**
	 * Set speciality
	 *
	 * @param Integer $specialityId
	 */
	public function setSpeciality($specialityId)
	{
		$this->speciality = $specialityId;
	}

	/**
	 * Set native country
	 *
	 * @param Integer $countryId
	 */
	public function setCountry($countryId)
	{
		$this->country = $countryId;
	}

	/**
	 * Set min TSI
	 *
	 * @param Integer $tsi
	 */
	public function setMinTSI($tsi)
	{
		$this->tsiMin = $tsi;
	}

	/**
	 * Set max TSI
	 *
	 * @param Integer $tsi
	 */
	public function setMaxTSI($tsi)
	{
		$this->tsiMax = $tsi;
	}

	/**
	 * Set min price
	 *
	 * @param Integer $price
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 */
	public function setMinPrice($price, $countryCurrency = HTMoney::SVERIGE)
	{
		$this->priceMin = HTMoney::toSEK($price, $countryCurrency);
	}

	/**
	 * Set max price
	 *
	 * @param Integer $price
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 */
	public function setMaxPrice($price, $countryCurrency = HTMoney::SVERIGE)
	{
		$this->priceMax = HTMoney::toSEK($price, $countryCurrency);
	}

	/**
	 * Set result page size
	 *
	 * @param Integer $size
	 */
	public function setPageSize($size)
	{
		$this->pageSize = $size;
	}

	/**
	 * Set result page index
	 *
	 * @param Integer $number
	 */
	public function setPageIndex($number)
	{
		$this->pageIndex = $number;
	}

	/**
	 * Returns min age
	 *
	 * @return Integer
	 */
	public function getMinAge()
	{
		return $this->ageMin;
	}

	/**
	 * Returns max age
	 *
	 * @return Integer
	 */
	public function getMaxAge()
	{
		return $this->ageMax;
	}

	/**
	 * Returns min days
	 *
	 * @return Integer
	 */
	public function getMinDays()
	{
		return $this->dayMin;
	}

	/**
	 * Returns max days
	 *
	 * @return Integer
	 */
	public function getMaxDays()
	{
		return $this->dayMax;
	}

	/**
	 * Returns first skill type, min and max values
	 *
	 * @return Array
	 */
	public function getFirstSkill()
	{
		return array($this->skillType1, $this->skillMin1, $this->skillMax1);
	}

	/**
	 * Returns second skill type, min and max values
	 *
	 * @return Array
	 */
	public function getSecondSkill()
	{
		return array($this->skillType2, $this->skillMin2, $this->skillMax2);
	}

	/**
	 * Returns third skill type, min and max values
	 *
	 * @return Array
	 */
	public function getThirdSkill()
	{
		return array($this->skillType3, $this->skillMin3, $this->skillMax3);
	}

	/**
	 * Returns fourth skill type, min and max values
	 *
	 * @return Array
	 */
	public function getFourthSkill()
	{
		return array($this->skillType4, $this->skillMin4, $this->skillMax4);
	}

	/**
	 * Returns speciality id
	 *
	 * @return Integer
	 */
	public function getSpeciality()
	{
		return $this->speciality;
	}

	/**
	 * Returns country id
	 *
	 * @return Integer
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * Returns min tsi
	 *
	 * @return Integer
	 */
	public function getMinTSI()
	{
		return $this->tsiMin;
	}

	/**
	 * Returns max tsi
	 *
	 * @return Integer
	 */
	public function getMaxTSI()
	{
		return $this->tsiMax;
	}

	/**
	 * Returns min price
	 *
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 */
	public function getMinPrice($countryCurrency = null)
	{
		if($countryCurrency !== null)
		{
			return HTMoney::convert($this->priceMin, $countryCurrency);
		}
		return $this->priceMin;
	}

	/**
	 * Returns max price
	 *
	 * @param Integer $countryCurrency (Constant taken from HTMoney class)
	 */
	public function getMaxPrice($countryCurrency = null)
	{
		if($countryCurrency !== null)
		{
			return HTMoney::convert($this->priceMax, $countryCurrency);
		}
		return $this->priceMax;
	}

	/**
	 * Returns page size
	 *
	 * @return Integer
	 */
	public function getPageSize()
	{
		return $this->pageSize;
	}

	/**
	 * Returns page index
	 *
	 * @return Integer
	 */
	public function getPageIndex()
	{
		return $this->pageIndex;
	}
}
class HTSetLineup
{
	private $matchId;
	private $sourceSystem;
	private $errors = array();
	private $goalkeeper = 0;
	private $rightDefender = 0;
	private $rightDefenderBehaviour = 0;
	private $rightCentralDefender = 0;
	private $rightCentralDefenderBehaviour = 0;
	private $centralDefender = 0;
	private $centralDefenderBehaviour = 0;
	private $leftCentralDefender = 0;
	private $leftCentralDefenderBehaviour = 0;
	private $leftDefender = 0;
	private $leftDefenderBehaviour = 0;
	private $rightWinger = 0;
	private $rightWingerBehaviour = 0;
	private $rightMidfield = 0;
	private $rightMidfieldBehaviour = 0;
	private $centralMidfield = 0;
	private $centralMidfieldBehaviour = 0;
	private $leftMidfield = 0;
	private $leftMidfieldBehaviour = 0;
	private $leftWinger = 0;
	private $leftWingerBehaviour = 0;
	private $rightForward = 0;
	private $rightForwardBehaviour = 0;
	private $centralForward = 0;
	private $centralForwardBehaviour = 0;
	private $leftForward = 0;
	private $leftForwardBehaviour = 0;
	private $substituteGoalkeeper = 0;
	private $substituteDefender = 0;
	private $substituteMidfield = 0;
	private $substituteForward = 0;
	private $substituteWinger = 0;
	private $captain = 0;
	private $setpieces = 0;
	private $penalty1 = 0;
	private $penalty2 = 0;
	private $penalty3 = 0;
	private $penalty4 = 0;
	private $penalty5 = 0;
	private $penalty6 = 0;
	private $penalty7 = 0;
	private $penalty8 = 0;
	private $penalty9 = 0;
	private $penalty10 = 0;
	private $penalty11 = 0;
	private $tactic = 0;
	private $speechLevel = 0;
	private $newLineup = '';
	private $substitution1 = null;
	private $substitution2 = null;
	private $substitution3 = null;
	private $substitution4 = null;
	private $substitution5 = null;

	const BEHAVIOUR_NORMAL = 0;
	const BEHAVIOUR_OFFENSIVE = 1;
	const BEHAVIOUR_DEFENSIVE = 2;
	const BEHAVIOUR_TOWARDS_MIDDLE = 3;
	const BEHAVIOUR_TOWARDS_WING = 4;
	const TACTIC_NORMAL = 0;
	const TACTIC_PRESSING = 1;
	const TACTIC_COUNTER_ATTACK = 2;
	const TACTIC_ATTACK_MIDDLE = 3;
	const TACTIC_ATTACK_WINGS = 4;
	const TACTIC_PLAY_CREATIVE = 7;
	const TACTIC_LONG_SHOTS = 8;
	const ATTITUDE_NORMAL = 0;
	const ATTITUDE_PIC = -1;
	const ATTITUDE_MOTS = 1;
	const TYPE_SUBSTITUTION = 1;
	const TYPE_BEHAVIOUR = 1;
	const TYPE_SWAP = 3;
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
	const CHECK_FAILED_LESS_9 = 1;
	const CHECK_FAILED_MORE_11 = 2;
	const CHECK_FAILED_NO_GOALKEEPER = 3;
	const CHECK_FAILED_NO_CAPTAIN = 4;
	const CHECK_FAILED_NO_SETPIECES = 5;
	const CHECK_FAILED_INVALID_FORMATION = 6;
	const CHECK_FAILED_DUPLICATE_PLAYER = 7;
	const CHECK_FAILED_SUBSTITUTION_OUT = 8;
	const CHECK_FAILED_SUBSTITUTION_IN = 9;

	/**
	 * @param Integer $matchId
	 * @param String $sourceSystem (use HTMatch constants)
	 */
	public function __construct($matchId, $sourceSystem)
	{
		$this->matchId = $matchId;
		if(is_bool($sourceSystem))
		{
			if($sourceSystem === true)
			{
				$sourceSystem = HTMatch::YOUTH;
			}
			else
			{
				$sourceSystem = HTMatch::SENIOR;
			}
		}
		$this->sourceSystem = $sourceSystem;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getMatchId()
	{
		return $this->matchId;
	}

	/**
	 * Return if match source system
	 *
	 * @return String
	 */
	public function getSourceSystem()
	{
		return $this->sourceSystem;
	}

	/**
	 * Define goalkeeper
	 *
	 * @param Integer $playerId
	 */
	public function setGoalkeeper($playerId)
	{
		$this->goalkeeper = $playerId;
	}

	/**
	 * Define right defender
	 *
	 * @param Integer $playerId
	 */
	public function setRightDefender($playerId)
	{
		$this->rightDefender = $playerId;
	}

	/**
	 * Define right defender behaviour
	 *
	 * @param Integer $code
	 */
	public function setRightDefenderBehaviour($code)
	{
		$this->rightDefenderBehaviour = $code;
	}

	/**
	 * Define right central defender
	 *
	 * @param Integer $playerId
	 */
	public function setRightCentralDefender($playerId)
	{
		$this->rightCentralDefender = $playerId;
	}

	/**
	 * Define right central defender behaviour
	 *
	 * @param Integer $code
	 */
	public function setRightCentralDefenderBehaviour($code)
	{
		$this->rightCentralDefenderBehaviour = $code;
	}

	/**
	 * Define central defender
	 *
	 * @param Integer $playerId
	 */
	public function setCentralDefender($playerId)
	{
		$this->centralDefender = $playerId;
	}

	/**
	 * Define central defender behaviour
	 *
	 * @param Integer $code
	 */
	public function setCentralDefenderBehaviour($code)
	{
		$this->centralDefenderBehaviour = $code;
	}

	/**
	 * Define left central defender
	 *
	 * @param Integer $playerId
	 */
	public function setLeftCentralDefender($playerId)
	{
		$this->leftCentralDefender = $playerId;
	}

	/**
	 * Define left central defender behaviour
	 *
	 * @param Integer $code
	 */
	public function setLeftCentralDefenderBehaviour($code)
	{
		$this->leftCentralDefenderBehaviour = $code;
	}

	/**
	 * Define left defender
	 *
	 * @param Integer $playerId
	 */
	public function setLeftDefender($playerId)
	{
		$this->leftDefender = $playerId;
	}

	/**
	 * Define left defender behaviour
	 *
	 * @param Integer $code
	 */
	public function setLeftDefenderBehaviour($code)
	{
		$this->leftDefenderBehaviour = $code;
	}

	/**
	 * Define right winger
	 *
	 * @param Integer $playerId
	 */
	public function setRightWinger($playerId)
	{
		$this->rightWinger = $playerId;
	}

	/**
	 * Define right winger behaviour
	 *
	 * @param Integer $code
	 */
	public function setRightWingerBehaviour($code)
	{
		$this->rightWingerBehaviour = $code;
	}

	/**
	 * Define right midfield
	 *
	 * @param Integer $playerId
	 */
	public function setRightMidfield($playerId)
	{
		$this->rightMidfield = $playerId;
	}

	/**
	 * Define right midfield behaviour
	 *
	 * @param Integer $code
	 */
	public function setRightMidfieldBehaviour($code)
	{
		$this->rightMidfieldBehaviour = $code;
	}

	/**
	 * Define central midfield
	 *
	 * @param Integer $playerId
	 */
	public function setCentralMidfield($playerId)
	{
		$this->centralMidfield = $playerId;
	}

	/**
	 * Define central midfield behaviour
	 *
	 * @param Integer $code
	 */
	public function setCentralMidfieldBehaviour($code)
	{
		$this->centralMidfieldBehaviour = $code;
	}

	/**
	 * Define left midfield
	 *
	 * @param Integer $playerId
	 */
	public function setLeftMidfield($playerId)
	{
		$this->leftMidfield = $playerId;
	}

	/**
	 * Define left midfield behaviour
	 *
	 * @param Integer $code
	 */
	public function setLeftMidfieldBehaviour($code)
	{
		$this->leftMidfieldBehaviour = $code;
	}

	/**
	 * Define left winger
	 *
	 * @param Integer $playerId
	 */
	public function setLeftWinger($playerId)
	{
		$this->leftWinger = $playerId;
	}

	/**
	 * Define left winger behaviour
	 *
	 * @param Integer $code
	 */
	public function setLeftWingerBehaviour($code)
	{
		$this->leftWingerBehaviour = $code;
	}

	/**
	 * Define right forward
	 *
	 * @param Integer $playerId
	 */
	public function setRightForward($playerId)
	{
		$this->rightForward = $playerId;
	}

	/**
	 * Define right forward behaviour
	 *
	 * @param Integer $code
	 */
	public function setRightForwardBehaviour($code)
	{
		$this->rightForwardBehaviour = $code;
	}

	/**
	 * Define central forward
	 *
	 * @param Integer $playerId
	 */
	public function setCentralForward($playerId)
	{
		$this->centralForward = $playerId;
	}

	/**
	 * Define central forward behaviour
	 *
	 * @param Integer $code
	 */
	public function setCentralForwardBehaviour($code)
	{
		$this->centralForwardBehaviour = $code;
	}

	/**
	 * Define left forward
	 *
	 * @param Integer $playerId
	 */
	public function setLeftForward($playerId)
	{
		$this->leftForward = $playerId;
	}

	/**
	 * Define left forward behaviour
	 *
	 * @param Integer $code
	 */
	public function setLeftForwardBehaviour($code)
	{
		$this->leftForwardBehaviour = $code;
	}

	/**
	 * Define goalkeeper substitute
	 *
	 * @param Integer $playerId
	 */
	public function setSubstituteGoalkeeper($playerId)
	{
		$this->substituteGoalkeeper = $playerId;
	}

	/**
	 * Define defender substitute
	 *
	 * @param Integer $playerId
	 */
	public function setSubstituteDefender($playerId)
	{
		$this->substituteDefender = $playerId;
	}

	/**
	 * Define midfield substitute
	 *
	 * @param Integer $playerId
	 */
	public function setSubstituteMidfield($playerId)
	{
		$this->substituteMidfield = $playerId;
	}

	/**
	 * Define winger substitute
	 *
	 * @param Integer $playerId
	 */
	public function setSubstituteWinger($playerId)
	{
		$this->substituteWinger = $playerId;
	}

	/**
	 * Define forward substitute
	 *
	 * @param Integer $playerId
	 */
	public function setSubstituteForward($playerId)
	{
		$this->substituteForward = $playerId;
	}

	/**
	 * Define captain
	 *
	 * @param Integer $playerId
	 */
	public function setCaptain($playerId)
	{
		$this->captain = $playerId;
	}

	/**
	 * Define set pieces taker
	 *
	 * @param Integer $playerId
	 */
	public function setSetpieces($playerId)
	{
		$this->setpieces = $playerId;
	}

	/**
	 * Define penalty taker number 1
	 *
	 * @param Integer $playerId
	 */
	public function setPenalty1($playerId)
	{
		$this->penalty1 = $playerId;
	}

	/**
	 * Define penalty taker number 2
	 *
	 * @param Integer $playerId
	 */
	public function setPenalty2($playerId)
	{
		$this->penalty2 = $playerId;
	}

	/**
	 * Define penalty taker number 3
	 *
	 * @param Integer $playerId
	 */
	public function setPenalty3($playerId)
	{
		$this->penalty3 = $playerId;
	}

	/**
	 * Define penalty taker number 4
	 *
	 * @param Integer $playerId
	 */
	public function setPenalty4($playerId)
	{
		$this->penalty4 = $playerId;
	}

	/**
	 * Define penalty taker number 5
	 *
	 * @param Integer $playerId
	 */
	public function setPenalty5($playerId)
	{
		$this->penalty5 = $playerId;
	}

	/**
	 * Define penalty taker number 6
	 *
	 * @param Integer $playerId
	 */
	public function setPenalty6($playerId)
	{
		$this->penalty6 = $playerId;
	}

	/**
	 * Define penalty taker number 7
	 *
	 * @param Integer $playerId
	 */
	public function setPenalty7($playerId)
	{
		$this->penalty7 = $playerId;
	}

	/**
	 * Define penalty taker number 8
	 *
	 * @param Integer $playerId
	 */
	public function setPenalty8($playerId)
	{
		$this->penalty8 = $playerId;
	}

	/**
	 * Define penalty taker number 9
	 *
	 * @param Integer $playerId
	 */
	public function setPenalty9($playerId)
	{
		$this->penalty9 = $playerId;
	}

	/**
	 * Define penalty taker number 10
	 *
	 * @param Integer $playerId
	 */
	public function setPenalty10($playerId)
	{
		$this->penalty10 = $playerId;
	}

	/**
	 * Define penalty taker number 11
	 *
	 * @param Integer $playerId
	 */
	public function setPenalty11($playerId)
	{
		$this->penalty11 = $playerId;
	}

	/**
	 * Define match tactic
	 *
	 * @param Integer $code
	 */
	public function setTactic($code)
	{
		$this->tactic = $code;
	}

	/**
	 * Play the match in normal mode
	 */
	public function playNormal()
	{
		$this->speechLevel = self::ATTITUDE_NORMAL;
	}

	/**
	 * Play the match in play it cool mode
	 */
	public function playPIC()
	{
		$this->speechLevel = self::ATTITUDE_PIC;
	}

	/**
	 * Play the match in match of the season mode
	 */
	public function playMOTS()
	{
		$this->speechLevel = self::ATTITUDE_MOTS;
	}

	/**
	 * Return HTLineupSubstitution object
	 *
	 * @return HTLineupSubstitution
	 */
	public function getSubstitution1()
	{
		if($this->substitution1 === null)
		{
			$this->substitution1 = new HTLineupSubstitution();
		}
		return $this->substitution1;
	}

	/**
	 * Return HTLineupSubstitution object
	 *
	 * @return HTLineupSubstitution
	 */
	public function getSubstitution2()
	{
		if($this->substitution2 === null)
		{
			$this->substitution2 = new HTLineupSubstitution();
		}
		return $this->substitution2;
	}

	/**
	 * Return HTLineupSubstitution object
	 *
	 * @return HTLineupSubstitution
	 */
	public function getSubstitution3()
	{
		if($this->substitution3 === null)
		{
			$this->substitution3 = new HTLineupSubstitution();
		}
		return $this->substitution3;
	}

	/**
	 * Return HTLineupSubstitution object
	 *
	 * @return HTLineupSubstitution
	 */
	public function getSubstitution4()
	{
		if($this->substitution4 === null)
		{
			$this->substitution4 = new HTLineupSubstitution();
		}
		return $this->substitution4;
	}

	/**
	 * Return HTLineupSubstitution object
	 *
	 * @return HTLineupSubstitution
	 */
	public function getSubstitution5()
	{
		if($this->substitution5 === null)
		{
			$this->substitution5 = new HTLineupSubstitution();
		}
		return $this->substitution5;
	}

	/**
	 * Returns if lineup has error
	 *
	 * @return Boolean
	 */
	public function hasError()
	{
		$this->errors = array();
		$team = array();
		$num = $def = $mid = $for = 0;
		$lineups = array('5-5-0', '5-4-1', '5-3-2', '5-2-3', '4-5-1', '4-4-2', '4-3-3', '3-5-2', '3-4-3', '2-5-3');
		if($this->goalkeeper != 0) { ++$num; $team[] = $this->goalkeeper; }
		if($this->rightDefender != 0) { ++$num; ++$def; $team[] = $this->rightDefender; }
		if($this->rightCentralDefender != 0) { ++$num; ++$def; $team[] = $this->rightCentralDefender; }
		if($this->centralDefender != 0) { ++$num; ++$def; $team[] = $this->centralDefender; }
		if($this->leftCentralDefender != 0) { ++$num; ++$def; $team[] = $this->leftCentralDefender; }
		if($this->leftDefender != 0) { ++$num; ++$def; $team[] = $this->leftDefender; }
		if($this->rightWinger != 0) { ++$num; ++$mid; $team[] = $this->rightWinger; }
		if($this->rightMidfield != 0) { ++$num; ++$mid; $team[] = $this->rightMidfield; }
		if($this->centralMidfield != 0) { ++$num; ++$mid; $team[] = $this->centralMidfield; }
		if($this->leftMidfield != 0) { ++$num; ++$mid; $team[] = $this->leftMidfield; }
		if($this->leftWinger != 0) { ++$num; ++$mid; $team[] = $this->leftWinger; }
		if($this->rightForward != 0) { ++$num; ++$for; $team[] = $this->rightForward; }
		if($this->centralForward != 0) { ++$num; ++$for; $team[] = $this->centralForward; }
		if($this->leftForward != 0) { ++$num; ++$for; $team[] = $this->leftForward; }
		if($num < 9)
		{
			$this->errors[] = new HTError("Less than 9 players", false, self::CHECK_FAILED_LESS_9);
		}
		if($num > 11)
		{
			$this->errors[] = new HTError("More than 11 players", false, self::CHECK_FAILED_MORE_11);
		}
		if($this->goalkeeper == 0)
		{
			$this->errors[] = new HTError("No goalkeeper", false, self::CHECK_FAILED_NO_GOALKEEPER);
		}
		if($this->captain == 0)
		{
			$this->errors[] = new HTError("No captain", false, self::CHECK_FAILED_NO_CAPTAIN);
		}
		if($this->setpieces == 0)
		{
			$this->errors[] = new HTError("No set pieces taker", false, self::CHECK_FAILED_NO_SETPIECES);
		}
		if(!in_array($def.'-'.$mid.'-'.$for, $lineups))
		{
			$this->errors[] = new HTError("Invalid formation: ".$def.'-'.$mid.'-'.$for, false, self::CHECK_FAILED_INVALID_FORMATION);
		}
		if(count($team) != count(array_unique($team)))
		{
			$count = array_filter(array_count_values($team), create_function('$e', 'return $e>1;'));
			foreach ($count as $id => $use)
			{
				$this->errors[] = new HTError("Player ".$id." used ".$use." times", false, self::CHECK_FAILED_DUPLICATE_PLAYER);
			}
		}
		for($i=1; $i<=5; $i++)
		{
			if($this->{'substitution'.$i} !== null)
			{
				$sub = $this->{'getSubstitution'.$i}();
				if($sub->getOrderType() == HTSetLineup::TYPE_BEHAVIOUR || $sub->getOrderType() == HTSetLineup::TYPE_SUBSTITUTION)
				{
					if($sub->getPlayerOut() && !in_array($sub->getPlayerOut(), $team))
					{
						$this->errors[] = new HTError("Substitution $i player out not in lineup : ".$sub->getPlayerOut(), false, self::CHECK_FAILED_SUBSTITUTION_OUT);
					}
					if($sub->getPlayerIn() && !in_array($sub->getPlayerIn(), array_merge($team, array($this->substituteGoalkeeper, $this->substituteDefender, $this->substituteMidfield, $this->substituteForward, $this->substituteWinger))))
					{
						$this->errors[] = new HTError("Substitution $i player in is not substitute : ".$sub->getPlayerIn(), false, self::CHECK_FAILED_SUBSTITUTION_IN);
					}
				}
				elseif($sub->getOrderType() == HTSetLineup::TYPE_SWAP)
				{
					if(!in_array($sub->getPlayerOut(), $team))
					{
						$this->errors[] = new HTError("Substitution $i player out not in lineup : ".$sub->getPlayerOut(), false, self::CHECK_FAILED_SUBSTITUTION_OUT);
					}
					if(!in_array($sub->getPlayerIn(), array_merge($team, array($this->substituteGoalkeeper, $this->substituteDefender, $this->substituteMidfield, $this->substituteForward, $this->substituteWinger))))
					{
						$this->errors[] = new HTError("Substitution $i player in not in lineup : ".$sub->getPlayerIn(), false, self::CHECK_FAILED_SUBSTITUTION_IN);
					}
				}
			}
		}
		return $this->getErrorsNumber() > 0;
	}

	/**
	 * Returns errors number
	 *
	 * @return Integer
	 */
	public function getErrorsNumber()
	{
		return count($this->errors);
	}

	/**
	 * Return error
	 *
	 * @param Integer $index
	 * @return HTError
	 */
	public function getError($index)
	{
		$index = round($index);
		if($index > 0 && $index <= $this->getErrorsNumber())
		{
			--$index;
			return $this->errors[$index];
		}
		return null;
	}

	/**
	 * Return lineup as json string
	 *
	 * @return String
	 */
	public function getJson()
	{
		$json = array();
		$json["positions"] = array(
			array("id"=>$this->goalkeeper, "behaviour"=>0),
			array("id"=>$this->rightDefender, "behaviour"=>$this->rightDefenderBehaviour),
			array("id"=>$this->rightCentralDefender, "behaviour"=>$this->rightCentralDefenderBehaviour),
			array("id"=>$this->centralDefender, "behaviour"=>$this->centralDefenderBehaviour),
			array("id"=>$this->leftCentralDefender, "behaviour"=>$this->leftCentralDefenderBehaviour),
			array("id"=>$this->leftDefender, "behaviour"=>$this->leftDefenderBehaviour),
			array("id"=>$this->rightWinger, "behaviour"=>$this->rightWingerBehaviour),
			array("id"=>$this->rightMidfield, "behaviour"=>$this->rightMidfieldBehaviour),
			array("id"=>$this->centralMidfield, "behaviour"=>$this->centralMidfieldBehaviour),
			array("id"=>$this->leftMidfield, "behaviour"=>$this->leftMidfieldBehaviour),
			array("id"=>$this->leftWinger, "behaviour"=>$this->leftWingerBehaviour),
			array("id"=>$this->rightForward, "behaviour"=>$this->rightForwardBehaviour),
			array("id"=>$this->centralForward, "behaviour"=>$this->centralForwardBehaviour),
			array("id"=>$this->leftForward, "behaviour"=>$this->leftForwardBehaviour),
			array("id"=>$this->substituteGoalkeeper, "behaviour"=>0),
			array("id"=>$this->substituteDefender, "behaviour"=>0),
			array("id"=>$this->substituteMidfield, "behaviour"=>0),
			array("id"=>$this->substituteForward, "behaviour"=>0),
			array("id"=>$this->substituteWinger, "behaviour"=>0),
			array("id"=>$this->captain, "behaviour"=>0),
			array("id"=>$this->setpieces, "behaviour"=>0),
			array("id"=>$this->penalty1, "behaviour"=>0),
			array("id"=>$this->penalty2, "behaviour"=>0),
			array("id"=>$this->penalty3, "behaviour"=>0),
			array("id"=>$this->penalty4, "behaviour"=>0),
			array("id"=>$this->penalty5, "behaviour"=>0),
			array("id"=>$this->penalty6, "behaviour"=>0),
			array("id"=>$this->penalty7, "behaviour"=>0),
			array("id"=>$this->penalty8, "behaviour"=>0),
			array("id"=>$this->penalty9, "behaviour"=>0),
			array("id"=>$this->penalty10, "behaviour"=>0),
			array("id"=>$this->penalty11, "behaviour"=>0)
		);
		$json["settings"] = array(
			"tactic"=>$this->tactic,
			"speechLevel"=>$this->speechLevel,
			"newLineup"=>""
		);
		$json["substitutions"] = array();
		for($i=1; $i<=5; $i++)
		{
			if($this->{'substitution'.$i} !== null)
			{
				$json["substitutions"][] = array(
					"playerin"=>$this->{'getSubstitution'.$i}()->getPlayerIn(),
					"playerout"=>$this->{'getSubstitution'.$i}()->getPlayerOut(),
					"orderType"=>$this->{'getSubstitution'.$i}()->getOrderType(),
					"min"=>$this->{'getSubstitution'.$i}()->getMinute(),
					"pos"=>$this->{'getSubstitution'.$i}()->getPosition(),
					"beh"=>$this->{'getSubstitution'.$i}()->getBehaviour(),
					"card"=>$this->{'getSubstitution'.$i}()->getCard(),
					"standing"=>$this->{'getSubstitution'.$i}()->getStanding()
				);
			}
		}
		return json_encode($json);
	}

}
class HTLineupSubstitution
{
	private $playerIn;
	private $playerOut = 0;
	private $orderType;
	private $minute;
	private $position;
	private $behaviour = 0;
	private $card = -1;
	private $standing = -1;

	/**
	 * Set player who replace
	 *
	 * @param Integer $playerId
	 */
	public function setPlayerIn($playerId)
	{
		$this->playerIn = $playerId;
	}

	/**
	 * Get player who replace
	 *
	 * @return Integer
	 */
	public function getPlayerIn()
	{
		return $this->playerIn;
	}

	/**
	 * Set player replaced
	 *
	 * @param Integer $playerId
	 */
	public function setPlayerOut($playerId)
	{
		$this->playerOut = $playerId;
	}

	/**
	 * Get player replaced
	 *
	 * @return Integer
	 */
	public function getPlayerOut()
	{
		return $this->playerOut;
	}

	/**
	 * Set type of substitution
	 *
	 * @param Integer $code
	 */
	public function setOrderType($code)
	{
		$this->orderType = $code;
	}

	/**
	 * Get type of substitution
	 *
	 * @return Integer
	 */
	public function getOrderType()
	{
		return $this->orderType;
	}

	/**
	 * Set minute of substitution
	 *
	 * @param Integer $minute
	 */
	public function setMinute($minute)
	{
		$this->minute = $minute;
	}

	/**
	 * Get minute of substitution
	 *
	 * @return Integer
	 */
	public function getMinute()
	{
		return $this->minute;
	}

	/**
	 * Set position of substitution
	 *
	 * @param Integer $position
	 */
	public function setPosition($position)
	{
		$this->position = $position;
	}

	/**
	 * Get position of substitution
	 *
	 * @return Integer
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * Set behaviour of substitution
	 *
	 * @param Integer $code
	 */
	public function setBehaviour($code)
	{
		$this->behaviour = $code;
	}

	/**
	 * Get behaviour of substitution
	 *
	 * @return Integer
	 */
	public function getBehaviour()
	{
		return $this->behaviour;
	}

	/**
	 * Set red card condition of substitution
	 *
	 * @param Integer $code
	 */
	public function setCard($code)
	{
		$this->card = $code;
	}

	/**
	 * Get red card condition of substitution
	 *
	 * @return Integer
	 */
	public function getCard()
	{
		return $this->card;
	}

	/**
	 * Set standing situation of substitution
	 *
	 * @param Integer $code
	 */
	public function setStanding($code)
	{
		$this->standing = $code;
	}

	/**
	 * Get standing situation of substitution
	 *
	 * @return Integer
	 */
	public function getStanding()
	{
		return $this->standing;
	}
}
class HTLineupPrediction extends HTXml
{
	private $matchId = null;
	private $isYouth = null;
	private $tacticType = null;
	private $tacticSkill = null;
	private $midfield = null;
	private $rightdef = null;
	private $centraldef = null;
	private $leftdef = null;
	private $rightatt = null;
	private $centralatt = null;
	private $leftatt = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml;
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->loadXML($xml);
		$this->xml = $dom;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getMatchId()
	{
		if(!isset($this->matchId) || $this->matchId === null)
		{
			$this->matchId = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->matchId;
	}

	/**
	 * Return if match is youth
	 *
	 * @return Boolean
	 */
	public function isYouth()
	{
		if(!isset($this->isYouth) || $this->isYouth === null)
		{
			$this->isYouth = strtolower($this->getXml()->getElementsByTagName('IsYouth')->item(0)->nodeValue) == 'true';
		}
		return $this->isYouth;
	}

	/**
	 * Return tactic type
	 *
	 * @return Integer
	 */
	public function getTacticType()
	{
		if(!isset($this->tacticType) || $this->tacticType === null)
		{
			$this->tacticType = $this->getXml()->getElementsByTagName('TacticType')->item(0)->nodeValue;
		}
		return $this->tacticType;
	}

	/**
	 * Return tactic skill
	 *
	 * @return Integer
	 */
	public function getTacticSkill()
	{
		if(!isset($this->tacticSkill) || $this->tacticSkill === null)
		{
			$this->tacticSkill = $this->getXml()->getElementsByTagName('TacticSkill')->item(0)->nodeValue;
		}
		return $this->tacticSkill;
	}

	/**
	 * Return midfield rating
	 *
	 * @return Integer
	 */
	public function getMidfield()
	{
		if(!isset($this->midfield) || $this->midfield === null)
		{
			$this->midfield = $this->getXml()->getElementsByTagName('RatingMidfield')->item(0)->nodeValue;
		}
		return $this->midfield;
	}

	/**
	 * Return right defense rating
	 *
	 * @return Integer
	 */
	public function getRightDefense()
	{
		if(!isset($this->rightdef) || $this->rightdef === null)
		{
			$this->rightdef = $this->getXml()->getElementsByTagName('RatingRightDef')->item(0)->nodeValue;
		}
		return $this->rightdef;
	}

	/**
	 * Return central defense rating
	 *
	 * @return Integer
	 */
	public function getCentralDefense()
	{
		if(!isset($this->centraldef) || $this->centraldef === null)
		{
			$this->centraldef = $this->getXml()->getElementsByTagName('RatingMidDef')->item(0)->nodeValue;
		}
		return $this->centraldef;
	}

	/**
	 * Return left defense rating
	 *
	 * @return Integer
	 */
	public function getLeftDefense()
	{
		if(!isset($this->leftdef) || $this->leftdef === null)
		{
			$this->leftdef = $this->getXml()->getElementsByTagName('RatingLeftDef')->item(0)->nodeValue;
		}
		return $this->leftdef;
	}

	/**
	 * Return right attack rating
	 *
	 * @return Integer
	 */
	public function getRightAttack()
	{
		if(!isset($this->rightatt) || $this->rightatt === null)
		{
			$this->rightatt = $this->getXml()->getElementsByTagName('RatingRightAtt')->item(0)->nodeValue;
		}
		return $this->rightatt;
	}

	/**
	 * Return central attack rating
	 *
	 * @return Integer
	 */
	public function getCentralAttack()
	{
		if(!isset($this->centralatt) || $this->centralatt === null)
		{
			$this->centralatt = $this->getXml()->getElementsByTagName('RatingMidAtt')->item(0)->nodeValue;
		}
		return $this->centralatt;
	}

	/**
	 * Return left attack rating
	 *
	 * @return Integer
	 */
	public function getLeftAttack()
	{
		if(!isset($this->leftatt) || $this->leftatt === null)
		{
			$this->leftatt = $this->getXml()->getElementsByTagName('RatingLeftAtt')->item(0)->nodeValue;
		}
		return $this->leftatt;
	}
}
class HTLineupResult extends HTXml
{
	private $matchId = null;
	private $isYouth = null;
	private $orderset = null;
	private $reason = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xmlText = $xml;
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->loadXML($xml);
		$this->xml = $dom;
	}

	/**
	 * Return match id
	 *
	 * @return Integer
	 */
	public function getMatchId()
	{
		if(!isset($this->matchId) || $this->matchId === null)
		{
			$this->matchId = $this->getXml()->getElementsByTagName('MatchID')->item(0)->nodeValue;
		}
		return $this->matchId;
	}

	/**
	 * Return if match is youth
	 *
	 * @return Boolean
	 */
	public function isYouth()
	{
		if(!isset($this->isYouth) || $this->isYouth === null)
		{
			$this->isYouth = strtolower($this->getXml()->getElementsByTagName('IsYouth')->item(0)->nodeValue) == 'true';
		}
		return $this->isYouth;
	}

	/**
	 * Return if orders are set
	 *
	 * @return Boolean
	 */
	public function ordersSet()
	{
		if(!isset($this->orderset) || $this->orderset === null)
		{
			$this->orderset = strtolower($this->getXml()->getElementsByTagName('MatchData')->item(0)->getAttribute('OrdersSet')) == 'true';
		}
		return $this->orderset;
	}

	/**
	 * Return reason when orders failed
	 *
	 * @return String
	 */
	public function getReason()
	{
		if(!isset($this->reason) || $this->reason === null)
		{
			$node = $this->getXml()->getElementsByTagName('Reason');
			if($node->length)
			{
				$this->reason = $node->item(0)->nodeValue;
			}
		}
		return $this->reason;
	}
}
class HTError extends Exception
{
	/**
	 * @var DOMDocument
	 */
	private $xml;
	private $isXml;

	/**
	 * @param String $message
	 * @param Boolean $isXml
	 */
	public function __construct($message, $isXml, $code = 0)
	{
		$this->isXml = $isXml;
		parent::__construct($message, $code);
		if($this->isXmlError())
		{
			$this->xml = new DOMDocument('1.0', 'UTF-8');
			$this->xml->loadXML($message);
		}
	}

	/**
	 * Is error xml ?
	 *
	 * @return Boolean
	 */
	public function isXmlError()
	{
		return $this->isXml;
	}

	/**
	 * Return error code
	 *
	 * @return Integer
	 */
	public function getErrorCode()
	{
		if($this->isXmlError())
		{
			return $this->xml->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
		}
		return null;
	}

	/**
	 * Return error
	 *
	 * @return String
	 */
	public function getError()
	{
		if($this->isXmlError())
		{
			return $this->xml->getElementsByTagName('Error')->item(0)->nodeValue;
		}
		return null;
	}

	/**
	 * Return error guid
	 *
	 * @return String
	 */
	public function getErrorGuid()
	{
		if($this->isXmlError())
		{
			return $this->xml->getElementsByTagName('ErrorGUID')->item(0)->nodeValue;
		}
		return null;
	}

	/**
	 * Return server
	 *
	 * @return String
	 */
	public function getServer()
	{
		if($this->isXmlError())
		{
			return $this->xml->getElementsByTagName('Server')->item(0)->nodeValue;
		}
		return null;
	}

	/**
	 * Return request
	 *
	 * @return String
	 */
	public function getRequest()
	{
		if($this->isXmlError())
		{
			return $this->xml->getElementsByTagName('Request')->item(0)->nodeValue;
		}
		return null;
	}

	/**
	 * Return xml
	 *
	 * @param Boolean $asObject
	 * @return DOMDocument | String
	 */
	public function getXml($asObject = true)
	{
		if($this->isXmlError())
		{
			if($asObject === true)
			{
				return $this->xml;
			}
			return $this->xml->saveXML();
		}
		else
		{
			return parent::getMessage();
		}
	}
}
/*-events-*/
class HTEvent20 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent21 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent22 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent25 extends HTEvent
{
	const VALUE1 = 'Derby';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent26 extends HTEvent
{
	const VALUE1 = 'Neutral ground';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent27 extends HTEvent
{
	const VALUE1 = 'Away is home';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent30 extends HTEvent
{
	const VALUE1 = 'Rain';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getCrowdTurnout()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent31 extends HTEvent
{
	const VALUE1 = 'Overcast';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getCrowdTurnout()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent32 extends HTEvent
{
	const VALUE1 = 'Partially cloudy';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getCrowdTurnout()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent33 extends HTEvent
{
	const VALUE1 = 'Sunny';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getCrowdTurnout()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent40 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getPossesionPercent()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent41 extends HTEvent
{
	const VALUE1 = 'Best player';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent42 extends HTEvent
{
	const VALUE1 = 'Worst player';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent45 extends HTEvent
{
	const VALUE1 = 'Half time';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent46 extends HTEvent
{
	const VALUE1 = 'Hattrick';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent47 extends HTEvent
{
}
class HTEvent55 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent56 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent57 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent58 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent59 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent60 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent61 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isUp()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent62 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent63 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isUp()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent64 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isUp()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent65 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isUp()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent66 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isUp()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent67 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isUp()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent68 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent69 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isUp()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent70 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isStart()
	{
		return true;
	}
}
class HTEvent71 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isStart()
	{
		return false;
	}
}
class HTEvent72 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isStart()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getWinnerTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent73 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isStart()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getWinnerTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent80 extends HTEvent
{
	/**
	 * @return Boolean
	 */
	public function isStart()
	{
		return true;
	}
}
class HTEvent90 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getInjuredPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getInjuredPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent91 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getInjuredPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getInjuredPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getReplacementPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent92 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getInjuredPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getInjuredPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getReplacementPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent93 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getInjuredPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getInjuredPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent94 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getInjuredPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getInjuredPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent95 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getInjuredPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getInjuredPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getReplacementPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}
}
class HTEvent96 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getInjuredPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getInjuredPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent97 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getInjuredPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getInjuredPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent100 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent101 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent102 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent103 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent104 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent105 extends HTEvent
{
	const VALUE1 = 'Unpredictable';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent106 extends HTEvent
{
	const VALUE1 = 'Unpredictable';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent107 extends HTEvent
{
	const VALUE1 = 'Long shot';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent108 extends HTEvent
{
	const VALUE1 = 'Unpredictable';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent109 extends HTEvent
{
	const VALUE1 = 'Unpredictable';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return false;
	}
}
class HTEvent110 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent111 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent112 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent113 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent114 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent115 extends HTEvent
{
	const VALUE1 = 'Quick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent116 extends HTEvent
{
	const VALUE1 = 'Quick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent117 extends HTEvent
{
	const VALUE1 = 'Low stamina';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return false;
	}
}
class HTEvent118 extends HTEvent
{
	const VALUE1 = 'Corner';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent119 extends HTEvent
{
	const VALUE1 = 'Corner';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent120 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent121 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent122 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent123 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent124 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent125 extends HTEvent
{
	const VALUE1 = 'Unpredictable';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent130 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent131 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent132 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent133 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent134 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent135 extends HTEvent
{
	const VALUE1 = 'High experience';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent136 extends HTEvent
{
	const VALUE1 = 'Low experience';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return false;
	}
}
class HTEvent137 extends HTEvent
{
	const VALUE1 = 'Cross pass';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent138 extends HTEvent
{
	const VALUE1 = 'Cross pass';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent139 extends HTEvent
{
	const VALUE1 = 'Technical';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent140 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent141 extends HTEvent
{
	const VALUE1 = 'Counter attack';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent142 extends HTEvent
{
	const VALUE1 = 'Counter attack';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent143 extends HTEvent
{
	const VALUE1 = 'Counter attack';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent150 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent151 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent152 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent153 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent154 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent160 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent161 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent162 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent163 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent164 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent170 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent171 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent172 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent173 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent174 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent180 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent181 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent182 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent183 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent184 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent185 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'indirect free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent186 extends HTEvent
{
	const VALUE1 = 'Counter attack';
	const VALUE2 = 'indirect free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent187 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'long-range shot';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return true;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent200 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent201 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent202 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent203 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent204 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent205 extends HTEvent
{
	const VALUE1 = 'Unpredictable';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent206 extends HTEvent
{
	const VALUE1 = 'Unpredictable';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent207 extends HTEvent
{
	const VALUE1 = 'Long shot';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent208 extends HTEvent
{
	const VALUE1 = 'Unpredictable';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent209 extends HTEvent
{
	const VALUE1 = 'Unpredictable';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return false;
	}
}
class HTEvent210 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent211 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent212 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent213 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent214 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent215 extends HTEvent
{
	const VALUE1 = 'Quick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent216 extends HTEvent
{
	const VALUE1 = 'Quick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent217 extends HTEvent
{
	const VALUE1 = 'Low stamina';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return false;
	}
}
class HTEvent218 extends HTEvent
{
	const VALUE1 = 'Corner';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent219 extends HTEvent
{
	const VALUE1 = 'Corner';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent220 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent221 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent222 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent223 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent224 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent225 extends HTEvent
{
	const VALUE1 = 'Unpredictable';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent230 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent231 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent232 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent233 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent234 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent235 extends HTEvent
{
	const VALUE1 = 'High experience';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent236 extends HTEvent
{
	const VALUE1 = 'Low experience';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return false;
	}
}
class HTEvent237 extends HTEvent
{
	const VALUE1 = 'Cross pass';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent239 extends HTEvent
{
	const VALUE1 = 'Technical';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent240 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent241 extends HTEvent
{
	const VALUE1 = 'Counter attack';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent242 extends HTEvent
{
	const VALUE1 = 'Counter attack';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent243 extends HTEvent
{
	const VALUE1 = 'Counter attack';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent250 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent251 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent252 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent253 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent254 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent260 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent261 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent262 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent263 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent264 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent270 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent271 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent272 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent273 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent274 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent280 extends HTEvent
{
	const VALUE1 = 'Free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent281 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'middle';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent282 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'left';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent283 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'right';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent284 extends HTEvent
{
	const VALUE1 = 'Penalty';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefendingGoalieId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent285 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'indirect free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent286 extends HTEvent
{
	const VALUE1 = 'Counter attack';
	const VALUE2 = 'indirect free kick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent287 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'long-range shot';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent288 extends HTEvent
{
	const VALUE1 = 'Regular';
	const VALUE2 = 'long-range shot defended';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSide()
	{
		return self::VALUE2;
	}
}
class HTEvent289 extends HTEvent
{
	const VALUE1 = 'Quick';

	/**
	 * @return Boolean
	 */
	public function isGoal()
	{
		return false;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackingTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent301 extends HTEvent
{
	const VALUE1 = 'Rain';
	const VALUE2 = 'Technical';

	/**
	 * @return String
	 */
	public function getWeather()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSpecialty()
	{
		return self::VALUE2;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return false;
	}
}
class HTEvent302 extends HTEvent
{
	const VALUE1 = 'Rain';
	const VALUE2 = 'Powerful';

	/**
	 * @return String
	 */
	public function getWeather()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSpecialty()
	{
		return self::VALUE2;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent303 extends HTEvent
{
	const VALUE1 = 'Sunny';
	const VALUE2 = 'Technical';

	/**
	 * @return String
	 */
	public function getWeather()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSpecialty()
	{
		return self::VALUE2;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return true;
	}
}
class HTEvent304 extends HTEvent
{
	const VALUE1 = 'Sunny';
	const VALUE2 = 'Powerful';

	/**
	 * @return String
	 */
	public function getWeather()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSpecialty()
	{
		return self::VALUE2;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return false;
	}
}
class HTEvent305 extends HTEvent
{
	const VALUE1 = 'Rain';
	const VALUE2 = 'Quick';

	/**
	 * @return String
	 */
	public function getWeather()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSpecialty()
	{
		return self::VALUE2;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return false;
	}
}
class HTEvent306 extends HTEvent
{
	const VALUE1 = 'Sunny';
	const VALUE2 = 'Quick';

	/**
	 * @return String
	 */
	public function getWeather()
	{
		return self::VALUE1;
	}

	/**
	 * @return String
	 */
	public function getSpecialty()
	{
		return self::VALUE2;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getSpecialEventPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Boolean
	 */
	public function isPositiveEvent()
	{
		return false;
	}
}
class HTEvent331 extends HTEvent
{
	const VALUE1 = 'Pressing';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent332 extends HTEvent
{
	const VALUE1 = 'Counter attack';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent333 extends HTEvent
{
	const VALUE1 = 'Attack in the middle';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent334 extends HTEvent
{
	const VALUE1 = 'Attack on wings';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent335 extends HTEvent
{
	const VALUE1 = 'Creative';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent336 extends HTEvent
{
	const VALUE1 = 'Long shot';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent343 extends HTEvent
{
	const VALUE1 = 'Attack in the middle';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent344 extends HTEvent
{
	const VALUE1 = 'Attack on wings';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent350 extends HTEvent
{
	const VALUE1 = 'Deficit';

	/**
	 * @return Integer
	 */
	public function getReplacedPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getReplacedPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getReplacementPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getReason()
	{
		return self::VALUE1;
	}
}
class HTEvent351 extends HTEvent
{
	const VALUE1 = 'Lead';

	/**
	 * @return Integer
	 */
	public function getReplacedPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getReplacedPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getReplacementPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getReason()
	{
		return self::VALUE1;
	}
}
class HTEvent352 extends HTEvent
{
	const VALUE1 = 'Score independently';

	/**
	 * @return Integer
	 */
	public function getReplacedPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getReplacedPlayersTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getReplacementPlayerId()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return String
	 */
	public function getReason()
	{
		return self::VALUE1;
	}
}
class HTEvent360 extends HTEvent
{
	const VALUE1 = 'Deficit';

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getReason()
	{
		return self::VALUE1;
	}
}
class HTEvent361 extends HTEvent
{
	const VALUE1 = 'Lead';

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getReason()
	{
		return self::VALUE1;
	}
}
class HTEvent362 extends HTEvent
{
	const VALUE1 = 'Score independently';

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return String
	 */
	public function getReason()
	{
		return self::VALUE1;
	}
}
class HTEvent400 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent401 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent402 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent403 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent404 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent500 extends HTEvent
{
	const VALUE1 = 'Draw';

	/**
	 * @return String
	 */
	public function getWinningTeam()
	{
		return self::VALUE1;
	}
}
class HTEvent501 extends HTEvent
{
	const VALUE1 = 'Away team';

	/**
	 * @return String
	 */
	public function getWinningTeam()
	{
		return self::VALUE1;
	}
}
class HTEvent502 extends HTEvent
{
	const VALUE1 = 'Home team';

	/**
	 * @return String
	 */
	public function getWinningTeam()
	{
		return self::VALUE1;
	}
}
class HTEvent503 extends HTEvent
{
	const VALUE1 = 'None';

	/**
	 * @return String
	 */
	public function getWinningTeam()
	{
		return self::VALUE1;
	}
}
class HTEvent504 extends HTEvent
{
	const VALUE1 = 'Away team';

	/**
	 * @return String
	 */
	public function getWinningTeam()
	{
		return self::VALUE1;
	}
}
class HTEvent505 extends HTEvent
{
	const VALUE1 = 'Home team';

	/**
	 * @return String
	 */
	public function getWinningTeam()
	{
		return self::VALUE1;
	}
}
class HTEvent510 extends HTEvent
{
	const VALUE1 = 'First booking';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent511 extends HTEvent
{
	const VALUE1 = 'First booking';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent512 extends HTEvent
{
	const VALUE1 = 'Second booking';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent513 extends HTEvent
{
	const VALUE1 = 'Second booking';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent514 extends HTEvent
{
	const VALUE1 = 'Red Card';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}

	/**
	 * @return Integer
	 */
	public function getPlayerId()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}
}
class HTEvent550 extends HTEvent
{
	/**
	 * @return Integer
	 */
	public function getTeamId()
	{
		if(!isset($this->subjectTeamID) || $this->subjectTeamID === null)
		{
			$this->subjectTeamID = $this->getXml()->getElementsByTagName('SubjectTeamID')->item(0)->nodeValue;
		}
		return $this->subjectTeamID;
	}

	/**
	 * @return Integer
	 */
	public function getDefenseRating()
	{
		if(!isset($this->objectPlayerID) || $this->objectPlayerID === null)
		{
			$this->objectPlayerID = $this->getXml()->getElementsByTagName('ObjectPlayerID')->item(0)->nodeValue;
		}
		return $this->objectPlayerID;
	}

	/**
	 * @return Integer
	 */
	public function getAttackRating()
	{
		if(!isset($this->subjectPlayerID) || $this->subjectPlayerID === null)
		{
			$this->subjectPlayerID = $this->getXml()->getElementsByTagName('SubjectPlayerID')->item(0)->nodeValue;
		}
		return $this->subjectPlayerID;
	}
}
class HTEvent599 extends HTEvent
{
	const VALUE1 = 'Full time';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent600 extends HTEvent
{
	const VALUE1 = 'Comment afterwards';

	/**
	 * @return String
	 */
	public function getType()
	{
		return self::VALUE1;
	}
}
class HTEvent
{
	const N1 = 'StartingFormation';
	const N2 = 'StartingLineUp';
	const N3 = 'YouthNeighbourhood';
	const N4 = 'MatchPlace';
	const N5 = 'Weather';
	const N6 = 'Possession';
	const N7 = 'PlayerPerformance';
	const N8 = 'Score';
	const N9 = 'PossesionEquals';
	const N10 = 'ShootoutPenaltyEvent';
	const N11 = 'OverConfidence';
	const N12 = 'Organization';
	const N13 = 'GoingDefensive';
	const N14 = 'PressingSuccessful';
	const N15 = 'Extratime';
	const N16 = 'Captain';
	const N17 = 'Bruised';
	const N18 = 'Injury';
	const N19 = 'RegularChanceEvent';
	const N20 = 'SpecialChanceEvent';
	const N21 = 'WeatherEvent';
	const N22 = 'Tactic';
	const N23 = 'Substitution';
	const N24 = 'TacticChange';
	const N25 = 'Absurd';
	const N26 = 'Walkover';
	const N27 = 'BreakGame';
	const N28 = 'Card';
	const N29 = 'IndirectFreeKickRating';
	const N30 = 'Comments';

	const E20 = HTEvent::N1;
	const E21 = HTEvent::N2;
	const E22 = HTEvent::N3;
	const E25 = HTEvent::N4;
	const E26 = HTEvent::N4;
	const E27 = HTEvent::N4;
	const E30 = HTEvent::N5;
	const E31 = HTEvent::N5;
	const E32 = HTEvent::N5;
	const E33 = HTEvent::N5;
	const E40 = HTEvent::N6;
	const E41 = HTEvent::N7;
	const E42 = HTEvent::N7;
	const E45 = HTEvent::N8;
	const E46 = HTEvent::N7;
	const E47 = HTEvent::N9;
	const E55 = HTEvent::N10;
	const E56 = HTEvent::N10;
	const E57 = HTEvent::N10;
	const E58 = HTEvent::N10;
	const E59 = HTEvent::N10;
	const E60 = HTEvent::N11;
	const E61 = HTEvent::N12;
	const E62 = HTEvent::N13;
	const E63 = HTEvent::N12;
	const E64 = HTEvent::N12;
	const E65 = HTEvent::N12;
	const E66 = HTEvent::N12;
	const E67 = HTEvent::N12;
	const E68 = HTEvent::N14;
	const E69 = HTEvent::N12;
	const E70 = HTEvent::N15;
	const E71 = HTEvent::N15;
	const E72 = HTEvent::N15;
	const E73 = HTEvent::N15;
	const E80 = HTEvent::N16;
	const E90 = HTEvent::N17;
	const E91 = HTEvent::N18;
	const E92 = HTEvent::N18;
	const E93 = HTEvent::N18;
	const E94 = HTEvent::N17;
	const E95 = HTEvent::N18;
	const E96 = HTEvent::N18;
	const E97 = HTEvent::N18;
	const E100 = HTEvent::N19;
	const E101 = HTEvent::N19;
	const E102 = HTEvent::N19;
	const E103 = HTEvent::N19;
	const E104 = HTEvent::N19;
	const E105 = HTEvent::N20;
	const E106 = HTEvent::N20;
	const E107 = HTEvent::N20;
	const E108 = HTEvent::N20;
	const E109 = HTEvent::N20;
	const E110 = HTEvent::N19;
	const E111 = HTEvent::N19;
	const E112 = HTEvent::N19;
	const E113 = HTEvent::N19;
	const E114 = HTEvent::N19;
	const E115 = HTEvent::N20;
	const E116 = HTEvent::N20;
	const E117 = HTEvent::N20;
	const E118 = HTEvent::N20;
	const E119 = HTEvent::N20;
	const E120 = HTEvent::N19;
	const E121 = HTEvent::N19;
	const E122 = HTEvent::N19;
	const E123 = HTEvent::N19;
	const E124 = HTEvent::N19;
	const E125 = HTEvent::N20;
	const E130 = HTEvent::N19;
	const E131 = HTEvent::N19;
	const E132 = HTEvent::N19;
	const E133 = HTEvent::N19;
	const E134 = HTEvent::N19;
	const E135 = HTEvent::N20;
	const E136 = HTEvent::N20;
	const E137 = HTEvent::N20;
	const E138 = HTEvent::N20;
	const E139 = HTEvent::N20;
	const E140 = HTEvent::N19;
	const E141 = HTEvent::N19;
	const E142 = HTEvent::N19;
	const E143 = HTEvent::N19;
	const E150 = HTEvent::N19;
	const E151 = HTEvent::N19;
	const E152 = HTEvent::N19;
	const E153 = HTEvent::N19;
	const E154 = HTEvent::N19;
	const E160 = HTEvent::N19;
	const E161 = HTEvent::N19;
	const E162 = HTEvent::N19;
	const E163 = HTEvent::N19;
	const E164 = HTEvent::N19;
	const E170 = HTEvent::N19;
	const E171 = HTEvent::N19;
	const E172 = HTEvent::N19;
	const E173 = HTEvent::N19;
	const E174 = HTEvent::N19;
	const E180 = HTEvent::N19;
	const E181 = HTEvent::N19;
	const E182 = HTEvent::N19;
	const E183 = HTEvent::N19;
	const E184 = HTEvent::N19;
	const E185 = HTEvent::N19;
	const E186 = HTEvent::N19;
	const E187 = HTEvent::N19;
	const E200 = HTEvent::N19;
	const E201 = HTEvent::N19;
	const E202 = HTEvent::N19;
	const E203 = HTEvent::N19;
	const E204 = HTEvent::N19;
	const E205 = HTEvent::N20;
	const E206 = HTEvent::N20;
	const E207 = HTEvent::N20;
	const E208 = HTEvent::N20;
	const E209 = HTEvent::N20;
	const E210 = HTEvent::N19;
	const E211 = HTEvent::N19;
	const E212 = HTEvent::N19;
	const E213 = HTEvent::N19;
	const E214 = HTEvent::N19;
	const E215 = HTEvent::N20;
	const E216 = HTEvent::N20;
	const E217 = HTEvent::N20;
	const E218 = HTEvent::N20;
	const E219 = HTEvent::N20;
	const E220 = HTEvent::N19;
	const E221 = HTEvent::N19;
	const E222 = HTEvent::N19;
	const E223 = HTEvent::N19;
	const E224 = HTEvent::N19;
	const E225 = HTEvent::N20;
	const E230 = HTEvent::N19;
	const E231 = HTEvent::N19;
	const E232 = HTEvent::N19;
	const E233 = HTEvent::N19;
	const E234 = HTEvent::N19;
	const E235 = HTEvent::N20;
	const E236 = HTEvent::N20;
	const E237 = HTEvent::N20;
	const E239 = HTEvent::N20;
	const E240 = HTEvent::N19;
	const E241 = HTEvent::N19;
	const E242 = HTEvent::N19;
	const E243 = HTEvent::N19;
	const E250 = HTEvent::N19;
	const E251 = HTEvent::N19;
	const E252 = HTEvent::N19;
	const E253 = HTEvent::N19;
	const E254 = HTEvent::N19;
	const E260 = HTEvent::N19;
	const E261 = HTEvent::N19;
	const E262 = HTEvent::N19;
	const E263 = HTEvent::N19;
	const E264 = HTEvent::N19;
	const E270 = HTEvent::N19;
	const E271 = HTEvent::N19;
	const E272 = HTEvent::N19;
	const E273 = HTEvent::N19;
	const E274 = HTEvent::N19;
	const E280 = HTEvent::N19;
	const E281 = HTEvent::N19;
	const E282 = HTEvent::N19;
	const E283 = HTEvent::N19;
	const E284 = HTEvent::N19;
	const E285 = HTEvent::N19;
	const E286 = HTEvent::N19;
	const E287 = HTEvent::N19;
	const E288 = HTEvent::N19;
	const E289 = HTEvent::N20;
	const E301 = HTEvent::N21;
	const E302 = HTEvent::N21;
	const E303 = HTEvent::N21;
	const E304 = HTEvent::N21;
	const E305 = HTEvent::N21;
	const E306 = HTEvent::N21;
	const E331 = HTEvent::N22;
	const E332 = HTEvent::N22;
	const E333 = HTEvent::N22;
	const E334 = HTEvent::N22;
	const E335 = HTEvent::N22;
	const E336 = HTEvent::N22;
	const E343 = HTEvent::N22;
	const E344 = HTEvent::N22;
	const E350 = HTEvent::N23;
	const E351 = HTEvent::N23;
	const E352 = HTEvent::N23;
	const E360 = HTEvent::N24;
	const E361 = HTEvent::N24;
	const E362 = HTEvent::N24;
	const E400 = HTEvent::N25;
	const E401 = HTEvent::N25;
	const E402 = HTEvent::N25;
	const E403 = HTEvent::N25;
	const E404 = HTEvent::N25;
	const E500 = HTEvent::N26;
	const E501 = HTEvent::N26;
	const E502 = HTEvent::N26;
	const E503 = HTEvent::N27;
	const E504 = HTEvent::N27;
	const E505 = HTEvent::N27;
	const E510 = HTEvent::N28;
	const E511 = HTEvent::N28;
	const E512 = HTEvent::N28;
	const E513 = HTEvent::N28;
	const E514 = HTEvent::N28;
	const E550 = HTEvent::N29;
	const E599 = HTEvent::N8;
	const E600 = HTEvent::N30;

	/**
	 * @var DOMDocument
	 */
	private $xml;
	protected $sujectPlayerId = null;
	protected $sujectTeamId = null;
	protected $objectPlayerId = null;

	/**
	 * @param DOMDocument $xml
	 */
	public function __construct($xml)
	{
		$this->xml = $xml;
	}

	/**
	 * Return xml object
	 *
	 * @param Boolean $asObject
	 * @return DOMDocument
	 */
	public function getXml($asObject = true)
	{
		if($asObject === true)
		{
			return $this->xml;
		}
		return $this->xml->saveXML();
	}

	/**
	 * Return type of event as english string
	 *
	 * @return String
	 */
	public function getEventTypeName()
	{
		$number = substr(get_class($this), strlen(get_class()));
		return constant(get_class().'::E'.$number);
	}

	/**
	 * Returns available methods on event
	 *
	 * @return Array
	 */
	public function getMethodsNames()
	{
		$methods = get_class_methods(get_class($this));
		return array_diff($methods, array('__construct', 'getXml', 'getMethodsNames'));
	}
}
/*-events-*/