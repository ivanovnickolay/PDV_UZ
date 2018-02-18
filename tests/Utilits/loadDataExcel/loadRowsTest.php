<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.02.2018
 * Time: 15:53
 */

namespace App\Utilits\loadDataExcel\loadData;

use App\Utilits\loadDataExcel\configLoader\configLoaderFactory;
use App\Utilits\loadDataExcel\handlerRow\handlerRowsValid;
use PHPUnit\Framework\TestCase;

class loadRowsTest extends TestCase
{

    /**
     * Тестирование создания класса чтения кредита
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     */
    public function test__constructIN()
    {
        $fileName = 'C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrIn_TAB1.xls';
        $load  = new loadRows( $fileName,
                               configLoaderFactory::getConfigLoad($fileName)
        );
        $this->assertInstanceOf(loadRows::class,$load);
    }

    /**
     * Тестирование создания класса чтения обязательств
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     */
    public function test__constructOut()
    {
        $fileName = 'C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrOut_TAB2.xlsx';
        $load  = new loadRows( $fileName,
            configLoaderFactory::getConfigLoad($fileName)
        );
        $this->assertInstanceOf(loadRows::class,$load);
    }

}
