<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.08.2016
 * Time: 22:08
 */

namespace App\Utilits\createEntitys\interfaceReestr;
//namespace AnalizPdvBundle\Entity;

/**
 * Class createReestr
 * @deprecated
 * @package App\Utilits\createEntitys\interfaceReestr
 */
abstract class createReestr
{
	public function __construct ()
	{

	}

	abstract public function createReestr(array $arr);
	abstract public function unsetReestr();

	/**
	 * преобразовывает число вида "111.11" в "111,11"
	 * @param string $str
	 * @return mixed
	 */
	public function getDouble(string $str)
	{
		if (is_numeric($str)){
			return str_replace ("." , "," , $str);
		} else
		{
			return $str;
		}
	}

	/**
	 * преобразовывает число как указание дати в объект дати
	 * для вставки в базу
	 * @param float $n
	 * @return \DateTime|float
	 */
	public function getDataType($n)
	{
		// иногда в реестре выданных НН вместо даты стоит "ноль" и вместо null
		// присваивается дата "2000-01-01" что етс ошибка
		if (is_numeric($n) and $n!=0) {
			return \PHPExcel_Shared_Date::ExcelToPHPObject ($n);
		} else
		{
			//todo найти как в DateTime показать нулевую строку и после этого обновить тест !
			return new \DateTime('0000-00-00');
		}

	}
}