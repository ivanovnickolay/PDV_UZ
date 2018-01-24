<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02.10.2016
 * Time: 16:56
 */

namespace App\Utilits;


/**
 * Клас проверяет данные которые вводятся при вводе команд на проведение анализов
 * Class validInputCommand
 * @package AnalizPdvBundle\Utilits
 */
class validInputCommand
{
	private $em;
	private $textError;

	public function __construct ($entityManager)
	{
		$this->em=$entityManager;
	}

	public function  validMonth($month)
	{
		$result=true;
		$this->textError='';
		if(is_null($month) or empty($month))
		{
			$this->textError=$this->textError." Вы не ввели обязательный параметр (он нулевой или пустой) --month=__.
			Выполнение команды  не возможно!!";
			$result=false;
		}


		$arrMonth=array(1,2,3,4,5,6,7,8,9,10,11,12);
		$str=strlen($month);
		$f=in_array((int) $month,$arrMonth);
		if((2<strlen($month)) or (!in_array((int) $month,$arrMonth)))
		{
			$this->textError=$this->textError." Вы не ввели не верное значение обязательного параметра (он не в
			дипазоне 1-12) --month=__. Выполнение команды не возможно!!";
			$result=false;
		}

			return $result;
	}

	public function getTextError()
	{
		return $this->textError;
	}
	public function  validYear($year)
	{
		$result=true;
		$this->textError='';

		$arrYear=array(2015,2016,2017);
		if(is_null($year) or empty($year))
		{
			$this->textError=$this->textError."Вы не ввели обязательный параметр --year=__. Выполнение команды не возможно!!";
			$result=false;
		}
		if(4<strlen($year) or (!in_array($year,$arrYear)))
		{
			$this->textErrorMonth=$this->textError." Вы не ввели не верное значение обязательного параметра --year=__. Выполнение команды
                 не возможно!!";
			$result=false;
		}

		return $result;
	}
	public function  validBranch($numBranch)
	{
		return false;
	}
}