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
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Templating\TemplatingExtension;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class GestionTunnelAchat
{
    private $entityManager;
    private $formFactory;
    private $session;
    private $parameters;
    private $mailer;
    private $templating;

    public function __construct(EntityManager $entityManager, FormFactory $formFactory, Session $session, $parameters, \Swift_Mailer $mailer, TwigEngine $templating)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->parameters = $parameters;
        $this->mailer = $mailer;
        $this->templating = $templating;
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

//        récupère les données de reservation de la session si il y en a
        if ($this->session->get('reservation')) {
            $reservation = $this->session->get('reservation');
        } else {
            $reservation = new Reservation();
        }

//        Création du formulaire
        $form = $this->formFactory->create(ReservationType::class, $reservation, array(
            'parameters' => $this->parameters
        ));

//        Gestion du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->session->set('reservation', $reservation);
            $this->session->get('reservation')->calculePrixTotal();

//            Créer ou actualise la reservation dans la bdd
            if($this->session->get('reservation')->getId() && $reservationBdd = $this->entityManager->getRepository('LoremwebTicketmanagerBundle:Reservation')->find($this->session->get('reservation')->getId())){
                $reservationBdd->setNombreBillet($this->session->get('reservation')->getNombreBillet());
                $this->entityManager->flush();
            } else {
                $this->entityManager->persist($this->session->get('reservation'));
                $this->entityManager->flush();
            }


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
        if ($this->session->get('reservation') !== null && isset($this->session->get('reservation')->getVisiteurs()[$numero])) {
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

    public function gestionValidationPaiement($request)
    {

        Stripe::setApiKey("sk_test_gMXa70m6mNIl5MRI2bdhLLWc");

        $message = '';

//        si le paiement a été traité par stripe
        if ($request->request->get('stripeToken')) {

            try {
//                Essaye de débiter l'internaute
                Charge::create(array(
                    "amount" => ( $this->session->get('reservation')->getPrixTotal() * 100 ),
                    "currency" => "eur",
                    "card" => $request->request->get('stripeToken')));
                $message = '<div class="alert alert-success">
                <strong>Félicitation !</strong> Votre paiement a été accepté.</div>';

            } catch (Card $e) {
//                En cas de problème créer le message d'erreur
                $message = '<div class="alert alert-danger">
			  <strong>Erreur ! </strong> ' . $e->getMessage() . '. Merci de réessayer ultérierement ou de contacter le support.
			  </div>';

                return $message;
            }

//            Envoie un mail de confirmation avec ticket
            $message = \Swift_Message::newInstance()
                ->setSubject("Billets de reservation du musée du Louvre")
                ->setFrom('happyhevia@gmail.com')
                ->setTo($this->session->get('reservation')->getEmail())
                ->setBody($this->templating->renderResponse('@LoremwebTicketmanager/mail/mail.html.twig')->getContent(), 'text/html');

            $this->mailer->send($message);

            //            Créer ou actualise la reservation dans la bdd
            if($this->session->get('reservation')->getId() && $reservationBdd = $this->entityManager->getRepository('LoremwebTicketmanagerBundle:Reservation')->find($this->session->get('reservation')->getId())){
                $reservationBdd->setValide(true);
                $this->entityManager->flush();
            }
//              Vide le cache
            $this->session->clear();

            return "redirect";
        }


        return $message;
    }

}
