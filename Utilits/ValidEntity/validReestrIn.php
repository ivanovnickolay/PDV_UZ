<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02.09.2016
 * Time: 18:16
 */

namespace AnalizPdvBundle\Utilits\ValidEntity;

use AnalizPdvBundle\Entity\ReestrbranchIn;
use AnalizPdvBundle\Utilits\ValidEntity\interfaceValidEntity;

class validReestrIn extends interfaceValidEntity
{


	/**
	 * валидация сущности которая передаан в параметре $entity
	 * если
	 * @param $entity
	 * @return bool
	 * true ошибок нет
	 * false есть ошибки
	 */
	public function validEntity ($entity)
	{
		$this->error='';
		$this->entity=$entity;
		// вызов правил валидации
		$this->validTypeInvoiceFull($type=$this->entity->getTypeInvoiceFull());
		$this->validInn($this->entity->getInnClient());
		$this->validNumInvoice($this->entity->getNumInvoice());

		// если строка с ошибками пуста
		if(empty($this->error))
		{
			// значит ошибок нет и сущность не содержит ошибок
			return true ;
		} else
		{
			// значит есть ошибки
			$this->key_field=$this->entity->getKeyField();
			$this->numBranch=$this->entity->getNumBranch();
			return false;
		}

	}

	/**
	 *Проверка типа документа из $validType и $elType
	 * $validType все допустиме виды документов
	 * $elType виды документов которые могут быть при электроннм документообороте
	 * @param $type string вид документа
	 */
	public function validTypeInvoiceFull($type)
	{
		$validType= array('ПНП','ПНЕ','РКП','РКЕ','МДП','МДЕ','ЧК','ТК','ГР','ПЗ','ПО','ЗП','НП','БО');
		$elType=array('ПНЕ','РКЕ','МДЕ','ЧК','ТК','ГР','ПЗ','ПО','ЗП','НП','БО');
		//$type=$this->entity->getTypeInvoiceFull();
		if (!in_array($type,$validType))
		{
			$this->error=$this->error."Тип документа не соответствует установленному. ";
		}
		if (!in_array($type,$elType))
		{
			$this->error=$this->error."Указан тип документа, на бумажных носителях. ";
		}

	}

	/**
	 * Проверка ИНН клиента - правило только цифры
	 * @param $inn string
	 */
	public function validInn($inn)
	{
		//$inn=$this->entity->getInnClient();
		if(!ctype_digit($inn))
		{
			$this->error=$this->error."ИНН клиента содержит буквы";
		}
	}

	/**
	 * Проверка номера документа - правило только цифры и слеш
	 * @ @link http://php.net/manual/ru/function.strspn.php#101133
	 * @param $numInvoice string
	 */
	public function validNumInvoice($numInvoice)
	{
		//$numInvoice=$this->entity->getNumInvoice();
		if(strlen($numInvoice) != strspn($numInvoice,'0123456789/'))
		{
			$this->error=$this->error."Номер накладной содержит буквы или пробелы";
		}
	}

	public function validRKE()
	{
		if(!empty($this->entity->getRkeNumInvoice()) and ("РКЕ"<>$this->entity->getTypeInvoiceFull()))
		{
			$this->error=$this->error." Указан документ который уточняется но тип документа не РКЕ";
		}

	}
}