<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.03.2017
 * Time: 23:41
 */

namespace AnalizPdvBundle\Utilits\LoadInvoice\configLoad\validConstraint;


use Symfony\Component\Validator\Constraint;

/**
 * Class ContainsEntity
 * @package AnalizPdvBundle\Utilits\LoadInvoice\configLoad\validConstraint
 */
class ContainsGetLines extends Constraint
{
	public $message = 'Переданный объект "%string%" не типа loadLinesDataInterface';
}
