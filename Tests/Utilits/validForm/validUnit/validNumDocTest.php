<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.12.2016
 * Time: 21:19
 */

namespace AnalizPdvBundle\Tests\Utilits\validForm\validUnit;


use AnalizPdvBundle\Utilits\ValidForm\validUnit\validNumDoc;

class validNumDocTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @return array
	 */
	public function DataFor_isValid_Uniq()
	{
		return [
			["18",true],
			["999",true],
			["999//",true],
			["+66",false],
			["fdfssadf",false],
			["45\\",false],
			["4444g",false],
			["12//hh",false],
			["212//015",true],
		];
	}


	/**
	 * Проверка на уникальность номера документа
	 * @dataProvider DataFor_isValid_Uniq
	 */
	public function testValidNumDoc($num,$Result)
	{
		$vd = new validNumDoc();
		$txt='testValidUniqBranch. -> Num '.$num.' result plan '.$Result;
		$this->assertEquals($Result,$vd->isValid($num),$txt);

	}
}
