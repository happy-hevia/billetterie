<?php

namespace Loremweb\Bundle\TicketmanagerBundle\Controller;

use Stripe\Charge;
use Stripe\Error\Card;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Stripe\Stripe;


class DefaultController extends Controller
{

    /**
     * @route("/", name="homepage")
     */
    public function accueilAction()
    {
        // Script qui active le livereload
// @TODO À retirer avant la mise en production
        echo "<script src=\"//localhost:35729/livereload.js\"></script>" . "\n";
        return $this->render('LoremwebTicketmanagerBundle:Default:index.html.twig', array('etape' => 1));
    }

    /**
     * @Route("/tarifs", name = "tarifs")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tarifsAction(Request $request)
    {


        return $this->render('LoremwebTicketmanagerBundle:Default:index.html.twig', array('etape' => 1));
    }

    /**
     * @Route("/reservation", name = "reservation")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reservationAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->get('loremweb__ticketmanager.services.gestion_tunnel_achat')->validationFormulaireReservation($request);
        $nombreVisiteursSelonDates = $em->getRepository('LoremwebTicketmanagerBundle:Reservation')->getPlacesRestantesSelonDates();

        if ($form === true) {
            return $this->redirectToRoute("visiteurs");
        }


        return $this->render('LoremwebTicketmanagerBundle:Default:index.html.twig',
            array('formReservation' => $form,
                'etape' => 2,
                'nombreVisiteursSelonDates' => $nombreVisiteursSelonDates));
    }

    /**
     * @Route("/visiteurs/{numero}", name = "visiteurs")
     * @param Request $request
     * @param int $numero
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function visiteursAction(Request $request, $numero = 1)
    {
        $form = $this->get('loremweb__ticketmanager.services.gestion_tunnel_achat')->validationFormulaireVisiteur($request, $numero);

        if ($form === true) {
            //            si il n'y a pas de formulaire suivant, on redirige vers la page de paiement
//            return $this->redirectToRoute("paiement");
            if ($this->get('session')->get('reservation')->getNombreBillet() == $numero) {
                return $this->redirectToRoute('paiement');
            }

//            si le formulaire est correcte alors on redirige au formulaire du visiteur suivant
            return $this->redirectToRoute('visiteurs', array('numero' => $numero + 1));

        }


        return $this->render('LoremwebTicketmanagerBundle:Default:index.html.twig', array('formVisiteur' => $form, 'etape' => 3, 'numero' => $numero));
    }

    /**
     * @Route("/paiement", name = "paiement")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paiementAction(Request $request)
    {
        $message = $this->get('loremweb__ticketmanager.services.gestion_tunnel_achat')->gestionValidationPaiement($request);

        if ($message === "redirect") {
            return $this->redirectToRoute('tarifs');
        }


        return $this->render('LoremwebTicketmanagerBundle:Default:index.html.twig', array('etape' => 4, 'message' => $message));
    }

    /**
     * @Route("/mentions", name = "mentions")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mentionsAction(Request $request)
    {

        return $this->render('LoremwebTicketmanagerBundle:Default:mentions.html.twig', array('etape' => 4));
    }

    /**
     * @Route("/cgv", name = "cgv")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgvAction(Request $request)
    {



        return $this->render('LoremwebTicketmanagerBundle:Default:cgv.html.twig', array('etape' => 4));
    }
}
