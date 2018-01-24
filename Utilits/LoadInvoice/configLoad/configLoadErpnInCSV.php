<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.03.2017
 * Time: 23:20
 */

namespace App\Utilits\LoadInvoice\configLoad;


use App\Utilits\LoadInvoice\configLoad\validConstraint\ContainsEntity;
use App\Utilits\LoadInvoice\configLoad\validConstraint\ContainsEntityValidator;
use App\Utilits\LoadInvoice\configLoad\validConstraint\ContainsGetLines;
use App\Utilits\LoadInvoice\createEntity\createErpnIn;
use App\Utilits\LoadInvoice\loadLinesData\loadLinesDataCSV;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class configLoadErpnInCSV
 * @package AnalizPdvBundle\Utilits\LoadInvoice\configLoad
 */
class configLoadErpnInCSV extends configLoadAbstract
{
	/**
	 *
	 */
	private function setCountRecordSave()
	{
		$this->countRecordSave=1000;
	}

	/**
	 *
	 */
	private function setEntity()
	{
		$this->entity=new createErpnIn();
	}

	/**
	 *
	 */
	private function setGetLines()
	{
		$this->getLines=new loadLinesDataCSV();
	}

	/**
	 * @param ClassMetadata $metadata
	 */
	public static function loadValidatorMetadata(ClassMetadata $metadata)
	{
		$metadata->addPropertyConstraint('countRecordSave', new Range(array('min' => 1,)));
		$metadata->addPropertyConstraint('entity', new ContainsEntity());
		$metadata->addPropertyConstraint('getLines',new ContainsGetLines());
		$metadata->addPropertyConstraint('fileName',new File(array(
			'notReadableMessage' => 'File not found or file not reader')));
	}
}