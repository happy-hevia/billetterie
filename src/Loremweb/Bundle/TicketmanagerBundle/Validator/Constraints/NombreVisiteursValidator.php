<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 19/11/2016
 * Time: 14:21
 */

namespace Loremweb\Bundle\TicketmanagerBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Loremweb\Bundle\TicketmanagerBundle\Entity\Reservation;
use Loremweb\Bundle\TicketmanagerBundle\Entity\Visiteur;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NombreVisiteursValidator extends ConstraintValidator
{

    private $session;
    private $em;


    /**
     * DateValideValidator constructor.
     * @param Visiteur $visiteur
     * @param Session $session
     * @param EntityManager $entityManager
     * @internal param $parameters
     */
    public function __construct(Session $session, EntityManager $entityManager)
    {
        $this->session = $session;
        $this->em = $entityManager;
    }


    /**
     * @param mixed $reservation
     * @param Constraint $constraint
     * @internal param mixed $dateVisite
     */
    public function validate($reservation, Constraint $constraint)
    {

//        Si le nombre de visiteurs demandé n'est pas supérieur au nombre de place restante pour le jour selectionné
        $nombreDePlaceRestante = $this->em->getRepository('LoremwebTicketmanagerBundle:Reservation')->getPlacesRestantesSelonDate($reservation->getDateVisite());



    }
}
