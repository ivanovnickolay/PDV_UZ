<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.03.2017
 * Time: 23:41
 */

namespace AnalizPdvBundle\Utilits\LoadInvoice\configLoad\validConstraint;


use AnalizPdvBundle\Utilits\LoadInvoice\createEntity\createEntityInterface;
use AnalizPdvBundle\Utilits\LoadInvoice\loadLinesData\loadLinesDataInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
 * Class ContainsEntityValidator
 * @package AnalizPdvBundle\Utilits\LoadInvoice\configLoad\validConstraint
 */
class ContainsGetLinesValidator extends ConstraintValidator
{
	/**
	 * @param mixed $value
	 * @param Constraint $constraint
	 */
	public function validate($value, Constraint $constraint)
	{
		if (!$value instanceof loadLinesDataInterface) {
			$this->context->buildViolation($constraint->message)
				->setParameter('%string%', $value)
				->addViolation();
		}
	}
}