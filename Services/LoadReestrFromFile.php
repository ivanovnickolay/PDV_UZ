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
 *  todo написать тест на чтение свыше 6 файлов с данными за раз. Причем файлы должны быть как верные так и нет !!!
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
     * @var integer количество файлов, которые загружаются за один вызов LoadReestrFromFile::execute
     */
    private $numberOfFilesAtTime;
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
                        $this->numberOfFilesAtTime = 6;
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
     * проверим переданы ли все необходимые пути к директориям
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
     *  -   возьмем из этого массива первые numberOfFilesAtTime файлов
     *  -   для каждого файла:
     *      - создаем объект класса downloadFromFile
     *      - проводим проверку содержимого файла
     *      - если validDataToFile вернул false то файл содержит ошибки проверки и надо читать следующий файл с данными
     *          -   очищаем все используемые в классе объекты перед загрузкой нового файла
     *          -   вызываем continue для нового начала цикла
     *      - если validDataToFile вернул true то файл не содержит ошибок
     *          -   вызываем downloadDataAndSave для сохранения данных в базу
     *          -   переносим файл в директорию для успешно загруженных файлов
     *          -   очищаем все используемые в классе объекты перед загрузкой нового файла
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
        //режем список файлов на куски по 6 штук
        //todo внести возможность производить загрузку файлов с переменным количестом файлов за один раз. Сейчас 6 файлов
        $arr_slice = array_slice($arrayFiles,
            0,
                $this->numberOfFilesAtTime);
        foreach ($arr_slice as $fileName) {
            try {
                // создаем класс для загрузки данных
                $downloadData = new downloadFromFile($this->entityManager);
                //передаем название файлов в класс
                $downloadData->setFileName($fileName);
                if (!$this->validDataToFile($downloadData, $fileName)){
                    $downloadData->unSetAllObjects();
                    unset($downloadData);
                    //http://ru.php.net/manual/ru/features.gc.collecting-cycles.php
                    gc_collect_cycles();
                    continue;
                };

                // если массив пустой
                        // загружаем данные в базу
                        $downloadData->downloadDataAndSave();
                            // переносим файл в директорию для успешно загруженных файлов
                            workWithFiles::moveFiles($fileName, $this->dirForMoveFiles);


            } catch (errorLoadDataException $exception) {
                echo $exception->getMessage();
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
            // очищаем все используеміе в классе объекты перед загрузкой нового файла
            $downloadData->unSetAllObjects();
            unset($downloadData);
            //http://ru.php.net/manual/ru/features.gc.collecting-cycles.php
            gc_collect_cycles();
        }
    }

    /**
     * Проводим проверку данных в файле
     *  - запускаем downloadDataAndValid для получения массива с ошибками валидации
     *      -   если массив не пустой
     *          - переносим файл с данным в директорию для ошибок
     *          - создаем в директории для ошибок лог с содержимым массива ошибок валидации
     *          - возвращаем false - файл не прошел валидацию
     *      -   если массив пустой - возвращаем true - файл прошел валидацию
     * @param $downloadData
     * @param $fileName
     * @return bool
     *      - false - файл не прошел валидацию
     *      - true - файл прошел валидацию
     * @throws \Exception
     */
    private function validDataToFile(downloadFromFile $downloadData, string $fileName): bool
    {
    // проводим валидациию данных
        $arrayErrorValidation = $downloadData->downloadDataAndValid();
        // проверяем массив с ошибками
        if (!empty($arrayErrorValidation)) {
            // если массив не пустой
            // переносим файл с данными в директорию для ошибочных файлов
            workWithFiles::moveFiles($fileName, $this->dirForMoveFilesWithError);
            //  формируем файл с ошибками валидации
            workWithFiles::createFileErrorValidation($this->dirForMoveFilesWithError, $fileName, $arrayErrorValidation);
            return false;
        }
        return true;
    }
}