<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.09.2016
 * Time: 0:35
 */

namespace App\Model\getDataFromSQL;
use Doctrine\ORM\EntityManager;


/**
 * Задача класса предоставить данные для заполннения анализа опаздавших выданных НН по одному филиалу
 * @uses getDataFromAnalizPDVOutDelay::getAllDelay Получаем весь список опаздавших выданных НН по одному филиалу в периоде
 * @uses getDataFromAnalizPDVOutDelay::getDelayToReestr Получаем список опаздавших выданных НН которые включены в Реестр филиала по одному филиалу в периоде
 * @uses getDataFromAnalizPDVOutDelay::getDelayToNotReestr Получаем список опаздавших вчыданных НН которые НЕ включены в Реестр филиала по одному филиалу в периоде
 *
 * @package AnalizPdvBundle\Model\getDataFromSQL
 */
class getDataFromAnalizPDVOutDelay
{
	private $em;

	/**
	 * getDataFromAnalizPDVOutDiff constructor.
	 * @param EntityManager $em
	 */
	public function __construct (EntityManager $em)
	{
		$this->em=$em;
	}

	public function disconnect()
	{
		$this->em->getConnection()->close();
	}

	public function connect()
	{
		$this->em->getConnection()->connect();
	}

	/**
	 * MySQL Server has gone away
	 */
	public function reconnect()
	{
		$connection = $this->em->getConnection();
		if (!$connection->ping()) {

			$this->disconnect();
			$this->connect();

			$this->checkEMConnection($connection);
		}
	}

	/**
	 * method checks connection and reconnect if needed
	 * MySQL Server has gone away
	 *
	 * @param $connection
	 * @throws \Doctrine\ORM\ORMException
	 */
	protected function checkEMConnection($connection)
	{

		if (!$this->em->isOpen()) {
			$config = $this->em->getConfiguration();

			$this->em = $this->em->create(
				$connection, $config
			);
		}
	}

	/**
	 * Получаем весь список опаздавших выданных НН по одному филиалу в периоде
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @see writeAnalizPDVToFile::writeAnalizPDVOutDelayByOneBranch - отсюда вызывается функция (вызов убран 27-10-16)
	 * @uses store_procedure::AnalizPDVOutDiffDateOneBranchInnerJoinERPN_tempTable - хранимая процедура для генерации данных
	 */
	public function getAllDelay(int $month, int $year, string $numBranch)
	{
		// так как в хранимой процедуре используются временные таблицы, для их обнуления
		// "передергнем соединение с базой для очистки временных таблиц
		$this->disconnect();
		$this->connect();
		$sql="CALL AnalizPDVOutDiffDateOneBranchInnerJoinERPN_tempTable(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получаем список опаздавших выданных НН которые включены в Реестр филиала по одному филиалу в периоде
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @see writeAnalizPDVToFile::writeAnalizPDVOutDelayByOneBranch - отсюда вызывается функция (вызов убран 27-10-16)
	 * @uses store_procedure::AnalizPDVOutDiffDateOneBranchInnerJoinReestr_tempTable - хранимая процедура для генерации данных
	 */
	public function getDelayToReestr(int $month, int $year, string $numBranch)
	{
		// так как в хранимой процедуре используются временные таблицы, для их обнуления
		// "передергнем соединение с базой для очистки временных таблиц
		$this->disconnect();
		$this->connect();
		$sql="CALL AnalizPDVOutDiffDateOneBranchInnerJoinReestr_tempTable(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Получаем список опаздавших вчыданных НН которые НЕ включены в Реестр филиала по одному филиалу в периоде
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @see writeAnalizPDVToFile::writeAnalizPDVOutDelayByOneBranch - отсюда вызывается функция (вызов убран 27-10-16)
	 * @uses store_procedure::AnalizPDVOutDiffDateOneBranchLeftJoinERPN_tempTable - хранимая процедура для генерации данных
	 */
	public function getDelayToNotReestr(int $month, int $year, string $numBranch)
	{
		// так как в хранимой процедуре используются временные таблицы, для их обнуления
		// "передергнем соединение с базой для очистки временных таблиц
		$this->disconnect();
		$this->connect();
		$sql="CALL AnalizPDVOutDiffDateOneBranchLeftJoinERPN_tempTable(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
}