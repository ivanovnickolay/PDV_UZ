<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.09.2016
 * Time: 17:04
 */

namespace AnalizPdvBundle\Tests\Utilits\loadData;


use AnalizPdvBundle\Utilits\loadData\workWithFiles;

class workWithFilesTest extends \PHPUnit_Framework_TestCase
{

	public function datavalidFile()
	{
		return array(
		["19082016095050_40075815_J1201508_TAB1.xls",true],
			["19082016095050_40075815_J1201508_TAB1.xlsz",false],
				["19082016100630_40075815_J1201508_TAB1.xlsx",true],
					["19082016100630_40075815_J1201508_TAB1_.xlsx",true],
						["19082016100632_40075815_J1201508_TAB2.xlsx",true],
							["test.xlsx",false],
								["testtab1.xlsx",false],
							["testTAB1.xlsx",true]
		);
	}

	/**
	 * @dataProvider datavalidFile
	 */
	public function test_isValidFile($file,$res)
	{
		$l=new workWithFiles($file);
		$mes="Equals file name " .$file." res ".$res;
			$this->assertEquals($res,$l->isValidFile($file),$mes);
		unset($l);

	}

	public function test_getFiles()
	{
		$l=new workWithFiles("d:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\reestrBranch\\");
			$arr=$l->getFiles();
		var_dump($arr);
	}
	public function test_moveFiles()
	{
		$fileFrom="d:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\reestrBranch\\19082016095008_40075815_J1201508_TAB1.xls";
		$fileTo="d:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\reestrBranch\\Arch";
		workWithFiles::moveFiles($fileFrom,$fileTo);
		$this->assertTrue(file_exists("d:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\reestrBranch\\Arch\\19082016095008_40075815_J1201508_TAB1.xls"));
	}
}
