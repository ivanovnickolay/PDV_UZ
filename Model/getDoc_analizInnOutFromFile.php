<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.03.2017
 * Time: 19:56
 */

namespace App\Model;


/**
 * Класс предназначен для формирования списков документов
 * из ЕРПН и РПН по которым при анализе налоговых обязательств по ИПН
 * выявлены расхождения
 *
 * Класс используется ТОЛЬКО при формировании файлов расхождений в файл Excel
 *
 * Class getDoc_analizInnOutFromFile
 * @package AnalizPdvBundle\Model
 */
class getDoc_analizInnOutFromFile
{

	/**
	 *  массив документов с ЕРПН
	 * @var array
	 */
	private $docByErpn;

	/**
	 *  массив документов с РПН
	 * @var array
	 */
	private $docByReestr;

	/**
	 *  из ЕРПН массив вида [keyField]=>[pdvinvoice] для использования в сравнениии
	 *
	 * @var array
	 */
	private $docByERPN_ForDiff;

	/**
	 * из РПН массив вида [keyField]=>[pdvinvoice] для использования в сравнениии
	 *
	 * @var array
	 */
	private $docByReestr_ForDiff;

	/**
	 * расхождение массивов $docByReestr_ForDiff и $docByERPN_ForDiff,
	 * по ключам
	 * @var array
	 */
	private $diffReestrToErpnByKey;

	/**
	 * расхождение массивов $docByReestr_ForDiff и $docByERPN_ForDiff,
	 * по значениям
	 * @var array
	 */
	private $diffReestrToErpnByValue;

	/**
	 * расхождение массивов $docByERPN_ForDiff и $docByReestr_ForDiff ,
	 * по ключам
	 * @var array
	 */
	private $diffErpnToReestrByKey;

	/**
	 * расхождение массивов $docByERPN_ForDiff и $docByReestr_ForDiff,
	 * по значениям
	 * @var array
	 */
	private $diffErpnToReestrByValue;
	/**
	 * Получение данных с ЕРПН
	 * @param array $docByErpn
	 */
	public function setDocByErpn(array $docByErpn)
	{
		$this->docByErpn=$docByErpn;
	}

	/**
	 * Получение данных с РПН
	 * @param array $docByReestr
	 * @internal param array $docByErpn
	 */
	public function setDocByReestr(array $docByReestr)
	{
		$this->docByReestr=$docByReestr;
	}

	/**
	 * Возвращает массив docByERPN_ForDiff
	 * вида [keyField]=>[pdvinvoice] для использования в сравнениии
	 *
	 * array_column — Возвращает массив из значений одного столбца входного массива
	 * @link  http://php.net/manual/ru/function.array-column.php
	 */
	private function getDocByERPN_ForDiff()
	{
		// получаем массив вида [keyField]=>[pdvinvoice]
		if (empty($this->docByERPN_ForDiff)) {
			//$this->docByERPN_ForDiff = array_column($this->getDocByERPN(), 'pdvinvoice', 'keyField');
			$arrayDoc=$this->docByErpn;
			if (!empty($arrayDoc)){
				foreach ($arrayDoc as $elem){
					$key=$elem['num_invoice']."/".$elem['date_create_invoice']."/".$elem['type_invoice_full']."/".$elem['inn_client'];
					$sumPdv=$elem['pdvinvoice'];
					$this->docByERPN_ForDiff[$key]=$sumPdv;
					$key='';
					$sumPdv=0;
				}
			}else{
				$this->docByERPN_ForDiff=array();
			}

		}
		return $this->docByERPN_ForDiff;
	}

	/**
	 * Возвращает массив docByERPN_ForDiff
	 * вида [keyField]=>[pdvinvoice] для использования в сравнениии
	 *
	 * array_column — Возвращает массив из значений одного столбца входного массива
	 * @link  http://php.net/manual/ru/function.array-column.php
	 */
	private function getDocByReestr_ForDiff()
	{
		// получаем массив вида [keyField]=>[pdvinvoice]
		if (empty($this->docByReestr_ForDiff)) {
			//$this->docByERPN_ForDiff = array_column($this->getDocByERPN(), 'pdvinvoice', 'keyField');
			$arrayDoc=$this->docByReestr;
			if (!empty($arrayDoc)){
				foreach ($arrayDoc as $elem){
					$key=$elem['num_invoice']."/".$elem['date_create_invoice']."/".$elem['type_invoice_full']."/".$elem['inn_client'];
					$sumPdv=$elem['pdvinvoice'];
					$this->docByReestr_ForDiff[$key]=$sumPdv;
					$key='';
					$sumPdv=0;
				}
			}else{
				$this->docByReestr_ForDiff=array();
			}

		}
		return $this->docByReestr_ForDiff;
	}

	/**
	 * Получаем документы из ЕРПН которых нет в РПН, то есть
	 * не включенные в декларацию
	 *
	 * @uses docByERPN_ForDiff
	 * @uses docByReestr_ForDiff
	 *
	 * @link http://php.net/manual/ru/function.array-diff-key.php
	 *
	 * array_diff_key — Вычисляет расхождение массивов, сравнивая ключи
	 * array array_diff_key ( array $array1 , array $array2 [, array $... ] )
	 * Возвращает array, содержащий все элементы array1 с ключами, которых нет в во всех последующих массивах.
	 * @return array
	 */
	private function getDiffErpnToReestrByKey()
	{
		if (empty($this->diffErpnToReestrByKey)) {
			$this->diffErpnToReestrByKey = array_diff_key($this->getDocByERPN_ForDiff(), $this->getDocByReestr_ForDiff());
		}
		return $this->diffErpnToReestrByKey;
	}

	/**
	 * Получаем документы из РПН которых нет в ЕРПН, то есть
	 * документы не зарегистрированные в ЕРПН - приписки !!!
	 *
	 * @uses docByERPN_ForDiff
	 * @uses docByReestr_ForDiff
	 *
	 * @link http://php.net/manual/ru/function.array-diff-key.php
	 * array_diff_key — Вычисляет расхождение массивов, сравнивая ключи
	 * array array_diff_key ( array $array1 , array $array2 [, array $... ] )
	 * Возвращает array, содержащий все элементы array1 с ключами, которых нет в во всех последующих массивах.
	 * @return array
	 */
	private function getDiffReestrToErpnByKey()
	{
		if (empty($this->diffReestrToErpnByKey)) {
			$this->diffReestrToErpnByKey = array_diff_key($this->getDocByReestr_ForDiff(), $this->getDocByERPN_ForDiff());
		}
		return $this->diffReestrToErpnByKey;
	}

	/**
	 * Получаем документы из ЕРПН по которым есть не совпадение сумм с РПН, то есть
	 * по одинаковому документу в ЕРПН и РПН включены разные суммы ПДВ
	 *
	 * @uses docByERPN_ForDiff
	 * @uses docByReestr_ForDiff
	 *
	 * @link http://php.net/manual/ru/function.array-diff.php
	 *
	 * array_diff_key — Вычисляет расхождение массивов, сравнивая ключи
	 * array array_diff_key ( array $array1 , array $array2 [, array $... ] )
	 * Возвращает array, содержащий элементы array1, отсутствующие в любом из всех остальных массивах.
	 */
	private function getDiffErpnToReestrByValue()
	{
		if (empty($this->diffErpnToReestrByValue)){
			$this->diffErpnToReestrByValue=array_diff($this->getDocByERPN_ForDiff(), $this->getDocByReestr_ForDiff());
		}
		return $this->diffErpnToReestrByValue;
	}

	/**
	 * Получаем документы из РПН по которым есть не совпадение сумм с ЕРПН, то есть
	 * по одинаковому документу в ЕРПН и РПН включены разные суммы ПДВ
	 *
	 * @uses docByERPN_ForDiff
	 * @uses docByReestr_ForDiff
	 *
	 * @link http://php.net/manual/ru/function.array-diff.php
	 * array_diff — Вычисляет расхождение массивов, сравнивая ключи
	 * array array_diff ( array $array1 , array $array2 [, array $... ] )
	 * Возвращает array, содержащий элементы array1, отсутствующие в любом из всех остальных массивах.

	 *
	 */
	private function getDiffReestrToErpnByValue()
	{
		if (empty($this->diffReestrToErpnByValue)){
			$this->diffReestrToErpnByValue=array_diff($this->getDocByReestr_ForDiff(),$this->getDocByERPN_ForDiff());
		}
		return $this->diffReestrToErpnByValue;

	}

	/**
	 *  Получаем результирующий массив расходжений между ЕРПН и РПН
	 *  с описанием ошибок
	 *  формат массива [keyField]=>[errorDescription]
	 * @param array $arrayKey массив отклонений по ключу (документу), например $this->diffErpnToReestrByKey
	 * @param array $arrayValue массив отклонений по значению (суме ПДВ), например $this->diffErpnToReestrByValue
	 * @param string $errKey ошибка при отклонению по ключу "Документ есть в ЕРПН, но не включен в РПН" или "Документ есть в РПН, но зарегистрирован в ЕРПН"
	 * @param string $errValue ошибка отклонения по значению "По документу в ЕРПН и РПН включены разные суммы ПДВ"
	 * @return mixed
	 */
	private function getResultArrayDiff(array $arrayKey, array $arrayValue, string $errKey,$errValue)
	{
		// если все массивы пустые то ошибок именно тут нет
		// возвращем пустой массив
		if (empty($arrayKey) and empty($arrayValue)){
			return array();
		}
		//  если нет отклонений по сумме ПДВ, а есть по докуметам
		if (!empty($arrayKey) and empty($arrayValue)){
			foreach ($arrayKey as $elemKey=>$elemVal){
				$result[$elemKey]=$errKey;
			}
			return $result;
		}
		//  если нет отклонений по документам, но есть по суммам
		if (empty($arrayKey) and !empty($arrayValue)){
			foreach ($arrayValue as $elemKey=>$elemVal){
				$result[$elemKey]=$errValue;
			}
			return $result;
		}
		if (!empty($arrayKey) and !empty($arrayValue)){
			// обходим массив с отклонениями по документам
			foreach ($arrayKey as $elemKey=>$elemVal){
				// получаем значение keyField - ключевого поля документа
				$key=$elemKey;
				// провериим есть ли такой ключ в массиве с отклоениями по сумме ПДВ
				if ((array_key_exists($key, ($arrayValue)))){
					// если ключи совпали то проверим совпали ли значения по ключам
					if ($elemVal=$arrayValue[$key]){
						// если значние совпали то
						$result[$key]=$errKey;
					} else{
						// // если значние не совпали то
						$result[$key]=$errValue;
					}
					// если есть ключ и там и там то отклонение есть и по документу и по сумме ПДВ
					//$result[$key]="Документ не включен в декларацию. По документу в ЕРПН и РПН включены разные суммы ПДВ";
				}else{
					// иначе отклонения только по документу
					$result[$key]=$errKey;
				}
			}
			// обходим массив с отклоненим по сумме ПДВ
			foreach ($arrayValue as $elemKey=>$elemVal){
				// получаем значение keyField - ключевого поля документа
				$key=$elemKey;
				// провериим есть ли такой ключ в массиве с отклоениями по документам
				if (!array_key_exists($key, $arrayKey)){
					//если ключа нет то записываем
					//если ключ есть - он совпал при обходе массива отклонений по документам и результаты
					// отклонений записаны там
					$result[$key]=$errValue;
				}
			}
			return $result;
		}
	}
	/**
	 *
	 *  Получаем результирующий массив расходжений между ЕРПН и РПН
	 *  с описанием ошибок
	 *  формат массива [keyField]=>[errorDescription]
	 * @uses  getResultArrayDiff для формирования массива ошибок
	 */
	private function getResultArrayDiffErpnToReestr():array
	{
		return $this->getResultArrayDiff($this->getDiffErpnToReestrByKey(),
			$this->getDiffErpnToReestrByValue(),
			"Документ есть в ЕРПН, но не включен в РПН",
			"По документу в ЕРПН и РПН включены разные суммы ПДВ");
	}

	/**
	 *
	 *  Получаем результирующий массив расходжений между РПН и ЕРПН
	 *  с описанием ошибок
	 *  формат массива [keyField]=>[errorDescription]
	 * @uses  getResultArrayDiff для формирования массива ошибок
	 */
	private function getResultArrayDiffReestrToErpn():array
	{
		return $this->getResultArrayDiff($this->getDiffReestrToErpnByKey(),
			$this->getDiffReestrToErpnByValue(),
			"Документ есть в РПН, но не зарегистрирован в ЕРПН",
			"По документу в ЕРПН и РПН включены разные суммы ПДВ");
	}
	/**
	 * формирует массив с ошибками по ЕРПН
	 * к массиву данных из ЕРПН добавим поле Error с описанием ошибки
	 *
	 *
	 */
	public function getDocByErpnWithError():array
	{
		//$this->getDocByERPN();
		$arrayError=$this->getResultArrayDiffErpnToReestr();
		return $this->getArrayDocWithError($this->docByErpn, $arrayError);


	}

	/**
	 * формирует массив с ошибками по РПН
	 * к массиву данных из РПН добавим поле Error с описанием ошибки
	 *
	 */
	public function getDocByReestrWithError()
	{
		//$this->getDocByERPN();
		$arrayError=$this->getResultArrayDiffReestrToErpn();
		return $this->getArrayDocWithError($this->docByReestr, $arrayError);


	}

	/**
	 * формирует из массива документов $docBy и массива ошибок $error
	 * сводный массив в котором каждому документу из массива документов по которому
	 * обнаружено расхождение добавлятся поле 'Error' с описанием ошибки
	 *
	 * @param array $docBy
	 * @param array $error
	 * @return array
	 */
	protected function getArrayDocWithError(array $docBy, array $error):array
	{
		$arrResult = array();
		if(!empty($docBy) and !empty($error)){
			// Для того, чтобы напрямую изменять элементы
			// массива внутри цикла, переменной $value должен предшествовать знак &.
			// @link http://php.net/manual/ru/control-structures.foreach.php
			$I=0;
			foreach ($docBy as &$arr){
				//проверям есть ли в рекущем подмассиве значений полученных с ЕРПН
				// ключ  keyField равный ключу из массива с ошибками
				$key=$arr['num_invoice']."/".$arr['date_create_invoice']."/".$arr['type_invoice_full']."/".$arr['inn_client'];
				if (key_exists($key, $error)){
					// если ключ есть то создадим новое поле в подмассиве
					// и запишем туда значение ошибки
					$arr['Error']=$error[$key];
					$I++;
				}
			}
		}
		return $docBy;
	}
}