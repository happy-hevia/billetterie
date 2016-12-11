<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 10/12/2016
 * Time: 10:26
 */

namespace tests\Loremweb\Bundle\TicketmanagerBundle\Entity;

use Loremweb\Bundle\TicketmanagerBundle\Entity\Reservation;
use Loremweb\Bundle\TicketmanagerBundle\Entity\Visiteur;


class VisiteurTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Visiteur
     */
    private $visiteur;

    public function setUp()
    {
        if ($this->visiteur === null) {
            $this->visiteur = new Visiteur();
        }

    }


    public function testSetPrenom()
    {
        $prenom = "amelie";

        // valeur valide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom($prenom));

        // valeur invalide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom(new \DateTime()));

    }

    public function testSetNom()
    {
        $prenom = "dani";

        // valeur valide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom($prenom));

        // valeur invalide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom(new \DateTime()));

    }

    public function testSetDateNaissance()
    {
        $prenom = new \DateTime();

        // valeur valide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom($prenom));

        // valeur invalide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom("fausse"));

    }

    public function testSetPays()
    {
        $prenom = "france";

        // valeur valide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom($prenom));

        // valeur invalide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom("fausse"));

    }

    public function testSetTarifReduit()
    {
        $prenom = true;

        // valeur valide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom($prenom));

        // valeur invalide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom("fausse"));

    }

    public function testSetTarif()
    {
        $prenom = "reduit";

        // valeur valide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom($prenom));

        // valeur invalide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom("fausse"));

    }

    public function testSetPrix()
    {
        $prenom = 42;

        // valeur valide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom($prenom));

        // valeur invalide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom("fausse"));

    }

    public function testSetReservation()
    {
        $prenom = new Reservation();

        // valeur valide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom($prenom));

        // valeur invalide
        $this->assertEquals($this->visiteur, $this->visiteur->setPrenom("fausse"));

    }

    public function testGetPrenom()
    {
        // valeur correct
        $prenom = "bob";
        $this->visiteur->setPrenom($prenom);
        $this->assertEquals($prenom, $this->visiteur->getPrenom());

        // valeur null
        $prenom = null;
        $this->visiteur->setPrenom($prenom);
        $this->assertEquals($prenom, $this->visiteur->getPrenom());

    }

    public function testGetNom()
    {
        // valeur correct
        $Nom = "Bertae";
        $this->visiteur->setNom($Nom);
        $this->assertEquals($Nom, $this->visiteur->getNom());

        // valeur null
        $Nom = null;
        $this->visiteur->setNom($Nom);
        $this->assertEquals($Nom, $this->visiteur->getNom());

    }

    public function testGetDateNaissance()
    {
        // valeur correct
        $DateNaissance = new \DateTime();
        $this->visiteur->setDateNaissance($DateNaissance);
        $this->assertEquals($DateNaissance, $this->visiteur->getDateNaissance());

        // valeur null
        $DateNaissance = null;
        $this->visiteur->setDateNaissance($DateNaissance);
        $this->assertEquals($DateNaissance, $this->visiteur->getDateNaissance());

    }

    public function testGetPays()
    {
        // valeur correct
        $Pays = "Espagne";
        $this->visiteur->setPays($Pays);
        $this->assertEquals($Pays, $this->visiteur->getPays());

        // valeur null
        $Pays = null;
        $this->visiteur->setPays($Pays);
        $this->assertEquals($Pays, $this->visiteur->getPays());

    }

    public function testGetTarifReduit()
    {
        // valeur correct
        $TarifReduit = true;
        $this->visiteur->setTarifReduit($TarifReduit);
        $this->assertEquals($TarifReduit, $this->visiteur->getTarifReduit());

        // valeur null
        $TarifReduit = null;
        $this->visiteur->setTarifReduit($TarifReduit);
        $this->assertEquals($TarifReduit, $this->visiteur->getTarifReduit());

    }

    public function testGetTarif()
    {
        // valeur correct
        $Tarif = "reduit";
        $this->visiteur->setTarif($Tarif);
        $this->assertEquals($Tarif, $this->visiteur->getTarif());

        // valeur null
        $Tarif = null;
        $this->visiteur->setTarif($Tarif);
        $this->assertEquals($Tarif, $this->visiteur->getTarif());

    }

    public function testGetPrix()
    {
        // valeur correct
        $Prix = 42;
        $this->visiteur->setPrix($Prix);
        $this->assertEquals($Prix, $this->visiteur->getPrix());

        // valeur null
        $Prix = null;
        $this->visiteur->setPrix($Prix);
        $this->assertEquals($Prix, $this->visiteur->getPrix());

    }

    public function testGetReservation()
    {
        // valeur correct
        $Reservation = new Reservation();
        $this->visiteur->setReservation($Reservation);
        $this->assertEquals($Reservation, $this->visiteur->getReservation());

        // valeur null
        $Reservation = null;
        $this->visiteur->setReservation($Reservation);
        $this->assertEquals($Reservation, $this->visiteur->getReservation());

    }

    public function testActualiteTarifPrix()
    {

        // simulation parameter
        $parameters = array(
            "tarifs" => array(
                "normal"=> [12, 60, 16],
                "enfant"=> [4, 12, 8],
                "senior"=> [60, 200, 12],
                "gratuit"=> [0, 4, 0]
            ),
            "reduit" => array(10)
        );


        $this->visiteur->setDatenaissance(new \DateTime('1993-09-16'));
        $this->visiteur->actualiseTarifPrix($parameters);
        $this->assertEquals(16, $this->visiteur->getPrix());

        $this->visiteur->setDatenaissance(new \DateTime('1901-09-16'));
        $this->visiteur->actualiseTarifPrix($parameters);
        $this->assertEquals(12, $this->visiteur->getPrix());

        $this->visiteur->setDatenaissance(new \DateTime('2009-09-16'));
        $this->visiteur->actualiseTarifPrix($parameters);
        $this->assertEquals(8, $this->visiteur->getPrix());

        $this->visiteur->setDatenaissance(new \DateTime('2015-09-16'));
        $this->visiteur->actualiseTarifPrix($parameters);
        $this->assertEquals(0, $this->visiteur->getPrix());

        $this->visiteur->setDatenaissance(new \DateTime('2015-09-16'));
        $this->visiteur->setTarifReduit(true);
        $this->visiteur->actualiseTarifPrix($parameters);
        $this->assertEquals(10, $this->visiteur->getPrix());
    }


}
