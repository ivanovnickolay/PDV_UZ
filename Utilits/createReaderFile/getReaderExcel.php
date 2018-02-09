<?php
namespace App\Utilits\createReaderFile;
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.08.2016
 * Time: 0:20
  */

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Utilits\createReaderFile\chunkReadFilter;

/**
 * @deprecated
 * Class getReaderExcel
 * @package App\Utilits\createReaderFile
 */
class getReaderExcel
{
	/**
	 * @var класс вида class PHPExcel_Reader_Excel2007 extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
	 */
	private $Reader;

	/**
	 * @var загруженный файл Excel с параметрами указанными $ChunkFilter
	 */
	private $Excel;
	/**
	 * @var string наименование файла с путем к нему
	 */
	private $fileName;
	/**
	 * @var \App\Utilits\createReaderFile\chunkReadFilter
	 */
	private $ChunkFilter;

	/**
	 * номер строки с которой надо начинать считывать файл
	 * @var int
	 */
	private $filterStartRow=2;
	/**
	 * массив столбцов фильтра
	 * строится при помощи процедуры createColumnsArray
	 * @var array
	 */
	private $filterColumn;
	/**
	 * Количество строк, которые считываются з один раз с файла
	 * @var int
	 */
	private $filterChunkSize=2000;
	/**
	 * Название листа в книге
	 * @var string
	 */
	private $filterWorksheetName;
	/**
	 * первый столбец массива $filterColumn
	 * @var string
	 */
	private $columnFirst = "A";
	/**
	 * Последний столбец массива $filterColumn
	 * @var string
	 */
	private $columnLast="Z";

	/**
	 * loadDataFromExcel constructor.
	 * @param EntityManager $entityManager
	 * @param string $fileName Имя файла должно содержать полный путь к нему !!!
	 */
	public function __construct(string $fileName)
	{
		// Заполняем первоначальными значениями
		$this->fileName=$fileName;


	}

	public function __destruct ()
	{
		unset($this->ChunkFilter);
		unset($this->Reader);
		unset($this->Excel);
	}

	/**
	* Проверяем наименование файла
	 * @return bool
	 */
	public function validFileName()
	{
		if(empty($this->fileName))
		{
			return false;
		}

		if (file_exists($this->fileName))
		{
			return true;
		}else{
			return false;
		}
	}
	/**
	 * Определение типа файла исходя из названия (расширения) файла
	 * @param $pFilename
	 * @return null|string
	 */
	public function getFileType()
	{
		$pathinfo = pathinfo($this->fileName);
		$extensionType = NULL;
		if (isset($pathinfo['extension'])) {
			switch (strtolower($pathinfo['extension'])) {
				case 'xlsx':            //	Excel (OfficeOpenXML) Spreadsheet
				case 'xlsm':            //	Excel (OfficeOpenXML) Macro Spreadsheet (macros will be discarded)
				case 'xltx':            //	Excel (OfficeOpenXML) Template
				case 'xltm':            //	Excel (OfficeOpenXML) Macro Template (macros will be discarded)
					$extensionType = 'Excel2007';
					break;
				case 'xls':                //	Excel (BIFF) Spreadsheet
				case 'xlt':                //	Excel (BIFF) Template
					$extensionType = 'Excel5';
					break;
				case 'ods':                //	Open/Libre Offic Calc
				case 'ots':                //	Open/Libre Offic Calc Template
					$extensionType = 'OOCalc';
					break;
				case 'slk':
					$extensionType = 'SYLK';
					break;
				case 'xml':                //	Excel 2003 SpreadSheetML
					$extensionType = 'Excel2003XML';
					break;
				case 'gnumeric':
					$extensionType = 'Gnumeric';
					break;
				case 'htm':
				case 'html':
					$extensionType = 'HTML';
					break;
				case 'csv':
					// Do nothing
					// We must not try to use CSV reader since it loads
					// all files including Excel files etc.
					break;
				default:
					break;
			}
			return $extensionType;
		}

	}

	/**
	 * Установка параметров для фильтра по файлу
	 * @param int $startRow - стартовая строка
	 * @param string $columnFirst начало диапазона строк  текстовое
	 * @param string $columnLast конец диапазона столбцов текстовое
	 * @param int $chunkSize количество строк читаемое заодин раз
	 * @param string $worksheetName наименовение рабочего листа книги
	 */
	public function createFilter(string  $columnLast="Z",int $chunkSize=2000,string $worksheetName='')
	{
		$this->filterChunkSize=$chunkSize;
			$this->filterWorksheetName=$worksheetName;
				$this->ChunkFilter=new chunkReadFilter($columnLast);
					$this->columnLast=$columnLast;
	}

	/**
	 * Создание экземпляра класса Ридера файла и настроек чтения
	 * включая фильтр
	 * @param $FileName
	 * @return array
	 * @throws \PHPExcel_Reader_Exception
	 */
	private function createReader ()
	{
		// если файл существует и класс фильтра подключен
		try {
					// получаем класс вида class PHPExcel_Reader_Excel2007 extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
					$this->Reader = \PHPExcel_IOFactory::createReader ($this->getFileType ());
						/* Подключаем фильтр **/
						$this->Reader->setReadFilter ($this->ChunkFilter);
							// Указываем что нам нужны только данные из файла - без форматирования
							$this->Reader->setReadDataOnly (true);
								// указываем диапазон для считывания
								//$this->ChunkFilter->setRows($this->filterStartRow,$this->filterColumn,$this->filterChunkSize);
					// получаем объект книги
					//$this->objReader=$this->phpExcel->load($this->fileName);

				} catch (Exception $e) {
					echo 'Ошибка подключения к файлу' . $this->fileName . ': ' , $e->getMessage () , "\n";
				}
	}

	/**
	 * Создание экземпляра класса Ридера файла и настроек чтения
	 * включая фильтр
	 * @param $FileName
	 * @return array
	 * @throws \PHPExcel_Reader_Exception
	 */
	private function createReaderMinusFilter ()
	{
		// если файл существует и класс фильтра подключен
		try {
			// получаем класс вида class PHPExcel_Reader_Excel2007 extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
			$this->Reader = \PHPExcel_IOFactory::createReader ($this->getFileType ());
			// Указываем что нам нужны только данные из файла - без форматирования
			$this->Reader->setReadDataOnly (true);
			// указываем диапазон для считывания
			//$this->ChunkFilter->setRows($this->filterStartRow,$this->filterColumn,$this->filterChunkSize);
			// получаем объект книги
			//$this->objReader=$this->phpExcel->load($this->fileName);

		} catch (Exception $e) {
			echo 'Ошибка подключения к файлу' . $this->fileName . ': ' , $e->getMessage () , "\n";
		}
	}



	/**
	 * получить класс ридера
	 * @return mixed
	 */
	public function getReader()
	{
		$this->createReader();
		return $this->Reader;
	}

	/**
	 * получить класс ридера
	 * @return mixed
	 */
	public function getReaderMinusFilter()
	{
		$this->createReaderMinusFilter();
		return $this->Reader;
	}

	public function getRowDataArray($numRow)
	{
		$range="$this->columnFirst$numRow:$this->columnLast$numRow";
		if(empty($this->filterWorksheetName)){
			$d=$this->Excel->getActiveSheet () ->rangeToArray($range,null,true,true,false);
			return $d;
		} else{
			$f=$this->Excel->getSheetByName($this->filterWorksheetName)->rangeToArray($range,null,true,true,false);
			return $f;
		}

	}

	/**
	 * Получаем количество строк с данными в открытом файле
	 * @return mixed
	 */
	public function getMaxRow()
	{
		$spreadsheetInfo=$this->Reader->listWorksheetInfo($this->fileName);
		$maxRows = $spreadsheetInfo[0]['totalRows'];
		return $maxRows;
	}

	/**
	 * @return int
	 */
	public function getFilterChunkSize ()
	{
		return $this->filterChunkSize;
	}

	public function loadFileWithFilter($startRow)
	{
		$this->ChunkFilter->setRows($startRow,$this->filterChunkSize);
			$this->Excel=$this->Reader->load($this->fileName);
		return $this->Excel;
	}
	public function loadFileMinusFilter()
	{
		$this->Excel=$this->Reader->load($this->fileName);
		return $this->Excel;
	}

	public function unset_loadFileWithFilter()
	{
		unset($this->Excel);
	}



}