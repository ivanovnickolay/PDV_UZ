<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.09.2016
 * Time: 0:35
 */

namespace App\Model\getDataFromSQL;

/**
 * Задача класса предоставить данные для заполннения анализа обязательств
 * реестра и ЕРПН в разрезе ИНН по одному филиалу
 * @package App\Model\getDataFromSQL


 */
class getDataOutINNByOne extends getDataFromAnalizAbstract
{
	/**
	 * Данные анализа обязательств если документы с ЕРПН равны документам с Реестра филиала
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranch - отсюда вызывается функция
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::AnalizInnOutInnerJoinOneBranch - хранимая процедура для генерации данных
	 */
	public function getReestrEqualErpn(int $month, int $year, string $numBranch)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutInnerJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnOutInnerJoinBranch(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получение документов с ЕРПН по расхождению сформированному в getReestrEqualErpn
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::getDocErpnBy_AnalizInnOutInnerJoinBranch - хранимая процедура для генерации данных
	 */

	public function getReestrEqualErpn_DocErpn(int $month, int $year,string $numBranch)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutInnerJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocErpnBy_AnalizInnOutInnerJoinBranch(:m,:y,:b)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("b",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получение документов с Реестров по расхождению сформированному в getReestrEqualErpn
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::getDocReestrBy_AnalizInnOutInnerJoinBranch - хранимая процедура для генерации данных
	 */
	public function getReestrEqualErpn_DocReestr(int $month, int $year,string $numBranch)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutInnerJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocReestrBy_AnalizInnOutInnerJoinBranch(:m,:y,:b)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("b",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Данные анализа обязательств только документы которые есть в Реестрах филиала но нет в ЕРПН
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranch - отсюда вызывается функция
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::AnalizInnOutRightJoinOneBranch - хранимая процедура для генерации данных
	 */
	public function getReestrNoEqualErpn(int $month, int $year, string $numBranch)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutRightJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnOutRightJoinBranch(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получение документов с Реестров по расхождению сформированому getReestrNoEqualErpn
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::getDocReestrBy_AnalizInnOutRightJoinBranch - хранимая процедура для генерации данных
	 */
	public function getReestrNoEqualErpn_DocReestr(int $month, int $year, string $numBranch)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutRightJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocReestrBy_AnalizInnOutRightJoinBranch(:m,:y,:b)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("b",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Данные анализа обязательств только документы которые есть в ЕРПН но нет в Реестрах филилала
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranch - отсюда вызывается функция
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::AnalizInnOutLeftJoinOneBranch - хранимая процедура для генерации данных
	 */
	public function getErpnNoEqualReestr(int $month, int $year, string $numBranch)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutLeftJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnOutLeftJoinBranch(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получение документов с ЕРПН по расхождению сформированому в getErpnNoEqualReestr
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::getDocErpnBy_AnalizInnOutLeftJoinBranch - хранимая процедура для генерации данных
	 */
	public function getErpnNoEqualReestr_DocErpn(int $month, int $year, string $numBranch)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutRightJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocErpnBy_AnalizInnOutLeftJoinBranch(:m,:y,:b)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("b",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

}