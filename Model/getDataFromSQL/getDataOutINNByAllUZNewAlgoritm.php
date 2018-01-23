<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.03.2017
 * Time: 22:21
 */

namespace App\Model\getDataFromSQL;


use App\Model\Exception\noCorrectDataException;
use App\Model\getDataFromSQL\prepareSQL\prepareSQDataOutINNByAllUZ;
use App\Model\workDateForSQL;

/**
 * Class getDataOutINNByOneNewAlgoritm
 * @package AnalizPdvBundle\Model\getDataFromSQL
 */
class getDataOutINNByAllUZNewAlgoritm extends getDataFromAnalizAbstract
{
	/**
	 * месяц анализа
	 * @var int
	 */
	private $monthAnaliz;
	/**
	 * @var array
	 */
	protected $monthAnailzCorrect=["1","2","3","4","5","6","7","8","9","10","11","12"];
	/**
	 * год анализа
	 * @var int
	 */
	private $yearAnaliz;
	protected $yearAnalizCorrect=["2015","2016","2017"];
	/**
	 * @var string
	 */
	protected $numBranch;
	/**
	 * @var prepareSQDataOutINNByAllUZ
	 */
	private $prepareSQLData;

	/**
	 * @var workDateForSQL
	 */
	private $workDate;

	/**
	 * @param $month
	 * @param $year
	 * @throws noCorrectDataException
	 */
	public function init($month, $year)
	{
		if (in_array($month, $this->monthAnailzCorrect)){
			$this->monthAnaliz = $month;
		} else{
			throw new noCorrectDataException("Номер месяца вне диапазона. Инициализация объекта не проведена ");
		}

		if (in_array($year, $this->yearAnalizCorrect)){
			$this->yearAnaliz = $year;
		} else{
			throw new noCorrectDataException("Номер года вне диапазона. Инициализация объекта не проведена ");
		}
		if ($this->monthAnaliz<12 and $this->yearAnaliz==2015){
			throw new noCorrectDataException("Дата меньше 12-2015 не допускается. Инициализация объекта не проведена ");
		}
		try{
			$this->workDate=new workDateForSQL($this->monthAnaliz, $this->yearAnaliz);
		} catch (noCorrectDataException $e){
			echo 'Поймано исключение при инициализации класса workDateForSQL: ',  $e->getMessage(), "\n";
		}
		$this->prepareSQLData= new prepareSQDataOutINNByAllUZ($this->workDate);
		$this->createTempTable();
	}

	/**
	 * Формирование запроса который возвращает результат своей работы
	 * @param string $SQL текст запроса
	 * @param array $paramBind параметры запросе
	 * @return array
	 */
	private function executeSQLWithReturn(string $SQL, array $paramBind=null){
		$smtp=$this->em->getConnection()->prepare($SQL);
		if(!is_null($paramBind)){
			foreach ($paramBind as $key=>$value){
				$smtp->bindValue("$key", $value);
			}
		}
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;

	}

	/**
	 * Формирование запроса который НЕ возвращает результат своей работы
	 * @param string $SQL текст запроса
	 * @param array $paramBind параметры запросе
	 */
	private function executeSQLWithoutReturn(string $SQL, array $paramBind=null){
		$smtp=$this->em->getConnection()->prepare($SQL);
		if(!is_null($paramBind)){
			foreach ($paramBind as $key=>$value){
				$smtp->bindValue("$key", $value);
			}
		}
		$smtp->execute();
	}

	/**
	 * Создание временных таблиц
	 */
	private function createTempTable(){
		$this->executeSQLWithoutReturn(
			$this->prepareSQLData->getPrepareSQLCreateTmpTableErpnOut(),
			$this->prepareSQLData->getPrepareBindValueCreateTmpTableErpnOut());

		$this->executeSQLWithoutReturn(
			$this->prepareSQLData->getPrepareSQLCreateTmpTableReestrOut(),
			$this->prepareSQLData->getPrepareBindValueCreateTmpTableReestrOut());

		$this->executeSQLWithoutReturn(
			$this->prepareSQLData->getPrepareSQLCreateTmpTableOut_InnerJoin(),
			$this->prepareSQLData->getPrepareBindValueCreateTmpTableOut_InnerJoint());

		$this->executeSQLWithoutReturn(
			$this->prepareSQLData->getPrepareSQLCreateTmpTableOut_LeftJoin(),
			$this->prepareSQLData->getPrepareBindValueCreateTmpTableOut_LeftJoin());

		$this->executeSQLWithoutReturn(
			$this->prepareSQLData->getPrepareSQLCreateTmpTableOut_RightJoin(),
			$this->prepareSQLData->getPrepareBindValueCreateTmpTableOut_RightJoin());
	}

	/**
	 * Данные анализа обязательств если документы с ЕРПН равны документам с Реестра филиала
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranch - отсюда вызывается функция
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 */
	public function getReestrEqualErpnAllUZ()
	{
		$arrayResult=$this->executeSQLWithReturn(
			$this->prepareSQLData->getPrepareSQLOut_InnerJoin(),
			$this->prepareSQLData->getPrepareBindValueOut_InnerJoin()
		);
		return $arrayResult;
	}

	/**
	 * Получение документов с ЕРПН по расхождению сформированному в getReestrEqualErpn
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 */
	public function getReestrEqualErpnAllUZ_DocErpn(){
		$arrayResult=$this->executeSQLWithReturn(
			$this->prepareSQLData->getPrepareSQLOut_InnerJoin_DocByErpn(),
			$this->prepareSQLData->getPrepareBindValueOut_InnerJoin_DocByErpn()
		);
		return $arrayResult;
	}

	/**
	 * Получение документов с Реестров по расхождению сформированному в getReestrEqualErpn
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 */
	public function getReestrEqualErpnAllUZ_DocReestr(){
		$arrayResult=$this->executeSQLWithReturn(
			$this->prepareSQLData->getPrepareSQLOut_InnerJoin_DocByReestr(),
			$this->prepareSQLData->getPrepareBindValueOut_InnerJoin_DocByReestr()
		);
		return $arrayResult;
	}

	/**
	 * Данные анализа обязательств только документы которые есть в Реестрах филиала но нет в ЕРПН
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranch - отсюда вызывается функция
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 */
	public function getReestrNoEqualErpnAllUZ(){
		$arrayResult=$this->executeSQLWithReturn(
			$this->prepareSQLData->getPrepareSQLOut_RightJoin(),
			$this->prepareSQLData->getPrepareBindValueOut_RightJoin()
		);
		return $arrayResult;
	}
	/**
	 * Получение документов с Реестров по расхождению сформированому getReestrNoEqualErpn
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 */
	public function getReestrNoEqualErpnAllUZ_DocReestr(){
		$arrayResult=$this->executeSQLWithReturn(
			$this->prepareSQLData->getPrepareSQLOut_RightJoin_DocByReestr(),
			$this->prepareSQLData->getPrepareBindValueOut_RightJoin_DocByReestr()
		);
		return $arrayResult;
	}

	/**
	 * Данные анализа обязательств только документы которые есть в ЕРПН но нет в Реестрах филилала
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranch - отсюда вызывается функция
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 */
	public function getErpnNoEqualReestrAllUZ(){
		$arrayResult=$this->executeSQLWithReturn(
			$this->prepareSQLData->getPrepareSQLOut_LeftJoin(),
			$this->prepareSQLData->getPrepareBindValueOut_LeftJoin()
		);
		return $arrayResult;
	}

	/**
	 * Получение документов с ЕРПН по расхождению сформированому в getErpnNoEqualReestr
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 */
	public function getErpnNoEqualReestrAllUZ_DocErpn(){
		$arrayResult=$this->executeSQLWithReturn(
			$this->prepareSQLData->getPrepareSQLOut_LeftJoin_DocByErpn(),
			$this->prepareSQLData->getPrepareBindValueOut_LeftJoin_DocByErpn()
		);
		return $arrayResult;
	}
}