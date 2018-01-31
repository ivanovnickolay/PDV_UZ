<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.01.2018
 * Time: 23:48
 */

namespace App\Utilits\loadDataExcel\handlerRow;
use App\Utilits\loadDataExcel\configLoader\configLoader_interface;
use App\Utilits\LoadInvoice\createEntity\createEntityInterface;
use Doctrine\ORM\EntityManager;


/**
 * Абстрактный класс описывает фукнкции которые вызывваются для обработки строки
 * с данными которая выгружается из файла.
 *
 * Основные задачи которые решает обработчик
 * - разделение ответственности за обработку данных, а именно один класс реализует чтение данных
 * а второй построчную обработку полученных данных
 * - расширение функционала программы при помощи разных обработчиков:
 *      -   проверки данных
 *      -   сохранение данных
 *
 * Interface handlerRowInterface
 * @package App\Utilits\loadDataExcel\handlerRow
 */
abstract class handlerRowAbstract
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    protected $entity;

    /**
     * @var configLoader_interface
     */
    protected $configLoader;
    /**
     * handlerRowAbstract constructor.
     * @param EntityManager $entityManager
     * @param configLoader_interface $configLoader
     */
    public function __construct(EntityManager $entityManager, configLoader_interface $configLoader)
    {
        $this->configLoader=$configLoader;
        $this->entityManager=$entityManager;
        /** @var createEntityInterface $this */
        $this->entity=$this->configLoader->getEntityForLoad();
    }

    /**
     * реализация по строчной обработки
     * @param array $data
     * @return mixed
     */
    public abstract function handlerRow(array $data);

    /**
     * Практическая реализация сохранения в базе обработанных строк
     * -    если в handlerRow происходит
     *      -    $this->entityManager->persist ($e);
     * -    то в saveProcessedRows
     *     -    $this->entityManager->flush ();
     *     -    $this->entityManager->clear ();
     * @return mixed
     */
    public abstract function saveProcessedRows();



}