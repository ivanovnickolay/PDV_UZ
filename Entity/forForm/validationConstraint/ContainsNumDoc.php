<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.12.2016
 * Time: 22:12
 */

namespace App\Entity\forForm\validationConstraint;


use Symfony\Component\Validator\Constraint;

/**
 * Валидатор проверяет правильность написания номера документа
 *
 * Class ContainsNumDoc
 * @package AnalizPdvBundle\Entity\forForm\validationConstraint
 */
class ContainsNumDoc extends Constraint
{
	public $message = '"%string%" - не верный номер документа ';
}