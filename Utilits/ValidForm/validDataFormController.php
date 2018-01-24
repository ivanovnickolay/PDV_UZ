<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.02.2017
 * Time: 22:25
 */

namespace App\Utilits\ValidForm;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

/**
 * Задача класса централизованое хранение правил валидации данных которые
 * получаются контроллером из вне. За исключением данных которые получаются от форм.
 *
 *  Данные от форм валидируются в сущности соответтствующего класса
 *
 * Class validDataFormController
 * @package AnalizPdvBundle\Utilits\ValidForm
 */
class validDataFormController implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	private  $validator;

	private $numBranch;

	/**
	 * validDataFormController constructor.
	 * @param ContainerInterface $container
	 */
	public function __construct( ContainerInterface $container)
	 {
		$this->setContainer($container);
	 }

	/**
	 * Проверка значения месяца на интервал от 1 до 12
	 *
	 * выводим массив - результат работы валидатора
	 *  Для определения наличия ошибок надо проверить количество
	 *  элементов массива функцией Count ()
	 *
	 * @param $month int
	 * @return array
	 */
	public function validMonth($month)
	 {
		$validator=$this->container->get('validator');
		$validNotNull=new NotBlank();
		$validRange=new Range(array(
			'min' => 1,
			'max' => 12,
		));
		$result=$validator->validate($month,[$validNotNull,$validRange]);
		return $result;

	 }

	/**
	 * Проверка значения года на интервал от 2015 до 2017
	 *
	 * выводим массив - результат работы валидатора
	 *  Для определения наличия ошибок надо проверить количество
	 *  элементов массива функцией Count ()
	 *
	 * @param $year int
	 * @return array
	 */
	public function validYear($year)
	{
		$validator=$this->container->get('validator');
		$validNotNull=new NotBlank();
		$validRange=new Range(array(
			'min' => 2015,
			'max' => 2017,
		));
		$result=$validator->validate($year,[$validNotNull,$validRange]);
		return $result;

	}


	/**
	 * Проверка значения ИНН
	 *
	 * выводим массив - результат работы валидатора
	 *  Для определения наличия ошибок надо проверить количество
	 *  элементов массива функцией Count ()
	 *
	 * @param string $INN
	 * @return array
	 */
	public function validINN(string $INN)
	{
		$validator=$this->container->get('validator');
		$validNotNull=new NotBlank();
		$validInnLenght= new Length(array(
			'min'        => 1,
			'max'        => 12,
			'maxMessage' => 'Длина ИНН не может быть более {{ limit }} цифр',
		));
		$vailidInnType=new Type(array(
			'type'    => 'digit',
			'message' => 'ИНН {{ value }} должен содержать только цифры .',
		));
		$result=$validator->validate($INN,[$validNotNull, $vailidInnType, $validInnLenght]);
		return $result;

	}

	/**
	 * Проверка значения номера филиала
	 *
	 * выводим массив - результат работы валидатора
	 *  Для определения наличия ошибок надо проверить количество
	 *  элементов массива функцией Count ()
	 *
	 * @param string $numBranch
	 * @return array
	 */
	public function validNumBranch(string $numBranch)
	{
		$validator=$this->container->get('validator');
		$validNotNull=new NotBlank();
		$validNBLenght= new Length(array(
			'min'        => 3,
			'max'        => 3,
			'maxMessage' => 'Длина номер филиала не может быть более {{ limit }} цифр',
		));
		$validNBType=new Type(array(
			'type'    => 'digit',
			'message' => 'Номер филиала {{ value }} должен содержать только цифры .',
		));
		$result=$validator->validate($numBranch,[$validNotNull, $validNBType, $validNBLenght]);
		// Если ошибок предварительной валидации нет то проверим наличие номера филиала в базе
		if (count($result)==0) {
			$isExistBranch=$this->container->get('doctrine')->getManager()->getRepository('App:SprBranch')->findCountNumBranch($this->numBranch);
				//если номер филиала существуент
				if($isExistBranch){
					// возвращаем пустой массив
					return $branch = array();
				}
		} else {
			// Если ошибки при предварительно валидации есть от вывводи их без проверки по базе данных
			return $result;
		}


	}

	/**
	 * Проверка соответсвия перида поиска данных дате создания документа
	 *  периоды должны совпадать
	 * @param ExecutionContextInterface $context
	 * @param $payload
	 */
	public function validateNB(ExecutionContextInterface $context, $payload)
	{
		$result=$this->container('doctrine')->getManager()->getRepository('SprBranch')->findCountNumBranch($this->numBranch);
		if (!$result)
			{
				$context->buildViolation("Номер филиала указан не верно  !")
					->atPath('numBranch')
					->addViolation();
			}

	}



}