<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.08.2016
 * Time: 0:09
 */

namespace LoadFileBundle\Tests\LoadFileBundle\Utilits;

use LoadFileBundle\Utilits\LoadInvoice\LoadInvoiceOut\LoadInvoiceOut_CSV;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use LoadFileBundle\Utilits\LoadInvoice\LoadInvoiceOut\LoadInvoiceOut;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class LoadInvoiceOutTest_CSV extends  KernelTestCase
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

   public function testLoad()
    {
        $out= new ConsoleOutput();;
        $test=new LoadInvoiceOut_CSV($this->em,$out);
            $fileName="12-2015_out1.csv";
            $test->Load($fileName,$this->path1);
    }

}
