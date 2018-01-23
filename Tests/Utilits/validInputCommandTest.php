<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02.10.2016
 * Time: 17:02
 */

namespace AnalizPdvBundle\Tests\Utilits;


use AnalizPdvBundle\Utilits\validInputCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class validInputCommandTest extends KernelTestCase
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * {@inheritDoc}
	 */
	protected function setUp()
	{
		self::bootKernel();

		$this->em = static::$kernel->getContainer()
			->get('doctrine')
			->getManager();
	}

	protected function tearDown()
	{
		parent::tearDown();

		$this->em->close();
		$this->em = null; // avoid memory leaks
	}

	public function DataFor_isValidMonth()
	{
		return [
			["012",false],
			["018",false],
			["999",false],
			["1",true],
			[6,true],
			["fdfssadf",false],
			["12",true],
			["-2",false],
			["o2",false],
			["",false],
		];
	}
	/**
	 * @dataProvider DataFor_isValidMonth
	 */
	public function test_validMonth($m,$r)
	{
		$v=new validInputCommand($this->em);
		$txt='test_validMonth-> month '.$m.' result plan '.$r;
		$this->assertEquals($r,$v->validMonth($m),$txt);
		unset($v);
	}

	public function DataFor_isValidYear()
	{
		return [
			["2012",false],
			["32018",false],
			["999",false],
			["2016",true],
			["fdfssadf",false],
			["2017",true],
			[2016,true],
			["-2017",false],
			["2o16",false],
			["",false],
		];
	}
	/**
	 * @dataProvider DataFor_isValidYear
	 */
	public function test_validMYear($y,$r)
	{
		$v=new validInputCommand($this->em);
		$txt='test_validMonth-> year '.$y.' result plan '.$r;
		$this->assertEquals($r,$v->validYear($y),$txt);
		unset($v);
	}
}
