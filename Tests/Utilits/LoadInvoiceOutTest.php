<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.08.2016
 * Time: 0:09
 */

namespace LoadFileBundle\Tests\LoadFileBundle\Utilits;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use LoadFileBundle\Utilits\LoadInvoice\LoadInvoiceOut\LoadInvoiceOut;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class LoadInvoiceOutTest extends  KernelTestCase
{
    private  $obj;
    private  $em;
    private $path;
    private $path1;

    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->path = static::$kernel->getContainer()
            ->getParameter('file_dir_branch');
        $this->path1 = static::$kernel->getContainer()
        ->getParameter('file_dir_invoiceout');

    }

    public function dataFileName()
    {
        return
        [
            ["fskf.xls","Excel5"],
            ["fskf.xlsx","Excel2007"],
            ["fskf",null],
        ];
    }

    /**
     * @dataProvider  dataFileName
     * @param $FileName
     * @param $res
     */
    public function testGetFileType($FileName,$res)
    {
        $test=new LoadInvoiceOut($this->em);
        $txt='testGetFileType . -> Num '.$FileName.' result plan '.$res;
        $this->assertEquals($res,$test->getFileType($FileName),$txt);
    }

    public function testGetMaxRow()
    {
        $File_path="$this->path\SprBranch.xlsx";
        $test=new LoadInvoiceOut($this->em);
        $txt='testGetMaxRow';
        $this->assertEquals(860,$test->getMaxRow("$File_path"),$txt);

    }

    public function testLoad()
    {
        $out= new ConsoleOutput();;
        $test=new LoadInvoiceOut($this->em,$out);
            $fileName="12-2015_out.xlsx";
            $test->Load($fileName,$this->path1);
    }

}
