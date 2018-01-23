<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.12.2016
 * Time: 18:58
 */

namespace AnalizPdvBundle\Utilits\ValidForm\parserUnit;
use AnalizPdvBundle\Utilits\ValidForm\parserUnit\parserUnitAbstract;
use AnalizPdvBundle\Utilits\ValidForm\validUnit\validInnDoc;


/**
 * парсинг ИНН клиента в ЕРПН
 * Class parseInnDoc
 * @package AnalizPdvBundle\Utilits\ValidForm\parserUnit
 */
class parserInnDocErpn extends parserUnitAbstract
{
	/**
	 * @param array $data
	 */
	public function parser (array $data)
	{
		$valid=new validInnDoc();
		foreach ($data as $key=>$value) {
			if (($key = "inn_client"))
			{
				if ($valid->isValid ($value))
				{
					$arr["inn_client"] = $value;
					return $arr;
				} else
				{
					return null;
				}

			}
		}
	}
}