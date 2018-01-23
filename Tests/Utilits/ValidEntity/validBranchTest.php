<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.07.2016
 * Time: 1:29
 */

namespace AnalizPdvBundle\Utilits\ValidEntity;

use AnalizPDVBundle\Utilits\ValidEntity\validBranch;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AnalizPDVBundle\Entity\SprBranch;



class validBranchTest extends KernelTestCase
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

    public function DataFor_isValid()
    {
        return [
            ["018",false],
            ["999",true],
            ["+66",false],
            ["fdfssadf",false],
        ];


    }
    public function DataFor_isValid_Num()
    {
        return [
            ["018", true],
            ["999", true],
            ["+66", false],
            ["fdfssadf", false],
        ];
    }
    public function DataFor_isValid_Uniq()
    {
        return [
            ["018",false],
            ["999",true],
            ["+66",true],
            ["fdfssadf",true],
        ];
    }


    /**
     * Проверка на уникальность номера филиала в базе
     * @dataProvider DataFor_isValid_Uniq
     */
    public function testValidUniqBranch($num,$Result)
    {
        $vd = new validBranch($this->em);
         $txt='testValidUniqBranch. -> Num '.$num.' result plan '.$Result;
            $this->assertEquals($Result,$vd->validUniqBranch($num),$txt);

    }



   /**
    * Тест на соответствие номера филиаоа требованию
    * количество символов = 3 и только цифры
    * @dataProvider DataFor_isValid_Num
    */
 public function testValidNumBranch($Num, $Result)
    {
        $Valid=new validBranch($this->em);
            $txt='testValidNumBranch. -> Num '.$Num.' result plan '.$Result;
                $this->assertEquals($Result,$Valid->validNumBranch($Num),$txt);

    }

    /**
     * @dataProvider DataFor_isValid
     */

    public function test_isValid($Num,$Result)
    {
        $Branch=new SprBranch();
            $Branch->setNumBranch($Num);
                $Valid= new validBranch($this->em);
                $txt='test_isValid-> Num '.$Num.' result plan '.$Result;
                    $this->assertEquals($Result,$Valid->isValid($Branch),$txt);

    }
}
