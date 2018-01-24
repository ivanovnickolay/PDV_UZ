<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.03.2017
 * Time: 20:01
 */

namespace App\Utilits\LoadInvoice\configLoad;


use App\Utilits\LoadInvoice\createEntity\createEntity;
use App\Utilits\LoadInvoice\createEntity\createEntityInterface;
use App\Utilits\LoadInvoice\loadLinesData\loadLinesDataInterface;

/**
 * Абстрактный класс реализует методы которые хранят параметры
 * необходимые для успешной работы класса loadInvoiceFromХХХХ
 * Class configLoadAbstract
 * @package AnalizPdvBundle\Utilits\LoadInvoice\configLoad
 */
abstract class configLoadAbstract
{
	/**
	 * Хранит класс который создает сущность на основании
	 * строки данных
	 * @var createEntity
	 */
	protected $entity;

	/**
	 * имя файла для загрузки с полный путем к нему
	 * @var string
	 */
	protected $fileName;

	/**
	 * класс поддерживающий интерфейс loadLinesDataInterface
	 * который предоставит возможность построчного чтения файла с данными
	 * @var loadLinesDataInterface
	 */
	protected $getLines;

	/**
	 * количество валидных и подготовленных к сохранению записей
	 * при которых проводиться запись в базу
	 * @var integer
	 */
	protected $countRecordSave;

	/**
	 * configLoadAbstract constructor.
	 */
	public function __construct()
	{
		$this->fileName=null;
		$this->entity=null;
		$this->countRecordSave=null;
		$this->getLines=null;

	}

	/**
	 * Необходимо реализовать в классе вызов класса необходимой сущности
	 * и присваивание ее переменной  $entity
	 * метод должен быть private
	 *
	 * @return mixed
	 */
	abstract function setEntity();

	/**
	 * @return createEntity
	 */
	final public function getEntity():createEntityInterface{
		return $this->entity;
	}

	/**
	 * Присваивание переменной  $fileName
	 * метод должен быть private
	 *
	 * @return
	 */
	final function setFileName(string $fileName){
		$this->fileName=$fileName;
	}

	/**
	 * @return string
	 */
	final public function getFileName():string {
		return $this->fileName;
	}

	/**
	 * Необходимо реализовать в классе вызов класса необходимой сущности
	 * и присваивание ее переменной  $countRecordSave
	 * метод должен быть private
	 *
	 * @return mixed
	 */
	abstract function setCountRecordSave();

	/**
	 * количество валидных и подготовленных к сохранению записей
	 * при которых проводиться запись в базу
	 * @return integer
	 */
	final public function getCountRecordSave():int {
		return $this->countRecordSave;
	}

	/**
	 * Необходимо реализовать в классе вызов класса необходимой сущности
	 * c интерфейсом loadLinesDataInterface
	 * и присваивание ее переменной  getLines
	 * метод должен быть private
	 *
	 * @return mixed
	 */
	abstract function setGetLines();

	/**
	 * @return loadLinesDataInterface
	 */
	final public function getGetLines():loadLinesDataInterface{
		return $this->getLines;
	}


}