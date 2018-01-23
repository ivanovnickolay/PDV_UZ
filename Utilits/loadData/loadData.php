<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.09.2016
 * Time: 18:40
 */

namespace AnalizPdvBundle\Utilits\loadData;


use AnalizPdvBundle\Utilits\createEntitys;
use AnalizPdvBundle\Utilits\createReaderFile\getReaderExcel;
use AnalizPdvBundle\Utilits\ValidEntity\interfaceValidEntity;
use Doctrine\ORM\EntityManager;

/**
 * Класс служит для загрузки данных из файлов
 * Class loadData
 * @package AnalizPdvBundle\Utilits\loadData
 */
class loadData
{
	private $em;
	private $readerFile;
	private $entity;
	private $validator;


	/**
	 * loadData constructor.
	 * @param EntityManager $em
	 * @param getReaderExcel $reader
	 * @param $entity сущность которая будет заполнятся в процессе загрузки данных
	 */
	public function __construct (EntityManager $em,$fileName,string $columnLast, int $chunkSize=200)
	{
		$this->em=$em;
		$this->readerFile=new getReaderExcel($fileName);
		$this->readerFile->createFilter($columnLast,$chunkSize);
		$this->readerFile->getReader();
		return $this;
	}

	public function __destruct ()
	{
		unset($this->readerFile);
		unset($this->validator);
		unset($this->entity);


	}

	public function setValidator(interfaceValidEntity $validData)
	{
		$this->validator=$validData;

		return $this;
	}

	public function setEntity($entity)
	{
		$this->entity=$entity;

		return $this;
	}


	private function validParametrClass()
	{
		if (((is_null($this->validator))||(is_null($this->entity))||(is_null($this->readerFile)))) {
			return false;
		}else{
			return true;
		}
	}

	public function loadData()
	{
		if (($this->validParametrClass ())) {

			$maxRowToFile = $this->readerFile->getMaxRow ();
			for ($startRow = 2; $startRow <= $maxRowToFile; $startRow += $this->readerFile->getFilterChunkSize ())
			{
				$this->readerFile->loadFileWithFilter ($startRow);
				$maxRowReader = $this->readerFile->getFilterChunkSize () + $startRow;
				if ($maxRowReader > $maxRowToFile) {
					// специально что бы была прочитана последняя строка с данными
					$maxRowReader = $maxRowToFile + 1;
				}
				/** todo надо добавить проверку на файла на уникальность
				 *  месяца, года, номера филиала  и направления реестра
				 * для этого надо получить первую строку данных файла
				 * прочитать значение
				 **/
				for ($d = $startRow; $d < $maxRowReader; $d ++) {
					// решенние проблемы PDO::beginTransaction(): MySQL server has gone away
					$this->reconnect();
					$arr = $this->readerFile->getRowDataArray ($d);
					$e = $this->entity->createReestr ($arr);
					if ($this->validator->validEntity ($e)) {
						$this->em->persist ($e);
						$this->entity->unsetReestr ();
					} else {
						$this->em->persist ($e);
						$errorEntity = $this->validator->getErrorEntity ();
						$this->em->persist ($errorEntity);
						$this->entity->unsetReestr ();
					}
				}
				$this->em->flush ();
				$this->em->clear ();
				$this->readerFile->unset_loadFileWithFilter ();
				//http://ru.php.net/manual/ru/features.gc.collecting-cycles.php
				gc_enable();
				gc_collect_cycles();
			}
		}
	}
		// http://seyferseed.ru/ru/php/reshenie-problemy-doctrine-2-i-mysql-server-has-gone-away.html#sthash.vh49fkii.dpbs

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