<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.09.2016
 * Time: 0:35
 */

namespace App\Model\getDataFromSQL;
use App\Model\writeAnalizPDVToFile\writeAnalizInByInn;

/**
 * Задача класса предоставить данные для заполннения анализа кредита реестра и ЕРПН в разрезе ИНН всему УЗ
 * Class getDataFromAnalizPDVOutINN
 * @package AnalizPdvBundle\Model\getDataFromSQL
 */
class getDataInINNByAll extends getDataFromAnalizAbstract
{
	/**
	 * Анализ документов в разрезе ИНН которые есть и в ЕРПН но нет в Реестре по УЗ
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @see writeAnalizInByInn::writeAnalizPDVInInnByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::getAnalizInnInLeftJoinAllUZ - хранимая процедура для генерации данных
	 */

	public function getErpnNoEqualReestrAllUZ(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutLeftJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnInLeftJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		//$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Анализ документов в разрезе ИНН которые есть и в ЕРПН и в Реестре по УЗ
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @see writeAnalizInByInn::writeAnalizPDVInInnByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::getAnalizInnInInnerJoinAllUZ - хранимая процедура для генерации данных
	 */
	public function getReestrEqualErpnAllUZ(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutInnerJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnInInnerJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получение документов из ЕРПН по которым в getReestrEqualErpnAllUZ сформированы расходждения
	 * @param int $month
	 * @param int $year
	 * @return array
	 * @see writeAnalizInByInn::writeAnalizPDVInInnByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::getDocErpnBy_AnalizInnInInnerJoinAllUZ - хранимая процедура для генерации данных
	 *
	 */
	public function getReestrEqualErpnAllUZ_DocErpn(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutInnerJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocErpnBy_AnalizInnInInnerJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получение документов из реестров филиалов по которым в getReestrEqualErpnAllUZ сформированы расходждения
	 * @param int $month
	 * @param int $year
	 * @return array
	 * @see writeAnalizInByInn::writeAnalizPDVInInnByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::getDocReestrBy_AnalizInnInInnerJoinAllUZ - хранимая процедура для генерации данных

	 */
	public function getReestrEqualErpnAllUZ_DocReestr(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutInnerJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocReestrBy_AnalizInnInInnerJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Анализ документов в разрезе ИНН которые есть в Реестре но нет в ЕРПН по УЗ
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @see writeAnalizInByInn::writeAnalizPDVInInnByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::getAnalizInnInRightJoinAllUZ - хранимая процедура для генерации данных
	 */
	public function getReestrNoEqualErpnAllUZ(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutRightJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnInRightJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		//$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получение документов из реестров филиалов по которым в getReestrNoEqualErpnAllUZ сформированы расхождения
	 * @param int $month
	 * @param int $year
	 * @return array
	 * @see writeAnalizInByInn::writeAnalizPDVInInnByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::getDocReestrBy_AnalizInnInRightJoin - хранимая процедура для генерации данных
	 */
	public function getReestrNoEqualErpnAllUZ_DocReestr(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutRightJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocReestrBy_AnalizInnInRightJoin(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		//$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * если не используется ни где = удалить !!!
	 * @param int $month
	 * @param int $year
	 * @return array
	 */
	public function getEqualNoReestrErpnAllUZ(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutRightJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnInLeftJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		//$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получение документов из ЕРПН по которым в getErpnNoEqualReestrAllUZ сформированы расходждения
	 * @param int $month
	 * @param int $year
	 * @return array
	 * @see writeAnalizInByInn::writeAnalizPDVInInnByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::getDocErpnBy_AnalizInnInLeftJoin - хранимая процедура для генерации данных
	 */
	public function getErpnNoEqualReestrAllUZ_DocErpn(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutRightJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getDocErpnBy_AnalizInnInLeftJoin(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		//$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
}