<?php

namespace App\Form;

use App\Entity\forForm\search\allFromPeriod_Branch;
use App\Entity\forForm\search\docFromParam;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class allFromPeriod_BranchForm
 * Создание формы поиска данных
 * @uses allFromPeriod_Branch класс хранения данных формы
 * @package AnalizPdvBundle\Form
 */
class docFromParamForm extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
		    ->add("monthCreate",IntegerType::class,array('label'=>"Период поиска документов. Месяц",'attr' => array('min' => 1, 'max' => 12)))
		        ->add("yearCreate",IntegerType::class,array('label'=>"Период поиска документов. Год", 'attr' => array('min' => 2015, 'max' => 2017)))
				    ->add("routeSearch",ChoiceType::class,array(
					    'choices'  => array(
						    'Обязательства' => 'Обязательства',
						    'Кредит' => 'Кредит',),
					    'label'=>'Направление поиска документов'))
		            ->add("numDoc",TextType::class,array('label'=>"Номер документа"))
		                ->add('dateCreateDoc',DateType::class,array(
							'widget'=>'choice',
			                'placeholder' => array('day' => 'день', 'month' => 'месяц','year' => 'год'),
		                	'label'=>"Дата создания документа",
			                'format' => 'dd-MM-yyyy'
			                ))
			                ->add("iNN",TextType::class,array('label'=>"ИНН контрагента"))
			                    ->add("typeDoc",ChoiceType::class,array(
				                    'choices'  => array(
					                    'ПНЕ' => 'ПНЕ',
					                    'РКЕ' => 'РКЕ',),
			                                'label'=>'Тип документа'))


		    ->add('search', SubmitType::class, array('label' => 'Искать'));
    }

	/**
	 *
	 * Добавляем класс в котором хранятся данные
	 * и который будет проверятся валидатором
	 *
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
    {
    	    $resolver->setDefaults(array('data_class' => docFromParam::class));

    }

	/**
	 * @return string
	 */
	public function getName()
    {
        return 'analiz_pdv_bundleall_from_period_branch_form';
    }
}
