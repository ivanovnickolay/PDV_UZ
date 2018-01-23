<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.12.2016
 * Time: 23:54
 */

namespace AnalizPdvBundle\Utilits\ValidForm;


use AnalizPdvBundle\Utilits\ValidForm\parseUnit\parserUnitAbstract;
use AnalizPdvBundle\Utilits\ValidForm\validUnit\validUnitAbsract;

/**
 * Класс хранит обработчики валидации
 * Class validUnitRepository
 * @package AnalizPdvBundle\Utilits\ValidForm
 */
class parserUnitRepository
{
	private  $repository;

	/**
	 * @param $field
	 * @param validUnitAbsract $validUnit
	 */
	public function addParserUnit($field, parserUnitAbstract $parserUnit)
	{
		$this->repository[$field]=$parserUnit;
	}

	/**
	 * @param $field
	 * @return bool
	 */
	public function isField($field)
	{
		if(array_key_exists($field,$this->repository))
		{
			return true;
		} else
		{
			return false;
		}
	}

	/**
	 * @param $field
	 * @return mixed
	 */
	public function getParserUnit($field)
	{
		if(array_key_exists($field,$this->repository))
		{
			return $this->repository[$field];
		} else
		{
			return null;
		}


	}
}