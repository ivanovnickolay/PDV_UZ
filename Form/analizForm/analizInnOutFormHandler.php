<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.12.2016
 * Time: 12:23
 */

namespace App\Form\analizForm;
use App\Form\analizForm\analizInnOutForm;
use Symfony\Component\Form\Form;


/**
 * класс обработчика данных формы analizInnOutForm
 * @uses analizInnOutForm форма данные которые обрабатывает обработчик
 * Class analizInnOutFormHandler
 * @package AnalizPdvBundle\Form
 */
class analizInnOutFormHandler
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