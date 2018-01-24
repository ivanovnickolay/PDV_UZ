<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.09.2016
 * Time: 22:30
 */

namespace App\Utilits\loadReestrBranch;


use App\Utilits\createWriteFile\renameWorksheet;
use App\Utilits\loadData\factoryLoadData;
use App\Utilits\loadData\workWithFiles;
use Doctrine\ORM\EntityManager;

/**
 * класс загружает даннные из файла реестра
 * Class loadReestrBranch
 * @package AnalizPdvBundle\Utilits\loadReestrBranch
 */
class loadReestrBranch
{
	private $em;
	public function __construct ($em)
	{
		$this->em=$em;
	}

	public function loadFile($fileName, $type)
	{
		$factoryLoad=new factoryLoadData($this->em);
		$factoryLoad->loadDataFromFile($fileName,$type);
		unset($factoryLoad);

	}

	/**
	 * на основании данных сформированых getFileFromDir->getFiles() загружает данные их файла
	 * @param EntityManager $em
	 * @param $fileName string название файла с путем к нему
	 * @param $type string "RestrIn"|"RestrOut"
	 */
	public static function load(EntityManager $em, $fileName, $type)
	{
		//заменим название листа в книге
		//renameWorksheet::renameWorksheet($fileName);
		// получим класс загрузчика из фабрики
		$factoryLoad=new factoryLoadData($em);
		// загрузим файл
		$factoryLoad->loadDataFromFile($fileName,$type);
		// очистим фабрику
		unset($factoryLoad);

	}
}