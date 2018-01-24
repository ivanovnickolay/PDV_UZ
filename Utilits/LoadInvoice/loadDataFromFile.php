<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23.03.2017
 * Time: 0:14
 */

namespace App\Utilits\LoadInvoice;
use App\Utilits\LoadInvoice\configLoad\configLoadAbstract;
use App\Utilits\LoadInvoice\createEntity\createEntityInterface;
use App\Utilits\LoadInvoice\Exception\noValidConfigLoadException;
use App\Utilits\LoadInvoice\loadLinesData\loadLinesDataInterface;


/**
 * Class loadDataFromFile
 * @package AnalizPdvBundle\Utilits\LoadInvoice
 */
class loadDataFromFile implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * Название файла с данными с полным путем к нему
	 * @var string
	 */
	private $fileName;

	/**
	 * класс который создает сущность на основании
	 * полученного массива значений
	 * @var createEntityInterface
	 */
	private $createEntity;

	/**
	 * Ресурс содержащий файл с данными
	 * @var loadLinesDataInterface
	 */
	private $getLines;

	/**
	 * @var сервис проверки сущностей
	 */
	private $validator;

	/**
	 * количество валидных и подготовленных к сохранению записей
	 * при которых проводиться запись в базу
	 * @var integer
	 */
	private $countRecordSave;


	/**
	 * loadDataFromFile constructor.
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->setContainer($container);
		$this->validator=$this->container->get('validator');
		$this->em=$this->container->get('doctrine')->getManager();
		$this->fileName =null;
		$this->createEntity=null;
		$this->getLines=null;
		$this->countRecordSave=null;
	}

	/**
	 * Получение класса с конфигурацией
	 * @param configLoadAbstract $config
	 * @throws noValidConfigLoadException  при ошибках валидации данных
	 */
	public function setConfig(configLoadAbstract $config){
		$errorValid=$this->validator->validate($config);
		if (count($errorValid)==0) {
			$this->fileName = $config->getFileName();
			$this->createEntity=$config->getEntity();
			$this->getLines=$config->getGetLines();
			$this->countRecordSave=$config->getCountRecordSave();
		} else{
			throw new noValidConfigLoadException();
		}
	}

	/**
	 * загрузка данных из файла
	 */
	public function load()
	{
		/**
		 * Отключение логов и событий ускоряет процесс процентов на 80 в dev окружении.
		 * @link https://toster.ru/q/200991
		 */

		$this->em->getConnection()->getConfiguration()->setSQLLogger(null);
		//@link http://blog.cinu.pl/2013/08/doctrine2-php-inserting-large-amount-of.html
		/** @var integer $count счетчик валидных записей */
		$count = 0;
		foreach ($this->getLines->getLines($this->fileName) as $n => $line) {
				// n=0 значит строка = заголовок
				/**
				 * если это первая строка данных
				 * то в ней надо реализовать проверку соответствией данных из файла
				 * каким то условиям.
				 * Например:
				 *  - если это файл РПН то проверить что бы не было уже загруженно ранее данных за этот
				 *    период по данному филиалу или что бы указанный номер филиала был разрешен для загрузки РПН
				 *  если файл не прошел проверку то надо выйти из загрузки с ошибкой
				 */
				if($n=1){

				}
				/**
				 * если строка не заголовок
				 * - получаем строку с данными как масив $line
				 * - getEntity($line) распарсивает строку заполняя сущность данными из массива
				 * - проводится валидация сущности (в основном на уникальность)
				 * - если запись валидка то передаем ее для записи иначе пробуем писать ошибку в лог ??
				 * - если количество подготовленных записей равно $this->countRecordSave то записываем записи а базу
				 */
				if($n!=0){
					// заполняем сущность данными из полученной строки
					$entity=$this->createEntity->getEntity($line);
					// проверяем сущность на уникальность
					$errorValid=$this->validator->validate($entity);
					// если количество ошибок валидации равно нулю
					if (count($errorValid)==0){
						// передаем сущность для сохранения
						$this->em->persist($entity);
						// обнуляем сущность
						$this->createEntity->unsetEntity();
						// увеличиваем счетчик валидных и подготовленных к сохранению записей
						$count++;
					}
					// если счетчик кратный $this->countRecordSave то сохраням записи в базе данных
					if ($count%$this->countRecordSave == 0) {
						$this->em->flush();
						$this->em->clear();
					} else{
						//todo если есть ошибка валидации то возможно надо ее писать в лог ?
					}
					// обнуляем сущность если она не валидна
					$this->createEntity->unsetEntity();
					// обнуляем массив с ошибками
					unset($errorValid);
				}
			}
			// если цик прервался на записи не кратной $entityPerChunk
			// то сохраним все валидные и подготовленные для записи данные
			$this->em->flush();
			$this->em->clear();
		}



}