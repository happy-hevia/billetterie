<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 11/12/2016
 * Time: 14:28
 */

namespace tests\Loremweb\Bundle\TicketmanagerBundle\Controller;


use Loremweb\Bundle\TicketmanagerBundle\Entity\Reservation;
use Loremweb\Bundle\TicketmanagerBundle\Entity\Visiteur;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultControllerTest extends WebTestCase
{
    private $client;

    protected function setUp()
    {
        $client = static::createClient();
        $this->client = $client;
    }


    public function testTarif()
    {

        // accède à la page tarif
        $crawler = $this->client->request('GET', '/tarifs');

        // succès
        $this->assertTrue($this->client->getResponse()->isSuccessful());

//        encart résumé
        $this->assertEquals(
            1,
            $crawler->filter('div.panel-info')->count()
        );

        $this->assertEquals(
            1,
            $crawler->filter('div.panel-info:contains("Aucune réservation en cours")')->count()
        );




//        Barre de navigation
        $this->assertEquals(
            4,
            $crawler->filter('a.ongletsNavigation-lien')->count()
        );


        $this->assertEquals(
            2,
            $crawler->filter('[data-url]')->count()
        );

//        lien fonctionne

        $link = $crawler->filter('a[href="/mentions"]')->link();
        $crawler = $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $link = $crawler->filter('a[href="/cgv"]')->link();
        $crawler = $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isSuccessful());


    }

    public function testReservation()
    {

        // donnée de test
        $session = $this->client->getContainer()->get('session');
        $reservation = new Reservation();

        $reservation->setEmail("test@test.test");
        $reservation->setDateVisite(new \DateTime('2016-12-27'));

        $session->set('reservation', $reservation);


//        accès à la page reservation
        $crawler = $this->client->request('GET', '/reservation');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

//        encart résumé
        $this->assertEquals(
            1,
            $crawler->filter('div.panel-info')->count()
        );




//        Barre de navigation
        $this->assertEquals(
            4,
            $crawler->filter('a.ongletsNavigation-lien')->count()
        );


        $this->assertEquals(
            2,
            $crawler->filter('[data-url]')->count()
        );

//        formulaire
        $this->assertEquals(
            7,
            $crawler->filter('input')->count()
        );


//        Champ prérempli


        $this->assertEquals('test@test.test', $crawler->filter('#reservation_email')->extract(array('value'))[0]);

        $this->assertEquals('2016-12-27', $crawler->filter('#reservation_dateVisite')->extract(array('value'))[0]);



//        lien fonctionne

        $link = $crawler->filter('a[href="/mentions"]')->link();
        $crawler = $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $link = $crawler->filter('a[href="/cgv"]')->link();
        $crawler = $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isSuccessful());


    }

    public function testVisiteurs()
    {

//        donnée de test
        $session = $this->client->getContainer()->get('session');
        $reservation = new Reservation();

        $visiteur = new Visiteur();
        $visiteur->setDateNaissance(new \DateTime('1993-11-15'));
        $visiteur->setPrenom("bob");

        $reservation->addVisiteur(1, $visiteur);
        $reservation->setNombreBillet(5);
        $session->set('reservation', $reservation);


//        accède à la page visiteur
        $crawler = $this->client->request('GET', '/visiteurs');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        //        encart résumé
        $this->assertEquals(
            1,
            $crawler->filter('div.panel-info')->count()
        );




        //        Barre de navigation
        $this->assertEquals(
            4,
            $crawler->filter('a.ongletsNavigation-lien')->count()
        );


        $this->assertEquals(
            3,
            $crawler->filter('[data-url]')->count()
        );

        //        formulaire
        $this->assertEquals(
            5,
            $crawler->filter('input')->count()
        );


        //        Champ prérempli


        $this->assertEquals('bob', $crawler->filter('#visiteur_prenom')->extract(array('value'))[0]);

        $this->assertEquals('1993-11-15', $crawler->filter('#visiteur_dateNaissance')->extract(array('value'))[0]);



        //        lien fonctionne

        $link = $crawler->filter('a[href="/mentions"]')->link();
        $crawler = $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $link = $crawler->filter('a[href="/cgv"]')->link();
        $crawler = $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isSuccessful());


    }

    public function testPaiement()
    {

//        donnée de test
        $session = $this->client->getContainer()->get('session');
        $reservation = new Reservation();

        $visiteur = new Visiteur();
        $visiteur->setDateNaissance(new \DateTime('1993-11-15'));
        $visiteur->setPrenom("bob");

        $reservation->addVisiteur(1, $visiteur);
        $reservation->setNombreBillet(5);
        $session->set('reservation', $reservation);


//        accès à la page de paiement
        $crawler = $this->client->request('GET', '/paiement');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        //        encart résumé
        $this->assertEquals(
            1,
            $crawler->filter('div.panel-info')->count()
        );




        //        Barre de navigation
        $this->assertEquals(
            4,
            $crawler->filter('a.ongletsNavigation-lien')->count()
        );


        $this->assertEquals(
            4,
            $crawler->filter('[data-url]')->count()
        );

        //        formulaire
        $this->assertEquals(
            1,
            $crawler->filter("[src='https://checkout.stripe.com/checkout.js']")->count()
        );



        //        lien fonctionne

        $link = $crawler->filter('a[href="/mentions"]')->link();
        $crawler = $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $link = $crawler->filter('a[href="/cgv"]')->link();
        $crawler = $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isSuccessful());


    }

    public function testStripe()
    {
        Stripe::setApiKey("sk_test_gMXa70m6mNIl5MRI2bdhLLWc");

        try {

            // Test valide : 	Visa

            $token = \Stripe\Token::create(array("card" => array("number" => "4242424242424242", "exp_month" => 1, "exp_year" => 2019, "cvc" => "314")));


            Charge::create(array("amount" => 1000, "currency" => "eur", "card" => $token));

        } catch (Card $e) {
        $this->assertNotNull($e->getStripeCode());
    }
        try {

            // Test valide : Mastercard

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "5555555555554444",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));

        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }
        try {
            // Test valide : American Express

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "378282246310005",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));

        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }
        
        try {
            // Test valide : France (FR)	Visa

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000002500000003",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));

        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }
        try {
            // Test valide :Italy (IT)	Visa

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000003800000008",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));

        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }
        try {
            // Test valide :	Spain (ES)	Visa

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000007240000007",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));

        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }
        try {
            // Test valide :United Kingdom (GB)	Visa

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000008260000000",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));

        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }
        try {
        // Test valide :Charge succeeds and funds will be added directly to your available balance (bypassing your pending balance).

        $token = \Stripe\Token::create(array(
            "card" => array(
                "number" => "4000000000000077",
                "exp_month" => 1,
                "exp_year" => 2019,
                "cvc" => "314"
            )
        ));
        Charge::create(array(
            "amount" => 1000,
            "currency" => "eur",
            "card" => $token));



    } catch (Card $e) {
        $this->assertNotNull($e->getStripeCode());
    }
        
        /* Carte ou paiement invalide */



        try {
            // The address_line1_check and address_zip_check verifications fail. If your account is blocking payments that fail postal code validation, the charge is declined.

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000000000000010",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));



        } catch (Card $e) {
            $this->assertNull($e->getStripeCode());
        }

        try {
            // Charge succeeds but the address_line1_check verification fails.

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000000000000028",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));



        } catch (Card $e) {
            $this->assertNull($e->getStripeCode());
        }

        try {
            // Charge is declined with a card_declined code.

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000000000000002",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));



        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }

        try {
            // Charge is declined with a card_declined code and a fraudulent reason.


            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4100000000000019",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));



        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }

        try {
            // Charge is declined with an incorrect_cvc code.


            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000000000000127",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));



        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }

        try {
            // Charge is declined with an expired_card code.


            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000000000000069",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));



        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }

        try {
            // invalid_expiry_month

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000000000000010",
                    "exp_month" => 121,
                    "exp_year" => 2019,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));



        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }

        try {
            // invalid_expiry_year

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000000000000010",
                    "exp_month" => 1,
                    "exp_year" => 1944,
                    "cvc" => "314"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));



        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }

        try {
            // invalid_cvc

            $token = \Stripe\Token::create(array(
                "card" => array(
                    "number" => "4000000000000010",
                    "exp_month" => 1,
                    "exp_year" => 2019,
                    "cvc" => "4"
                )
            ));
            Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "card" => $token));



        } catch (Card $e) {
            $this->assertNotNull($e->getStripeCode());
        }


    }

}
