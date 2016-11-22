<?php

namespace Loremweb\Bundle\TicketmanagerBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DateValide extends Constraint
{
    public $message = "La date de visite selectionnée n'est pas disponible, merci de choisir une autre date";
}
