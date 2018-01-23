<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.12.2016
 * Time: 22:15
 */

namespace App\Entity\forForm\validationConstraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ContainsNumDocValidator
 * @package AnalizPdvBundle\Entity\forForm\validationConstraint
 */
class ContainsNumDocValidator extends ConstraintValidator
{
	/**
	 * @param mixed $value
	 * @param Constraint $constraint
	 */
	public function validate ($value , Constraint $constraint)
{
	if (preg_match("/[^0-9\/]/", $value, $matches))
	{
		$this->context->buildViolation($constraint->message)
			->setParameter('%string%', $value)
			->addViolation();
	}
}

}