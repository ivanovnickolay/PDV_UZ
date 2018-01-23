<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.09.2016
 * Time: 22:04
 */

namespace App\Utilits\createWriteFile;


use App\Utilits\createReaderFile\getReaderExcel;

/**
 * изменяем название листов книге с "лист1" на "Sheet1"
 * Class renameWorksheet
 * @package AnalizPdvBundle\Utilits\createWriteFile
 */
class renameWorksheet
{

	public static function renameWorksheet($file)
	{
		$read= new getReaderExcel($file);
		$read->getReaderMinusFilter();
		$loadFile=$read->loadFileMinusFilter();
		$loadFile->getActiveSheet()->setTitle('Sheet1');
		$typeFile=$read->getFileType();
		$writer=\PHPExcel_IOFactory::createWriter($loadFile, $typeFile);
		$writer->save($file);
		unset($read,$loadFile,$writer);
	}
}