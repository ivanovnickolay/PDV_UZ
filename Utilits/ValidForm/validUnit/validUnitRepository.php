<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.12.2016
 * Time: 23:54
 */

namespace App\Utilits\ValidForm\validUnit;


use App\Utilits\ValidForm\validUnit\validUnitAbsract;

/**
 * Класс хранит обработчики валидации
 * Class validUnitRepository
 * @package AnalizPdvBundle\Utilits\ValidForm
 */
class validUnitRepository
{
	private  $repository;

	/**
	 * @param $field
	 * @param validUnitAbsract $validUnit
	 */
	public function addValidUnit($field, validUnitAbsract $validUnit)
	{
		$this->repository[$field]=$validUnit;
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
	public function getValidUnit($field)
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