<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.09.2016
 * Time: 23:46
 */

namespace AnalizPdvBundle\Tests\Utilits\loadData;


use AnalizPdvBundle\Utilits\createEntitys\interfaceReestr;
use AnalizPdvBundle\Utilits\createEntitys\reestrIn;
use AnalizPdvBundle\Utilits\loadData\loadData;
use AnalizPdvBundle\Utilits\ValidEntity\validReestrIn;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class loadDataFullFileTest_In extends KernelTestCase
{
	private $em;
	protected function setUp()
	{
		self::bootKernel();

		$this->em = static::$kernel->getContainer()
			->get('doctrine')
			->getManager();
	}

	public function test_loadData()
	{
		$load=new loadData($this->em,'d:\OpenServer525\domains\AnalizPDV\src\AnalizPdvBundle\Tests\testData\19082016100630_40075815_J1201508_TAB1.xlsx','EE',500);
		$db=new reestrIn\createReestrIn();
		$load->setEntity($db);
		$load->setValidator(new validReestrIn('In'));
		$load->loadData();
	}
}
