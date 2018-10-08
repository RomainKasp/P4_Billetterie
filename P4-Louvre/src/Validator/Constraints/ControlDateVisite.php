<?php
namespace App\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 * @package App\Validator\Constraints
 */
class ControlDateVisite extends Constraint
{
    public $message = 'erreur.dateVisite';
}