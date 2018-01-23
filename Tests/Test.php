<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.10.2016
 * Time: 23:46
 */
class PathToTemplateTest extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{
	private $em;
	protected function setUp()
	{
		self::bootKernel();

		$this->em = static::$kernel->getContainer()
			->getParameter('path_template');

	}
public function test_path()
{
	echo $this->em;
	$dir = new \DirectoryIterator($this->em);
	foreach ($dir as $fileinfo) {
		if (!$fileinfo->isDot()) {
			var_dump($fileinfo->getFilename());
		}
	}
}

}
