<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.12.2016
 * Time: 22:03
 */

namespace AnalizPdvBundle\Tests\Utilits\validForm\validUnit;


use AnalizPdvBundle\Utilits\ValidForm\validUnit\validInnDoc;

class validInnDocTest extends \PHPUnit_Framework_TestCase
{
	public function DataFor_isValid_Uniq()
	{
		return [
			["18",true],
			["999",true],
			["999//",false],
			["+66",false],
			["fdfssadf",false],
			["45\\",false],
			["4444g",false],
			["12345678901234",false],
			["123456789012",true],
		];
	}


	/**
	 * Проверка на уникальность номера филиала в базе
	 * @dataProvider DataFor_isValid_Uniq
	 */

	public function test_validInnDoc ($num,$Result)
	{
	$vd = new validInnDoc();
	$txt='testValidUniqBranch. -> Num '.$num.' result plan '.$Result;
	$this->assertEquals($Result,$vd->isValid($num),$txt);

	}
	public function test_validInnDoc1 ()
	{
		$vd = new validInnDoc();

		$this->assertEquals(false,$vd->isValid("+66"));

	}
}
