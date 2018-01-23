<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.09.2016
 * Time: 20:52
 */

namespace App\Model\writeAnalizPDVToFile;
use App\Model\getDataFromSQL\getDataFromAnalizPDVOutDelay;
use App\Model\getDataFromSQL\getDataFromAnalizPDVOutINN;
use App\Model\getDataFromSQL\getDataFromReestrsAll;
use App\Model\getDataFromSQL\getDataFromReestrsByOne;
use App\Model\getDataFromSQL\getDataOutDelay;
use App\Model\getDataFromSQL\getDataPDVOutDelay;
use App\Utilits\createWriteFile\getWriteExcel;
use Doctrine\ORM\EntityManager;


/**
 * Реализация алгоритмов формирования файлов анализов реестров и ЕРПН по документам  за период
 * @uses writeAnalizPDVToFile::writeAnalizPDVByAllUZ - анализ по всему УЗ
 * @uses writeAnalizPDVToFile::writeAnalizPDVByOneBranch - анализ по одному филиалу
 * @uses writeAnalizPDVToFile::writeAnalizPDVByAllBranch - анализ по всем филиалам, каждый в свой файл
 * @package AnalizPdvBundle\Model\writeAnalizPDVToFile
 */
class writeAnalizPDVToFile
{
	private $em;
	private $pathToTemplate;
	const fileNameAllUZ="AnalizPDV_All.xlsx";
	const fileNameOneBranch="AnalizPDV_All.xlsx";

	/**
	 * writeAnalizPDVToFile constructor.
	 * @param $entityManager
	 * @param string $pathToTemplate
	 */
	public function __construct ($entityManager, string $pathToTemplate='')
{
	$this->em=$entityManager;
	$this->pathToTemplate=$pathToTemplate;
}

	/**
	 * формирование файла анализа сводного всему УЗ
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 */
	public function writeAnalizPDVByAllUZ(int $month, int $year)
{
	//$file="d:\\OpenServer525\\domains\\AnalizPDV\\web\\template\\AnalizPDV_All.xlsx";
	$file=$this->pathToTemplate.self::fileNameAllUZ;
	echo $file;
	if (file_exists($file)) {
		$data = new getDataFromReestrsAll($this->em);
		$write = new getWriteExcel($file);
		$write->setParamFile ($month , $year , 'ALL');
		$write->getNewFileName ();
		$arr = $data->getReestrInEqualErpn ($month , $year);
		$write->setDataFromWorksheet ('In_reestr=edrpu' , $arr , 'A4');
		unset($arr);
		gc_collect_cycles ();
		$arr = $data->getReestrInNotEqualErpn ($month , $year);
		$write->setDataFromWorksheet ('In_reestr<>edrpou' , $arr , 'A4');
		unset($arr);
		gc_collect_cycles ();
		$arr = $data->getReestrOutEqualErpn ($month , $year);
		$write->setDataFromWorksheet ('Out_reestr=edrpu' , $arr , 'A4');
		unset($arr);
		gc_collect_cycles ();
		$arr = $data->getReestrOutNotEqualErpn ($month , $year);
		$write->setDataFromWorksheet ('Out_reestr<>edrpou' , $arr , 'A4');
		unset($arr);
		gc_collect_cycles ();
		$write->fileWriteAndSave ();
		unset($data , $write);
		gc_collect_cycles ();
	}
}

	/**
	 *формирование файла анализа по одному конкретному филиалу
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 * @param string $numBranch номер филиала по которому надо сформировать анализ
	 * @uses getDataFromReestrsByOne::getReestrInEqualErpn - формирование данных
	 * @uses getDataFromReestrsByOne::getReestrInNotEqualErpn - формирование данных
	 * @uses getDataFromReestrsByOne::getReestrOutEqualErpn - формирование данных
	 * @uses getDataFromReestrsByOne::getReestrOutNotEqualErpn - формирование данных
	 * @uses getWriteExcel::setParamFile
	 * @uses getWriteExcel::getNewFileName
	 * @uses getWriteExcel::setDataFromWorksheet
	 * @uses getWriteExcel::fileWriteAndSave
	 * @see AnalizReestrByOneBranch_Command::execute - отсюда вызывается функция (убрано 27-10-16)
	 */
	public function writeAnalizPDVByOneBranch(int $month,int $year,string $numBranch)
	{
		$file="d:\\OpenServer525\\domains\\AnalizPDV\\web\\template\\AnalizPDV.xlsx";
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
	}

	/**
	 * формирование файлов анализа по всем филиалам каждый филиал в свой файл
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 * @uses writeAnalizPDVToFile::writeAnalizPDVByOneBranch
	 * @see AnalizReestrByOneBranchStream_Command::execute - отсюда вызывается функция
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

	/**
	 * формирование файла анализа расхождений по ИНН по одному конкретному филиалу
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 * @param string $numBranch номер филиала по которому надо сформировать анализ
	 * @uses getDataFromAnalizPDVOutINN::getReestrInEqualErpn - формирование данных
	 * @uses getDataFromAnalizPDVOutINN::getReestrInNotEqualErpn - формирование данных
	 * @uses getDataFromAnalizPDVOutINN::getReestrOutEqualErpn - формирование данных
	 * @uses getDataFromAnalizPDVOutINN::getReestrOutNotEqualErpn - формирование данных
	 * @uses getWriteExcel::setParamFile
	 * @uses getWriteExcel::getNewFileName
	 * @uses getWriteExcel::setDataFromWorksheet
	 * @uses getWriteExcel::fileWriteAndSave
	 * @see OutGroupInnByOneBranchCommand::execute - отсюда вызывается функция (удалено 27-10-16)
	 */
	public function OutGroupInnByOneBranch(int $month, int $year, string $numBranch)
	{
		$file="d:\\OpenServer525\\domains\\AnalizPDV\\web\\template\\AnalizPDV_Out_INN.xlsx";
		$data=new getDataFromAnalizPDVOutINN($this->em);
		$write=new getWriteExcel($file);
		$write->setParamFile($month,$year,$numBranch);
		$write->getNewFileName();

		$arr=$data->getReestrEqualErpn($month,$year,$numBranch);
		$write->setDataFromWorksheet('Out_reestr=erpn',$arr,'A4');
		unset($arr);
		gc_collect_cycles();

		$arr=$data->getErpnNoEqualReestr($month,$year,$numBranch);
		$write->setDataFromWorksheet('Out_erpn<>reestr',$arr,'A4');
		unset($arr);
		gc_collect_cycles();

		$arr=$data->getReestrNoEqualErpn($month,$year,$numBranch);
		$write->setDataFromWorksheet('Out_reestr<>erpn',$arr,'A4');
		unset($arr);
		gc_collect_cycles();

		$write->fileWriteAndSave();
		unset($data,$write);
		gc_collect_cycles();
	}

	/**
	 * формирование файлов анализа расхождений по ИНН по всем филиалам
	 * каждый филиал в свой файл
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 */
	public function writeAnalizPDVOutInnByAllBranch(int $month,int $year)
	{
		$data=new getDataFromReestrsByOne($this->em);
		$arrAllBranch=$data->getAllBranchToPeriod($month,$year);
		if(!empty($arrAllBranch)) {
			foreach ($arrAllBranch as $arrAll)
			{
				foreach ($arrAll as $key => $numBranch)
				{
					$this->OutGroupInnByOneBranch($month,$year,(string) $numBranch);
				}
			}
		}
	}
	/**
	 *формирование файла анализа опаздавших НН по одному конкретному филиалу
	 * @deprecated замена вызова getDataFromAnalizPDVOutDelay на более современный getDataOutDelay 27-10-16
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
	 * @see AnalizReestrByOneBranch_Command::execute - отсюда вызывается функция (убрано 27-10-16)
	 * @see writeAnalizPDVToFile::writeOutDelayByAllBranch - отсюда вызывается функция
	 */
	public function writeAnalizPDVOutDelayByOneBranch(int $month, int $year, string $numBranch)
	{
		$file="d:\\OpenServer525\\domains\\AnalizPDV\\web\\template\\AnalizPDV_DiffDate.xlsx";
		//$data=new getDataFromAnalizPDVOutDelay($this->em);
		$data=new getDataOutDelay($this->em);
		$write=new getWriteExcel($file);
		echo "$month $year $numBranch begin   \r\n";
		$write->setParamFile($month,$year,$numBranch);
		$write->getNewFileName();
		echo " getAllDiff begin   \r\n";

		$arr=$data->getAllDelay($month,$year,$numBranch);
		echo " getAllDiff end   \r\n";
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
		echo "$month $year $numBranch end   \r\n";
		unset($data,$write);
		gc_collect_cycles();
	}

	/**
	 * формирование файлов анализа опаздавших НН по всем филиалам
	 * каждый филиал в свой файл
	 * @param int $month номер месяца по которому надо сформировать анализ
	 * @param int $year номер года по которому надо сформировать анализ
	 * @uses writeAnalizPDVToFile::writeAnalizPDVOutDelayByOneBranch - формирование анализа
	 * @uses getDataFromReestrsByOne::getAllBranch получение списка филиалов
	 * @see OutDelayByOneBranchStream_Command::execute - отсюда вызывается функция (убрано 27-10-16)
	 */
	public function writeOutDelayByAllBranch(int $month, int $year)
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
}