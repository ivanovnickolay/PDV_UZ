<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02.08.2016
 * Time: 23:14
 */

namespace LoadFileBundle\Utilits\LoadInvoice\LoadInvoiceOut;


use Doctrine\ORM\EntityManager;
use LoadFileBundle\Entity\Erpn_out;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use LoadFileBundle\Utilits\LoadInvoice\LoadInvoiceOut\validInvoiceOut;
class LoadInvoiceOut
{
    private $entityManager;
    private $phpExcel;
    private $rowData;
    private $objPHPExcel;
    private $chunkSize=10000;
    private $chunkFilter;
    private $out;


    public function __construct(EntityManager $entityManager, OutputInterface $out)
    {
        $this->entityManager = $entityManager;
        $this->out=$out;
    }

    public function Load($FileName, $Path)
    {
       //$Path_FileName= "$Path $FileName";
        $cacheMethod=\PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array( 'memoryCacheSize' => '64MB' );
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
        $Path_FileName=$Path.$FileName;

        if ((file_exists($Path_FileName))) {
            //$Cont = new Container();
           $this->createExcelReader ($FileName);
            // Получаем количество строк с данном в файде
           $this->out->writeln("Create Excel reader");
             // Загружаем класс валидации данных
             $validInvoice = new ValidInvoiceOut($this->entityManager);
            $this->out->writeln("Load ValidInvoiceOut");
            //for ($row = 2; $row <= $this->rowData; $row += $this->chunkSize) {
            $row=2;
            $exit=false;
            while(!$exit)
            {
                $this->chunkFilter->setRows($row,$this->chunkSize);
                $this->objPHPExcel = $this->phpExcel->load($Path_FileName);
                $this->reconnect();
                $this->out->writeln("Load file from phpExcel");
                for ($rowStart=$row; $rowStart<$row+$this->chunkSize; $rowStart++)
                {
                    $num=trim($this->objPHPExcel->getActiveSheet ()->getCell ('A' . $rowStart)->getValue ());
                    // есди строка НЕ содержит пустые значения или значение "0" то это НЕ конец файла
                    if ((!empty($num)or $num=="0")) {
                        // Создаем сущность
                         $Invoice = $this->createInvoice ($rowStart);
                        // проверяем строку
                            if (($validInvoice->valid($Invoice))) {
                                $this->entityManager->persist($Invoice);
                                unset($Invoice);
                               // $this->out->writeln(" Persist Invoice. Row $rowStart");
                            } else
                                {
                                 unset($Invoice);
                                }
                    } else // иначе устанавливаем признак конца файла
                    {
                        $exit=true;
                        $this->out->writeln(" Exit = true. Row $rowStart num = $num");
                    }
                    //   if ((!empty($num)))
                } // for
                    // сохраняем в базе считанный кусок файла
                        try
                        {
                        $this->entityManager->flush();
                            $this->entityManager->clear();
                            $this->out->writeln(" flush Invoice. Row $rowStart");
                        }catch (Exception $e) {

                            echo 'Ошибка сохранения' . $FileName . ': ', $e->getMessage(), "\n";

                        }
                       // $this->phpExcel->disconnectWorksheets();
                        unset($this->objPHPExcel);

                            $this->out->writeln("un_set(this->objPHPExcel)");
                                $row=$this->chunkSize+$row;
                                    $this->out->writeln("new row $row");
             }


        } else {
            return false;
        }

    }


	/**
     * Определение типа файла исходя из названия (расширения) файла
     * @param $pFilename
     * @return null|string
     */
    public function getFileType($pFilename)
    {
        $pathinfo = pathinfo($pFilename);

        $extensionType = NULL;
        if (isset($pathinfo['extension'])) {
            switch (strtolower($pathinfo['extension'])) {
                case 'xlsx':            //	Excel (OfficeOpenXML) Spreadsheet
                case 'xlsm':            //	Excel (OfficeOpenXML) Macro Spreadsheet (macros will be discarded)
                case 'xltx':            //	Excel (OfficeOpenXML) Template
                case 'xltm':            //	Excel (OfficeOpenXML) Macro Template (macros will be discarded)
                    $extensionType = 'Excel2007';
                    break;
                case 'xls':                //	Excel (BIFF) Spreadsheet
                case 'xlt':                //	Excel (BIFF) Template
                    $extensionType = 'Excel5';
                    break;
                case 'ods':                //	Open/Libre Offic Calc
                case 'ots':                //	Open/Libre Offic Calc Template
                    $extensionType = 'OOCalc';
                    break;
                case 'slk':
                    $extensionType = 'SYLK';
                    break;
                case 'xml':                //	Excel 2003 SpreadSheetML
                    $extensionType = 'Excel2003XML';
                    break;
                case 'gnumeric':
                    $extensionType = 'Gnumeric';
                    break;
                case 'htm':
                case 'html':
                    $extensionType = 'HTML';
                    break;
                case 'csv':
                    // Do nothing
                    // We must not try to use CSV reader since it loads
                    // all files including Excel files etc.
                    break;
                default:
                    break;
            }
            return $extensionType;
        }

    }

    /**
     * Получаем количество строк с данными на листе
     * @param $FileName
     * @throws \Exception
     */
    public function getMaxRow($FileName)
    {
        $maxRow=0;
        if ((file_exists($FileName))) {
            $phpExcel= \PHPExcel_IOFactory::load($FileName);
            $maxRow=$phpExcel->getActiveSheet()->getHighestRow();
            $phpExcel->disconnectWorksheets();
            unset($phpExcel);
            return $maxRow;
        } else {
            return $maxRow;
        }




    }

    /**
     * Создание сущности Erpn_out() их данных строки $rowStart
     * @param $rowStart
     * @return Erpn_out
     */
    public function createInvoice ($rowStart)
    {
        $Invoice = new Erpn_out();
        $Invoice->setNumInvoice ($this->objPHPExcel->getActiveSheet ()->getCell ('A' . $rowStart)->getValue ());
        $Invoice->setDateCreateInvoice (\PHPExcel_Shared_Date::ExcelToPHPObject ($this->objPHPExcel->getActiveSheet ()->getCell ('B' . $rowStart)->getValue ()));
        $Invoice->setTypeInvoiceFull ($this->objPHPExcel->getActiveSheet ()->getCell ('C' . $rowStart)->getValue ());
        $Invoice->setEdrpouClient ($this->objPHPExcel->getActiveSheet ()->getCell ('D' . $rowStart)->getValue ());
        $Invoice->setInnClient ($this->objPHPExcel->getActiveSheet ()->getCell ('E' . $rowStart)->getValue ());
        $Invoice->setNumBranchClient ($this->objPHPExcel->getActiveSheet ()->getCell ('F' . $rowStart)->getValue ());
        $Invoice->setNameClient ($this->objPHPExcel->getActiveSheet ()->getCell ('G' . $rowStart)->getValue ());
        $Invoice->setSumaInvoice ($this->objPHPExcel->getActiveSheet ()->getCell ('H' . $rowStart)->getValue ());
        $Invoice->setPDVInvoice ($this->objPHPExcel->getActiveSheet ()->getCell ('I' . $rowStart)->getValue ());
        $Invoice->setBazaInvoice ($this->objPHPExcel->getActiveSheet ()->getCell ('J' . $rowStart)->getValue ());
        $Invoice->setNameVendor ($this->objPHPExcel->getActiveSheet ()->getCell ('K' . $rowStart)->getValue ());
        $Invoice->setNumBranchVendor ($this->objPHPExcel->getActiveSheet ()->getCell ('L' . $rowStart)->getValue ());
        $Invoice->setNumBranchClient ($this->objPHPExcel->getActiveSheet ()->getCell ('M' . $rowStart)->getValue ());
        $Invoice->setDateRegInvoice (\PHPExcel_Shared_Date::ExcelToPHPObject ($this->objPHPExcel->getActiveSheet ()->getCell ('N' . $rowStart)->getValue ()));
        $Invoice->setNumRegInvoice ($this->objPHPExcel->getActiveSheet ()->getCell ('O' . $rowStart)->getValue ());
        $Invoice->setTypeInvoice ($this->objPHPExcel->getActiveSheet ()->getCell ('P' . $rowStart)->getValue ());
        $Invoice->setNumContract ($this->objPHPExcel->getActiveSheet ()->getCell ('Q' . $rowStart)->getValue ());
        $Invoice->setDateContract (\PHPExcel_Shared_Date::ExcelToPHPObject ($this->objPHPExcel->getActiveSheet ()->getCell ('R' . $rowStart)->getValue ()));
        $Invoice->setTypeContract ($this->objPHPExcel->getActiveSheet ()->getCell ('S' . $rowStart)->getValue ());
        $Invoice->setPersonCreateInvoice ($this->objPHPExcel->getActiveSheet ()->getCell ('T' . $rowStart)->getValue ());
        return $Invoice;
    }

    /**
     * Создание экземпляра класса Ридера файла и настроек чтения
     * включая фильтр
     * @param $FileName
     * @return array
     * @throws \PHPExcel_Reader_Exception
     */
    public function createExcelReader ($FileName)
    {
        try {
            //$this->phpExcel = $Cont->get("phpexcel")->createPHPExcelObject("$Path \ $FileName");

            // получаем класс вида class PHPExcel_Reader_Excel2007 extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
            $this->phpExcel = \PHPExcel_IOFactory::createReader ($this->getFileType ($FileName));

            /** Создаем класс фильтра **/
            $this->chunkFilter = new chunkReadFilter();

            /** Tell the Reader that we want to use the Read Filter **/
            $this->phpExcel->setReadFilter ($this->chunkFilter);

            // Указываем что нам нужны только данные из файла - без форматирования
            $this->phpExcel->setReadDataOnly (true);
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
