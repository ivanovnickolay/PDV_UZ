<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.12.2016
 * Time: 23:54
 */

namespace  App\Utilits\ValidForm\parserUnit;


use App\Utilits\ValidForm\parserUnit\parserUnitAbstract;
use App\Utilits\ValidForm\validUnit\validUnitAbsract;

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
     * @param parserUnitAbstract $parserUnit
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