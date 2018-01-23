<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.03.2017
 * Time: 21:54
 */

namespace AnalizPdvBundle\Utilits\LoadInvoice\loadLinesData;


/**
 * интерфейс классов реализующих считываение строк из файлов с данными
 * при использовании генераторов
 *
 * @link http://php.net/manual/ru/language.generators.overview.php#112985
 * @link http://php.net/manual/ru/language.generators.comparison.php
 *
 * Interface loadLinesDataInterface
 * @package AnalizPdvBundle\Utilits\LoadInvoice\loadLinesData
 */
interface loadLinesDataInterface
{
	/**
	 *  Получение строки из файла с названием $fileName
	 *  Практическая реализация должна читать определенные типы файлов
	 *  и отдавать строки с данными
	 *
	 * @param string $fileName
	 * @return mixed
	 */
	public function getLines(string $fileName);
}