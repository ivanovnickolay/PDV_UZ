<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.03.2017
 * Time: 23:41
 */

namespace App\Utilits\LoadInvoice\configLoad\validConstraint;


use App\Utilits\LoadInvoice\createEntity\createEntityInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
 * Class ContainsEntityValidator
 * @package AnalizPdvBundle\Utilits\LoadInvoice\configLoad\validConstraint
 */
class ContainsEntityValidator extends ConstraintValidator
{
	/**
	 * @param mixed $value
	 * @param Constraint $constraint
	 */
	public function validate($value, Constraint $constraint)
	{
		if (!$value instanceof createEntityInterface) {
			$this->context->buildViolation($constraint->message)
				->setParameter('%string%', $value)
				->addViolation();
		}
	}
}