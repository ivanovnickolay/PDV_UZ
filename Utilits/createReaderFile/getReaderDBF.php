<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.09.2016
 * Time: 23:49
 */

namespace App\Utilits\createReaderFile;

/*
 * @deprecated
 */
class getReaderDBF
{
	private $dbfFile;
	public function __construct (string $fileName)
	{
		if(file_exists($fileName))
		{
			$this->dbfFile=dbase_open($fileName);
		} else
		{
			$this->dbfFile='';
		}
	}

	public function getMaxRow()
	{
		if(!empty($this->dbfFile))
		{
			return dbase_numrecords($this->dbfFile);
		}
	}
}