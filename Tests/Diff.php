<?php
	$getDiffErpnToReestrByKey = array("15"=>10,"20"=>8,"16"=>10);
	$getDiffErpnToReestrByValue = array("15"=>10,"20"=>9,"17"=>10);
	// если все массивы пустые то ошибок именно тут нет
	// возвращем пустой массив
	if (empty($getDiffErpnToReestrByKey) and empty($getDiffErpnToReestrByValue)){
		return array();
	}
	//  если нет отклонений по сумме ПДВ, а есть по докуметам
	if (!empty($getDiffErpnToReestrByKey) and empty($getDiffErpnToReestrByValue)){
		foreach ($diffErpnToReestrByKey as $elemKey=>$elemVal){
			$result[$elemKey]="Документ не включен в декларацию";
		}
		return $result;
	}
	//  если нет отклонений по документам, но есть по суммам
	if (empty($getDiffErpnToReestrByKey) and !empty($getDiffErpnToReestrByValue)){
		foreach ($diffErpnToReestrByValue as $elemKey=>$elemVal){
			$result[$elemKey]="По документу в ЕРПН и РПН включены разные суммы ПДВ";
		}
		return $result;
	}
	if (!empty($getDiffErpnToReestrByKey) and !empty($getDiffErpnToReestrByValue)){
		// обходим массив с отклонениями по документам 
		if (!empty($getDiffErpnToReestrByKey)) {
			foreach ($getDiffErpnToReestrByKey as $elemKey=>$elemVal){
				// получаем значение keyField - ключевого поля документа
				$key=$elemKey;
				// провериим есть ли такой ключ в массиве с отклоениями по сумме ПДВ
				if (array_key_exists($key, ($getDiffErpnToReestrByValue))){
					// если есть ключ и там и там то отклонение есть и по документу и по сумме ПДВ
					$result[$key]="Документ не включен в декларацию. По документу в ЕРПН и РПН включены разные суммы ПДВ";
				}else{
					// иначе отклонения только по документу
					$result[$key]="Документ не включен в декларацию.";
				}
			}
		}
		unset($key);
		// обходим массив с отклоненим по сумме ПДВ
		if (!empty($getDiffErpnToReestrByValue)) {
			foreach ($getDiffErpnToReestrByValue as $elemKey=>$elemVal){
				// получаем значение keyField - ключевого поля документа
				$key=$elemKey;
				// провериим есть ли такой ключ в массиве с отклоениями по документам
				if (!array_key_exists($key, $getDiffErpnToReestrByKey)){
					//если ключа нет то записываем
					//если ключ есть - он совпал при обходе массива отклонений по документам и результаты
					// отклонений записаны там
					$result[$key]="По документу в ЕРПН и РПН включены разные суммы ПДВ";
				}
			}
		}
	}
	var_dump($result);