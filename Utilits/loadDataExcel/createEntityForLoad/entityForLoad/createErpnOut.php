<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12.03.2017
 * Time: 18:33
 */

namespace App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad;


use App\Entity\ErpnOut;
use App\Utilits\LoadInvoice\createEntity\createEntityInterface;


/**
 * Создание сущности ErpnOut на основании полученного массива значений
 *
 * Class createErpnOut
 * @package AnalizPdvBundle\Utilits\LoadInvoice\createEntity
 */
class createErpnOut implements createEntityInterface
{
    private  $E_Out;
	/**
	 * Создание сущности ErpnOut на основании полученного массива значений
	 * @param array $data
	 * @return ErpnOut
	 */
	public function getEntity(array $data)
 {
	  $this->E_Out=new ErpnOut();
	  $this->E_Out->setNumInvoice($data[0]);
	  $this->E_Out->setDateCreateInvoice($data[1]);
	  $this->E_Out->setDateRegInvoice($data[2]);
	  $this->E_Out->setTypeInvoiceFull($data[3]);
	  $this->E_Out->setEdrpouClient($data[4]);
	  $this->E_Out->setInnClient($data[5]);
	  $this->E_Out->setNumBranchClient($data[6]);
	  $this->E_Out->setNameClient($data[7]);
	  $this->E_Out->setSumaInvoice($data[8]);
	  $this->E_Out->setPdvinvoice($data[9]);
	  $this->E_Out->setBazaInvoice($data[10]);
	  $this->E_Out->setNameVendor($data[11]);
	  $this->E_Out->setNumBranchVendor($data[12]);
	  $this->E_Out->setNumRegInvoice($data[13]);
	  $this->E_Out->setTypeInvoice($data[14]);
	  $this->E_Out->setNumContract($data[15]);
	  $this->E_Out->setDateContract($data[16]);
	  $this->E_Out->setTypeContract($data[17]);
	  $this->E_Out->setPersonCreateInvoice($data[18]);
	  $this->E_Out->setKeyField($data[19]);
	  $this->E_Out->setRkeInfo($data[20]);

	 return  $this->E_Out;
 }


	/**
	 *
	 */
	public function unsetEntity()
 {
	 unset($this->E_Out);
 }

}