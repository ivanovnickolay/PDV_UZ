<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.12.2016
 * Time: 21:53
 */

namespace App\Utilits\ValidForm\validUnit;


/**
 * Интерфейс валидаторов данных
 * Interface validUnitInterface
 * @package AnalizPdvBundle\Utilits\ValidForm\validUnit
 */
abstract class validUnitAbsract
{
	/**
	 * @var string
	 */
	protected $errorMsg ;
	/**
	 * валидация данных
	 * @return mixed
	 */
	public function isValid($data)
	{

	}

	/**
	 * возвращение ошибки валидации
	 * @return string
	 */
	final public function getError()
	{
		return $this->errorMsg;
	}

}