<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.09.2016
 * Time: 14:00
 */

namespace AnalizPdvBundle\Tests\Utilits\createWriteFile;


use AnalizPdvBundle\Utilits\createWriteFile\getReaderExcel;
use AnalizPdvBundle\Utilits\createWriteFile\getWriteExcel;

class getWriteExcelTest extends \PHPUnit_Framework_TestCase
{
	public function test_getNewFileName()
	{
		$write=new getWriteExcel("Analiz_in.xls");
		$write->setParamFile(7,2016,'678');
		$f=$write->getNewFileName();
		$this->assertEquals('./Analiz_in month 7 year 2016 numBranch 678.xls',$f);
	}

	public function test_setDataFromWorksheet()
	{
		$arr=array('7','2016','678');
		$file="d:\\OpenServer525\\domains\\AnalizPDV\\web\\template\\analiz_In.xlsx";
		$write=new getWriteExcel($file);
		$write->setParamFile(7,2016,'678');
		$f=$write->getNewFileName();
		$write->setDataFromWorksheet('reestr=edrpu',$arr,'A4');
		$write->fileWriteAndSave();
		//$this->assertEquals('./Analiz_in month 7 year 2016 numBranch 678.xls',$f);

	}
}
