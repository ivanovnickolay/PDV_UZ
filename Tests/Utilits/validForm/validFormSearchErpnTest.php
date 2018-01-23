<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.12.2016
 * Time: 23:56
 */

namespace AnalizPdvBundle\Tests\Utilits\validForm;
use AnalizPdvBundle\Utilits\ValidForm\validFormSearchErpn;
use AnalizPdvBundle\Utilits\ValidForm\validUnit\validForm;


/**
 * Class validFormSearchErpnTest
 * @package AnalizPdvBundle\Tests\Utilits\validForm
 */
class validFormSearchErpnTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @return array
	 */
	public function DataFor_isValid_Uniq()
	{
		return [
		[["num_invoice"=>"12//hh","inn_client"=>"dfssaf","typeRoute"=>"Выданные"],false],
		[["num_invoice"=>"12//","inn_client"=>"12457878","typeRoute"=>"Выданные"],true],
		[["num_invoice"=>"+12//g","inn_client"=>"12457878gg","typeRoute"=>"Выданные"],false],
		[["num_invoice"=>"12//f","inn_client"=>"12457878","typeRoute"=>"Выданне"],false]
		];
	}


	/**
	 * Проверка
	 * @dataProvider DataFor_isValid_Uniq
	 */

	public function test_validFormSearchErpn($num,$res)
	{
		$vd = new validFormSearchErpn();
		$this->assertEquals ($res , $vd->isValdForm ($num),var_dump($vd->getErrorMessage()) );

	}

	/**
	 * Проверка без передачи репозитория валидаторов
	 * выдает ошибку .. это верно
	 * @dataProvider DataFor_isValid_Uniq
	 */

	public function test_validFormSearchErpn1($num,$res)
	{
		$vd = new validForm();
		$this->assertEquals ($res , $vd->isValdForm ($num),var_dump($vd->getErrorMessage()) );

	}
}
