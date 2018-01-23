<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12.03.2017
 * Time: 18:33
 */

namespace AnalizPdvBundle\Utilits\LoadInvoice\createEntity;


use AnalizPdvBundle\Entity\ErpnIn;
use AnalizPdvBundle\Entity\ErpnOut;
use AnalizPdvBundle\Utilits\LoadInvoice\LoadInvoiceOut\createEntity\createEntity;

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