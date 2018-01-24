<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.12.2016
 * Time: 22:53
 */

namespace App\Tests\Entity\forForm;


use App\Entity\forForm\search\docFromParam;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\SecurityBundle\Tests\Functional\WebTestCase;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;

/**
 * Class allFromPeriod_BranchTest
 * @package AnalizPdvBundle\Tests\Entity\forForm
 */
class docFromParamTest extends KernelTestCase
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
		$test=new docFromParam();
		 $test->setMonthCreate($value["monthCreate"]);
		  $test->setYearCreate($value["yearCreate"]);
			$test->setTypeDoc($value["typeDoc"]);
				$test->setNumDoc($value["numDoc"]);
					$test->setINN($value["INN"]);
						$test->setDateCreateDoc($value["dateCreateDoc"]);
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
			[["monthCreate"=>1,"yearCreate"=>2016,"numDoc"=>"212//015","typeDoc"=>"ПНЕ","INN"=>"12132121кк","dateCreateDoc"=>null],1],
			[["monthCreate"=>1,"yearCreate"=>2016,"numDoc"=>"212//015","typeDoc"=>"ПНЕ","INN"=>"12132121","dateCreateDoc"=>null],0],
			[["monthCreate"=>1,"yearCreate"=>2016,"numDoc"=>"212//пп","typeDoc"=>"ПНЕ","INN"=>"12132121","dateCreateDoc"=>null],1],
			[["monthCreate"=>1,"yearCreate"=>2016,"numDoc"=>"212//0в","typeDoc"=>"ааа","INN"=>"12132121","dateCreateDoc"=>null],2],
			[["monthCreate"=>1,"yearCreate"=>2016,"numDoc"=>"212//015","typeDoc"=>"ПНЕ","INN"=>"12132121кк","dateCreateDoc"=>null],1],
			[["monthCreate"=>1,"yearCreate"=>2016,"numDoc"=>"212//015","typeDoc"=>"ПНЕ4","INN"=>"+12132121","dateCreateDoc"=>null],2],
			[["monthCreate"=>1,"yearCreate"=>2016,"numDoc"=>"","typeDoc"=>"","INN"=>"///","dateCreateDoc"=>null],2],
			[["monthCreate"=>15,"yearCreate"=>2016,"numDoc"=>"","typeDoc"=>"","INN"=>"","dateCreateDoc"=>null],2],
			[["monthCreate"=>1,"yearCreate"=>2016,"numDoc"=>"","typeDoc"=>"ПНЕ","INN"=>"12132121","dateCreateDoc"=> new \DateTime("2016-02-01")],1],
			[["monthCreate"=>1,"yearCreate"=>2016,"numDoc"=>"","typeDoc"=>"ПНЕ","INN"=>"12132121","dateCreateDoc"=> new \DateTime("2016-01-01")],0],


		];
	}
}
