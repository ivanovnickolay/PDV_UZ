<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.08.2016
 * Time: 23:22
 */

namespace AnalizPdvBundle\Tests\Utilits\createEntity\ReestrIn;

use AnalizPdvBundle\Utilits\createEntity\reestrIn\createReestrIn;
use AnalizPdvBundle\Utilits\createReaderFile\getReaderExcel;

class createReestrInTest extends \PHPUnit_Framework_TestCase
{
		public function test_createReestrIn()
		{
			$reader= new getReaderExcel('d:\OpenServer525\domains\AnalizPDV\src\AnalizPdvBundle\Tests\testData\19082016095050_40075815_J1201508_TAB1.xls');
			$reader->createFilter('EE',20);
			$reader->getReader();
			$this->assertInstanceOf("PHPExcel_Reader_IReader",$reader->getReader());
			$en=new createReestrIn();
			$reader->loadFileWithFilter(2);
			$arr=$reader->getRowDataArray(2);
			var_dump($arr);
			$entity=$en->createReestr($arr);
			// http://stackoverflow.com/questions/10420925/phpunit-forces-me-to-require-classes-before-asserting
			//-instance-of
			$this->assertInstanceOf("AnalizPdvBundle\Entity\ReestrbranchIn",$entity);
			$this->assertEquals('7',$entity->getMonth());
			$this->assertEquals('2016',$entity->getYear());
			$this->assertEquals('578',$entity->getNumBranch());
			$this->assertEquals(new \DateTime('13.07.2016'),$entity->getDateGetInvoice());
			$this->assertEquals(new \DateTime('01.07.2016'),$entity->getDateCreateInvoice());
			$this->assertEquals('1',$entity->getNumInvoice());
			$this->assertEquals('ПНЕ',$entity->getTypeInvoiceFull());
			$this->assertEquals('Приватне підприємство "Укрмед  Вінниця"',$entity->getNameClient());
			$this->assertEquals('248989002286',$entity->getInnClient());
			$this->assertEquals('640.34',$entity->getZagSumm());
			$this->assertEquals('0',$entity->getBaza20());
			$this->assertEquals('0',$entity->getPdv20());
			$this->assertEquals('598.45',$entity->getBaza7());
			$this->assertEquals('41.89',$entity->getPdv7());
			$this->assertEquals('0',$entity->getBaza0());
			$this->assertEquals('0',$entity->getPdv0());
			$this->assertEquals('0',$entity->getBazaZvil());
			$this->assertEquals('0',$entity->getPdvZvil());
			$this->assertEquals('0',$entity->getBazaNeGos());
			$this->assertEquals('0',$entity->getPdvNeGos());
			$this->assertEquals('0',$entity->getBazaZaMezhi());
			$this->assertEquals('0',$entity->getPdvZaMezhi());
			$this->assertEquals(new \DateTime('0000-00-00'),$entity->getRkeDateCreateInvoice());
			$this->assertEquals('',$entity->getRkeNumInvoice());
			$this->assertEquals('',$entity->getRkePidstava());
			$this->assertEquals('1/ПНЕ/01-07-2016/248989002286',$entity->getKeyField());


		}
}
