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
 * Задача класса предоставить данные для заполннения анализа
 * реестра и ЕРПН в разрезе ИНН по кредиту
 * Class getDataFromAnalizPDVOutINN
 * @package AnalizPdvBundle\Model\getDataFromSQL
 */
class getDataFromAnalizPDVOutINN
{
	private $em;

	/**
	 * getDataFromAnalizPDVOutINN constructor.
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
	 * Анализ документов в разрезе ИНН которые есть и в ЕРПН и в Реестре по филиалу
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function getReestrEqualErpn(int $month, int $year, string $numBranch)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutInnerJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnOutInnerJoin(:m,:y,:nb)";//
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Анализ документов в разрезе ИНН которые есть в Реестре но нет в ЕРПН по филиалу
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function getReestrNoEqualErpn(int $month, int $year, string $numBranch)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutRightJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnOutRightJoin(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Анализ документов в разрезе ИНН которые есть и в ЕРПН но нет в Реестре по филиалу
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 */

	public function getErpnNoEqualReestr(int $month, int $year, string $numBranch)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutLeftJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnOutLeftJoin(:m,:y,:nb)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->bindValue("nb",$numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Анализ документов в разрезе ИНН которые есть и в ЕРПН и в Реестре по филиалу
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function getReestrEqualErpnAllUZ(int $month, int $year)
	{
		$this->disconnect();
		$this->connect();
		//$sql="CALL AnalizInnOutInnerJoinOneBranch_tempTable(:m,:y,:nb)";
		$sql="CALL getAnalizInnOutInnerJoinAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Анализ документов в разрезе ИНН которые есть в Реестре но нет в ЕРПН по филиалу
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
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
	 * Анализ документов в разрезе ИНН которые есть и в ЕРПН но нет в Реестре по филиалу
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @throws \Doctrine\DBAL\DBALException
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
}