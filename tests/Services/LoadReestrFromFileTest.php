<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11.03.2018
 * Time: 01:11
 */

namespace App\Services;

use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
//todo написать тест на пробное чтение данных и контроль их после записи в базе

class LoadReestrFromFileTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function setUp(){

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function test_createObject(){
        $obj = new LoadReestrFromFile($this->entityManager);
        $this->assertInstanceOf(LoadReestrFromFile::class,$obj);
    }
    public function test_Exception_setDirForLoadFiles(){
        $this->expectException(errorLoadDataException::class);
        $this->expectExceptionMessage('Директории, из которой надо загрузить файлы, не существует');
        $obj = new LoadReestrFromFile($this->entityManager);
        $obj->setDirForLoadFiles("");
    }
    public function test_Exception_setDirForMoveFiles(){
        $this->expectException(errorLoadDataException::class);
        $this->expectExceptionMessage('Директории, в которую надо переместить загруженные без проблем файлы, не существует');
        $obj = new LoadReestrFromFile($this->entityManager);
        $obj->setDirForMoveFiles("");
    }
    public function test_Exception_setDirForMoveFilesWithError(){
        $this->expectException(errorLoadDataException::class);
        $this->expectExceptionMessage('Директории, в которую надо переместить файлы с ошибками валидации, не существует');
        $obj = new LoadReestrFromFile($this->entityManager);
        $obj->setDirForMoveFilesWithError("");
    }
    public function test_Exception_NullDir(){
        $this->expectException(errorLoadDataException::class);
        $this->expectExceptionMessage('Пути к необходимым директориям не заданы');
        $obj = new LoadReestrFromFile($this->entityManager);
        $obj->execute();
    }
}
