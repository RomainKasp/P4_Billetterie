<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null,['label' => 'step2.nom' , ])
            ->add('prenom',null,['label' => 'step2.prenom' , ])
            ->add('dateNaissance',BirthdayType::class,['label' => 'step2.ddn' , ])
            ->add('nationalite',CountryType::class,[	'label' => 'step2.pays' , 
												'preferred_choices' => array('France', 'FR'), ])
			->add('demiJournee',null,['label' => 'step2.demijournee' , 
									  'attr' => ['class' => 'dmiJrn'],
									  ])
			->add('tarifReduit',null,['label' => 'step2.tarifReduit' , 
									  'attr' => ['class' => 'chkBoxTrfRdt'],
									  ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
