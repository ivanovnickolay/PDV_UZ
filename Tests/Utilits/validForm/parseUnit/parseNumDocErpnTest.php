<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.12.2016
 * Time: 19:11
 */

namespace AnalizPdvBundle\Tests\Utilits\validForm\parseUnit;


use AnalizPdvBundle\Utilits\ValidForm\parserUnit\parserInnDocErpn;
use AnalizPdvBundle\Utilits\ValidForm\parserUnit\parserNumDocErpn;

/**
 * Class parseInnDocErpnTest
 * @package AnalizPdvBundle\Tests\Utilits\validForm\parseUnit
 */
class parseNumDocErpnTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @return array
	 */
	public function data()
	{
		return[
		[["num_invoice"=>"12456ff54"],false],
		[["num_invoice"=>"1245654"],true],
		[["num_invoice"=>"dfsdf//464"],false],
		[["num_invoice"=>"1245654//ff"],false]
		];
	}


	/**
	 * @param $data
	 * @param $res
	 * @dataProvider data
	 */
	public function test_parseNumDoc ($data, $res)
	{
		$parse=new parserNumDocErpn();
		$arr=$parse->parser($data);
		if (!empty($arr))
		{
			$this->assertEquals($res,key_exists("num_invoice",$arr));
		}else
		{
			$this->assertEquals($res,false);
		}

	}
}
