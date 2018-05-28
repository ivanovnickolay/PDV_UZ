<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.05.2018
 * Time: 15:46
 */

namespace App\Services;

use App\Entity\ReestrbranchOut;
use App\Entity\Repository\ReestrBranch_out;
use App\Entity\SprBranch;
use App\Utilits\loadDataExcel\downloadFromFile;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\workToFileSystem\workWithFiles;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class LoadReestrFromFileReestrOutValidDataTest extends TestCase
{
    private $objectManager;

    public function setUp(){
        $mapIn = array(
            array(
                ["month" => 7, "year" => 2016, "numBranch" => "578"],
                null,
                1
            ),
            array(
                array('month' => 7, 'year' => 2016, 'numBranch' => "579"),
                null,
                null
            ),
        );
        $repoReestrIn = $this->createMock(ReestrBranch_out::class);
        $repoReestrIn->expects($this->any())
            ->method("findOneBy")
            ->will($this->returnValueMap($mapIn));

        $mapSpr = array(
            array(
                array('numMainBranch' => '578'),
                null,
                10
            ),
            array(
                array('numMainBranch' => '579'),
                null,
                null
            )
        );
        $repoSpr = $this->createMock(\App\Entity\Repository\SprBranch::class);
        $repoSpr->expects($this->any())
            ->method("findOneBy")
            ->will($this->returnValueMap($mapSpr));
        $mapReestr = array(
            array(SprBranch::class, $repoSpr),
            array(ReestrbranchOut::class, $repoReestrIn)
        );
        $this->objectManager = $this->createMock(EntityManager::class);
        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($mapReestr));
            workWithFiles::moveFiles(
                __DIR__.'\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.xlsx',
                __DIR__.'\\dirForLoadFiles');
                if (file_exists(__DIR__.'\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.log')) {
                    unlink(__DIR__ . '\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.log');
                }

    }
    /**
     * @throws \ReflectionException
     * @throws errorLoadDataException
     */

    public function test_validDataToFile(){
        // делаем частный метод публичным в рамках теста
        $method = new \ReflectionMethod(LoadReestrFromFile::class,"validDataToFile");
        $method->setAccessible(true);
        // создаем  объект для тестирования
        $obj = new LoadReestrFromFile($this->objectManager);
        $obj->setDirForLoadFiles(__DIR__.'\\dirForLoadFiles');
        $obj->setDirForMoveFiles(__DIR__.'\\dirForMoveFiles');
        $obj->setDirForMoveFilesWithError(__DIR__.'\\dirForMoveFilesWithError');
        // создаем необходимые для тестирования "публичного" метода параметры
        $download = new downloadFromFile($this->objectManager);
        $download->setFileName(__DIR__.'\\dirForLoadFiles\\testDataReestrOut_TAB2.xlsx');
        // проверяем работу "публичного метода
        $resTest = $method->invoke($obj,$download,__DIR__.'\\dirForLoadFiles\\testDataReestrOut_TAB2.xlsx');
        $this->assertEquals(false,$resTest );
        // проверяем внутреннюю работу "публичного" метода
        $this->assertFileExists(
            __DIR__.'\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.xlsx',
            "Файл c данными не найден !");
        $this->assertFileExists(
            __DIR__.'\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.log',
            "Файл c логами не найден !");

        $arrayLog=$this->getFileLogToArray(__DIR__.'\\dirForMoveFilesWithError\\testDataReestrIn_TAB1.log');
        $this->assertLogContext($arrayLog);
    }
    //todo написать проверку содержимого лог файла
    private function getFileLogToArray(string $fileName):array {
        if(!file_exists($fileName)){
            throw new errorLoadDataException("Файл с логами не найден !");
        }
        return file($fileName);
    }

    /**
     * @param $arrayLog
     */
    private function assertLogContext($arrayLog): void
    {
        $this->assertEquals(
            'Строка № 2 содержит ошибки =>> Филиал уже подавал РПН за этот период ранее |',
            trim($arrayLog[0]),
            "Элемент arrayLog с индексом 0 содержи ошибки ");
        $this->assertEquals(
            'Строка № 3 содержит ошибки =>>  Номер филиала реестра не соответствует номеру указанному в первой строке файла! Дата получения документа null не может быть пустым  | Дата создания документа null не может быть пустым  |  |',
            trim($arrayLog[1]),
            "Элемент arrayLog с индексом 1 содержи ошибки ");
        $this->assertEquals(
            'Строка № 4 содержит ошибки =>>  "пп" - не верный номер документа  | ИНН документа не может быть пустым  |  |',
            trim($arrayLog[2]),
            "Элемент arrayLog с индексом 2 содержи ошибки ");
        $this->assertEquals(
            'Строка № 5 содержит ошибки =>>  Номер документа null не может быть пустым  | ИНН документа не может быть пустым  |  |',
            trim($arrayLog[3]),
            "Элемент arrayLog с индексом 3 содержи ошибки ");
        $this->assertEquals(
            'Строка № 6 содержит ошибки =>>  Поле zagSumm "лрлрло" содержит данные не того типа. | Поле baza7 "рлрл" содержит данные не того типа. | Поле pdv7 "_8888" содержит данные не того типа. |  |',
            trim($arrayLog[4]),
            "Элемент arrayLog с индексом 4 содержи ошибки ");
        $this->assertEquals(
            'Строка № 7 содержит ошибки =>>  Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[5]),
            "Элемент arrayLog с индексом 5 содержи ошибки ");
    }


}
