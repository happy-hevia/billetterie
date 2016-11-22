<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 10/11/2016
 * Time: 18:05
 */

namespace Loremweb\Bundle\TicketmanagerBundle\Services;


use DateTime;
use Doctrine\ORM\EntityManager;
use Loremweb\Bundle\TicketmanagerBundle\Entity\Reservation;
use Loremweb\Bundle\TicketmanagerBundle\Entity\Visiteur;
use Loremweb\Bundle\TicketmanagerBundle\Form\Type\ReservationType;
use Loremweb\Bundle\TicketmanagerBundle\Form\Type\VisiteurType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class GestionTunnelAchat
{
    private $entityManager;
    private $formFactory;
    private $session;
    private $parameters;

    public function __construct(EntityManager $entityManager, FormFactory $formFactory, Session $session, $parameters)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->parameters = $parameters;
    }

    /**
     * @param $request
     * @return \Symfony\Component\Form\FormView
     * Gère la page de réservation
     * renvoie vrai si le formulaire est soumis et valide
     *         le formulaire si il ne l'ai pas
     *
     */
    public function validationFormulaireReservation($request)
    {

        if ($this->session->get('reservation')) {
            $reservation = $this->session->get('reservation');
        } else {
            $reservation = new Reservation();
        }

        $form = $this->formFactory->create(ReservationType::class, $reservation, array(
            'parameters' => $this->parameters
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->session->set('reservation', $reservation);
            $this->session->get('reservation')->calculePrixTotal();
            return true;
        }

        return $form->createView();
    }

    /**
     * @param $request
     * @return \Symfony\Component\Form\FormView
     * Gère la page des visiteurs
     * renvoie vrai si le formulaire est soumis et valide
     *         le formulaire si il ne l'ai pas
     *
     */
    public function validationFormulaireVisiteur($request, $numero)
    {
        if ( $this->session->get('reservation') !== null && isset($this->session->get('reservation')->getVisiteurs()[$numero])) {
            $visiteur = $this->session->get('reservation')->getVisiteurs()[$numero];
        } else {
            $visiteur = new visiteur();
        }
        $form = $this->formFactory->create(VisiteurType::class, $visiteur, array());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->session->get('reservation')->addVisiteur($numero, $visiteur);
            $this->session->get('reservation')->getVisiteurs()[$numero]->actualiseTarifPrix($this->parameters);
            $this->session->get('reservation')->calculePrixTotal();
            return true;
        }
        return $form->createView();
    }

}
