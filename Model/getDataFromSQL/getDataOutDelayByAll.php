<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.09.2016
 * Time: 0:35
 */

namespace App\Model\getDataFromSQL;

/**
 * Задача класса предоставить данные для заполннения анализа опаздавших выданных НН по ПАТ
 * @see writeAnalizOutDelayDate::writeAnalizPDVOutDelayByAllUZ
 * @package AnalizPdvBundle\Model\getDataFromSQL
 */
class getDataOutDelayByAll extends getDataFromAnalizAbstract
{
	/**
	 * Получаем весь список опаздавших с регистрацией НН по ПАТ
	 * @param int $month
	 * @param int $year
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @see writeAnalizOutDelayDate::writeAnalizPDVOutDelayByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::AnalizPDVOutDiffDateAllUZInnerJoinERPN - хранимая процедура для генерации данных
	 */
	public function getAllDelay(int $month, int $year)
	{
		// так как в хранимой процедуре используются временные таблицы, для их обнуления
		// "передергнем соединение с базой для очистки временных таблиц
		$this->disconnect();
		$this->connect();
		$sql="CALL AnalizPDVOutDiffDateAllUZInnerJoinERPN(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		//var_dump($arrayResult);

		return $arrayResult;
	}

	/**
	 * Получаем список опаздавших НН которые включены в Реестр филиала по ПАТ
	 * @param int $month
	 * @param int $year
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @see writeAnalizOutDelayDate::writeAnalizPDVOutDelayByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::AnalizPDVOutDiffDateAllUZInnerJoinReestr - хранимая процедура для генерации данных
	 */
	public function getDelayToReestr(int $month, int $year)
	{
		// так как в хранимой процедуре используются временные таблицы, для их обнуления
		// "передергнем соединение с базой для очистки временных таблиц
		$this->disconnect();
		$this->connect();
		$sql="CALL AnalizPDVOutDiffDateAllUZInnerJoinReestr(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получаем список опаздавших НН которые НЕ включены в Реестр филиала
	 * @param int $month
	 * @param int $year
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @see writeAnalizOutDelayDate::writeAnalizPDVOutDelayByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::AnalizPDVOutDiffDateAllUZLeftJoinERPN - хранимая процедура для генерации данных
	 */
	public function getDelayToNotReestr(int $month, int $year)
	{
		// так как в хранимой процедуре используются временные таблицы, для их обнуления
		// "передергнем соединение с базой для очистки временных таблиц
		$this->disconnect();
		$this->connect();
		$sql="CALL AnalizPDVOutDiffDateAllUZLeftJoinERPN(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
}