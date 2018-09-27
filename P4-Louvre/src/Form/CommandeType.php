<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$annee=date('Y');
		$tabAnnee= [$annee  , $annee+1, $annee+2, $annee+3, $annee+4, $annee+5,
					$annee+6, $annee+7, $annee+8, $annee+9];
        $builder
            ->add('mail', EmailType::class, ['mapped' => true, 'label' => 'Votre mail: ' ])
            ->add('dateCommande', DateType::class,[	'mapped' => true, 
													'widget' => 'single_text',
													'html5' => false, 
													'attr' => ['class' => 'js-hide'], ])
            ->add('dateVisite', DateType::class,[	'mapped' => true, 
													'years' =>$tabAnnee,
													'label' => 'Date de la visite: ', 
													'widget' => 'single_text',
													'html5' => false, 
													'attr' => ['class' => 'js-datepicke'], ])
			->add('number_ticket', IntegerType::class, [ 'mapped' => false,
														'label' => 'Nombre de tickets: ' ,
														'attr'  => [ 	'step' => 1,
																		'min'  => 1,
																		'max'  => 30,
																	]
														])		
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
