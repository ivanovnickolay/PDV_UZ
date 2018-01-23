<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.12.2016
 * Time: 23:49
 */

namespace AnalizPdvBundle\Tests\Utilits\validForm\validUnit;


use AnalizPdvBundle\Utilits\ValidForm\validUnit\validTypeRoute;

class validTypeRouteTest extends \PHPUnit_Framework_TestCase
{
	public function DataFor_isValid_Uniq()
	{
		return [
			["Выданные",true],
			["Полученные",true],
			["Полученные999//",false],
			["+66Полученные",false],
			["Выданныеfdfssadf",false],
			["45\\Выданные",false],
			["Выданные4444g",false],
			["12345678Выданные901234",false],
			["Выданные Полученные",false],
		];
	}


	/**
	 * Проверка на уникальность номера филиала в базе
	 * @dataProvider DataFor_isValid_Uniq
	 */

	public function test_validTypeRoute ($num,$Result)
	{
		$vd = new validTypeRoute();
		$txt='testValidUniqBranch. -> Num '.$num.' result plan '.$Result;
		$this->assertEquals($Result,$vd->isValid($num),$txt);

	}
}
