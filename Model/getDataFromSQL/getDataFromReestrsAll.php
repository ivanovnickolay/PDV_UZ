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
 * Задача класса предоставить данные для заполннения анализа реестров по всему УЗ за период
 * заполнение идет в как "слитие" всех расхождений филиалов в один файл по всему УЗ
 *
 * @package AnalizPdvBundle\Model\getDataFromSQL
 */
class getDataFromReestrsAll
{
	private $em;

	/**
	 * getDataFromReestrsAll constructor.
	 * @param EntityManager $em
	 */
	public function __construct (EntityManager $em)
	{
		$this->em=$em;
	}

	/**
	 * Возвращает массив информации с реестра полученных НН которые
	 * совпали с ЕРПН по параметрам по всему УЗ
	 * @link  http://yapro.ru/web-master/mysql/doctrine2-nativnie-zaprosi.html
	 * @param $month string
	 * @param $year string
	 * @return array arrayResult
	 * @see writeAnalizReestr::writeAnalizPDVByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::getReestrInEqualErpnAllUZ - хранимая процедура для генерации данных
	 */
	public function getReestrInEqualErpn($month, $year)
{
	$this->reconnect();
	$sql="call getReestrInEqualErpnAllUZ(:m,:y)";
	$smtp=$this->em->getConnection()->prepare($sql);
	$smtp->bindValue("m",$month);
	$smtp->bindValue("y",$year);
	$smtp->execute();
	$arrayResult=$smtp->fetchAll();
	return $arrayResult;
}
	/**
	 * Возвращает массив информации с реестра полученных НН которые не
	 * совпали с ЕРПН по параметрам по всему УЗ
	 * @link  http://yapro.ru/web-master/mysql/doctrine2-nativnie-zaprosi.html
	 * @param $month string
	 * @param $year string
	 * @return array arrayResult
	 * @see writeAnalizReestr::writeAnalizPDVByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::getReestrInNotEqualErpnAllUZ - хранимая процедура для генерации данных
	 */
	public function getReestrInNotEqualErpn($month, $year)
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="call getReestrInNotEqualErpnAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Возвращает массив информации с реестра выданных НН которые совпали с ЕРПН по параметрам по всему УЗ
	 * @param $month
	 * @param $year
	 * @return array
	 * @see writeAnalizReestr::writeAnalizPDVByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::getReestrOutEqualErpnAllUZ - хранимая процедура для генерации данных
	 */
	public function getReestrOutEqualErpn($month, $year)
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="call getReestrOutEqualErpnAllUZ(:m,:y)";
		$smtp=$this->em->getConnection()->prepare($sql);
		$smtp->bindValue("m",$month);
		$smtp->bindValue("y",$year);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}
	/**
	 * Возвращает массив информации с реестра полученных НН которые
	 * не совпали с ЕРПН по параметрам по всему УЗ
	 * @link  http://yapro.ru/web-master/mysql/doctrine2-nativnie-zaprosi.html
	 * @param $month string
	 * @param $year string
	 * @return array arrayResult
	 * @see writeAnalizReestr::writeAnalizPDVByAllUZ - отсюда вызывается функция
	 * @uses store_procedure::getReestrOutNotEqualErpnAllUZ - хранимая процедура для генерации данных
	 */
	public function getReestrOutNotEqualErpn($month, $year)
	{
		//$smtp=$this->em->getConnection();
		$this->reconnect();
		$sql="call getReestrOutNotEqualErpnAllUZ(:m,:y)";
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