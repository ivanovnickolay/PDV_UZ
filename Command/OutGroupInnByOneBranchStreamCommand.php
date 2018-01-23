<?php

namespace AnalizPdvBundle\Command;

use AnalizPdvBundle\Model\writeAnalizPDVToFile\writeAnalizOutByInn;
use AnalizPdvBundle\Model\writeAnalizPDVToFile\writeAnalizPDVToFile;
use AnalizPdvBundle\Utilits\validInputCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда формирует анализ совпадения номеров ИНН выданных НН по одному филиаоу в периоде
 * Class AnalizPDVOutInnByOneBranchCommand
 * @package AnalizPdvBundle\Command
 */
class OutGroupInnByOneBranchStreamCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('analiz_pdv:OutGroupInnByOneBranchStream')
            ->setDescription('Анализ НН по обязательствам в разрезе ИНН  по каждому филиалу в периоде')
            ->addOption('month',null,InputOption::VALUE_REQUIRED,'Введите месяц')
            ->addOption('year',null,InputOption::VALUE_REQUIRED,'Введите год')
            ->setHelp("Анализ НН по обязательствам в разрезе ИНН  по каждому филиалу в периоде. Обязательные
            параметры месяц анализа --month= и год анализа --year=. Например analiz_pdv:OutGroupInnByOneBranchStream --month=6 --year=2016");
    }

	/**
	 * {@inheritdoc}
	 * @uses validInputCommand::validMonth
	 * @uses validInputCommand::validYear
	 * @uses validInputCommand::getTextError
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByAllBranch формирование файла анализа
	 */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        gc_enable();
        $dt=$this->getContainer()->get('doctrine');
        $em=$dt->getManager();
        $pathTemplate=$this->getContainer()->getParameter('path_template');

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
        $write=new writeAnalizOutByInn($em,$pathTemplate);
        $write->writeAnalizPDVOutInnByAllBranch($month,$year);
        unset($write);
        gc_collect_cycles();
    }
}
