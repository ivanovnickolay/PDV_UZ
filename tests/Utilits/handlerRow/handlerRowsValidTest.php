<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 07.02.2018
 * Time: 22:11
 */

namespace App\Utilits\loadDataExcel\handlerRow;

use App\Utilits\loadDataExcel\configLoader\configLoadReestrIn;
use App\Utilits\loadDataExcel\createEntityForLoad\interfaceEntityForLoad\createEntityForLoad_interface;
use App\Utilits\loadDataExcel\createReaderFile\getReaderExcel;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class handlerRowsValidTest extends KernelTestCase
{
    /**
     * @var createEntityForLoad_interface
     */
    private $entity;
    /**
     * @var getReaderExcel
     */
    private $reader;
    /**
     * @var handlerRowsValid
     */
    private $handlerRow;

    /**
     * перед каждым вызовом теста создаем ридер
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     */
    public function setUp(){
        $kernel = self::bootKernel();

        $em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $configLoader = new configLoadReestrIn();
        $this->reader= new getReaderExcel('C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrIn_TAB1.xls');
        $this->reader->createFilter($configLoader->getLastColumn());
        $this->reader->getReader();
       // $this->entity = $configLoader->getEntityForLoad();
        $this->handlerRow = new handlerRowsValid($em,$configLoader);




    }

    public function test_valid(){
        $this->reader->loadDataFromFileWithFilter(2);
        $arr=$this->reader->getRowDataArray(2);
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->saveHandlingRows();

    }


}
