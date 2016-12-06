<?php

namespace Loremweb\Bundle\TicketmanagerBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NombreVisiteursValide extends Constraint
{
    public $message = "Il n'y a pas assez de place pour le jour selectionné";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
