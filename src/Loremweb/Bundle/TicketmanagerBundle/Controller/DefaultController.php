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
        $form = $this->get('loremweb__ticketmanager.services.gestion_tunnel_achat')->validationFormulaireReservation($request);

        if ($form === true) {
            return $this->redirectToRoute("visiteurs");
        }


        return $this->render('LoremwebTicketmanagerBundle:Default:index.html.twig',
            array('formReservation' => $form,
                'etape' => 2));
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
        Stripe::setApiKey("sk_test_gMXa70m6mNIl5MRI2bdhLLWc");

        $error = '';
        $success = '';

        try {
            if (!$request->request->get('stripeToken')) {
                throw new \Exception("The Stripe Token was not generated correctly");
            } else{
                Charge::create(array("amount" => 3000,
                    "currency" => "eur",
                    "card" => $_POST['stripeToken']));
                $success = '<div class="alert alert-success">
                <strong>Success!</strong> Your payment was successful.
				</div>';
            }

        } catch (Card $e) {
            $error = '<div class="alert alert-danger">
			  <strong>Error!</strong> ' . $e->getMessage() . '
			  </div>';
        }

        return $this->render('LoremwebTicketmanagerBundle:Default:index.html.twig', array('etape' => 4, 'succes' => $success, 'erreur' => $error));
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
