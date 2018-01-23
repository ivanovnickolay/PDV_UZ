<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.12.2016
 * Time: 22:53
 */

namespace AnalizPdvBundle\Tests\Entity\forForm;


use AnalizPdvBundle\Entity\forForm\search\allFromPeriod_Branch;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\SecurityBundle\Tests\Functional\WebTestCase;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;

/**
 * Class allFromPeriod_BranchTest
 * @package AnalizPdvBundle\Tests\Entity\forForm
 */
class allFromPeriod_BranchTest extends KernelTestCase
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
		$test=new allFromPeriod_Branch();
		 $test->setMonthCreate($value["monthCreate"]);
		  $test->setYearCreate($value["yearCreate"]);
		   $test->setNumBranch($value["numBranch"]);
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
			//
			//"monthCreate"=>null,"yearCreate"=>null в классе изменяются на "0" потому валидацию не проходит
			[["monthCreate"=>1,"yearCreate"=>2016,"numBranch"=>null,"numMainBranch"=>null],0],
			[["monthCreate"=>1,"yearCreate"=>2016,"numBranch"=>"12121","numMainBranch"=>"112"],1],
			[["monthCreate"=>14,"yearCreate"=>2018,"numBranch"=>"12121","numMainBranch"=>"112"],3],
			[["monthCreate"=>1,"yearCreate"=>2016,"numBranch"=>"jjj","numMainBranch"=>"j12"],2],
			[["monthCreate"=>null,"yearCreate"=>2016,"numBranch"=>"jjj","numMainBranch"=>"j12"],3],
			[["monthCreate"=>null,"yearCreate"=>"","numBranch"=>"jjj","numMainBranch"=>"j12"],4],
			[["monthCreate"=>"","yearCreate"=>"","numBranch"=>"jjj","numMainBranch"=>"j12"],4],
			[["monthCreate"=>null,"yearCreate"=>null,"numBranch"=>null,"numMainBranch"=>null],2],
			[["monthCreate"=>null,"yearCreate"=>null,"numBranch"=>"011","numMainBranch"=>"012"],2],
			[["monthCreate"=>1,"yearCreate"=>2016,"numBranch"=>"011","numMainBranch"=>"012"],0]

		];
	}
}
