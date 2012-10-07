<?php
include_once realpath(dirname(__FILE__).'/../PHT.php');

class TeamdetailsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var HTTeam
	 */
	private $HT;

	/**
	 * @var HTTeamFlags
	 */
	private $HTF;

	/**
	 * @var HTTeamSupporters
	 */
	private $HTS;

	public function setUp()
	{
		$content = file_get_contents(dirname(__FILE__).'/xml/teamdetails.xml');
		$this->HT  = new HTTeam($content);
		$this->HTF = new HTTeamFlags($content);
		$this->HTS = new HTTeamSupporters($content);
	}

	public function testFileName()
	{
		$this->assertEquals("teamdetails.xml", $this->HT->getXmlFileName());
	}

	public function testFileVersion()
	{
		$this->assertEquals("2.6", $this->HT->getXmlFileVersion());
	}

	public function testUserId()
	{
		$this->assertEquals(653581, $this->HT->getUserId());
	}

	public function testFetchedDate()
	{
		$this->assertEquals('2012-03-06 22:02:03', $this->HT->getFetchedDate());
		$this->assertEquals('06/03/2012', $this->HT->getFetchedDate('d/m/Y'));
		$this->assertEquals('1231pm', $this->HT->getFetchedDate('Lwta'));
	}

	public function testLanguage()
	{
		$this->assertEquals(5, $this->HT->getLanguageId());
		$this->assertEquals("Français", $this->HT->getLanguageName());
	}

	public function testHTSupporter()
	{
		$this->assertTrue($this->HT->isHtSupporter());
	}

	public function testLoginname()
	{
		$this->assertEquals('CHPP-teles', $this->HT->getLoginName());
	}

	public function testName()
	{
		$this->assertNull($this->HT->getName());
	}

	public function testIcq()
	{
		$this->assertEmpty($this->HT->getIcq());
	}

	public function testSignupDate()
	{
		$this->assertEquals('2003-10-06 16:36:38', $this->HT->getSignupDate());
		$this->assertEquals('06/10/2003', $this->HT->getSignupDate('d/m/Y'));
		$this->assertEquals('0131pm', $this->HT->getSignupDate('Lwta'));
	}

	public function testActivationDate()
	{
		$this->assertEquals('2003-10-28 03:46:00', $this->HT->getActivationDate());
		$this->assertEquals('28/10/2003', $this->HT->getActivationDate('d/m/Y'));
		$this->assertEquals('0231am', $this->HT->getActivationDate('Lwta'));
	}

	public function testLastloginDate()
	{
		$this->assertEquals('2012-03-06 18:54:33', $this->HT->getLastLoginDate());
		$this->assertEquals('06/03/2012', $this->HT->getLastLoginDate('d/m/Y'));
		$this->assertEquals('1231pm', $this->HT->getLastLoginDate('Lwta'));
	}

	public function testNationalTeam()
	{
		$this->assertEquals(1, $this->HT->getNationalTeamNumber());
		$this->assertEquals(3004, $this->HT->getNationalTeam(1)->getTeamId());
		$this->assertEquals("France", $this->HT->getNationalTeam(1)->getTeamName());
	}

	public function testTeam()
	{
		$this->assertEquals(176295, $this->HT->getTeamId());
		$this->assertEquals("CPAM FC", $this->HT->getTeamName());
		$this->assertEquals("CPAM", $this->HT->getShortTeamName());
	}

	public function testArena()
	{
		$this->assertEquals(176295, $this->HT->getArenaId());
		$this->assertEquals("16 rue de Lausanne", $this->HT->getArenaName());
	}

	public function testLeague()
	{
		$this->assertEquals(5, $this->HT->getLeagueId());
		$this->assertEquals("France", $this->HT->getLeagueName());
	}

	public function testRegion()
	{
		$this->assertEquals(129, $this->HT->getRegionId());
		$this->assertEquals("Alsace", $this->HT->getRegionName());
	}

	public function testTrainer()
	{
		$this->assertEquals(109499810, $this->HT->getTrainerId());
	}

	public function testHomepage()
	{
		$this->assertEquals("http://www.htloto.org", $this->HT->getHomePageUrl());
	}

	public function testDress()
	{
		$this->assertEquals("http://res.hattrick.org/kits/11/102/1019/1018938/matchKitSmall.png", $this->HT->getDressURI());
		$this->assertEquals("http://res.hattrick.org/kits/9/81/805/804563/matchKitSmall.png", $this->HT->getDressAlternateURI());
	}

	public function testLeagueLevel()
	{
		$this->assertEquals(36407, $this->HT->getLeagueLevelId());
		$this->assertEquals("VI.419", $this->HT->getLeagueLevelName());
		$this->assertEquals(6, $this->HT->getLeagueLevel());
	}

	public function testBot()
	{
		$this->assertFalse($this->HT->isBot());
	}

	public function testCup()
	{
		$this->assertFalse($this->HT->isInCup());
		$this->assertNull($this->HT->getCupId());
		$this->assertNull($this->HT->getCupName());
	}

	public function testFriendly()
	{
		$this->assertEquals('175919', $this->HT->getFriendlyOppositeTeamId());
	}

	public function testVictories()
	{
		$this->assertEquals(3, $this->HT->getNumberOfVictories());
		$this->assertEquals(4, $this->HT->getNumberOfUndefeat());
	}

	public function testRank()
	{
		$this->assertEquals(7054, $this->HT->getTeamRank());
	}

	public function testFanclub()
	{
		$this->assertEquals(63884, $this->HT->getFanClubId());
		$this->assertEquals("L'ancien club D5", $this->HT->getFanClubName());
		$this->assertEquals(1870, $this->HT->getFanClubSize());
	}

	public function testLogoUrl()
	{
		$this->assertEquals("http://res.hattrick.org/teamlogo/2/18/177/176295/176295.png", $this->HT->getLogoUrl());
	}

	public function testGuestbook()
	{
		$this->assertEquals(2236, $this->HT->getNumberMessageInGuestbook());
	}

	public function testPressAnnouncement()
	{
		$this->assertEquals("Les caisses sont vides", $this->HT->getPressAnnouncementTitle());
		$this->assertEquals("La défaite de ce soir révèle l'état du club", $this->HT->getPressAnnouncementText());
		$this->assertEquals("2012-03-03 23:13:00", $this->HT->getPressAnnouncementDate());
		$this->assertEquals("03/03/2012", $this->HT->getPressAnnouncementDate('d/m/Y'));
	}

	public function testYouthteam()
	{
		$this->assertEquals(0, $this->HT->getYouthTeamId());
		$this->assertEmpty($this->HT->getYouthTeamName());
	}

	public function testVisits()
	{
		$this->assertEquals(2, $this->HT->getNumberOfVisits());
	}

	public function testFlags()
	{
		$this->assertEquals(1, $this->HTF->getNumberFlagsAway());
		$this->assertEquals(5, $this->HTF->getFlagAway(1)->getLeagueId());
		$this->assertEquals("France", $this->HTF->getFlagAway(1)->getLeagueName());
		$this->assertEquals("FR", $this->HTF->getFlagAway(1)->getCountryCode());
		$this->assertEquals(112, $this->HTF->getNumberFlagsHome());
		$this->assertEquals(77, $this->HTF->getFlagHome(1)->getLeagueId());
		$this->assertEquals("Al Maghrib", $this->HTF->getFlagHome(1)->getLeagueName());
		$this->assertEquals("MO", $this->HTF->getFlagHome(1)->getCountryCode());
		$this->assertEquals(144, $this->HTF->getFlagHome(29)->getLeagueId());
		$this->assertEquals("Dhivehi Raajje", $this->HTF->getFlagHome(29)->getLeagueName());
		$this->assertEquals("MV", $this->HTF->getFlagHome(29)->getCountryCode());
		$this->assertEquals(70, $this->HTF->getFlagHome(112)->getLeagueId());
		$this->assertEquals("Việt Nam", $this->HTF->getFlagHome(112)->getLeagueName());
		$this->assertEquals("VN", $this->HTF->getFlagHome(112)->getCountryCode());
	}
}