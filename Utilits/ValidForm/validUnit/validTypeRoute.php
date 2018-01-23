<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.12.2016
 * Time: 23:43
 */

namespace App\Utilits\ValidForm\validUnit;


/**
 * Class validTypeRoute
 * @package AnalizPdvBundle\Utilits\ValidForm\validUnit
 */
class validTypeRoute extends validUnitAbsract
{
private $validType= array('Выданные','Полученные');

	/**
	 * @param $data
	 * @return bool|mixed
	 */
	public function isValid ($data)
 {
	 if (!in_array($data,$this->validType))
	 {
	 	$this->errorMsg="Не верное направление";
		 return false;
	 }
	 return true;
 }
}