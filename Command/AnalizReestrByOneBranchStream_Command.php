<?php

namespace AnalizPdvBundle\Command;


use AnalizPdvBundle\Model\writeAnalizPDVToFile\writeAnalizPDVToFile;
use AnalizPdvBundle\Model\writeAnalizPDVToFile\writeAnalizReestr;
use AnalizPdvBundle\Utilits\validInputCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда формирует анализ ПДВ по реестрам и ЕРПН по всем филиалам в периоде
 * Class AnalizPDVByOneBranchStream_Command
 * @package AnalizPdvBundle\Command
 */
class AnalizReestrByOneBranchStream_Command extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('analiz_pdv:AnalizReestrByOneBranchStream')
            ->setDescription('Анализ ПДВ между ЕРПН и Реестрами филиалов по каждому филиалу в периоде.')
            ->addOption('month',null,InputOption::VALUE_REQUIRED,'Введите месяц')
            ->addOption('year',null,InputOption::VALUE_REQUIRED,'Введите год')
            ->setHelp("Анализ ПДВ между ЕРПН и Реестрами филиалов по всем филиалам отдельно. Обязательные параметры
             месяц анализа --month= и год анализа --year=. Например analiz_pdv:AnalizReestrByOneBranchStream --month=6 --year=2016");
    }

	/**
	 * {@inheritdoc}
	 * @uses validInputCommand::validMonth
	 * @uses validInputCommand::validYear
	 * @uses validInputCommand::getTextError
	 * @uses writeAnalizPDVToFile::writeAnalizPDVByAllBranch формирование файла анализа (удалено 06-11-16)
	 * @uses writeAnalizReestr::writeAnalizPDVByAllBranch формирование файла анализа
	 */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        gc_enable();
        $dt=$this->getContainer()->get('doctrine');
        $em=$dt->getManager();

        $valid=new validInputCommand($em);
        $month=$input->getOption('month');
        if (!$valid->validMonth($month))
        {
            $output->writeln($valid->getTextError());
            exit();
        }

        $year=$input->getOption('year');
        if (!$valid->validYear($year))
        {
            $output->writeln($valid->getTextError());
            exit();
        }
        $pathTemplate=$this->getContainer()->getParameter('path_template');
        //$write=new writeAnalizPDVToFile($em,$pathTemplate);
	    $write=new writeAnalizReestr($em,$pathTemplate);
        //$write->writeAnalizPDVByAllBranch($month,$year);
	    $write->writeAnalizPDVByAllBranch($month,$year);
        unset($write);
        gc_collect_cycles();
    }

}
