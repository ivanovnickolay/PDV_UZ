<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.12.2016
 * Time: 18:58
 */

namespace AnalizPdvBundle\Utilits\ValidForm\parserUnit;


use AnalizPdvBundle\Utilits\ValidForm\validUnit\validNumDoc;

/**
 * парсинг ИНН клиента в ЕРПН
 * Class parseInnDoc
 * @package AnalizPdvBundle\Utilits\ValidForm\parseUnit
 */
class parserNumDocErpn extends parserUnitAbstract
{
	/**
	 * @param array $data
	 */
	public function parser (array $data)
	{
		$valid=new validNumDoc();
		foreach ($data as $key=>$value) {
			if (($key = "num_invoice"))
			{
				if ($valid->isValid ($value))
				{
					$arr["num_invoice"] = $value;
					return $arr;
				} else
				{
					return null;
				}

			}
		}
	}
}