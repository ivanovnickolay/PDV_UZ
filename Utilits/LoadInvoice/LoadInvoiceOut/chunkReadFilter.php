<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 03.08.2016
 * Time: 23:33
 *  https://habrahabr.ru/post/148203/
 * Собственно класс chunkReadFilter — это то, что нам нужно.
 * Устанавливаем его в качестве фильтра для чтения файла, и файл будет загружаться не целиком,
 * а лишь определенное количество строк.

 */

namespace LoadFileBundle\Utilits\LoadInvoice\LoadInvoiceOut;


class chunkReadFilter implements \PHPExcel_Reader_IReadFilter
{
    private $_startRow = 0;
    private $_endRow = 0;
    private $_column=array();

    public function setRows($startRow,$column, $chunkSize) {
        $this->_startRow    = $startRow;
        $this->_endRow      = $startRow + $chunkSize;
        $this->_column      = $column;
    }

    public function readCell($column, $row, $worksheetName = '') {
        if (($row >= $this->_startRow && $row < $this->_endRow)) {
            if (in_array($column,$this->_column)) {
                return true;
            }
        }
        return false;
    }
}