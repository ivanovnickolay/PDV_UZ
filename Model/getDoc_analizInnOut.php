<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21.02.2017
 * Time: 21:59
 */

namespace App\Model;


use App\Controller\analizDocController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Класс предназначен для формирования списков документов
 * из ЕРПН и РПН по которым при анализе налоговых обязательств по ИПН
 * выявлены расхождения
 *
 * Формируются две пары проверок "ЕРПН с РПН" (ErpnToReestr) и "РПН с ЕРПН" (ReestrToErpn)
 * по результатам которых выводяться отклонения по документам (key) и суммам ПДВ (value)
 *
 *
 * route /getDoc_analizInnOut/{month}/{year}/{numBranch}/{INN}/
 * @uses analizDocController::getDoc_analizInnOutAction() используется в контроллере
 *
 * Class getDoc_analizInnOut
 * @package AnalizPdvBundle\Model
 */
class getDoc_analizInnOut implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	private $month;
	private $year;
	private $numBranch;
	private $INN;
	
	
	/**
	 * документы из ЕРПН 
	 * @var array
	 */
	private $docByERPN;
	
	/** 
	 * документы из РПН
	 * @var array
	 */
	private $docByReestr;

	
	/** 
	 * массивы для вычисления расхождений по документам в ЕРПН
	 * [keyField]=>[pdvinvoice]
	 * @var array
	 */
	private $docByERPN_ForDiff;
	/**
	 * массивы для вычисления расхождений по документам в РПН
	 * [keyField]=>[pdvinvoice]
	 * @var array
	 */
	private $docByReestr_ForDiff;
	
	
	/**
	 * информация из ЕРПН которой нет в РПН. Сравнивание по ключу массива
	 * @var array
	 */
	private $diffErpnToReestrByKey;
	
	/**
	 * информация из РПН которой нет в ЕРПН. Сравнивание по ключу массива
	 * @var array
	 */
	private $diffReestrToErpnByKey;

	/**
	 * информация из ЕРПН которой нет в РПН. Сравнивание по значению массива
	 * @var array
	 */
	private $diffErpnToReestrByValue;

	/**
	 * информация из РПН которой нет в ЕРПН. Сравнивание по значению массива
	 * @var array
	 */
	private $diffReestrToErpnByValue;


	/**
	 * getDoc_analizInnOut constructor.
	 * @param ContainerInterface $container
	 */
	public function __construct( ContainerInterface $container)
	{
		$this->setContainer($container);
	}

	/**
	 * установка параметров поиска документов
	 *  $paramSeachDoc array "key"=>"value"
	 *
	 *  $paramSeachDoc['month'];
	 *  $paramSeachDoc['year'];
	 *  $paramSeachDoc['INN'];
	 *  $paramSeachDoc['numBranch'];
	 * @param array $paramSeachDoc
	 */
	public function setParamSearchDoc(array $paramSeachDoc)
	{
		$this->month=$paramSeachDoc['month'];
		$this->year=$paramSeachDoc['year'];
		$this->INN=$paramSeachDoc['INN'];
		$this->numBranch=$paramSeachDoc['numBranch'];

	}

	/**
	 * формирует и возвращает  информацию о документах из ЕРПН
	 *
	 * @return array docByERPN
	 */
	public function getDocByERPN():array
	{
		// если массив значений пустой то
		// формируем запрос и отдаем результат
		if (empty($this->docByERPN)){
			$this->docByERPN=$this->container->get('doctrine')->getRepository('AnalizPdvBundle:ErpnOut')->getDocFromERPN($this->month, $this->year,$this->numBranch, $this->INN);
		}
		// если массив не пустой - отдаем результат
		return $this->docByERPN;
				//$this->docByERPN=$this->get('doctrine')->getRepository('AnalizPdvBundle:ErpnOut')->getDocFromERPN($this->month, $this->year,$this->numBranch, $this->INN);
	}

	/**
	 * формирует массив с ошибками по ЕРПН
	 *
	 * $arrResult[$I]['NumInvoice']
	 * $arrResult[$I]['DateCreateInvoice']
	 * $arrResult[$I]['InnClient']
	 * $arrResult[$I]['Error']
	 *
	 */
	public function getDocByErpnWithError():array
	{
		//$this->getDocByERPN();
		$arrayError=$this->getResultArrayDiffErpnToReestr();
		return $this->getArrayDocWithError($this->getDocByERPN(), $arrayError);


	}

	/**
	 * формирует массив с ошибками по РПН
	 *
	 * $arrResult[$I]['NumInvoice']
	 * $arrResult[$I]['DateCreateInvoice']
	 * $arrResult[$I]['InnClient']
	 * $arrResult[$I]['Error']
	 *
	 */
	public function getDocByReestrWithError()
	{
		//$this->getDocByERPN();
		$arrayError=$this->getResultArrayDiffReestrToErpn();
		return $this->getArrayDocWithError($this->getDocByReestr(), $arrayError);


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

				if (key_exists($arr->getKeyField(), $error)){
					// если ключ есть то создадим новое поле в подмассиве
					// и запишем туда значение ошибки
					$arrResult[$I]['NumInvoice']=$arr->getNumInvoice();
					$arrResult[$I]['DateCreateInvoice']=$arr->getDateCreateInvoice();
					$arrResult[$I]['TypeDoc']=$arr->getTypeInvoiceFull();
					$arrResult[$I]['Error']=$error[$arr->getKeyField()];
					$I++;
				}
			}
		}
		return $arrResult;
	}



	/**
	 * формирует и возвращает  информацию о документах из РПН
	 *
	 * @return mixed
	 */
	public function getDocByReestr()
	{
		// если массив значений пустой то
		// формируем запрос и отдаем результат
		if (empty($this->docByReestr)){
			$this->docByReestr=$this->container->get('doctrine')->getRepository('AnalizPdvBundle:ReestrbranchOut')->getDocFromReestr($this->month, $this->year,$this->numBranch, $this->INN);
		}
			// если массив не пустой - отдаем результат
			return $this->docByReestr;
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
			$arrayDoc=$this->getDocByERPN();
			if (!empty($arrayDoc)){
				foreach ($arrayDoc as $elem){
					$key=$elem->getKeyField();
					$sumPdv=$elem->getPdvinvoice();
					$this->docByERPN_ForDiff[$key]=$sumPdv;
				}
			}else{
				$this->docByERPN_ForDiff=array();
			}

		}
		return $this->docByERPN_ForDiff;
	}

	/**
	 * Возвращает массив docByReestr_ForDiff
	 * вида [keyField]=>[pdvinvoice] для использования в сравнениии
	 *
	 * так как надо подсчитать сумму ПДВ по ставке 20% и 7%  используем
	 * foreach по всем элементам массива
	 */
	private function getDocByReestr_ForDiff()
	{
		if (empty($this->docByReestr_ForDiff)){
			$arrayDoc=$this->getDocByReestr();
			if (!empty($arrayDoc)){
				foreach ($arrayDoc as $elem){
					$key=$elem->getKeyField();
					$sumPdv=$elem->getPdv20()+$elem->getPdv7();
					$this->docByReestr_ForDiff[$key]=$sumPdv;
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
	 */
	private function getResultArrayDiffErpnToReestr_old()
	{
		// формируем значние массивов отклонений
		$this->getDiffErpnToReestrByKey();
		$this->getDiffErpnToReestrByValue();

		// если все массивы пустые то ошибок именно тут нет
		// возвращем пустой массив
		if (empty($this->diffErpnToReestrByKey) and empty($this->diffErpnToReestrByValue)){
			return array();
		}
		//  если нет отклонений по сумме ПДВ, а есть по докуметам
		if (!empty($this->diffErpnToReestrByKey()) and empty($this->diffErpnToReestrByValue)){
			foreach ($this->diffErpnToReestrByKey as $elemKey=>$elemVal){
				$result[$elemKey]="Документ не включен в декларацию";
			}
			return $result;
		}
		//  если нет отклонений по документам, но есть по суммам
		if (empty($this->diffErpnToReestrByKey()) and !empty($this->diffErpnToReestrByValue)){
			foreach ($this->diffErpnToReestrByValue as $elemKey=>$elemVal){
				$result[$elemKey]="По документу в ЕРПН и РПН включены разные суммы ПДВ";
			}
			return $result;
		}
		if (!empty($this->diffErpnToReestrByKey) and !empty($this->diffErpnToReestrByValue)){
			// обходим массив с отклонениями по документам
			foreach ($this->diffErpnToReestrByKey as $elemKey=>$elemVal){
				// получаем значение keyField - ключевого поля документа
				$key=$elemKey;
				// провериим есть ли такой ключ в массиве с отклоениями по сумме ПДВ
				if (array_key_exists($key, ($this->diffErpnToReestrByValue))){
					// если есть ключ и там и там то отклонение есть и по документу и по сумме ПДВ
					$result[$key]="Документ не включен в декларацию. По документу в ЕРПН и РПН включены разные суммы ПДВ";
				}else{
					// иначе отклонения только по документу
					$result[$key]="Документ не включен в декларацию.";
				}
			}
			// обходим массив с отклоненим по сумме ПДВ
			foreach ($this->diffErpnToReestrByValue as $elemKey=>$elemVal){
				// получаем значение keyField - ключевого поля документа
				$key=$elemKey;
				// провериим есть ли такой ключ в массиве с отклоениями по документам
				if (!array_key_exists($key, $this->diffErpnToReestrByKey)){
					//если ключа нет то записываем
					//если ключ есть - он совпал при обходе массива отклонений по документам и результаты
					// отклонений записаны там
					$result[$key]="По документу в ЕРПН и РПН включены разные суммы ПДВ";
				}
			}
			return $result;
		}
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
}