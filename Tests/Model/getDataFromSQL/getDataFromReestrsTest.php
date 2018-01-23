<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.09.2016
 * Time: 17:57
 */

namespace AnalizPdvBundle\Tests\Model\getDataFromSQL\getDataFtomSQL;


use AnalizPdvBundle\Model\getDataFromSQL\getDataFromReestrsAll;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class getDataFromReestrsTest extends KernelTestCase
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

public function test_getReestrInEqualErpn()
{
	$n=new getDataFromReestrsAll($this->em);
	$a=$n->getReestrInEqualErpn(7,2016,678);
	var_dump($a);
}

}
