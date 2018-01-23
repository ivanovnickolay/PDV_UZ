<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.12.2016
 * Time: 20:01
 */

namespace App\Utilits\ValidForm;
use App\Utilits\ValidForm\validUnit\validForm;
use App\Utilits\ValidForm\validUnit\validInnDoc;
use App\Utilits\ValidForm\validUnit\validNumDoc;
use App\Utilits\ValidForm\validUnit\validTypeRoute;
use App\Utilits\ValidForm\validUnit\validUnitRepository;


/**
 * Класс проверяет даныне полученные от формы поиска данные в ЕРПН
 * Class validFormSearchErpn
 * @package AnalizPdvBundle\Utilits\ValidForm
 */
class validFormSearchErpn extends validForm
{
	/**
	 * validFormSearchErpn constructor.
	 */
	public function __construct()
{
	$valid=new validUnitRepository();
	$valid->addValidUnit("num_invoice",new validNumDoc());
	$valid->addValidUnit("inn_client",new validInnDoc());
	$valid->addValidUnit("typeRoute",new validTypeRoute());
	$this->setValidUnitRepository($valid);
}
}