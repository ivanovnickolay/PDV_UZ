<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.08.2016
 * Time: 13:35
 */

namespace AnalizPdvBundle\Utilits\ValidEntity;

use AnalizPdvBundle\Entity\Erpn_out;
use AnalizPdvBundle\Entity\ErpnOut;
use AnalizPdvBundle\Utilits\LoadInvoice\LoadInvoiceOut\validInvoiceOut;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class validInvoiceOutTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }

    public function dataInvoice()
    {
        return[
            ["2//484","2015-12-01","Податкова накладна","358397326509",false],
            ["2//484","2016-12-01","Податкова накладна","358397326509",true],
            ["","2016-12-01","Податкова накладна","358397326509",false],

        ];
    }

    /**
     ** @dataProvider dataInvoice
     * @param $numInvoice
     * @param $dateInvoice
     * @param $typeInvoice
     * @param $INN
     * @param $result
     */
    public function testVaildInvoice($numInvoice,$dateInvoice,$typeInvoice,$INN,$result)
    {
        $obj=new ErpnOut();
            $obj->setNumInvoice($numInvoice);
                $obj->setTypeInvoiceFull($typeInvoice);
                    $data=new \DateTime($dateInvoice);
                        $obj->setDateCreateInvoice($data);
                            $obj->setInnClient($INN);
                         $validator=new validInvoiceOut($this->em);
                    $resTest=$validator->valid($obj);
                $txt="testVaildInvoice : $numInvoice/$dateInvoice/$typeInvoice/$INN result $result";
            $this->assertEquals($result,$resTest,$txt);
    }
}
