<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.01.2017
 * Time: 10:49
 */

namespace App\Tests\Entity\analiz;


use App\Entity\forForm\analiz\analizInnOut;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class analizInnOutTest
 * @package AnalizPdvBundle\Tests\Entity\analiz
 */
class analizInnOutTest extends KernelTestCase
{


	/**
	 * @param $value
	 * @param $res
	 * @dataProvider dataTest
	 */
	public function testValidator (array $value, $res)
	{
		self::bootKernel();
		$validator =static::$kernel->getContainer()->get('validator');

		$em=static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
		$test=new analizInnOut($em);

		$test->setMonthCreate($value["monthCreate"]);
		$test->setYearCreate($value["yearCreate"]);
		$test->setNumMainBranch($value["numMainBranch"]);
		$violationList = $validator->validate($test);
		$this->assertEquals($res, $violationList->count());
		echo (string) $violationList."\n";



	}

	/**
	 * @return array
	 */
	public function dataTest()
	{
		return [
			// "numBranch"=>null,"numMainBranch"=>null в классе изменяются на "000" потому ошибки валидации нет
			//"monthCreate"=>null,"yearCreate"=>null в классе изменяются на "0" потому валидацию не проходит
			[["monthCreate"=>1,"yearCreate"=>2016,"numMainBranch"=>"fsdfsf"],2],
			[["monthCreate"=>1,"yearCreate"=>2016,"numMainBranch"=>""],1],
			[["monthCreate"=>10,"yearCreate"=>2016,"numMainBranch"=>"660"],0],


		];
	}

}
