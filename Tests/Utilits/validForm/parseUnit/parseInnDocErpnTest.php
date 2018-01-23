<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.12.2016
 * Time: 19:11
 */

namespace AnalizPdvBundle\Tests\Utilits\validForm\parseUnit;


use AnalizPdvBundle\Utilits\ValidForm\parserUnit\parserInnDocErpn;


/**
 * Class parseInnDocErpnTest
 * @package AnalizPdvBundle\Tests\Utilits\validForm\parserUnit
 */
class parseInnDocErpnTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @return array
	 */
	public function data()
	{
		return[
		[["inn_client"=>"12456ff54"],false],
		[["inn_client"=>"1245654"],true]
		];
	}


	/**
	 * @param $data
	 * @param $res
	 * @dataProvider data
	 */
	public function test_parseInnDoc ($data, $res)
	{
		$parse=new parserInnDocErpn();
		$arr=$parse->parser($data);
		if (!empty($arr))
		{
			$this->assertEquals($res,key_exists("inn_client",$arr));
		}else
		{
			$this->assertEquals($res,false);
		}

	}
}
