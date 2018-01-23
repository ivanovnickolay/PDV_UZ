<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2016
 * Time: 21:11
 */

namespace App\Model\writeAnalizPDVToFile;


abstract class writeAnalizToFileAbstract
{
	protected $em;
	protected $pathToTemplate;

	final function __construct ($entityManager,string $pathToTemplate='')
	{
		$this->em=$entityManager;
		$this->pathToTemplate=$pathToTemplate;
	}



}