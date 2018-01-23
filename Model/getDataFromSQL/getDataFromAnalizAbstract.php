<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2016
 * Time: 21:31
 */

namespace App\Model\getDataFromSQL;


use App\Model\Exception\noCorrectDataException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * Абстрактный класс который наследуют все классы которые "тянут" данные
 * для формирования анализов
 * Class getDataFromAnalizAbstract
 * @package AnalizPdvBundle\Model\getDataFromSQL
 * @ORM\Entity
 * @ORM\Table(name="get_data_from_analiz_abstract")
 */
class getDataFromAnalizAbstract
{

	protected $em;

	/**
	 * getDataFromAnalizAbstract constructor.
	 * @param EntityManager $em
	 */
	public function __construct (EntityManager $em)
	{
		$this->em=$em;
	}

	final function disconnect()
	{
		$this->em->getConnection()->close();
	}

	final function connect()
	{
		$this->em->getConnection()->connect();
	}

	/**
	 * MySQL Server has gone away
	 */
	final function reconnect()
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
	 * @return bool
	 */
	final function isPing():bool {
		return $this->em->getConnection()->ping();
	}
}