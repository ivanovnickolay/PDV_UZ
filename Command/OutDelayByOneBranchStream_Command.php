<?php

namespace App\Command;

use App\Model\writeAnalizPDVToFile\writeAnalizOutDelayDate;
use App\Model\writeAnalizPDVToFile\writeAnalizPDVToFile;
use App\Utilits\validInputCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда формирует анализ опаздавших выданных НН по всем филиалам в периоде
  * Class AnalizPDVOutDiffByOneBranchStream_Command
 * @package AnalizPdvBundle\Command
 */
class OutDelayByOneBranchStream_Command extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('analiz_pdv:OutDelayOneBranchStream')
            ->setDescription('Анализ НН по обязательствам, которые зарегистрированы с опазданием по каждому филиалу в периоде')
            ->addOption('month',null,InputOption::VALUE_REQUIRED,'Введите месяц')
            ->addOption('year',null,InputOption::VALUE_REQUIRED,'Введите год')
            ->setHelp("Анализ НН по обязательствам, которые зарегистрированы с опазданием по каждому филиалу в периоде.
            Обязательные параметры месяц анализа --month= и год анализа --year=. Например analiz_pdv:OutDelayOneBranchStream --month=6 --year=2016");
    }

	/**
	 * {@inheritdoc}
	 * @uses validInputCommand::validMonth
	 * @uses validInputCommand::validYear
	 * @uses validInputCommand::getTextError
	 * @see writeAnalizPDVToFile::writeOutDelayByAllBranch формирование файла анализа (убрано 27-10-16)
	 * @see writeAnalizOutDelayDate::writeAnalizPDVOutDelayByAllBranch формирование файла анализа
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
        //$write->writeOutDelayByAllBranch($month,$year);

	    $write=new writeAnalizOutDelayDate($em,$pathTemplate);
	    $write->writeAnalizPDVOutDelayByAllBranch($month,$year);
        unset($write);
        gc_collect_cycles();
    }
}
