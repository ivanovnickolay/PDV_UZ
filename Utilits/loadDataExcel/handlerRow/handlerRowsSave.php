<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.02.2018
 * Time: 00:16
 */

namespace App\Utilits\loadDataExcel\handlerRow;


/**
 * Обработчик который реализует фукционал сохранения
 * данных полученных из файла в базу данных
 *
 * !!! валидация тут не проводится!!!
 *
 * Class handlerRowsSaveReestrIn
 * @package App\Utilits\loadDataExcel\handlerRow
 */

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class handlerRowsSaveReestrIn
 * @package App\Utilits\loadDataExcel\handlerRow
 */
class handlerRowsSave extends handlerRowAbstract
{

    /**
     * реализация по строчной обработки
     * -    формируем сущность на основании массива данных
     * -    сохраняем в кеше для последующего сохранения
     * -    для сохранения памяти обнуляем сущность
     * @param array $data
     *
     */
    public function handlerRow(array $data)
    {
        $this->entity->createReestr($data);
        try {
            $this->entityManager->persist($this->entity);
        } catch (ORMException $e) {
        }
        $this->entity->unsetReestr();
    }

    /**
     * Практическая реализация сохранения в базе обработанных строк
     * -    если в handlerRow происходит
     *      -    $this->entityManager->persist ($e);
     * -    то в saveProcessedRows
     *     -    $this->entityManager->flush ();
     *     -    $this->entityManager->clear ();
     *
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
    public function saveHandlingRows()
    {
        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
        $this->entityManager->clear();
    }

    /**
     * Практическая реализация возврата результата обработки всех строк файла
     * @return mixed
     */
    public function getResultHandlingAllRows()
    {
      return null;
    }
}