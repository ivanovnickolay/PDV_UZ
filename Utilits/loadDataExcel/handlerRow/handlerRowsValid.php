<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.02.2018
 * Time: 00:49
 */

namespace App\Utilits\loadDataExcel\handlerRow;


/**
 * Класс реализует функциональность проверки данных полученных в строке
 * Class handlerRowsValid
 * @package App\Utilits\loadDataExcel\handlerRow
 */
class handlerRowsValid extends handlerRowAbstract
{

    /**
     * реализация по строчной обработки
     * @param array $data
     * @return mixed
     */
    public function handlerRow(array $data)
    {
        // TODO: Implement handlerRow() method.
    }

    /**
     * Практическая реализация сохранения в базе обработанных строк
     * -    если в handlerRow происходит
     *      -    $this->entityManager->persist ($e);
     * -    то в saveProcessedRows
     *     -    $this->entityManager->flush ();
     *     -    $this->entityManager->clear ();
     * @return mixed
     */
    public function saveProcessedRows()
    {
        // TODO: Implement saveProcessedRows() method.
    }
}