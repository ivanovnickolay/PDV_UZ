<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.08.2016
 * Time: 23:05
 */

namespace AnalizPdvBundle\Tests\Utilits\creareReaderFile;
use AnalizPdvBundle\Utilits\createReaderFile\getReaderExcel;

class getReaderExcelTest extends \PHPUnit_Framework_TestCase
{
	public function testValidFileName()
	{
		$reader=new getReaderExcel('d:\OpenServer525\domains\AnalizPDV\src\AnalizPdvBundle\Tests\testData\test.xlsx');
		$this->assertTrue($reader->validFileName());
		$this->assertEquals('Excel2007',$reader->getFileType());
		unset($reader);
		$reader=new getReaderExcel('d:\OpenServer525\domains\AnalizPDV\src\AnalizPdvBundle\Tests\testData\test1.xlsx');
		$this->assertFalse($reader->validFileName());
	}

 public function testCreateReader()
 {
	 $reader=new getReaderExcel('d:\OpenServer525\domains\AnalizPDV\src\AnalizPdvBundle\Tests\testData\test.xlsx');
	 $reader->createFilter('F');
	 $obj=$reader->getReader();
	 $this->assertInstanceOf("PHPExcel_Reader_IReader",$obj);
 }
	public function test_getRowDataArray()
	{
		$reader=new getReaderExcel('d:\OpenServer525\domains\AnalizPDV\src\AnalizPdvBundle\Tests\testData\test.xlsx');
		$reader->createFilter('F');
		// создаем класс Ридера PHPExcel_Reader_Excel2007
		$reader->getReader();
		// ПОЛУЧАЕМЫЙ МАССИВ ДВУХ МЕРНЫЙ !!!!
		// получаем загруженный файд согластно установленным фильтрам
		$reader->loadFileWithFilter(2);
		$arr=$reader->getRowDataArray(2);

		var_dump($arr);
		// получение первого столбца
		$this->assertEquals("текст",$arr[0][0]);
		// получение столбца даты
		$this->assertEquals(new \DateTime("12.08.2016"),\PHPExcel_Shared_Date::ExcelToPHPObject($arr[0][2]));
		// Знак разделения дробных частей - точка (((((
		$this->assertEquals("12.11",$arr[0][1]);
	}

	public function test_getMaxRow()
	{
		$reader=new getReaderExcel('d:\OpenServer525\domains\AnalizPDV\src\AnalizPdvBundle\Tests\testData\test.xlsx');
		$reader->createFilter('F');
		$reader->getReader();
		$this->assertEquals(2,$reader->getMaxRow());
	}
}
