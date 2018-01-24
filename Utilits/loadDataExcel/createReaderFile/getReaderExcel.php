<?php
namespace App\Utilits\loadDataExcel\createReaderFile;
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.08.2016
 * Time: 0:20
  */

use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\loadDataExcel\chunkReadFilter\chunkReadFilter;

/**
 * Класс возвращает Reader настроенный для чтения данных
 * Class getReaderExcel
 * @package LoadDataExcelBundle\Util\createReaderFile
 */
class getReaderExcel
{
	/**
	 * Количество строк, которые считываются за один раз из файла
	 * @var int
	 */
	private const filterChunkSize=1000;
	/**
	 * номер строки с которой надо начинать считывать файл
	 * @var int
	 */
	private const filterStartRow=2;
	/**
	 * класс вида class PHPExcel_Reader_Excel2007 extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
	 * @var \PHPExcel_Reader_Abstract
	 */
	private $Reader;

	/**
	 * @var \PHPExcel
	 * загруженный файл Excel с параметрами указанными $ChunkFilter
	 */
	private $Excel;
	/**
	 * @var string наименование файла с путем к нему
	 */
	private $fileName;
	/**
	 * фильтр для чтения информации из файла
	 * @var \App\Utilits\loadDataExcel\chunkReadFilter\chunkReadFilter
	 */
	private $ChunkFilter;

	/**
	 * Название листа в книге
	 * @var string
	 */
	private $filterWorksheetName;
	/**
	 * @var int количестов строк которые читаются за раз
	 */
	private $maxReadRows;

	/**
	 * Настройка Ридера для получения данных
	 * @param string $fileName Имя файла должно содержать полный путь к нему !!!
	 * @param int $maxReadRows количестов строк которые читаются за раз
	 */
	public function __construct(string $fileName, int $maxReadRows=0)
	{
		// Заполняем первоначальными значениями
		$this->fileName=$fileName;
		try {
			$this->validFileName();
		} catch (errorLoadDataException $e){
				echo $e->getMessage();
		}
		// Если не указано сколько строк читать за раз устанавливаем константу
		if (0==$maxReadRows){
			$this->maxReadRows=self::filterChunkSize;
		} else{
			$this->maxReadRows=$maxReadRows;
		}
	}

	public function __destruct ()
	{
		unset($this->ChunkFilter);
		unset($this->Reader);
		unset($this->Excel);
	}

    /**
     * Проверяем наименование файла
     * @throws errorLoadDataException
     */
	public function validFileName()
	{
		if(empty($this->fileName) or (!file_exists($this->fileName)))
		{
			throw new errorLoadDataException("Файл для чтения данных не найден !");
		}
	}
	/**
	 * Определение типа файла исходя из названия (расширения) файла
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
				default:
					$extensionType = null;
					break;
			}
		}
		return $extensionType;
	}

	/**
	 * Установка параметров для фильтра по файлу
	 * @param string $columnLast конец диапазона столбцов текстовое по умолчанию $columnLast="Z"
	 * @param string $worksheetName наименовение рабочего листа книги по умолчанию $worksheetName=''
	 */
	public function createFilter(string $columnLast="Z",string $worksheetName='')
	{
			$this->filterWorksheetName=$worksheetName;
				$this->ChunkFilter=new chunkReadFilter($columnLast);
					$this->columnLast=$columnLast;
	}

	/**
	 * Создание экземпляра класса Ридера файла и настроек чтения
	 * включая фильтр
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
		} catch (\PHPExcel_Reader_Exception $e) {
					echo "Ошибка при создании Ридера ". $e->getMessage();
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
	 * получение объекта Excel с установленным значениями фильтров
	 * и получение прочитанных строк с данным начиная с $startRow и общим
	 * количеством установленным в $maxReadRows
	 * @param $startRow int первая строка для чтения данных
	 * @return \PHPExcel
	 */
	public function loadDataFromFileWithFilter(int $startRow)
	{
		$this->ChunkFilter->setRows($startRow,$this->maxReadRows);
		try{
			$this->Excel=$this->Reader->load($this->fileName);
		} catch (\PHPExcel_Reader_Exception $e){
			echo 'Ошибка чтения данных из файла' . $this->fileName . ': ' , $e->getMessage () , "\n";
		}
		return $this->Excel;
	}
	/**
	 * чтение массива ячеек из указанной строки
	 * @param $numRow int номер строки
	 * @return mixed
	 */
	public function getRowDataArray(int $numRow):array
	{
		// вычисляем текущий диапазон ячеек с учетом номера строки $numRow
		$range="$this->columnFirst$numRow:$this->columnLast$numRow";
		// если не указано название листа
		if(empty($this->filterWorksheetName)){
			// читаем даные из текущего
			$d=$this->Excel->getActiveSheet () ->rangeToArray($range,null,true,true,false);
			return $d;
		} else{
			// иначе ищем указаный лист в книге и читаем даные из него
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

	public function unset_loadFileWithFilter()
	{
		unset($this->Excel);
	}

	/**
	 * Количество строк, которые считываются за один раз из файла
	 * @return int
	 */
	public function getFilterChunkSize(){
		return $this->maxReadRows;
	}
}