<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 03.03.2017
 * Time: 0:14
 */

namespace App\Model;


/**
 * Класс предназначен для работы с списками филиалов
 *
 * Class workWithBranch
 * @package AnalizPdvBundle\Model
 */
class workWithBranch
{

	/**
	 * возвращает номер главного филиала для филиала в периоде
	 * используется когда филиал перетерпел стуктурные изменения и
	 * переподчинился другому филиалу
	 *
	 *
	 */
	public function getNumMainBranchPeriod($dataInvoice, array $arrayData)
	{
		foreach ($arrayData as $arr) {
			$r=(strtotime($arr['beginData'])<= strtotime($dataInvoice));
			$f=(strtotime($arr['endData'])==strtotime('00-00-0000'));

			if ($r and $f){
				return $arr['numMainBranch'];
			}
				$t=(strtotime($arr['endData'])>=strtotime($dataInvoice));
				if ($r and $t){
						return $arr['numMainBranch'];
				}


		}
		return null;


	}

}