<?php

namespace App\Command;

use App\Utilits\ValidEntity\validBranch;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use App\Entity\SprBranch;

/**
 * Класс проводит загрузку файла с данными о филиалах
  * Class LoadReestrOutCommand
 * @package AnalizPdvBundle\Command
 * todo переделать под загрузку командой  LOAD DATA INFILE
 */
class LoadReestrOutCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('load_file:load_reestr_out_command')
            ->setDescription('load info Branch');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $dt=$this->getContainer()->get('doctrine');
            $em=$dt->getManager();
                $Path=$this->getContainer()->getParameter('file_dir_branch');
                   $File_path="$Path\SprBranch.xlsx";
    if(file_exists($File_path))
    {
        $phpExcelObject = $this->getContainer()->get('phpexcel')->createPHPExcelObject($File_path);
        //https://habrahabr.ru/post/245233/
        // http://www.cleverscript.ru/index.php/php/scripts-php/28-phpexel#.V3g26PmLTyF

        $rowIterator = $phpExcelObject->getActiveSheet()->getHighestRow();
        $progress = new ProgressBar($output, $rowIterator);
        $valid = new validBranch($em);
        $CountValidRecord = 0;
        for ($row = 2; $row <= $rowIterator; $row++) {
            $data = new SprBranch();
                $NumBranch = trim($phpExcelObject->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue());
                    $data->setNumBranch($NumBranch);
                        $data->setNameBranch($phpExcelObject->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue());
                            $data->setBranchAdr($phpExcelObject->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue());
                        $data->setNameMainBranch($phpExcelObject->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue());
                    $NumMainBranch = trim($phpExcelObject->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue());
                $data->setNumBranch($NumMainBranch);
            // Проверяем модель $data на валидность
            if ($valid->isValid($data)) {
                $em->persist($data);
                   unset($data);
                $CountValidRecord++;
            } else {
                unset($data);
            }
            $progress->advance();
        }
            $em->flush();
                $progress->finish();
                    $output->writeln(" ");
                        $output->writeln("File load");
                            $output->writeln("Load $CountValidRecord record from $rowIterator");
    } else
        {
            $output->writeln("Error!! File $File_path not found");
        }


    }
}
