<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14.02.2017
 * Time: 0:27
 */

namespace AnalizPdvBundle\Utilits\ValidForm;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


/**
 * Class validDataFormControllerTest
 * @package AnalizPdvBundle\Utilits\ValidForm
 */
class validDataFormControllerTest extends WebTestCase
{

	/**
	 * @return array
	 */
	public function dataMonth()
	{
		return[
			['',2],
			['14',1]
		];

	}


	/**
	 * @param $month
	 * @param $res
	 * @dataProvider dataMonth
	 */
	public function testMonth($month, $res)
	{
		$client = static::createClient();
		$container = $client->getContainer();
		//$v=new validDataFormController();
		$v=$container->get('validDataController');
		$err=$v->validMonth($month);
		$this->assertEquals($res, count($err));
	}


	/**
	 * @return array
	 */
	public function dataYear()
	{
		return[
			['',2],
			['2015',0],
			['2018',1],
			['464646',1],
			['dfsdfsf',1]
		];

	}


	/**
	 * @param $month
	 * @param $res
	 * @dataProvider dataYear
	 */
	public function testYear($year, $res)
	{
		$client = static::createClient();
		$container = $client->getContainer();
		$v=$container->get('validDataController');
		$err=$v->validYear($year);
		$this->assertEquals($res, count($err));
	}


	/**
	 * @return array
	 */
	public function dataINN()
	{
		return[
			['12456ff54',1],
			['1234567890',0],
			['',2],
			['464646',0],
			['dfsdfsf',1]
		];

	}


	/**
	 * @param $month
	 * @param $res
	 * @dataProvider dataINN
	 */
	public function testINN($INN, $res)
	{
		$client = static::createClient();
		$container = $client->getContainer();
		$v=$container->get('validDataController');
		$err=$v->validINN($INN);
		$this->assertEquals($res, count($err));
	}

	/**
	 * @return array
	 */
	public function dataNumBranch()
	{
		return[
			['12456ff54',2],
			['1234567890',1],
			['018',0],
			['682',0],
			['',2],
			['682/',2]
		];

	}


	/**
	 * @param $month
	 * @param $res
	 * @dataProvider dataNumBranch
	 */
	public function testNumBranch($numBranch, $res)
	{
		$client = static::createClient();
		$container = $client->getContainer();
		$v=$container->get('validDataController');
		$err=$v->validNumBranch($numBranch);
		$this->assertEquals($res, count($err));
	}


}
