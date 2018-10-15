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
                          ->setParameter('{{ string }}', 'Le musée est fermé le mardi.')
                          ->addViolation();
        }
		
		$mois = $value->format('m');
		$jour = $value->format('d');


		//Contrôle jours feriées
		$year = intval(date('Y'));
 
		$easterDate  = easter_date($year);
		$easterDay   = date('j', $easterDate);
		$easterMonth = date('n', $easterDate);
		$easterYear  = date('Y', $easterDate);
		
		$feries = array(
			// Dates fixes
			mktime(0, 0, 0, 1,  1,  $year),  // 1er janvier
			mktime(0, 0, 0, 5,  1,  $year),  // Fête du travail
			mktime(0, 0, 0, 5,  8,  $year),  // Victoire des alliés
			mktime(0, 0, 0, 7,  14, $year),  // Fête nationale
			mktime(0, 0, 0, 8,  15, $year),  // Assomption
			mktime(0, 0, 0, 11, 1,  $year),  // Toussaint
			mktime(0, 0, 0, 11, 11, $year),  // Armistice
			mktime(0, 0, 0, 12, 25, $year),  // Noel
		
			// Dates variables
			mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $easterYear),
			mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear),
			mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear),
		);
		
		foreach ($feries as $test) {
			
			if ( date("M-d-Y", $test) ==  $value){ 			 
				$this->context->buildViolation($constraint->message)
                    ->setParameter('{{ string }}', 'Le musée est fermé ce jour férié.')
                    ->addViolation();				 
			}
		}		
    }
	
}