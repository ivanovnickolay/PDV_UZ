<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2016
 * Time: 21:26
 */

namespace App\Model\writeAnalizPDVToFile;


use App\Model\getDataFromSQL\getDataFromAnalizPDVOutINN;
use App\Model\getDataFromSQL\getDataFromReestrsByOne;
use App\Model\getDataFromSQL\getDataInINNByAll;
use App\Model\getDataFromSQL\getDataOutINNByAll;
use App\Utilits\createWriteFile\getWriteExcel;

/**
 * Формирование файла анализа выданных НН в разрезе ИНН в целом по ПАТ
 * @uses writeAnalizInByInn::writeAnalizPDVInInnByAllUZ - формирование анализа
 * @package AnalizPdvBundle\Model\writeAnalizPDVToFile
 * @inheritdoc
 */
class writeAnalizInByInn extends writeAnalizToFileAbstract
{
	const fileNameAllUZ="AnalizPDV_In_INN.xlsx";
	/**
	 * Формирование файла анализа выданных НН в разрезе ИНН в целом по ПАТ
 	 * @param int $month месяц
	 * @param int $year год
	 * @uses getDataInINNByAll::getReestrEqualErpnAllUZ формирование данных
	 * @uses getDataInINNByAll::getReestrEqualErpnAllUZ_DocErpn формирование данных
	 * @uses getDataInINNByAll::getReestrEqualErpnAllUZ_DocReestr формирование данных
	 * @uses getDataInINNByAll::getReestrEqualErpnAllUZ_DocReestr формирование данных
	 * @uses getDataInINNByAll::getReestrNoEqualErpnAllUZ формирование данных
	 * @uses getDataInINNByAll::getReestrNoEqualErpnAllUZ_DocReestr формирование данных
	 * @uses getDataInINNByAll::getErpnNoEqualReestrAllUZ формирование данных
	 * @uses getDataInINNByAll::getErpnNoEqualReestrAllUZ_DocErpn формирование данных
	 * @uses getWriteExcel::setParamFile
	 * @uses getWriteExcel::getNewFileName
	 * @uses getWriteExcel::setDataFromWorksheet
	 * @uses getWriteExcel::fileWriteAndSave
	 * @see InGroupInnByAll_Command::execute - - отсюда вызывается функция
	  */
	public function writeAnalizPDVInInnByAllUZ(int $month, int $year)
	{
		//$file="d:\\OpenServer525\\domains\\AnalizPDV\\web\\template\\AnalizPDV_In_INN.xlsx";
		$file=$this->pathToTemplate.self::fileNameAllUZ;
		//echo $file;
		if (file_exists($file))
		{
			$data=new getDataInINNByAll($this->em);
			$write=new getWriteExcel($file);
			$write->setParamFile($month,$year,"All");
			$write->getNewFileName();

			$arr=$data->getReestrEqualErpnAllUZ($month,$year);
			$write->setDataFromWorksheet('In_R=E',$arr,'A4');
			unset($arr);
			gc_collect_cycles();

			$arr=$data->getReestrEqualErpnAllUZ_DocErpn($month,$year);
			$write->setDataFromWorksheet('In_R=E DocByE',$arr,'A3');
			unset($arr);
			gc_collect_cycles();

			$arr=$data->getReestrEqualErpnAllUZ_DocReestr($month,$year);
			$write->setDataFromWorksheet('In_R=E DocByR',$arr,'A3');
			unset($arr);
			gc_collect_cycles();

			$arr=$data->getReestrNoEqualErpnAllUZ($month,$year);
			$write->setDataFromWorksheet('In_R<>E',$arr,'A4');
			unset($arr);
			gc_collect_cycles();

			$arr=$data->getReestrNoEqualErpnAllUZ_DocReestr($month,$year);
			$write->setDataFromWorksheet('In_R<>E DocByR',$arr,'A3');
			unset($arr);
			gc_collect_cycles();

			$arr=$data->getErpnNoEqualReestrAllUZ($month,$year);
			$write->setDataFromWorksheet('In_E<>R',$arr,'A4');
			unset($arr);
			gc_collect_cycles();

			$arr=$data->getErpnNoEqualReestrAllUZ_DocErpn($month,$year);
			$write->setDataFromWorksheet('In_E<>R DocByE',$arr,'A3');
			unset($arr);
			gc_collect_cycles();

			$write->fileWriteAndSave();
			unset($data,$write);
			gc_collect_cycles();
		}	else
		{
			echo "File ".$file." not found";
		}
	}
}