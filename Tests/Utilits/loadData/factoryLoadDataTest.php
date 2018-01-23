<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.09.2016
 * Time: 21:42
 */

namespace AnalizPdvBundle\Tests\Utilits\loadData;


use AnalizPdvBundle\Utilits\loadData\factoryLoadData;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class factoryLoadDataTest extends KernelTestCase
{
	private $em;
	const pathToReestr="d:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\reestrBranch\\";
	private $path;
	protected function setUp()
	{
		self::bootKernel();

		$this->em = static::$kernel->getContainer()
			->get('doctrine')
			->getManager();
		$this->path = static::$kernel->getContainer()
			->getParameter('file_dir_reestr');

	}

	public function test_loadDataFromFile_in()
	{
		$fileName='19082016100630_40075815_J1201508_TAB1.xlsx';
		$factoryLoad=new factoryLoadData($this->em);
			$pathToFileReestr=$this->path.$fileName;
				$factoryLoad->loadDataFromFile($pathToFileReestr,'RestrIn');

	}

	public function test_loadDataFromFile_out()
	{
		$fileName='19082016100632_40075815_J1201508_TAB2.xlsx';
		$factoryLoad=new factoryLoadData($this->em);
		$pathToFileReestr=$this::pathToReestr.$fileName;
		$factoryLoad->loadDataFromFile($pathToFileReestr,'RestrOut');

	}
}
