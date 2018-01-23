<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.12.2016
 * Time: 12:23
 */

namespace App\Form;
use Symfony\Component\Form\Form;


/**
 * класс обработчика данных формы allFromPeriod_BranchForm
 * @uses docFromParamForm форма данные которые обрабатывает обработчик
 * Class allFromPeriod_BranchFormHandler
 * @package AnalizPdvBundle\Form
 */
class docFromParamFormHandler
{
	/**
	 * @param FormInterface $form
	 * @param Request $request
	 * @return bool
	 */
	public function handler($form, $request)
	{
		$form->handleRequest($request);
		if (!$form->isSubmitted())
		 {
		  return false;
		 }
		if (!$form->isValid())
		 {
		  return false;
		 }
		return true;

	}

}