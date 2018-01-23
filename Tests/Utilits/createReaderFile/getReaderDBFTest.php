<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23.09.2016
 * Time: 0:07
 */

namespace AnalizPdvBundle\Tests\Utilits\createReaderFile;


use AnalizPdvBundle\Utilits\createReaderFile\getReaderDBF;

class getReaderDBFTest extends \PHPUnit_Framework_TestCase
{

	public function test_getMaxRow()
	{
		$file="d:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\dbf\\17092016170105_40075815_J1201508_TAB2.dbf";
		$d=new getReaderDBF($file);
		$cnt=$d->getMaxRow();
		echo $cnt;
	}

}
