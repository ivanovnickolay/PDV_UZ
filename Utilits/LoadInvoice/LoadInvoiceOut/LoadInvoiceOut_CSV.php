<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02.08.2016
 * Time: 23:14
 */

namespace App\Utilits\LoadInvoice\LoadInvoiceOut;


use DateTime;
use Doctrine\ORM\EntityManager;
use App\Entity\Erpn_out;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use App\Utilits\LoadInvoice\LoadInvoiceOut\validInvoiceOut;
class LoadInvoiceOut_CSV
{
    private $entityManager;
    private $phpCSV;
    private $rowData;
    private $objPHPExcel;
    private $chunkSize=2000;
    private $chunkFilter;
    private $out;
    private $flushCount=2000;
    private $lineCSV;
    private $logger;


    public function __construct(EntityManager $entityManager, OutputInterface $out)
    {
        $this->entityManager = $entityManager;
        $this->out=$out;
    }

    public function Load($FileName, $Path)
    {
   setlocale(LC_ALL,'ru_RU.CP1251');
        $Path_FileName=$Path.$FileName;
        if ((file_exists($Path_FileName))) {
            $startTime=microtime(true);
                $this->out->writeln("Start $startTime");
                    $row=2;
            $this->createCSVReader ($Path_FileName);
                  $currentTime=microtime(true)-$startTime;
                         $this->out->writeln("Create CSV reader. Time: $currentTime");
                // Загружаем класс валидации данных
                $validInvoice = new ValidInvoiceOut($this->entityManager);
                    $this->out->writeln("Load ValidInvoiceOut ");
                        $this->reconnect();
	                        $currentTime=microtime(true)-$startTime;
                $this->out->writeln("Load file from CSV reader Time: $currentTime");
                $COUNT=1;
            while (!feof($this->phpCSV) )
                {
                   $this->lineCSV=explode(";",iconv('Windows-1251',"UTF-8",fgets($this->phpCSV)));
                        $num=trim($this->lineCSV[0]);
                    // есди строка НЕ содержит пустые значения или значение "0" то это НЕ конец файла
                    if ((!empty($num)or $num=="0")) {
                        // Создаем сущность
                        //$this->out->writeln("Start createInvoice Time: $currentTime");

                         $Invoice = $this->createInvoice ();
                        //$this->out->writeln("Finish createInvoice Time: $currentTime");
                        // проверяем строку
                            if (($validInvoice->valid($Invoice))) {
                                $this->entityManager->persist($Invoice);
                                    unset($Invoice);
                                    $f=$this->lineCSV[0];
                                        $this->out->writeln(" Persist Invoice. Row $f countRow $COUNT");
                                            fwrite($this->logger,"Persist Invoice. ".implode(";",$this->lineCSV));
                            } else{
                                $f=$this->lineCSV[0];
                                unset($Invoice);
                                    $this->out->writeln(" NO persist Invoice. Row $f countRow $COUNT");
                                fwrite($this->logger," NO persist Invoice. ".implode(";",$this->lineCSV));
                            }
                    } else // иначе устанавливаем признак конца файла
                    {
                        $this->out->writeln(" Exit = true. Row $COUNT num = $num");
                    }
                    //   if ((!empty($num)))
                        if ($COUNT%$this->flushCount==0)
                        {
                            // сохраняем в базе считанный кусок файла
                            try {
                                $this->entityManager->flush ();
                                $this->entityManager->clear ();
	                            $currentTime=microtime(true)-$startTime;
                                $this->out->writeln (" flush Invoice. Row $COUNT Time: $currentTime");
                            } catch (Exception $e) {

                                echo 'Ошибка сохранения' . $FileName . ': ' , $e->getMessage () , "\n";

                            }

                        }
                    $COUNT=$COUNT+1;     // $this->phpExcel->disconnectWorksheets();

                } // for
                        $this->out->writeln("un_set(this->objPHPExcel)");

                                    $this->out->writeln("new row $COUNT ");
             }
    }

    /**
     * Создание сущности Erpn_out() их данных строки $rowStart
     * @param $rowStart
     * @return Erpn_out
     */
    public function createInvoice ()
    {
    try {

        fwrite($this->logger,"createInvoice ".implode(";",$this->lineCSV));
        $Invoice = new Erpn_out();
        $Invoice->setNumInvoice ($this->lineCSV[0]);
        $Invoice->setDateCreateInvoice (new \DateTime($this->lineCSV[1]));
        $Invoice->setTypeInvoiceFull ($this->lineCSV[2]);
        $Invoice->setEdrpouClient ($this->lineCSV[3]);
        $Invoice->setInnClient ($this->lineCSV[4]);
        $Invoice->setNumBranchClient ($this->lineCSV[5]);
        $Invoice->setNameClient ($this->lineCSV[6]);
        $Invoice->setSumaInvoice ($this->lineCSV[7]);
        $Invoice->setPDVInvoice ($this->lineCSV[8]);
        $Invoice->setBazaInvoice ($this->lineCSV[9]);
        $Invoice->setNameVendor ($this->lineCSV[10]);
        $Invoice->setNumBranchVendor ($this->lineCSV[11]);
        $Invoice->setNumBranchClient ($this->lineCSV[12]);
        $Invoice->setDateRegInvoice (new \DateTime($this->lineCSV[13]));
        $Invoice->setNumRegInvoice ($this->lineCSV[14]);
        $Invoice->setTypeInvoice ($this->lineCSV[15]);
        $Invoice->setNumContract ($this->lineCSV[16]);
        $Invoice->setDateContract (new DateTime($this->lineCSV[17]));
        $Invoice->setTypeContract ($this->lineCSV[18]);
        $Invoice->setPersonCreateInvoice ($this->lineCSV[19]);

    }catch(Exception $e){
        echo 'Ошибка создания $Invoice с значениями' . var_dump($this->lineCSV) . ': ' , $e->getMessage () , "\n";
    }
        return $Invoice;
    }

    /**
     * Создание экземпляра класса Ридера файла и настроек чтения
     * включая фильтр
     * @param $FileName
     * @return array
     * @throws \PHPExcel_Reader_Exception
     */
    public function createCSVReader ($FileName)
    {
        try {
            $this->phpCSV = fopen($FileName,'r');
            $this->logger= fopen($FileName."-log",'w');
            } catch (Exception $e) {
            echo 'Ошибка подключения к файлу' . $FileName . ': ' , $e->getMessage () , "\n";
            }
    }

 // http://seyferseed.ru/ru/php/reshenie-problemy-doctrine-2-i-mysql-server-has-gone-away.html#sthash.vh49fkii.dpbs

    public function disconnect()
    {
        $this->entityManager->getConnection()->close();
    }

        public function connect()
    {
        $this->entityManager->getConnection()->connect();
    }

        /**
         * MySQL Server has gone away
         */
        public function reconnect()
    {
        $connection = $this->entityManager->getConnection();
        if (!$connection->ping()) {

            $this->disconnect();
            $this->connect();

            $this->checkEMConnection($connection);
        }
    }

        /**
         * method checks connection and reconnect if needed
         * MySQL Server has gone away
         *
         * @param $connection
         * @throws \Doctrine\ORM\ORMException
         */
        protected function checkEMConnection($connection)
    {

        if (!$this->entityManager->isOpen()) {
            $config = $this->entityManager->getConfiguration();

            $this->em = $this->entityManager->create(
                $connection, $config
            );
        }
    }
}
