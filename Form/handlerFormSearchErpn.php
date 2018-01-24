<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.12.2016
 * Time: 15:10
 */

namespace App\Form;


use App\Utilits\ValidForm\validFormSearchErpn;
use Doctrine\ORM\EntityManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class handlerFormSearchErpn
 * @package AnalizPdvBundle\Form
 */
class handlerFormSearchErpn
{
	private $dataForm;
	private $Doctrine;
	/**
	 * HandlerFormLoadFile constructor.
	 * @param RepositoryFactory $entityManager
	 */
	public function __construct(Registry $Doctrine)
	{
		$this->Doctrine=$Doctrine;

	}


	/**
	 * Обработчик формы поиска данных в ЕРПН
	 * @param Request $request
	 * @return bool
	 */
	public function handlerForm(Request $request)
	{
		$this->dataForm=$request->request->all();
				$validForm=new validFormSearchErpn();
					if($validForm->isValdForm($this->dataForm))
					{
						return true;
					}
					else
					{
						return false;
					}

	}

	/**
	 * возвращает результат обаботки формы
	 *
	 */
	public function getData()
	{
		$repository=$this->getTableRepository($this->dataForm);
		return $repository->findBy($this->getDataFromQuery());
	}

	/**
	 * возвращает массив данных в формате
	 * [название_поля]=>[значение_поля]
	 * для поиска значений в таблице данных
	 * в цикле чистятся поля с пустыми значенимям
	 */
	public function getDataFromQuery()
	{
		foreach ($this->dataForm as $item=>$value)
		{
			if(!empty($value))
			{
				$res[$item]=$value;
			}
		}
		return $res;

	}

	/**
	 * получение название таблицы для которой будет делаться запрос
	 * @param $d
	 * @return \Doctrine\Common\Persistence\ObjectRepository
	 */
	public function getTableRepository($d)
	{
		if($d["typeRoute"]="Выданные")
		{
			return	$this->Doctrine->getRepository("App:ErpnOut");
		}
		if($d["typeRoute"]="Полученные")
		{
			return	$this->Doctrine->getRepository("App:ErpnIn");
		}

	}

}