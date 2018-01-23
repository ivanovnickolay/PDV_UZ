<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.03.2017
 * Time: 23:20
 */

namespace AnalizPdvBundle\Utilits\LoadInvoice\configLoad;


use AnalizPdvBundle\Utilits\LoadInvoice\configLoad\validConstraint\ContainsEntity;
use AnalizPdvBundle\Utilits\LoadInvoice\configLoad\validConstraint\ContainsEntityValidator;
use AnalizPdvBundle\Utilits\LoadInvoice\configLoad\validConstraint\ContainsGetLines;
use AnalizPdvBundle\Utilits\LoadInvoice\createEntity\createErpnIn;
use AnalizPdvBundle\Utilits\LoadInvoice\createEntity\createErpnOut;
use AnalizPdvBundle\Utilits\LoadInvoice\loadLinesData\loadLinesDataCSV;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class configLoadErpnInCSV
 * @package AnalizPdvBundle\Utilits\LoadInvoice\configLoad
 */
class configLoadErpnOutCSV extends configLoadAbstract
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
		$this->entity=new createErpnOut();
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