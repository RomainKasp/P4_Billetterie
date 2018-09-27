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
            ->add('nom',null,['label' => 'Nom: ' , ])
            ->add('prenom',null,['label' => 'Prenom: ' , ])
            ->add('dateNaissance',BirthdayType::class,['label' => 'Date de naissance: ' , ])
            ->add('nationalite',CountryType::class,[	'label' => 'Pays: ' , 
												'preferred_choices' => array('France', 'FR'), ])
			->add('demiJournee',null,['label' => 'Demi-Journée ' , 
									  'attr' => ['class' => 'dmiJrn'],
									  ])
			->add('tarifReduit',null,['label' => 'Tarif réduit? ' , 
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
