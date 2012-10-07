<?php
include_once '../PHT.php';

class ClubTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var HTClub
	 */
	private $HT;

	public function setUp()
	{
		$this->HT = new HTClub(file_get_contents(dirname(__FILE__).'/xml/club.xml'));
	}

	public function testFileName()
	{
		$this->assertEquals("club.xml", $this->HT->getXmlFileName());
	}

	public function testFileVersion()
	{
		$this->assertEquals("1.2", $this->HT->getXmlFileVersion());
	}

	public function testUserId()
	{
		$this->assertEquals(653581, $this->HT->getUserId());
	}

	public function testFetchedDate()
	{
		$this->assertEquals('2012-03-06 20:23:23', $this->HT->getFetchedDate());
		$this->assertEquals('06/03/2012', $this->HT->getFetchedDate('d/m/Y'));
		$this->assertEquals('1231pm', $this->HT->getFetchedDate('Lwta'));
	}

	public function testTeamId()
	{
		$this->assertEquals(176295, $this->HT->getTeamId());
	}

	public function testTeamName()
	{
		$this->assertEquals("CPAM FC", $this->HT->getTeamName());
	}

	public function testSpecialists()
	{
		$spe = $this->HT->getSpecialists();
		$base = HTSpecialists::BASE_COST;
		$this->assertEquals(18000, $base);
		$this->assertEquals(10,  $spe->getAssistantTrainers());
		$this->assertEquals(10 * $base, $spe->getAssistantTrainersCost());
		$this->assertEquals(2,   $spe->getPsychologists());
		$this->assertEquals(2  * $base, $spe->getPsychologistsCost());
		$this->assertEquals(4,   $spe->getPressSpoken());
		$this->assertEquals(4  * $base, $spe->getPressSpokenCost());
		$this->assertEquals(4,   $spe->getPhysiotherapists());
		$this->assertEquals(4  * $base, $spe->getPhysiotherapistsCost());
		$this->assertEquals(8,   $spe->getDoctors());
		$this->assertEquals(8  * $base, $spe->getDoctorsCost());

		$spe = $this->HT->getSpecialists(HTMoney::France);
		$this->assertEquals(10 * 1800, $spe->getAssistantTrainersCost());
		$this->assertEquals(2  * 1800, $spe->getPsychologistsCost());
		$this->assertEquals(4  * 1800, $spe->getPressSpokenCost());
		$this->assertEquals(4  * 1800, $spe->getPhysiotherapistsCost());
		$this->assertEquals(8  * 1800, $spe->getDoctorsCost());
	}

	public function testYouthSquad()
	{
		$ys = $this->HT->getYouthSquad();
		$this->assertEquals(200000, $ys->getInvestment());
		$this->assertEquals(20000, $ys->getInvestment(HTMoney::France));
		$this->assertTrue($ys->hasPromoted());
		$this->assertEquals(8, $ys->getYouthLevel());
	}
}