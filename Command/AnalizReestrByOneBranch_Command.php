<?php

namespace App\Command;


use App\Model\writeAnalizPDVToFile\writeAnalizOutDelayDate;
use App\Model\writeAnalizPDVToFile\writeAnalizPDVToFile;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда формирует анализ ПДВ по реестрам и ЕРПН по одному филиалу
 * todo реализовать ввод параметров команды
 * Class AnalizPDVByOneBranch_Command
 * @package AnalizPdvBundle\Command
 */
class AnalizReestrByOneBranch_Command extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('analiz_pdv:AnalizReestrByOneBranch')
            ->setDescription('Анализ ПДВ между ЕРПН и Реестрами филиалов по одному филиалу за период.');
    }

	/**
	 * {@inheritdoc}
	 * @uses writeAnalizPDVToFile::writeAnalizPDVByOneBranch формирование файла анализа (убрано 27-10-16)
	 * @uses writeAnalizOutDelayDate::writeAnalizPDVOutDelayByOneBranch формирование файла анализа
	 */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dt=$this->getContainer()->get('doctrine');
        $em=$dt->getManager();
        $pathTemplate=$this->getContainer()->getParameter('path_template');
        //$write=new writeAnalizPDVToFile($em,$pathTemplate);
        //$write->writeAnalizPDVByOneBranch(7,2016,"667");
	    $write=new writeAnalizOutDelayDate($em,$pathTemplate);
	    $write->writeAnalizPDVOutDelayByOneBranch(7,2016,"667");
        unset($write);
        gc_collect_cycles();
    }
}
