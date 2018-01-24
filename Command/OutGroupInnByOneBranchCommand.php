<?php

namespace App\Command;

use App\Model\writeAnalizPDVToFile\writeAnalizOutByInn;
use App\Model\writeAnalizPDVToFile\writeAnalizPDVToFile;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда формирует анализ совпадения номеров ИНН выданных НН по одному филиалу в периоде
 * todo реализовать ввод параметров команды
 * Class AnalizPDVOutInnByOneBranchCommand
 * @package AnalizPdvBundle\Command
 */
class OutGroupInnByOneBranchCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('analiz_pdv:OutGroupInnByOneBranch')
            ->setDescription('Анализ НН по обязательствам в разрезе ИНН  по одному филиалу в периоде');
    }

	/**
	 * {@inheritdoc}
	 * @uses writeAnalizPDVToFile::OutGroupInnByOneBranch формирование файла анализа (удалено 27-10-16)
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranch формирование файла анализа
	 */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        gc_enable();
        $dt=$this->getContainer()->get('doctrine');
        $em=$dt->getManager();
        $pathTemplate=$this->getContainer()->getParameter('path_template');
        //$write=new writeAnalizPDVToFile($em,$pathTemplate);
        //$write->OutGroupInnByOneBranch(7,2016,"667");

	    $write=new writeAnalizOutByInn($em,$pathTemplate);
	   //$write->writeAnalizPDVOutInnByOneBranch(7,2016,"667");
	    $write->writeAnalizPDVOutInnByOneBranchWithDoc(5,2016,"660");
        unset($write);
        gc_collect_cycles();
    }
}
