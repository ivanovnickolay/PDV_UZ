<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.09.2016
 * Time: 23:44
 */

namespace App\Tests\Command;


use App\Command\loadReestBranchCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class loadReestBranchCommandTest extends KernelTestCase
{
	public function testExecute()
	{
		self::bootKernel();
		$application = new Application(self::$kernel);

		$application->add(new loadReestBranchCommand());

		$command = $application->find('analiz_pdv:load_reest_branch_command');
		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'command'  => $command->getName()));

		// the output of the command in the console
		$output = $commandTester->getDisplay();

	}
}