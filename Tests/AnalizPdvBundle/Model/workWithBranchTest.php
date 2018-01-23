<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 03.03.2017
 * Time: 0:30
 */

namespace AnalizPdvBundle\Model;


/**
 * Class workWithBranchTest
 * @package AnalizPdvBundle\Model
 */
class workWithBranchTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @return array
	 */
	public function dataFromTest()
	{
		return [
			['01-03-2016',"660"],
			['30-09-2017',"663"],
			['30-09-2016',"660"],
			['01-03-2017',"663"],
			['28-02-2017',"662"],
		];

	}


	/** @dataProvider dataFromTest
	 */
	public function test_getNumMainBranchPeriod($data, $res)
	{
		/** @var array $arrData */
		$arrData=array(
			['beginData'=>'01-01-2016' ,'endData'=>'30-09-2016','numMainBranch'=>"660",'numBranch'=>"018"],
			['beginData'=>'01-10-2016' ,'endData'=>'31-12-2016','numMainBranch'=>"661",'numBranch'=>"018"],
			['beginData'=>'01-01-2017' ,'endData'=>'28-02-2017','numMainBranch'=>"662",'numBranch'=>"018"],
			['beginData'=>'01-03-2017' ,'endData'=>'00-00-0000','numMainBranch'=>"663",'numBranch'=>"018"],
		);
		$work=new workWithBranch();
		$branch=$work->getNumMainBranchPeriod($data, $arrData);
		$text = "branch = ".$branch." res=".$res;
		$this->assertEquals($res, $branch,$text);

	}
}
