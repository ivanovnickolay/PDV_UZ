<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.12.2016
 * Time: 19:59
 */

namespace App\Utilits\ValidForm\validUnit;


/**
 * Interface validForm
 * @package AnalizPdvBundle\Utilits\ValidForm
 * Интерфейс валидации форм которые получают данные через AJAX
 */
class validForm
{
	protected $repository;
	private $errorMessage;
	// вызывается для валидации данных формы

	/**
	 * проверка данных на правильность
	 * $d[0] - название поля которое надо проверить
	 * $d[1] - значение  поля которое надо проверить
	 * @param $data array
	 * @return mixed
	 */
	final public function isValdForm(array $data)
	{
		if (($this->repository instanceof validUnitRepository)) {
			foreach ($data as $key=>$value) {
				//foreach ($d as list($field,$value)) {
				if ($this->repository->isField ($key)) {
					// получаем из репозитория класс валидатора
					$valid = $this->repository->getValidUnit ($key);
					if (!$valid->isValid ($value)) {
						// есть при проверке найдены ошибки
						// запишем ошиьку в массив с привязкой к названию поля
						$this->errorMessage[$key] = $valid->getError ();
					}
				} else {
					//$this->errorMessage[$field] = "Validator not found";
				}
				//}
			}
			// если количество записей в массиве более нуля значит данные не прошли проверку
			if (count ($this->errorMessage) > 0) {
				// возвращаем что форма не прошла провереку
				return false;
			} else {
				// возвращаем что форма прошла провереку
				return true;
			}
		} else
		{
			$this->errorMessage[0] = "Не установлены классы для проверки данных или не верные входные данные";
			return false;
		}
    }

	/**
	 * получает массив классов валидаторв
	 * @param validUnitRepository $repository
	 * @return mixed
	 */
	final public function setValidUnitRepository(validUnitRepository $repository)
	{
		$this->repository=$repository;
	}

	/**
	 * возвращает массив с ошибками проверки
	 * @return mixed
	 */
	final public function getErrorMessage()
    {
    	return $this->errorMessage;
    }



}