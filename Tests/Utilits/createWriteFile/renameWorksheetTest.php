<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.09.2016
 * Time: 22:17
 */

namespace AnalizPdvBundle\Tests\Utilits\createWriteFile;


use AnalizPdvBundle\Utilits\createWriteFile\renameWorksheet;

class renameWorksheetTest extends \PHPUnit_Framework_TestCase
{
	public function test_rename()
	{
		renameWorksheet::renameWorksheet("d:\\OpenServer525\\domains\\AnalizPDV\\src\\AnalizPdvBundle\\Tests\\testData\\test.xlsx");
	}
}
