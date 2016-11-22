<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 19/11/2016
 * Time: 14:21
 */

namespace Loremweb\Bundle\TicketmanagerBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateValideValidator extends ConstraintValidator
{

    private $parameters;


    /**
     * DateValideValidator constructor.
     * @param $parameters
     */
    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }


    /**
     * @param mixed $dateVisite
     * @param Constraint $constraint
     */
    public function validate($dateVisite, Constraint $constraint)
    {
        $dateCourrante = new \DateTime();

        if ($this->parameters['date']['past_dates'] == false) {
            // Vérifie que la date de visite selectionné n'est pas dans le passé
            if ($dateVisite->format('Y-m-d') < $dateCourrante->format('Y-m-d')) {
                $this->context->buildViolation('Vous ne pouvez pas reserver pour une date antérieur')
                    ->atPath('dateVisite')
                    ->addViolation();
            }
        }

        // Vérifie que la date ne correspond pas à la date d'un jour fermé
        $dateVisiteJourMois = $dateVisite->format('m-d');
        if (in_array($dateVisiteJourMois, $this->parameters['date']['date_closed'])) {
            $this->context->buildViolation('Le musée est fermé ce jour ci, merci de choisir une autre date')
                ->atPath('dateVisite')
                ->addViolation();
        }

        // Vérifie que la date ne correspond pas à la date d'un jour fermé
        $dateVisiteJourSemaine = $dateVisite->format('w');
        if (in_array($dateVisiteJourSemaine, $this->parameters['date']['days_closed'])) {
            $this->context->buildViolation('Le musée est fermé ce jour de la semaine, merci de choisir une autre date')
                ->atPath('dateVisite')
                ->addViolation();
        }

        // Vérifie que la date ne dépasse pas la date limite de reservation
        $dateLimiteReservation = $dateCourrante->add(new \DateInterval("P365D"));
        if ($dateVisite > $dateLimiteReservation) {
            $this->context->buildViolation('La réservation ne peux pas se faire plus d\'un an à l\'avance')
                ->atPath('dateVisite')
                ->addViolation();
        }
    }
}
