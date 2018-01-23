<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11.12.2016
 * Time: 22:01
 */

namespace AnalizPdvBundle\Utilits\ValidForm;


/**
 * Парсит значения данных формы для дальнейшей обработки в базе данных
 * Class parseFormData
 * @package AnalizPdvBundle\Utilits\ValidForm
 */
class parseFormData
{
	/**
	 * массив результатов парсинга
	 * @var array
	 */
	private $resultParser;

	/**
	 * @param array $data
	 * @return array
	 */
	final public function  parseData(array $data)
	{
		$this->resultParser=null;
		if (($this->repository instanceof parserUnitRepository)) {
			foreach ($data as $key => $value) {
				if ($this->repository->isField ($key)) {
					// получаем из репозитория класс парсера
					$parser = $this->repository->getValidUnit ($key);
					// формируем массив для передачи в парсер
					// только одно значение [ключ]=>[значение]
					$dataParser[$key] = $value;
					// получаем распарсерное значение
					$arrResultParser = $parser->parser ($dataParser);
					if (!empty($arrResultParser)) {
						// если получено не пустое значение то добавим массив к
						// общему массиву распарсенных значений
						$this->resultParser += $arrResultParser;
					}
				}
			}
			return $this->resultParser;
		}
	}
		/**
		 * получает массив классов валидаторв
		 * @param validUnitRepository $repository
		 * @return mixed
		 */
		final public function setValidUnitRepository(parserUnitRepository $repository)
	{
		$this->repository=$repository;
	}




}