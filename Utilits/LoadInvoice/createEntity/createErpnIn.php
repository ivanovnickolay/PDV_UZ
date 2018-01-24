<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12.03.2017
 * Time: 18:33
 */

namespace App\Utilits\LoadInvoice\createEntity;


use App\Entity\ErpnIn;
use App\Entity\ErpnOut;
use App\Utilits\LoadInvoice\LoadInvoiceOut;

/**
 * Создание сущности ErpnIn на основании полученного массива значений
 *
 * Class createErpnOut
 * @package AnalizPdvBundle\Utilits\LoadInvoice\createEntity
 */
class createErpnIn implements createEntityInterface
{
	private $E_In;

	/**
	 * Создание сущности ErpnIn на основании полученного массива значений
	 * @param array $data
	 * @return ErpnIn
	 */
	public function getEntity(array $data)
 {
	 $this->E_In=new ErpnIn();


	 return $this->E_In;
 }

	/**
	 *
	 */
	public function unsetEntity()
 {
	 unset($this->E_In);
 }
}