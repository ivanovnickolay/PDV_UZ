<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.09.2016
 * Time: 17:04
 */

namespace AnalizPdvBundle\Tests\Utilits\loadData;


use App\Utilits\workToFileSystem\workWithFiles;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\{
    TestCase
};

class workWithFilesTest extends TestCase
{

    /**
     * @var vfsStream
     */
    private $file_system;
    /**
     * формируем виртуальную файловую систему
     */
    public function setUp()
    {
        $this->file_system = vfsStream::setup('root');
        $testFile = vfsStream::newDirectory('testFile')->at($this->file_system);
    // Create Files
        vfsStream::newFile('19082016095050_40075815_J1201508_TAB1.xls')->at($testFile);
        vfsStream::newFile('19082016095050_40075815_J1201508_TAB1.xlsz')->at($testFile);
        vfsStream::newFile('19082016100630_40075815_J1201508_TAB1.xlsx')->at($testFile);
        vfsStream::newFile('19082016100630_40075815_J1201508_TAB1_.xlsx')->at($testFile);
        vfsStream::newFile('19082016100632_40075815_J1201508_TAB2.xlsx')->at($testFile);
        vfsStream::newFile('19082016095050_40075815_J1201508_TAB1.xlsz')->at($testFile);
        vfsStream::newFile('test.xlsx')->at($testFile);
        vfsStream::newFile('test.doc')->at($testFile);
    }

    /**
     * @throws \Exception
     */
    public function test_getArrayFilesFromDir_Exception(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Директория для чтения файлов не найдена!");
        $arrayFiles = workWithFiles::getArrayFilesFromDir($this->file_system->url().'/gg');
        $this->assertEmpty($arrayFiles);
    }

    /**
     * тест на контроль возврата из файлов в каталоге только тех файлов которые удовлетворяют условию
     * @throws \Exception
     */
    public function test_getArrayFilesFromDir(){
        $rootPath = vfsStream::url('root/testFile');
        $arrayFiles = workWithFiles::getArrayFilesFromDir($rootPath);
        //var_dump($arrayFiles);
        // количество записей в возвращенном массиве
        $this->assertEquals(count($arrayFiles),3);
        //должны быть в возвращенном массива
            $this->assertEquals(in_array('vfs://root/testFile\19082016095050_40075815_J1201508_TAB1.xls',$arrayFiles),1);
            $this->assertEquals(in_array('vfs://root/testFile\19082016100630_40075815_J1201508_TAB1.xlsx',$arrayFiles),1);
            $this->assertEquals(in_array('vfs://root/testFile\19082016100632_40075815_J1201508_TAB2.xlsx',$arrayFiles),1);
        // не должны быть в возвращенном массиве
            $this->assertNotEquals(in_array('vfs://root/testFile\19082016095050_40075815_J1201508_TAB1.xlsz',$arrayFiles),1);
            $this->assertNotEquals(in_array('vfs://root/testFile\19082016100630_40075815_J1201508_TAB1_.xlsx',$arrayFiles),1);
            $this->assertNotEquals(in_array('vfs://root/testFile\19082016095050_40075815_J1201508_TAB1.xlsz',$arrayFiles),1);
            $this->assertNotEquals(in_array('vfs://root/testFile\test.xlsx',$arrayFiles),1);
            $this->assertNotEquals(in_array('vfs://root/testFile\test.doc',$arrayFiles),1);
    }

    /**
     * тестирование переноса файлов из каталога в каталог
     */
    public function test_moveFile(){
        vfsStream::newDirectory('testFileMove')->at($this->file_system);
        $toPath = vfsStream::url('root/testFileMove');
        workWithFiles::moveFiles('vfs://root/testFile\test.doc',$toPath);
        $this->assertFileNotExists('vfs://root/testFile\test.doc');
        $this->assertFileExists('vfs://root/testFileMove\test.doc');
    }


}
