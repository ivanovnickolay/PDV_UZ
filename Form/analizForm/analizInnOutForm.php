<?php

namespace App\Form\analizForm;

use App\Entity\forForm\analiz\analizInnOut;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class analizInnOutForm
 * @package AnalizPdvBundle\Form
 */
class analizInnOutForm extends AbstractType
{
	/**
	 * numMainBranch = объекты класса SprBranch !!!
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder->add("monthCreate",IntegerType::class,array('label'=>"Месяц анализа документов",'attr' => array('min' => 1, 'max' => 12)))
		            ->add("yearCreate",IntegerType::class,array('label'=>"Год анализа документов", 'attr' => array('min' => 2015, 'max' => 2017)))
					    ->add("typeAnaliz",ChoiceType::class,array(
						    'choices'  => array(
							    'те которые совпали и в ЕРПН и в Реестре' => 'E=R',
							    'те которые есть только в ЕРПН ' => 'E<>R',
							    'те которые есть только в Реестре  ' => 'R<>E',),
						    'label'=>'Тип анализа документов'))
				    ->add("numMainBranch",EntityType::class,array(
				        'class'=> 'SprBranchRepository',
					    'label'=>'Филиал для анализа',
					    'choice_label'=>'nameMainBranch',
					    'choice_value'=>'numMainBranch',
						    'query_builder'=>function (EntityRepository $er) {
							  return $er->createQueryBuilder('s')
							  //->distinct()
								  ;
						    },
			        ))
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
		$resolver->setDefaults(array('data_class' => analizInnOut::class));

	}

	/**
	 * @return string
	 */
	public function getName()
    {
        return 'analiz_pdv_bundleanaliz_inn_out_form';
    }
}
