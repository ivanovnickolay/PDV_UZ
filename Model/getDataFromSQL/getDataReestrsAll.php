<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.09.2016
 * Time: 17:14
 */

namespace App\Model\getDataFromSQL;


/**
 * Задача класса предоставить данные для заполннения анализа реестров
 * в всему УЗ за период
 * НЕ используется - нет вызовов класса - удалить ??
 * todo перевести SQL в хранимые процедуры
 * Class getReestrEqualErpn
 * @package AnalizPdvBundle\Model\getDataFromSQL
 */
class getDataReestrsAll extends getDataFromAnalizAbstract
{

	/**
	 * Возвращает массив информации с реестра полученных НН которые
	 * совпали с ЕРПН по параметрам
	 * @link  http://yapro.ru/web-master/mysql/doctrine2-nativnie-zaprosi.html
	 * @param $month string
	 * @param $year string
	 * @param $numBranch string
	 * @return array arrayResult
	 */
	public function getReestrInEqualErpn($month, $year)
{
	//$smtp=$this->em->getConnection();
	$this->reconnect();
	$sql="SELECT
        month,
        year,num_branch,
        COUNT(num_invoice),
				SUM(suma_invoice) as edrpou_sum,
				SUM(baza_invoice) as edrpou_baza,
				SUM(pdvinvoice) as edrpou_pdv,
				SUM(zag_summ) as reestr_sum,
				SUM(baza) as reestr_baza,
				SUM(pdv) as reestr_pdv,
				SUM(suma_invoice - zag_summ) as saldo_sum,
				SUM(baza_invoice - baza) as saldo_baza,
				SUM(pdvinvoice - pdv) as saldo_pdv
				from `in_erpn=reestr`
				WHERE month =:m AND year=:y
        		GROUP BY
       			 month,
       			year,
        		num_branch";
	$smtp=$this->em->getConnection()->prepare($sql);
	$smtp->bindValue("m",$month);
	$smtp->bindValue("y",$year);
	$smtp->execute();
	$arrayResult=$smtp->fetchAll();
	return $arrayResult;
}
	/**
	 * Возвращает массив информации с реестра полученных НН которые
	 * совпали с ЕРПН по параметрам
	 * @link  http://yapro.ru/web-master/mysql/doctrine2-nativnie-zaprosi.html
	 * @param $month string
	 * @param $year string
	 * @param $numBranch string
	 * @return array arrayResult
	 */
	public function getReestrInNotEqualErpn($month, $year)
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="SELECT month,
		  	year,
		  	num_branch,
		  	COUNT(num_branch),
			SUM(zag_summ),
		  	SUM(baza),
		  	SUM(pdv)
			from no_valid_reestr_in
			WHEre month=:m and year=:y
		    GROUP BY
		        month,
		        year,
		        num_branch";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	public function getReestrOutEqualErpn($month, $year)
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="SELECT
        month,
        year,num_branch,
        COUNT(num_invoice),
				SUM(suma_invoice) as edrpou_sum,
				SUM(baza_invoice) as edrpou_baza,
				SUM(pdvinvoice) as edrpou_pdv,
				SUM(zag_summ) as reestr_sum,
				SUM(baza) as reestr_baza,
				SUM(pdv) as reestr_pdv,
				SUM(suma_invoice - zag_summ) as saldo_sum,
				SUM(baza_invoice - baza) as saldo_baza,
				SUM(pdvinvoice - pdv) as saldo_pdv
				from `out_erpn=reestr`
				WHERE month =:m AND year=:y
        		GROUP BY
       			 month,
       			year,
        		num_branch";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Возвращает массив информации с реестра полученных НН которые
	 * совпали с ЕРПН по параметрам
	 * @link  http://yapro.ru/web-master/mysql/doctrine2-nativnie-zaprosi.html
	 * @param $month string
	 * @param $year string
	 * @param $numBranch string
	 * @return array arrayResult
	 */
	public function getReestrOutNotEqualErpn($month, $year)
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="SELECT month,
		  	year,
		  	num_branch,
		  	COUNT(num_branch),
			SUM(zag_summ),
		  	SUM(baza),
		  	SUM(pdv)
			from no_valid_reestr_out
			WHEre month=:m and year=:y
		    GROUP BY
		        month,
		        year,
		        num_branch";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

}