<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.09.2016
 * Time: 17:14
 */

namespace App\Model\getDataFromSQL;


use Doctrine\ORM\EntityManager;

/**
 *
 * Задача класса предоставить данные для заполннения анализа реестров и ЕРПН по одному филиалу
 * @package AnalizPdvBundle\Model\getDataFromSQL
 */
class getDataFromReestrsByOne
{
	private $em;

	/**
	 * getDataFromReestrsByOne constructor.
	 * @param EntityManager $em
	 */
	public function __construct (EntityManager $em)
	{
		$this->em=$em;
	}

	/**
	 * Возвращает массив информации с реестра полученных НН которые совпали с ЕРПН
	 * по параметрам по одному филиалу
	 * @link  http://yapro.ru/web-master/mysql/doctrine2-nativnie-zaprosi.html
	 * @param $month string
	 * @param $year string
	 * @param $numBranch string
	 * @return array arrayResult
	 * @see writeAnalizReestr::writeAnalizPDVByOneBranch - отсюда вызывается функция
	 * @see writeAnalizPDVToFile::writeAnalizPDVByOneBranch - отсюда вызывается функция
	 * @uses store_procedure::getReestrInEqualErpn - хранимая процедура для генерации данных
	 */
	public function getReestrInEqualErpn($month, $year, $numBranch)
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="CALL getReestrInEqualErpn(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Возвращает массив информации с реестра полученных НН которые НЕ совпали с ЕРПН
	 * по параметрам по одному филиалу
	 * @link  http://yapro.ru/web-master/mysql/doctrine2-nativnie-zaprosi.html
	 * @param $month string
	 * @param $year string
	 * @param $numBranch string
	 * @return array arrayResult
	 * @see writeAnalizReestr::writeAnalizPDVByOneBranch - отсюда вызывается функция
	 * @see writeAnalizPDVToFile::writeAnalizPDVByOneBranch - отсюда вызывается функция
	 * @uses store_procedure::getReestrInNotEqualErpn - хранимая процедура для генерации данных
	 */
	public function getReestrInNotEqualErpn($month, $year, $numBranch)
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="call getReestrInNotEqualErpn(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Возвращает массив информации с реестра выданных НН которые совпали с ЕРПН по параметрам
	 * @link  http://yapro.ru/web-master/mysql/doctrine2-nativnie-zaprosi.html
	 * @param $month string
	 * @param $year string
	 * @param $numBranch string
	 * @return array arrayResult
	 * @see writeAnalizReestr::writeAnalizPDVByOneBranch - отсюда вызывается функция
	 * @see writeAnalizPDVToFile::writeAnalizPDVByOneBranch - отсюда вызывается функция
	 * @uses store_procedure::getReestrOutEqualErpn - хранимая процедура для генерации данных
	 */
	public function getReestrOutEqualErpn($month, $year, $numBranch)
	{
		$this->reconnect();
		$sql="call getReestrOutEqualErpn(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Возвращает массив информации с реестра выданных НН которые НЕ совпали с ЕРПН по параметрам
	 * @link  http://yapro.ru/web-master/mysql/doctrine2-nativnie-zaprosi.html
	 * @param $month string
	 * @param $year string
	 * @param $numBranch string
	 * @return array arrayResult
	 * @see writeAnalizReestr::writeAnalizPDVByOneBranch - отсюда вызывается функция
	 * @see writeAnalizPDVToFile::writeAnalizPDVByOneBranch - отсюда вызывается функция
	 * @uses store_procedure::getReestrOutNotEqualErpn - хранимая процедура для генерации данных
	 */
	public function getReestrOutNotEqualErpn($month, $year, $numBranch)
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="call getReestrOutNotEqualErpn(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * получает список уникальных филиалов из реестра полученных НН за период
	 * @param $month
	 * @param $year
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @see writeAnalizReestr::writeAnalizPDVByAllBranch - отсюда вызывается функция
	 */
	public function getAllBranchToPeriod($month, $year)
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="SELECT DISTINCT rbi.num_branch FROM ReestrBranch_in rbi
			WHERE rbi.month =:m AND rbi.year=:y";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * получить список всех главных филиалов ПАТ
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 * @see writeAnalizPDVToFile::writeOutDelayByAllBranch - отсюда вызывается функция
	 */
	public function getAllBranch()
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="SELECT DISTINCT num_main_branch FROM `SprBranch`";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * получает список уникальных  филиалов из реестра выдынных НН за период
	 * @param $month
	 * @param $year
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByAllBranch - отсюда вызывается функция
	 */
	public function getAllBranchToPeriodOut($month, $year)
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="SELECT DISTINCT rbi.num_branch FROM ReestrBranch_Out rbi
			WHERE rbi.month =:m AND rbi.year=:y";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
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
}