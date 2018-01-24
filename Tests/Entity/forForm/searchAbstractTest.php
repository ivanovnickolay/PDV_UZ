<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 07.01.2017
 * Time: 11:47
 */

namespace App\Tests\Entity\forForm;
use App\Entity\forForm\search\searchAbstract;


/**
 * Class searchAbstractTest
 * @package AnalizPdvBundle\Tests\Entity\forForm
 */
class searchAbstractTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @return array
	 */
	public function dataTest()
	{
		//"monthCreate"=>null,"yearCreate"=>null в классе присваивается текущее значение месяца и года !!!
		return [
			[["monthCreate"=>1,"yearCreate"=>2016,"routeSearch"=>null],["monthCreate"=>1,"yearCreate"=>2016,"routeSearch"=>"Обязательства"]],
			[["monthCreate"=>14,"yearCreate"=>2016,"routeSearch"=>"Обязательства"],["monthCreate"=>1,"yearCreate"=>2016,"routeSearch"=>"Обязательства"]],
			[["monthCreate"=>20,"yearCreate"=>2020,"routeSearch"=>"Обязательства"],["monthCreate"=>1,"yearCreate"=>2017,"routeSearch"=>"Обязательства"]],
			[["monthCreate"=>1,"yearCreate"=>2016,"routeSearch"=>"1641654"],["monthCreate"=>1,"yearCreate"=>2016,"routeSearch"=>"Обязательства"]],
			[["monthCreate"=>"gkgk","yearCreate"=>2016,"routeSearch"=>"1641654"],["monthCreate"=>1,"yearCreate"=>2016,"routeSearch"=>"Обязательства"]],
			[["monthCreate"=>"gkgk","yearCreate"=>"rfg45","routeSearch"=>"1641654"],["monthCreate"=>1,"yearCreate"=>2017,"routeSearch"=>"Обязательства"]],
			[["monthCreate"=>null,"yearCreate"=>null,"routeSearch"=>null],["monthCreate"=>1,"yearCreate"=>2017,"routeSearch"=>"Обязательства"]],
		];
	}

	/**
	 * @param $value
	 * @param $res
	 * @dataProvider dataTest
	 */
	public function testValidator (array $value, array $res)
	{
		$test = new searchAbstract();
		$test->setMonthCreate($value["monthCreate"]);
		$test->setYearCreate($value["yearCreate"]);
		$test->setRouteSearch($value["routeSearch"]);
		$this->assertEquals($res["monthCreate"], $test->getMonthCreate());
		$this->assertEquals($res["yearCreate"], $test->getYearCreate());
		$this->assertEquals($res["routeSearch"], $test->getRouteSearch());

	}
}
