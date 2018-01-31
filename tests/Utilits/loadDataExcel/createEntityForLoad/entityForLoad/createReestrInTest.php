<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.01.2018
 * Time: 00:14
 */

namespace App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad;

use App\Entity\ReestrbranchIn;
use App\Utilits\loadDataExcel\createReaderFile\getReaderExcel;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование на формирования объекта Реестра полученных НН. (Кредит)
 * Тестирование проходит путем сравнивания прочитанных данных с эталоном
 * файл с данными для теста testDataReestrIn_TAB1.xls
 * Class createReestrInTest
 * @package App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad
 */
class createReestrInTest extends TestCase
{

    /**
     * @var BaseReader
     */
    private $reader;

    /**
     * перед каждым вызовом теста создаем ридер
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     */
    public function setUp(){
        $this->reader= new getReaderExcel('C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrIn_TAB1.xls');
        $this->reader->createFilter('EE');
        $this->reader->getReader();
    }


    /**
     * Тестируем правильность формирования объекта с данными. Для этого:
     *  - читаем тестовый файл с данными
     *  - создаем объект нужного типа
     *  - сверяем полученные данные объекта с эталоном
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     */
    public function testCreateReestr()
    {
        $this->assertInstanceOf(BaseReader::class,$this->reader->getReader());
        $en=new createReestrIn();
        $this->reader->loadDataFromFileWithFilter(2);
        $arr=$this->reader->getRowDataArray(2);
        //var_dump($arr);
        $entity=$en->createReestr($arr);
        // http://stackoverflow.com/questions/10420925/phpunit-forces-me-to-require-classes-before-asserting
        //-instance-of
        $this->assertInstanceOf(ReestrbranchIn::class,$entity);
        $this->assertEquals('7',$entity->getMonth());
        $this->assertEquals('2016',$entity->getYear());
        $this->assertEquals('578',$entity->getNumBranch());
        $this->assertEquals(
            new \DateTime('13.07.2016',new \DateTimeZone('Europe/Kiev')),
            $entity->getDateGetInvoice()
        );
        $this->assertEquals(
            new \DateTime('01.07.2016',new \DateTimeZone('Europe/Kiev')),
            $entity->getDateCreateInvoice()
        );
        $this->assertEquals('1',$entity->getNumInvoice());
        $this->assertEquals('ПНЕ',$entity->getTypeInvoiceFull());
        $this->assertEquals('Приватне підприємство "Укрмед  Вінниця"',$entity->getNameClient());
        $this->assertEquals('248989002286',$entity->getInnClient());
        $this->assertEquals('640.34',$entity->getZagSumm());
        $this->assertEquals('0',$entity->getBaza20());
        $this->assertEquals('0',$entity->getPdv20());
        $this->assertEquals('598.45',$entity->getBaza7());
        $this->assertEquals('41.89',$entity->getPdv7());
        $this->assertEquals('0',$entity->getBaza0());
        $this->assertEquals('0',$entity->getPdv0());
        $this->assertEquals('0',$entity->getBazaZvil());
        $this->assertEquals('0',$entity->getPdvZvil());
        $this->assertEquals('0',$entity->getBazaNeGos());
        $this->assertEquals('0',$entity->getPdvNeGos());
        $this->assertEquals('0',$entity->getBazaZaMezhi());
        $this->assertEquals('0',$entity->getPdvZaMezhi());
        $this->assertEquals(
            new \DateTime('0000-00-00'),
            $entity->getRkeDateCreateInvoice()
        );
        $this->assertEquals('',$entity->getRkeNumInvoice());
        $this->assertEquals('',$entity->getRkePidstava());
        $this->assertEquals('1/ПНЕ/01-07-2016/248989002286',$entity->getKeyField());
    }
}
