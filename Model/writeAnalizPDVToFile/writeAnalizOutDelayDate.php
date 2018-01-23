<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2016
 * Time: 21:22
 */

namespace App\Model\writeAnalizPDVToFile;


use App\Model\getDataFromSQL\getDataFromAnalizPDVOutDelay;
use App\Model\getDataFromSQL\getDataFromReestrsByOne;
use App\Model\getDataFromSQL\getDataOutDelay;
use App\Model\getDataFromSQL\getDataOutDelayByAll;
use App\Utilits\createWriteFile\getWriteExcel;

/**
 * Формирование файла анализа опаздавших выданных НН
 * @uses writeAnalizOutDelayDate::writeAnalizPDVOutDelayByOneBranch по одному филиалу в периоде
 * @uses writeAnalizOutDelayDate::writeAnalizPDVOutDelayByAllBranch по всем филиалам в периоде
 * @package AnalizPdvBundle\Model\writeAnalizPDVToFile
 */
class writeAnalizOutDelayDate extends writeAnalizToFileAbstract
{
	const fileNameAllUZ="AnalizPDV_DiffDate.xlsx";

	/**
	 * формирование файла анализа опаздавших выданных НН по одному конкретному филиалу
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 * @param string $numBranch номер филиала по которому надо сформировать анализ
	 * @uses getDataFromAnalizPDVOutDelay::getAllDelay - формирование данных
	 * @uses getDataFromAnalizPDVOutDelay::getDelayToReestr- формирование данных
	 * @uses getDataFromAnalizPDVOutDelay::getDelayToNotReestr- формирование данных
	 * @uses getWriteExcel::setParamFile
	 * @uses getWriteExcel::getNewFileName
	 * @uses getWriteExcel::setDataFromWorksheet
	 * @uses getWriteExcel::fileWriteAndSave
	 * @see OutDelayByOneBranch_Command::execute - отсюда вызывается функция
	 */
	public function writeAnalizPDVOutDelayByOneBranch(int $month,int $year,string $numBranch)
	{

		//$file="d:\\OpenServer525\\domains\\AnalizPDV\\web\\template\\AnalizPDV_DiffDate.xlsx";
		//$data=new getDataFromAnalizPDVOutDelay($this->em);
		$file=$this->pathToTemplate.self::fileNameAllUZ;
		//echo $file;
		if (file_exists($file)) {
				$data=new getDataOutDelay($this->em);
				$write=new getWriteExcel($file);
				$write->setParamFile($month,$year,$numBranch);
				$write->getNewFileName();

				$arr=$data->getAllDelay($month,$year,$numBranch);
				$write->setDataFromWorksheet('AllDiff_out',$arr,'A4');
				unset($arr);
				gc_collect_cycles();

				$arr=$data->getDelayToReestr($month,$year,$numBranch);
				$write->setDataFromWorksheet('DiffOut_reestr=erpn',$arr,'A4');
				unset($arr);
				gc_collect_cycles();

				$arr=$data->getDelayToNotReestr($month,$year,$numBranch);
				$write->setDataFromWorksheet('DiffOut_reestr<>erpn',$arr,'A4');
				unset($arr);
				gc_collect_cycles();

				$write->fileWriteAndSave();
				unset($data,$write);
				gc_collect_cycles();
		}	else {
			echo "File " . $file . " not found";
		}
	}

	/**
	 * формирование файлов анализа опаздавших выданных НН по всем филиалам
	 * каждый филиал в свой файл
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 * @uses writeAnalizOutDelayDate::writeAnalizPDVOutDelayByOneBranch - формирование анализа
	 * @uses getDataFromReestrsByOne::getAllBranch получение списка филиалов
	 * @see OutDelayByOneBranchStream_Command::execute - отсюда вызывается функция
	 */
	public function writeAnalizPDVOutDelayByAllBranch(int $month,int $year)
	{
		$data=new getDataFromReestrsByOne($this->em);
		$arrAllBranch=$data->getAllBranch();
		foreach ($arrAllBranch as $arrAll)
		{
			foreach ($arrAll as $key => $numBranch)
			{
				$this->writeAnalizPDVOutDelayByOneBranch($month,$year,$numBranch);
			}
		}
	}

	/**
	 * формирование файла анализа опаздавших выданных НН по всему ПАТ
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 * @uses getDataFromAnalizPDVOutDelay::getAllDelay - формирование данных
	 * @uses getDataFromAnalizPDVOutDelay::getDelayToReestr- формирование данных
	 * @uses getDataFromAnalizPDVOutDelay::getDelayToNotReestr- формирование данных
	 * @uses getWriteExcel::setParamFile
	 * @uses getWriteExcel::getNewFileName
	 * @uses getWriteExcel::setDataFromWorksheet
	 * @uses getWriteExcel::fileWriteAndSave
	 * @see OutDelayByAll_Command::execute - отсюда вызывается функция
	 */
	public function writeAnalizPDVOutDelayByAllUZ(int $month,int $year)
	{

		//$file="d:\\OpenServer525\\domains\\AnalizPDV\\web\\template\\AnalizPDV_DiffDate.xlsx";
		$file=$this->pathToTemplate.self::fileNameAllUZ;
		//echo $file;
		if (file_exists($file))
		{
		//$data=new getDataFromAnalizPDVOutDelay($this->em);
				$data=new getDataOutDelayByAll($this->em);
				$write=new getWriteExcel($file);
				$write->setParamFile($month,$year,"All");
				$write->getNewFileName();

				$arr=$data->getAllDelay($month,$year);
				$write->setDataFromWorksheet('AllDiff_out',$arr,'A4');
				unset($arr);
				gc_collect_cycles();

				$arr=$data->getDelayToReestr($month,$year);
				$write->setDataFromWorksheet('DiffOut_reestr=erpn',$arr,'A4');
				unset($arr);
				gc_collect_cycles();

				$arr=$data->getDelayToNotReestr($month,$year);
				$write->setDataFromWorksheet('DiffOut_reestr<>erpn',$arr,'A4');
				unset($arr);
				gc_collect_cycles();

				$write->fileWriteAndSave();
				unset($data,$write);
				gc_collect_cycles();
		}	else {
			echo "File " . $file . " not found";
		}
	}

}