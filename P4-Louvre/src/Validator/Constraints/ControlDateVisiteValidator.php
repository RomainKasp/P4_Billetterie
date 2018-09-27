<?php

namespace App\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ControlDateVisiteValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
		//Contrôle sur le mardi
        if ($value->format('w') == 2) {
            $this->context->buildViolation($constraint->message)
                          ->setParameter('{{ string }}', 'Le musée est fermer le mardi.')
                          ->addViolation();
        }
		
		$mois = $value->format('m');
		$jour = $value->format('d');
		//Contrôle jours feriées
		if ( ( ($mois == 12) AND ($jour == 25) ) OR
		     ( ($mois == 11) AND ($jour == 01) ) OR
		     ( ($mois == 04) AND ($jour == 01) )    ){
				 
            $this->context->buildViolation($constraint->message)
                          ->setParameter('{{ string }}', 'Le musée est fermer ce jour férié.')
                          ->addViolation();				 
		}
		
		
    }
	
}