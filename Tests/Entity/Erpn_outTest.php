<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.08.2016
 * Time: 2:44
 */

namespace tests\LoadFileBundle\Entity;


use LoadFileBundle\Entity\Erpn_out;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Erpn_outTest extends \KernelTestCase
{
    private $em;

    public function setUp()
    {
        parent::setUp();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testKey()
    {
        $obj=new Erpn_out();
            $obj->setNumInvoice("2//108");
                $obj->setTypeInvoiceFull("Податкова накладна");
                    $data=new \DateTime('12-05-2016');
                        $obj->setDateCreateInvoice($data);
                            $obj->setInnClient('123456757910');
                    $this->assertEquals("2//108/ПНЕ/12-05-2016/123456757910",$obj->getKeyField());
       unset($obj);
              $obj=new Erpn_out();
                    $obj->setNumInvoice("3//108");
                         $obj->setTypeInvoiceFull("Додаток 2");
                            $data=new \DateTime('12-05-2016');
                                $obj->setDateCreateInvoice($data);
                                     $obj->setInnClient('123456757910');
                                          $this->assertEquals("3//108/РКЕ/12-05-2016/123456757910",$obj->getKeyField());
        unset($obj);
            $obj=new Erpn_out();
                    $obj->setNumInvoice("3//108");
                            $obj->setTypeInvoiceFull("Додаток 4");
                                $data=new \DateTime('12-05-2016');
                                    $obj->setDateCreateInvoice($data);
                                        $obj->setInnClient('123456757910');
                                                $this->assertEquals("3//108/Прочее/12-05-2016/123456757910",$obj->getKeyField());
    }
}
