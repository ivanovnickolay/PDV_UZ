<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.03.2017
 * Time: 22:06
 */

namespace App\Utilits\LoadInvoice\loadLinesData;


/**
 * По строчное чтение файла CSV при помощи генератора
 * @link http://php.net/manual/ru/language.generators.overview.php#112985
 * @link http://php.net/manual/ru/language.generators.comparison.php
 *
 * Class loadLinesDataCSV
 * @package AnalizPdvBundle\Utilits\LoadInvoice\loadLinesData
 */
class loadLinesDataCSV implements loadLinesDataInterface
{
	/**
	 * по строчное чтение файла CSV при помощи генератора
	 *
	 * @param string $fileName
	 * @return \Generator|void
	 */
	public function getLines(string $fileName)
	{
		if (!$fileHandle = fopen($fileName, 'r')) {
			return;
		}

		while (false !== $line = explode(";",iconv('Windows-1251',"UTF-8",fgets($fileHandle)))){
			yield $line;
		}

		fclose($fileHandle);
	}

}