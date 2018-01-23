<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.03.2017
 * Time: 22:06
 */

namespace AnalizPdvBundle\Model;


/**
 * Class getDoc_analizInnOutFromFileTest
 * @package AnalizPdvBundle\Model
 */
class getDoc_analizInnOutFromFileTest extends \PHPUnit_Framework_TestCase
{
	public function data_p()
	{


	}

	/**
	 * @dataProvider data_p
	 */
	public function test_()
	{

		$dataErpn=array(
			['num_invoice'=>'1','date_create'=>'15.03.2016','type_invoice_full'=>'ПНЕ','inn_client'=>'10000000','pdvinvoice'=>'10,00','numMainBranch'=>"660",'numBranch'=>"018"],
			['num_invoice'=>'2','date_create'=>'15.03.2016','type_invoice_full'=>'ПНЕ','inn_client'=>'10000000' ,'pdvinvoice'=>'11,50','numMainBranch'=>"661",'numBranch'=>"018"],
			['num_invoice'=>'3','date_create'=>'15.03.2016','type_invoice_full'=>'ПНЕ','inn_client'=>'10000000' ,'pdvinvoice'=>'11,80','numMainBranch'=>"662",'numBranch'=>"018"],
			['num_invoice'=>'7','date_create'=>'15.03.2016','type_invoice_full'=>'ПНЕ','inn_client'=>'10000000' ,'pdvinvoice'=>'15,00','numMainBranch'=>"663",'numBranch'=>"018"],
		);
		$dataReestr=array(
			['num_invoice'=>'1' ,'date_create'=>'15.03.2016','type_invoice_full'=>'ПНЕ','inn_client'=>'10000000' ,'pdvinvoice'=>'11,00','numMainBranch'=>"660",'numBranch'=>"018"],
			['num_invoice'=>'2' ,'date_create'=>'15.03.2016','type_invoice_full'=>'ПНЕ','inn_client'=>'10000000','pdvinvoice'=>'11,50','numMainBranch'=>"661",'numBranch'=>"018"],
			['num_invoice'=>'5' ,'date_create'=>'15.03.2016','type_invoice_full'=>'ПНЕ','inn_client'=>'10000000','pdvinvoice'=>'11,80','numMainBranch'=>"662",'numBranch'=>"018"],
			['num_invoice'=>'4' ,'date_create'=>'15.03.2016','type_invoice_full'=>'ПНЕ','inn_client'=>'10000000','pdvinvoice'=>'15,00','numMainBranch'=>"663",'numBranch'=>"018"],
		);

		$obj=new getDoc_analizInnOutFromFile();
		$obj->setDocByErpn($dataErpn);
		$obj->setDocByReestr($dataReestr);
		$E=$obj->getDocByErpnWithError();
		$this->assertEquals('По документу в ЕРПН и РПН включены разные суммы ПДВ', $E[0]['Error']);
		$this->assertEquals('Документ есть в ЕРПН, но не включен в РПН', $E[2]['Error']);
		$this->assertEquals('Документ есть в ЕРПН, но не включен в РПН', $E[3]['Error']);
		$R=$obj->getDocByReestrWithError();
		$this->assertEquals('По документу в ЕРПН и РПН включены разные суммы ПДВ', $R[0]['Error']);
		$this->assertEquals('Документ есть в РПН, но не зарегистрирован в ЕРПН', $R[2]['Error']);
		$this->assertEquals('Документ есть в РПН, но не зарегистрирован в ЕРПН', $R[3]['Error']);

		//var_dump($E);
		//var_dump($R);
	}
}
