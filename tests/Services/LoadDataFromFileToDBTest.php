<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.05.2018
 * Time: 21:39
 */

namespace App\Services;

use App\Entity\ReestrbranchIn;
use App\Entity\Repository\SprBranchRepository;
use App\Entity\SprBranch;
use App\Utilits\workToFileSystem\workWithFiles;
use Doctrine\ORM\EntityManager;
use LoadDataFromFileToDB_test;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoadDataFromFileToDBTest extends KernelTestCase
{

    /**
     * @var EntityManager
     */
    private $em;

    public function setUp(){

        // получаем Entity Manager
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $rr=$this->em->getConnection()->getDatabase();
        if ("AnalizPDV_test"!=$rr){
            throw new \Exception();
        }


    }

    public function test_EntityManager(){
        $this->assertInstanceOf(EntityManager::class,$this->em);
    }

    public function test_loadCorrectReestrIn(){
        // подготовим файлы для загрузки
        workWithFiles::moveFiles(
            __DIR__.'\\fixturesFiles\\testDataСorrectReestrIn_TAB1.xls',
            __DIR__.'\\dirForLoadFiles');
        if (file_exists(__DIR__.'\\dirForMoveFilesWithError\\testDataСorrectReestrIn_TAB1.log')) {
            unlink(__DIR__ . '\\dirForMoveFilesWithError\\testDataСorrectReestrIn_TAB1.log');
        }
        $obj=new LoadReestrFromFile($this->em);
            $obj->setDirForLoadFiles(__DIR__.'\\dirForLoadFiles');
                $obj->setDirForMoveFiles(__DIR__.'\\dirForMoveFiles');
                    $obj->setDirForMoveFilesWithError(__DIR__.'\\dirForMoveFilesWithError');
                        $obj->execute();

                    $this->assertFileNotExists(__DIR__.'\\dirForMoveFilesWithError\testDataСorrectReestrIn_TAB1.xls');
                    $this->assertFileExists(__DIR__.'\\dirForMoveFiles\testDataСorrectReestrIn_TAB1.xls');
                // Проведем проверку что загрузилось
                    // контроль количества записей
                    $SQLCountRec="SELECT COUNT(id) FROM reestrbranch_in";
                        $smtpCountRec = $this->em->getConnection()->prepare($SQLCountRec);
                            $smtpCountRec->execute();
                                $arrayResult=$smtpCountRec->fetchAll();
                                $this->assertEquals(7,$arrayResult[0]['COUNT(id)']);
                    // контроль общей загруженной суммы всех документов
                    $SQLSumZagSumm="SELECT sum(zag_summ) FROM reestrbranch_in";
                        $smtpSumZagSumm = $this->em->getConnection()->prepare($SQLSumZagSumm);
                            $smtpSumZagSumm->execute();
                                $arrayResult=$smtpSumZagSumm->fetchAll();
                                $this->assertEquals("122519.56",$arrayResult[0]['sum(zag_summ)']);
                // очистим таблицу с данными
                    $SQLDeleteRec="DELETE  FROM reestrbranch_in";
                        $smtpDeleteRec = $this->em->getConnection()->prepare($SQLDeleteRec);
                            $smtpDeleteRec->execute();
                // вернем файлы обратно
                    workWithFiles::moveFiles(
                        __DIR__.'\\dirForMoveFiles\\testDataСorrectReestrIn_TAB1.xls',
                        __DIR__.'\\fixturesFiles');
                    if (file_exists(__DIR__.'\\dirForMoveFilesWithError\\testDataСorrectReestrIn_TAB1.log')) {
                        unlink(__DIR__ . '\\dirForMoveFilesWithError\\testDataСorrectReestrIn_TAB1.log');
                    }
    }

    public function test_getRepository(){
        $repo = $this->em->getRepository(SprBranch::class);
        $this->assertInstanceOf(SprBranchRepository::class,$repo);
    }


}
