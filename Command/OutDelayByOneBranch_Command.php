<?php

namespace AnalizPdvBundle\Command;

use AnalizPdvBundle\Model\writeAnalizPDVToFile\writeAnalizOutDelayDate;
use AnalizPdvBundle\Model\writeAnalizPDVToFile\writeAnalizPDVToFile;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда формирует анализ опаздавших выданных НН одному филиалу в периоде
 * todo реализовать ввод параметров команды
 * Class AnalizPDVOutDiffByOneBranch_Command
 * @package AnalizPdvBundle\Command
 */
class OutDelayByOneBranch_Command extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('analiz_pdv:OutDelayByOneBranch')
            ->setDescription('Анализ НН по обязательствам, которые зарегистрированы с опазданием по одному филиалу в периоде');
    }

    /**
     * {@inheritdoc}
     * @uses writeAnalizPDVToFile::writeAnalizPDVOutDelayByOneBranch - формирование анализа (удалено 27-10-16)
     * @uses writeAnalizOutDelayDate::writeAnalizPDVOutDelayByOneBranch - формирование анализа
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        gc_enable();
        $dt=$this->getContainer()->get('doctrine');
        $em=$dt->getManager();
        $pathTemplate=$this->getContainer()->getParameter('path_template');
        //$write=new writeAnalizPDVToFile($em,$pathTemplate);
        //$write->writeAnalizPDVOutDelayByOneBranch(8,2016,"682");

	    $write=new writeAnalizOutDelayDate($em,$pathTemplate);
	    $write->writeAnalizPDVOutDelayByOneBranch(8,2016,"682");
        unset($write);
        gc_collect_cycles();
    }
}
