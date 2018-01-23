<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.09.2016
 * Time: 23:46
 */

namespace AnalizPdvBundle\Tests\Utilits\loadData;


use AnalizPdvBundle\Utilits\createEntitys\interfaceReestr;
use AnalizPdvBundle\Utilits\createEntitys\reestrOut;
use AnalizPdvBundle\Utilits\loadData\loadData;
use AnalizPdvBundle\Utilits\ValidEntity\validReestrIn;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class loadDataFullFileTest_Out extends KernelTestCase
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
		$load=new loadData($this->em,'d:\OpenServer525\domains\AnalizPDV\src\AnalizPdvBundle\Tests\testData\_19082016100632_40075815_J1201508_TAB2.xlsx','DR',1000);
		$db=new reestrOut\createReestrOut();
		$load->setEntity($db);
		$load->setValidator(new validReestrIn('Out'));
		$load->loadData();
	}
}
