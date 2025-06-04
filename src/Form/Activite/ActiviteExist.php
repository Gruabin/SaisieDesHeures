<?php 

namespace App\Form\Activite;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ActiviteExist extends Constraint
{
    public string $message = 'L\'activité avec l\'ID "{{ id }}" n\'existe pas.';
}
