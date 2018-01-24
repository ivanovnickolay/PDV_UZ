<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.12.2016
 * Time: 18:50
 */

namespace App\Utilits\ValidForm\parserUnit;


/**
 * Абстрактный класс парсера данных полученных из формы поиска
 * в массив годный для использования в поиске по базе данных
 * Class parseUnitAbstract
 * @package AnalizPdvBundle\Utilits\ValidForm\parserUnit
 */
abstract class parserUnitAbstract
{
	/**
	 * метод в котором проводится парсинг данных и вывод данных годных для поиска
	 * @param $data array
	 * @return array в формате [поле_базы_данных]=>[значение_поля]
	 */
	public function parser(array $data)
	{

	}


}