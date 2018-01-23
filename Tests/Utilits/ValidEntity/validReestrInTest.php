<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02.09.2016
 * Time: 18:56
 */

namespace AnalizPdvBundle\Tests\Utilits\ValidEntity;


use AnalizPdvBundle\Utilits\ValidEntity\validReestrIn;

class validReestrInTest extends \PHPUnit_Framework_TestCase
{
	public $reest;
	public function test_validTypeInvoiceFull()
	{
		$reest=new validReestrIn("In");
		$reest->validTypeInvoiceFull("ПНП");
		$this->assertContains('Указан тип документа, на бумажных носителях. ',$reest->error);
		$reest->validTypeInvoiceFull("ПП");
		$this->assertContains('Тип документа не соответствует установленному',$reest->error);
	}

	public function test_validInn()
	{
		$reest=new validReestrIn("In");
		$reest->validInn("ПНП");
		$this->assertContains('ИНН клиента содержит буквы',$reest->error);
	}

	public function test_validNumInvoice()
	{
		$reest=new validReestrIn("In");
		$reest->validNumInvoice("f454//44");
		$this->assertContains('Номер накладной содержит буквы',$reest->error);
		$reest->validNumInvoice("Б 454//44");
		$this->assertContains('Номер накладной содержит буквы',$reest->error);
		$reest->validNumInvoice(" 454//44");
		$this->assertContains('Номер накладной содержит буквы',$reest->error);
	}
}
