<?php

namespace App\Form;

use App\Entity\forForm\search\allFromPeriod_Branch;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
class allFromPeriod_BranchForm extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add("monthCreate",IntegerType::class,array('label'=>"Месяц поиска документов",'attr' => array('min' => 1, 'max' => 12)))
		 ->add("yearCreate",IntegerType::class,array('label'=>"Год поиска документов", 'attr' => array('min' => 2015, 'max' => 2017)))
		    ->add("routeSearch",ChoiceType::class,array(
			    'choices'  => array(
				    'Обязательства' => 'Обязательства',
				    'Кредит' => 'Кредит',),
			    'label'=>'Направление поиска документов'))

		    ->add("numBranch",TextType::class,array('label'=>"Номер структурного подразделения, создателя документа "))
		   ->add("numMainBranch",TextType::class,array('label'=>"Номер филиала, создателя документа "))
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
    	    $resolver->setDefaults(array('data_class' => allFromPeriod_Branch::class));

    }

	/**
	 * @return string
	 */
	public function getName()
    {
        return 'analiz_pdv_bundleall_from_period_branch_form';
    }
}
