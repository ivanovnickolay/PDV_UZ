<?php

namespace App\Command;

use App\Entity\Erpn_out;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Load_Erpn_OutCommand
 * @package AnalizPdvBundle\Command
 * Класс выполняет загрузку данных в таблицу Erpn_Out
 * из файлов csv указанного формата при помощи команды
 * LOAD DATA LOCAL INFILE и удаляет дубликаты данных по ключевому полю key_field
 * по умолчанию файл называется All_Out.csv

 */
class Load_Erpn_OutCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('analiz_pdv:load_erpn_out_command')
            ->setDescription('Load data to ERPN OUT');
        $this->addArgument('nameFile',InputArgument::OPTIONAL,'Enter name file *.csv');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    $nameFile=$input->getArgument('nameFile');
        $Path=$this->getContainer()->getParameter('file_dir_invoiceout');
        if (!file_exists($Path.$nameFile) and null == $nameFile){
            $output->writeln("File not found ");
            return;
        }
            if (null == $nameFile){
                $nameFile='All_Out.csv';
            }
                if (!file_exists($Path.$nameFile)){
                    $output->writeln("File All_Out.csv not found ");
                    return;
                }
        $t=$this->getContainer()->get('doctrine');
            $em=$t->getManager();
                $em->getRepository("ErpnOut")->loadDataInfile($Path.$nameFile);
    }
}
