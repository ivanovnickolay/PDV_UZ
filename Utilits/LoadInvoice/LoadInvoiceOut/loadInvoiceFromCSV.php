<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12.03.2017
 * Time: 17:50
 */

namespace App\Utilits\LoadInvoice\LoadInvoiceOut;


use App\Utilits\LoadInvoice\createEntity\createEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Загрузка данных ЕРПН из файлов CSV
 * запускается через сервис "loadInvoiceFromCSV"
 *
 * Class loadInvoiceFromCSV
 * @package AnalizPdvBundle\Utilits\LoadInvoice\LoadInvoiceOut
 */
class loadInvoiceFromCSV implements ContainerAwareInterface
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
	 * @var createEntity
	 */
	private $createEntity;

	/**
	 * Ресурс содержащий файл с данными
	 * @var
	 */
	private $CSV;

	/**
	 * @var va
	 */
	private $validator;
	/**
	 * loadInvoiceFromCSV constructor.
	 * @param EntityManager $em
	 */
	public function __construct( ContainerInterface $container)
	{
		$this->setContainer($container);
		$this->validator=$this->container->get('validator');
	}

	/**
	 * Получение названия файла с данными для чтения
	 *  Название файла содержит полный путь к файлу
	 * @param string $fileName
	 */
	public function setFileData(string $fileName)
	{
		$this->fileName=$fileName;
	}

	/**
	 * получение класса который создает сущность на основании
	 * полученного массива значений
	 * @param createEntity $createEntity
	 */
	public function setEntity(createEntity $createEntity)
	{
		$this->createEntity=$createEntity;

	}

	/**
	 * @param $FileName
	 */
	private function createCSVReader ($FileName)
	{
		try {
			$this->CSV = fopen($FileName,'r');
			} catch (Exception $e) {
			echo 'Ошибка подключения к файлу' . $FileName . ': ' , $e->getMessage () , "\n";
		}
	}

	/**
	 *
	 * @link http://php.net/manual/ru/language.generators.overview.php#112985
	 * @link http://php.net/manual/ru/language.generators.comparison.php
	 * @return \Generator
	 */
	private function getLines() {
		if (!$fileHandle = fopen($this->fileName, 'r')) {
			return;
		}

		while (false !== $line = explode(";",iconv('Windows-1251',"UTF-8",fgets($fileHandle)))){
			yield $line;
		}

		fclose($fileHandle);
	}


	/**
	 * загрузка данных из файла
	 */
	public function loadDataFromFile()
	{
		/**
		 * Отключение логов и событий ускоряет процесс процентов на 80 в dev окружении.
		 * @link https://toster.ru/q/200991
		 */
		$this->em->getConnection()->getConfiguration()->setSQLLogger(null);
		//@link http://blog.cinu.pl/2013/08/doctrine2-php-inserting-large-amount-of.html
		/** @var integer $count счетчик валидных записей */
		$count = 0;
		/**
		 * @var integer $entityPerChunk
		 * количество валидных и подготовленных к сохранению записей
		 * при которых проводиться запись в базу
		 */
		$entityPerChunk = 1000;
		// если файл существует
		if (file_exists($this->fileName)){
			//$Lines = $this->getLines();
			foreach ($this->getLines() as $n => $line) {
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
				 * - если количество подготовленных записей равно $entityPerChunk то записываем записи а базу
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
							// увеличиваем счетчик валидных и подготовленных к сохранению записей
							$count++;
						}
							// если счетчик кратный $entityPerChunk то сохраням записи в базе данных
							if ($count%$entityPerChunk == 0) {
							$this->em->flush();
							$this->em->clear();
							} else{
								//todo если есть ошибка валидации то возмодно надо ее писать в лог ?
							}
							// обнуляем сущность
							unset($entity);
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

	private function isUniqueEntity(){

	}

}