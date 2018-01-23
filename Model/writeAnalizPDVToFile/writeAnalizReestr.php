<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2016
 * Time: 21:14
 */

namespace App\Model\writeAnalizPDVToFile;


use App\Model\getDataFromSQL\getDataFromReestrsAll;
use App\Model\getDataFromSQL\getDataFromReestrsByOne;
use App\Utilits\createWriteFile\getWriteExcel;

/**
 * формирование файлов анализа реестров и ЕРПН по документам
 * @uses writeAnalizReestr::writeAnalizPDVByAllUZ анализ по всему ПАТ
 * @uses writeAnalizReestr::writeAnalizPDVByOneBranch анализ по одному конкретному филиалу
 * @uses writeAnalizReestr::writeAnalizPDVByAllBranch анализ по всем филиалам ПАТ
 * @package AnalizPdvBundle\Model\writeAnalizPDVToFile
 */
class writeAnalizReestr extends writeAnalizToFileAbstract
{
	const fileNameAllUZ="AnalizPDV_All.xlsx";
	const fileNameOneBranch="AnalizPDV.xlsx";

	/**
	 * формирование файла анализа реестров и ЕРПН по документам сводного всему УЗ
	 * как "сливание" всех анализов филиалов в один файл
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 * @uses getDataFromReestrsAll::getReestrInEqualErpn формирование данных
	 * @uses getDataFromReestrsAll::getReestrInNotEqualErpn формирование данных
	 * @uses getDataFromReestrsAll::getReestrOutEqualErpn формирование данных
	 * @uses getDataFromReestrsAll::getReestrOutNotEqualErpn формирование данных
	 * @uses getWriteExcel::setParamFile
	 * @uses getWriteExcel::getNewFileName
	 * @uses getWriteExcel::setDataFromWorksheet
	 * @uses getWriteExcel::fileWriteAndSave
	 * @see AnalizReestrByAll_Command::execute - отсюда вызывается функция
	 */
	public function writeAnalizPDVByAllUZ(int $month, int $year)
	{

		//$file="d:\\OpenServer525\\domains\\AnalizPDV\\web\\template\\AnalizPDV_All.xlsx";
		$file=$this->pathToTemplate.self::fileNameAllUZ;
		//echo $file;
		if (file_exists($file)) {
			$data=new getDataFromReestrsAll($this->em);
			$write=new getWriteExcel($file);
			$write->setParamFile($month,$year,'ALL');
			$write->getNewFileName();

			$arr=$data->getReestrInEqualErpn($month,$year);
			$write->setDataFromWorksheet('In_reestr=edrpu',$arr,'A4');
			unset($arr);
			gc_collect_cycles();

			$arr=$data->getReestrInNotEqualErpn($month,$year);
			$write->setDataFromWorksheet('In_reestr<>edrpou',$arr,'A4');
			unset($arr);
			gc_collect_cycles();

			$arr=$data->getReestrOutEqualErpn($month,$year);
			$write->setDataFromWorksheet('Out_reestr=edrpu',$arr,'A4');
			unset($arr);
			gc_collect_cycles();

			$arr=$data->getReestrOutNotEqualErpn($month,$year);
			$write->setDataFromWorksheet('Out_reestr<>edrpou',$arr,'A4');
			unset($arr);
			gc_collect_cycles();

			$write->fileWriteAndSave();
			unset($data,$write);
			gc_collect_cycles();
		} else
		{
			echo "File ".$file." not found";
		}
	}

	/**
	 * формирование файла анализа реестров и ЕРПН по документам по одному конкретному филиалу
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 * @param string $numBranch номер филиала по которому надо сформировать анализ
	 * @uses getDataFromReestrsByOne::getReestrInEqualErpn формирование данных
	 * @uses getDataFromReestrsByOne::getReestrInNotEqualErpn формирование данных
	 * @uses getDataFromReestrsByOne::getReestrOutEqualErpn формирование данных
	 * @uses getDataFromReestrsByOne::getReestrOutNotEqualErpn формирование данных
	 * @uses getWriteExcel::setParamFile
	 * @uses getWriteExcel::getNewFileName
	 * @uses getWriteExcel::setDataFromWorksheet
	 * @uses getWriteExcel::fileWriteAndSave
	 * $see  - отсюда вызывается функция
	 */
	public function writeAnalizPDVByOneBranch(int $month,int $year,string $numBranch)
	{

		//$file="d:\\OpenServer525\\domains\\AnalizPDV\\web\\template\\AnalizPDV.xlsx";
		$file=$this->pathToTemplate.self::fileNameOneBranch;
		//echo $file;
		if (file_exists($file))
		{
				$data=new getDataFromReestrsByOne($this->em);
				$write=new getWriteExcel($file);
				$write->setParamFile($month,$year,$numBranch);
				$write->getNewFileName();

				$arr=$data->getReestrInEqualErpn($month,$year,$numBranch);
				$write->setDataFromWorksheet('In_reestr=edrpu',$arr,'A4');
				unset($arr);
				gc_collect_cycles();

				$arr=$data->getReestrInNotEqualErpn($month,$year,$numBranch);
				$write->setDataFromWorksheet('In_reestr<>edrpou',$arr,'A4');
				unset($arr);
				gc_collect_cycles();

				$arr=$data->getReestrOutEqualErpn($month,$year,$numBranch);
				$write->setDataFromWorksheet('Out_reestr=edrpu',$arr,'A4');
				unset($arr);
				gc_collect_cycles();

				$arr=$data->getReestrOutNotEqualErpn($month,$year,$numBranch);
				$write->setDataFromWorksheet('Out_reestr<>edrpou',$arr,'A4');
				unset($arr);
				gc_collect_cycles();

				$write->fileWriteAndSave();
				unset($data,$write);
				gc_collect_cycles();
		} else
		{
			echo "File ".$file." not found";
		}
	}

	/**
	 * формирование файлов анализа по всем филиалам
	 * каждый филиал в свой файл
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 * @uses getDataFromReestrsByOne::getAllBranchToPeriod - получение всех филиалов в периоде из реестров полученных
	 * НН за период
	 * @uses writeAnalizPDVByOneBranch для каждого филиала в цикле
	 * $see  - отсюда вызывается функция
	 */
	public function writeAnalizPDVByAllBranch(int $month,int $year)
	{
		$data=new getDataFromReestrsByOne($this->em);
		$arrAllBranch=$data->getAllBranchToPeriod($month,$year);
		if(!empty($arrAllBranch)) {
			foreach ($arrAllBranch as $arrAll)
			{
				foreach ($arrAll as $key => $numBranch)
				{
					$this->writeAnalizPDVByOneBranch($month,$year,$numBranch);
				}
			}
		}
	}


}