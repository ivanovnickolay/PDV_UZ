<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.09.2016
 * Time: 23:11
 */

namespace AnalizPdvBundle\Tests\Utilits\loadReestrBranch;


use AnalizPdvBundle\Utilits\createWriteFile\renameWorksheet;
use AnalizPdvBundle\Utilits\loadData\workWithFiles;
use AnalizPdvBundle\Utilits\loadReestrBranch\loadReestrBranch;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class loadAllFilesFromDir_Test extends KernelTestCase
{
	private  $em;

	public function setUp()
	{
		self::bootKernel();
		$this->em = static::$kernel->getContainer()
			->get('doctrine')
			->getManager();
	}
	public function test_renameAllFilesFromDir()
	{
		$dir = "d:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\reestrBranch\\";
		$arr = workWithFiles::getFilesArray ($dir);
		foreach ($arr as $fileName => $type) {
			//loadReestrBranch::load ($this->em , $fileName , $type);
			renameWorksheet::renameWorksheet($fileName);
		}
	}

	public function test_loadAllFilesFromDir()
	{
		$dir = "d:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\reestrBranch\\";
		$dirTo = "d:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\reestrBranchArch\\";
		$arr = workWithFiles::getFilesArray ($dir);
		foreach ($arr as $fileName => $type) {
			loadReestrBranch::load ($this->em , $fileName , $type);
			workWithFiles::moveFiles ($fileName , $dirTo);
		}
	}
	 public function test_loadAllFilesFromDir_first10()
	 {
		 $dir = "d:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\reestrBranch\\";
		 $dirTo = "d:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\reestrBranchArch\\";
		 $arr = workWithFiles::getFilesArray ($dir);
		 $arr_slice = array_slice ($arr , 0 , 5);
		 foreach ($arr_slice as $fileName => $type) {
			 loadReestrBranch::load ($this->em , $fileName , $type);
			 workWithFiles::moveFiles ($fileName , $dirTo);
		 }
	 }
}
