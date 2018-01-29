<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.01.2018
 * Time: 00:38
 */

namespace App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad;

use App\Entity\ReestrbranchOut;
use App\Utilits\loadDataExcel\createReaderFile\getReaderExcel;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование на формирования объекта Реестра выданных НН. (Обязательства)
 * Тестирование проходит путем сравнивания прочитанных данных с эталоном
 * Файл с данными для теста testDataReestrOut_TAB2.xlsx
 * Class createReestrOutTest
 * @package App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad
 */
class createReestrOutTest extends TestCase
{
    /**
     * Тестируем правильность формирования объекта с данными. Для этого:
     *  - читаем тестовый файл с данными
     *  - создаем объект нужного типа
     *  - сверяем полученные данные объекта с эталоном
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function testCreateReestr()
    {
        $reader= new getReaderExcel('C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrOut_TAB2.xlsx');
        $reader->createFilter('DR');
        $reader->getReader();
        $this->assertInstanceOf(BaseReader::class,$reader->getReader());
        $en=new createReestrOut();
        $reader->loadDataFromFileWithFilter(2);
        $arr=$reader->getRowDataArray(2);
        //var_dump($arr);
        $entity=$en->createReestr($arr);
        $this->assertInstanceOf(ReestrbranchOut::class,$entity);
        $this->assertEquals('7',$entity->getMonth());
        $this->assertEquals('2016',$entity->getYear());
        $this->assertEquals('678',$entity->getNumBranch());
        $this->assertEquals(
            new \DateTime('30.06.2016',new \DateTimeZone('Europe/Kiev')),
            $entity->getDateCreateInvoice()
        );
        $this->assertEquals('122//275',$entity->getNumInvoice());
        $this->assertEquals('ПНЕ',$entity->getTypeInvoiceFull());
        $this->assertEquals('ПАТ \'\'УКРАЄНСЬКА ЗАЛIЗНИЦЯ\'\', Регiональна фiлiя \'\'ПРИДНIПРОВСЬКА ЗАЛIЗНИЦЯ\'\'  СП \'\'ДНIПРОПЕТРОВСЬКЕ МОТОРВАГОННЕ ДЕПО\'\'',$entity->getNameClient());
        $this->assertEquals('400000000000',$entity->getInnClient());
        $this->assertEquals('3544.98',$entity->getZagSumm());
        $this->assertEquals('2954.15',$entity->getBaza20());
        $this->assertEquals('590.83',$entity->getPdv20());
        $this->assertEquals('0',$entity->getBaza7());
        $this->assertEquals('0',$entity->getPdv7());
        $this->assertEquals('0',$entity->getBaza0());
        $this->assertEquals('0',$entity->getBazaZvil());
        $this->assertEquals('0',$entity->getBazaNeObj());
        $this->assertEquals('0',$entity->getBazaZaMezhiTovar());
        $this->assertEquals('0',$entity->getBazaZaMezhiPoslug());
        $this->assertEquals(
            new \DateTime('01.01.2000',new \DateTimeZone('Europe/Kiev')),
            $entity->getRkeDateCreateInvoice()
        );
        $this->assertEquals(0,$entity->getRkeNumInvoice());
        $this->assertEquals('Зайво виписана',$entity->getRkePidstava());
        $this->assertEquals('122//275/ПНЕ/30-06-2016/400000000000',$entity->getKeyField());
    }
}
