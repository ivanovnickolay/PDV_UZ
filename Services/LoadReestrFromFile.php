<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.03.2018
 * Time: 00:46
 */

namespace App\Services;


use App\Utilits\loadDataExcel\downloadFromFile;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\workToFileSystem\workWithFiles;
use Doctrine\ORM\EntityManager;

/**
 * класс предназначен для организации загрузки информации из файлов Excel
 *
 * Class LoadReestrFromFile
 * @package App\Services

 *
 */
class LoadReestrFromFile
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /*
     * @var string директория из которой загружаются файлы
     */
    private $dirForLoadFiles;

    /*
     * @var string директория в которую переносятся загруженные без проблем файлы
     */
    private $dirForMoveFiles;

    /*
     * @var string директория в которую переносятся файлы с ошибками валидации
     */
    private $dirForMoveFilesWithError;

    /**
     * LoadReestrFromFile constructor.- инициализирует все переменные и получает класс $entityManager
     * @param EntityManager $entityManager
     *
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
            $this->dirForMoveFiles="";
                $this->dirForLoadFiles="";
                    $this->dirForMoveFilesWithError="";
    }

    /*
     * получение директории из которой загружаются файлы
     * @param string $dirForLoadFiles
     * @throws errorLoadDataException
     */
    public function setDirForLoadFiles(string $dirForLoadFiles){
        if (!is_dir($dirForLoadFiles)){
            throw new errorLoadDataException("Директории, из которой надо загрузить файлы, не существует");
        }
        $this->dirForLoadFiles=$dirForLoadFiles;
    }
    /*
     * получение директории в которую переносятся загруженные без проблем файлы
     * @param string $dirForMoveFiles
     * @throws errorLoadDataException
     */
    public function setDirForMoveFiles(string $dirForMoveFiles){
        if (!is_dir($dirForMoveFiles)){
            throw new errorLoadDataException("Директории, в которую надо переместить загруженные без проблем файлы, не существует");
        }
        $this->dirForMoveFiles=$dirForMoveFiles;
    }
    /*
     * получение директории в которую переносятся файлы с ошибками валидации
     * @param string $dirForMoveFilesWithError
     * @throws errorLoadDataException
     */
    public function setDirForMoveFilesWithError(string $dirForMoveFilesWithError){
        if (!is_dir($dirForMoveFilesWithError)){
            throw new errorLoadDataException("Директории, в которую надо переместить файлы с ошибками валидации, не существует");
        }
        $this->dirForMoveFilesWithError=$dirForMoveFilesWithError;
    }

    /**
     * проверим переданы ли все не обходимые пути к директориям
     * @throws errorLoadDataException если какой то путь отсутствует
     */
    private function dirExist(){
        if(
            (empty($this->dirForLoadFiles)) or
                (empty($this->dirForMoveFiles)) or
            (empty($this->dirForMoveFilesWithError))
        ){
            throw new errorLoadDataException("Пути к необходимым директориям не заданы");
        }
    }

    /**
     * Реализация загрузки по алоритму
     *  -   проверим переданы ли все пути к директориям
     *  -   получим массив с валидными файлами из папки
     *  -   возьмем из этого массива первые 6 файлов
     *  -
     *
     */
    public function execute()
    {
        gc_enable();
        $this->dirExist();
        $arrayFiles=array();
        try {
            $arrayFiles = workWithFiles::getArrayFilesFromDir($this->dirForLoadFiles);
        } catch (\Exception $exception) {

        }
        // создаем класс для загрузки данных
        $downloadData = new downloadFromFile($this->entityManager);
        //режем список файлов на куски по 6 штук
        $arr_slice = array_slice($arrayFiles, 0, 6);
        foreach ($arr_slice as $fileName) {
            try {
                //передаем название файлов в класс
                $downloadData->setFileName($fileName);
                    // проводим валидациию данных
                    $arrayErrorValidation = $downloadData->downloadDataAndValid();
                    // проверяем массив с ошибками
                    if (!empty($arrayErrorValidation)) {
                        // если массив не пустой
                        // переносим файл с данными в директорию для ошибочных файлов
                        workWithFiles::moveFiles($fileName, $this->dirForMoveFilesWithError);
                            //  формируем файл с ошибками валидации
                            workWithFiles::createFileErrorValidation($this->dirForMoveFilesWithError, $fileName, $arrayErrorValidation);
                    } else {
                        // если массив пустой
                        // загружаем данные в базу
                        $downloadData->downloadDataAndSave();
                            // переносим файл в директорию для успешно загруженных файлов
                            workWithFiles::moveFiles($fileName, $this->dirForMoveFiles);
                    }

            } catch (errorLoadDataException $exception) {

            } catch (\Exception $exception) {

            }
            // очищаем все используеміе в классе объекты перед загрузкой нового файла
            $downloadData->unSetAllObjects();
            //http://ru.php.net/manual/ru/features.gc.collecting-cycles.php
            gc_collect_cycles();
        }
    }
}