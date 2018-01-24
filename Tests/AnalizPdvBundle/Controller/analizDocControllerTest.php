<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.02.2017
 * Time: 22:32
 */

namespace App\Controller;


/**
 * Class analizDocControllerTest
 * @package AnalizPdvBundle\Controller
 */
class analizDocControllerTest extends \PHPUnit_Framework_TestCase
{

	public function test()
	{
		$cont=new  analizDocController();
		$this->assertEquals('getReestrEqualErpn', $cont->getMethodAnaliz("R=E"));
	}
}
