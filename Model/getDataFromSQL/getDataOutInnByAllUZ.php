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
 * реестра и ЕРПН в разрезе ИНН всему УЗ
 * Class getDataOutInnByAllUZ
 * @package App\Model\getDataFromSQL
 */
class getDataOutInnByAllUZ extends getDataFromAnalizAbstract
{
	/**
	 * Анализ документов в разрезе ИНН которые есть и в ЕРПН но нет в Реестре по филиалу
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByAllUZ_new - отсюда вызывается функция
	 * @uses store_procedure::getAnalizInnOutLeftJoinAllUZ - хранимая процедура для генерации данных
	 */

	public function getErpnNoEqualReestrAllUZ(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutLeftJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnOutLeftJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		//$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получение документов с ЕРПН по расхождению сформированому в getErpnNoEqualReestrAllUZ
	 * @param int $month
	 * @param int $year
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByAllUZ_new - отсюда вызывается функция
	 * @uses store_procedure::getDocErpnBy_AnalizInnOutLeftJoinAllUZ - хранимая процедура для генерации данных
	 */
	public function getErpnNoEqualReestrAllUZ_DocErpn(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutRightJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocErpnBy_AnalizInnOutLeftJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		//$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Анализ документов в разрезе ИНН которые есть и в ЕРПН и в Реестре по ПАТ
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByAllUZ_new - отсюда вызывается функция
	 * @uses store_procedure::getAnalizInnOutInnerJoinAllUZ_tmp - хранимая процедура для генерации данных
	 */
	public function getReestrEqualErpnAllUZ(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutInnerJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnOutInnerJoinAllUZ_tmp(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Получение документов с ЕРПН по расхождению сформированному в getReestrEqualErpnAllUZ
	 * @param int $month
	 * @param int $year
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByAllUZ_new - отсюда вызывается функция
	 * @uses store_procedure::getDocErpnBy_AnalizInnOutInnerJoinAllUZ - хранимая процедура для генерации данных
	 */

	public function getReestrEqualErpnAllUZ_DocErpn(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutInnerJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocErpnBy_AnalizInnOutInnerJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получение документов с Реестров по расхождению сформированному в getReestrEqualErpnAllUZ
	 * @param int $month
	 * @param int $year
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByAllUZ_new - отсюда вызывается функция
	 * @uses store_procedure::getDocReestrBy_AnalizInnOutInnerJoinAllUZ - хранимая процедура для генерации данных
	 */
	public function getReestrEqualErpnAllUZ_DocReestr(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutInnerJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocReestrBy_AnalizInnOutInnerJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Анализ документов в разрезе ИНН которые есть в Реестре но нет в ЕРПН по ПАТ
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByAllUZ_new - отсюда вызывается функция
	 * @uses store_procedure::getAnalizInnOutRightJoinAllUZ - хранимая процедура для генерации данных
	 */
	public function getReestrNoEqualErpnAllUZ(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutRightJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnOutRightJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		//$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Получение документов с Реестров по расхождению сформированому getReestrNoEqualErpnAllUZ
	 * @param int $month
	 * @param int $year
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByAllUZ_new - отсюда вызывается функция
	 * @uses store_procedure::getDocReestrBy_AnalizInnOutRightJoinAllUZ - хранимая процедура для генерации данных
	 */
	public function getReestrNoEqualErpnAllUZ_DocReestr(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutRightJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocReestrBy_AnalizInnOutRightJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		//$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}



}