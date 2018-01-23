<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02.09.2016
 * Time: 13:52
 */

namespace AnalizPdvBundle\Tests\Utilits\createEntity\ReestrOut;


use AnalizPdvBundle\Utilits\createEntitys\reestrOut\createReestrOut;
use AnalizPdvBundle\Utilits\createReaderFile\getReaderExcel;

class createReestrOutTest extends \PHPUnit_Framework_TestCase
{
	public function test_createReestrOut()
	{
		$reader= new getReaderExcel('d:\OpenServer525\domains\AnalizPDV\src\AnalizPdvBundle\Tests\testData\19082016100632_40075815_J1201508_TAB2.xlsx');
		$reader->createFilter('DR',20);
		$reader->getReader();
		$this->assertInstanceOf("PHPExcel_Reader_IReader",$reader->getReader());
		$en=new createReestrOut();
		$reader->loadFileWithFilter(2);
		$arr=$reader->getRowDataArray(2);
		//var_dump($arr);
		$entity=$en->createReestr($arr);
		$this->assertInstanceOf("AnalizPdvBundle\Entity\ReestrbranchOut",$entity);
		$this->assertEquals('7',$entity->getMonth());
		$this->assertEquals('2016',$entity->getYear());
		$this->assertEquals('678',$entity->getNumBranch());
		$this->assertEquals(new \DateTime('30.06.2016'),$entity->getDateCreateInvoice());
		$this->assertEquals('122//275',$entity->getNumInvoice());
		$this->assertEquals('ПНЕ',$entity->getTypeInvoiceFull());
		$this->assertEquals('ПАТ \'\'УКРАЄНСЬКА ЗАЛIЗНИЦЯ\'\', Регiональна фiлiя \'\'ПРИДНIПРОВСЬКА ЗАЛIЗНИЦЯ\'\'  СП \'\'ДНIПРОПЕТРОВСЬКЕ МОТОРВАГОННЕ ДЕПО\'\'',$entity->getNameClient());
		$this->assertEquals('400000000000',$entity->getInnClient());
		$this->assertEquals('3544.98',$entity->getZagSumm());
		$this->assertEquals('2954.15',$entity->getBaza20());
		$this->assertEquals('590.83',$entity->getPdv20());
		$this->assertEquals('0',$entity->getBaza7());
		$this->assertEquals('0',$entity->getPdv7());
		$this->assertEquals('0',$entity->getBaza0());
		$this->assertEquals('0',$entity->getBazaZvil());
		$this->assertEquals('0',$entity->getBazaNeObj());
		$this->assertEquals('0',$entity->getBazaZaMezhiTovar());
		$this->assertEquals('0',$entity->getBazaZaMezhiPoslug());
		$this->assertEquals(new \DateTime('01.01.2000'),$entity->getRkeDateCreateInvoice());
		$this->assertEquals(0,$entity->getRkeNumInvoice());
		$this->assertEquals('Зайво виписана',$entity->getRkePidstava());
		$this->assertEquals('122//275/ПНЕ/30-06-2016/400000000000',$entity->getKeyField());

	}
}
