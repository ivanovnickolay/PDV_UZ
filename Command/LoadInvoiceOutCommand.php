<?php

namespace App\Command;

use App\Utilits\LoadInvoice\LoadInvoiceOut\LoadInvoiceOut2;
use App\Utilits\LoadInvoice\LoadInvoiceOut\LoadInvoiceOut_CSV;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadInvoiceOutCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('load_file:load_invoice_out_command')
            ->setDescription('Load Invoice from ERPN out ' );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $dt=$this->getContainer()->get('doctrine');
        $em=$dt->getManager();
        $Path=$this->getContainer()->getParameter('file_dir_invoiceout');
        $FileName="12-2015_out1.csv";
        $output->writeln("Load $FileName ...");
         //$loadInvoiceOut= new LoadInvoiceOut2($em,$output);
        $loadInvoiceOut= new LoadInvoiceOut_CSV($em,$output);
            $loadInvoiceOut->Load($FileName,$Path);
    }
}
