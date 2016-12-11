<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 10/12/2016
 * Time: 21:11
 */

namespace tests\Loremweb\Bundle\TicketmanagerBundle\Entity;


use Exception;
use InvalidArgumentException;
use Loremweb\Bundle\TicketmanagerBundle\Entity\Reservation;
use Loremweb\Bundle\TicketmanagerBundle\Entity\Visiteur;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ReservationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Reservation
     */
    private $reservation;

    public function setUp()
    {
        if ($this->reservation === null) {
            $this->reservation = new Reservation();
        }

    }

    public function testsetDateVisite()
    {
        $DateVisite = new \DateTime();

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->setDateVisite($DateVisite));

        // valeur invalide
        $this->assertEquals($this->reservation, $this->reservation->setDateVisite("invalide"));
    }

    public function testsetTypeBillet()
    {
        $TypeBillet = "demi";

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->setTypeBillet($TypeBillet));

        // valeur invalide
        $this->assertEquals($this->reservation, $this->reservation->setTypeBillet("invalide"));
    }

    public function testsetEmail()
    {
        $Email = "jaez@hotms.fr";

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->setEmail($Email));

        // valeur invalide
        $this->assertEquals($this->reservation, $this->reservation->setEmail("invalide"));
    }

    public function testsetNombreBillet()
    {
        $NombreBillet = 15;

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->setNombreBillet($NombreBillet));

        // valeur invalide
        $this->assertEquals($this->reservation, $this->reservation->setNombreBillet("invalide"));
    }

    public function testsetDateReservation()
    {
        $DateReservation = new \DateTime();

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->setDateReservation($DateReservation));

        // valeur invalide
        $this->assertEquals($this->reservation, $this->reservation->setDateReservation("invalide"));
    }

    public function testsetVisiteurs()
    {
        $Visiteurs = array(new Visiteur(), new Visiteur());

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->setVisiteurs($Visiteurs));

        // valeur invalide
        $this->assertEquals($this->reservation, $this->reservation->setVisiteurs("invalide"));
    }

    public function testaddVisiteur()
    {
        $Visiteur = new Visiteur();

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->addVisiteur(1, $Visiteur));

    }

    public function testsetPrixTotal()
    {
        $PrixTotal = 45;

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->setPrixTotal($PrixTotal));

        // valeur invalide
        $this->assertEquals($this->reservation, $this->reservation->setPrixTotal("invalide"));
    }

    public function testsetCodeConfirmation()
    {
        $CodeConfirmation = "sdqlkjfz5f5zef75ze7f567ze";

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->setCodeConfirmation($CodeConfirmation));

        // valeur invalide
        $this->assertEquals($this->reservation, $this->reservation->setCodeConfirmation("invalide"));
    }

    public function testsetCgu()
    {
        $Cgu = true;

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->setCgu($Cgu));

        // valeur invalide
        $this->assertEquals($this->reservation, $this->reservation->setCgu("invalide"));
    }

    public function testsetEmailConfirmation()
    {
        $EmailConfirmation = "happyhevia@slkdqfj.com";

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->setEmailConfirmation($EmailConfirmation));

        // valeur invalide
        $this->assertEquals($this->reservation, $this->reservation->setEmailConfirmation("invalide"));
    }

    public function testsetValide()
    {
        $Valide = true;

        // valeur valide
        $this->assertEquals($this->reservation, $this->reservation->setValide($Valide));

        // valeur invalide
        $this->assertEquals($this->reservation, $this->reservation->setValide("invalide"));
    }

    public function testgetValide()
    {
        // valeur correct
        $Valide = true;
        $this->reservation->setValide($Valide);
        $this->assertEquals($Valide, $this->reservation->getValide());
        
        // valeur null
        $Valide = null;
        $this->reservation->setValide($Valide);
        $this->assertEquals($Valide, $this->reservation->getValide());;
    }



    public function testgetDateVisite()
    {
        // valeur correct
        $DateVisite = new \DateTime();
        $this->reservation->setDateVisite($DateVisite);
        $this->assertEquals($DateVisite, $this->reservation->getDateVisite());

        // valeur null
        $DateVisite = null;
        $this->reservation->setDateVisite($DateVisite);
        $this->assertEquals($DateVisite, $this->reservation->getDateVisite());
    }

    public function testgetTypeBillet()
    {
        // valeur correct
        $TypeBillet = "senior";
        $this->reservation->setTypeBillet($TypeBillet);
        $this->assertEquals($TypeBillet, $this->reservation->getTypeBillet());

        // valeur null
        $TypeBillet = null;
        $this->reservation->setTypeBillet($TypeBillet);
        $this->assertEquals($TypeBillet, $this->reservation->getTypeBillet());
    }

    public function testgetEmail()
    {
        // valeur correct
        $Email = "qsdkfljqq@sdf.fd";
        $this->reservation->setEmail($Email);
        $this->assertEquals($Email, $this->reservation->getEmail());

        // valeur null
        $Email = null;
        $this->reservation->setEmail($Email);
        $this->assertEquals($Email, $this->reservation->getEmail());
    }

    public function testgetNombreBillet()
    {
        // valeur correct
        $NombreBillet = 15;
        $this->reservation->setNombreBillet($NombreBillet);
        $this->assertEquals($NombreBillet, $this->reservation->getNombreBillet());

        // valeur null
        $NombreBillet = null;
        $this->reservation->setNombreBillet($NombreBillet);
        $this->assertEquals($NombreBillet, $this->reservation->getNombreBillet());
    }

    public function testgetDateReservation()
    {
        // valeur correct
        $DateReservation = new DateTime();
        $this->reservation->setDateReservation($DateReservation);
        $this->assertEquals($DateReservation, $this->reservation->getDateReservation());

        // valeur null
        $DateReservation = null;
        $this->reservation->setDateReservation($DateReservation);
        $this->assertEquals($DateReservation, $this->reservation->getDateReservation());
    }

    public function testgetVisiteurs()
    {
        // valeur correct
        $Visiteurs = array(new Visiteur(), new Visiteur());
        $this->reservation->setVisiteurs($Visiteurs);
        $this->assertEquals($Visiteurs, $this->reservation->getVisiteurs());

        // valeur null
        $Visiteurs = null;
        $this->reservation->setVisiteurs($Visiteurs);
        $this->assertEquals($Visiteurs, $this->reservation->getVisiteurs());
    }

    public function testgetPrixTotal()
    {
        // valeur correct
        $PrixTotal = 54;
        $this->reservation->setPrixTotal($PrixTotal);
        $this->assertEquals($PrixTotal, $this->reservation->getPrixTotal());

        // valeur null
        $PrixTotal = null;
        $this->reservation->setPrixTotal($PrixTotal);
        $this->assertEquals($PrixTotal, $this->reservation->getPrixTotal());
    }

    public function testgetCodeConfirmation()
    {
        // valeur correct
        $CodeConfirmation = "loijuoi5646d5d45d";
        $this->reservation->setCodeConfirmation($CodeConfirmation);
        $this->assertEquals($CodeConfirmation, $this->reservation->getCodeConfirmation());

        // valeur null
        $CodeConfirmation = null;
        $this->reservation->setCodeConfirmation($CodeConfirmation);
        $this->assertEquals($CodeConfirmation, $this->reservation->getCodeConfirmation());
    }

    public function testisCgu()
    {
        // valeur correct
        $Cgu = true;
        $this->reservation->setCgu($Cgu);
        $this->assertEquals($Cgu, $this->reservation->isCgu());

        // valeur null
        $Cgu = null;
        $this->reservation->setCgu($Cgu);
        $this->assertEquals($Cgu, $this->reservation->isCgu());
    }

    public function testgetEmailConfirmation()
    {
        // valeur correct
        $EmailConfirmation = "lqskjfe@gmail.fe";
        $this->reservation->setEmailConfirmation($EmailConfirmation);
        $this->assertEquals($EmailConfirmation, $this->reservation->getEmailConfirmation());

        // valeur null
        $EmailConfirmation = null;
        $this->reservation->setEmailConfirmation($EmailConfirmation);
        $this->assertEquals($EmailConfirmation, $this->reservation->getEmailConfirmation());
    }



    public function testcalculePrixTotal()
    {
        $visiteur1 = new Visiteur();
        $visiteur1->setPrix(42);

        $visiteur2 = new Visiteur();
        $visiteur2->setPrix(11);

        $this->reservation->addVisiteur(1, $visiteur1);
        $this->reservation->addVisiteur(2, $visiteur2);

        $this->reservation->calculePrixTotal();

        $this->assertEquals(53, $this->reservation->getPrixTotal());

        $visiteur1->setPrix(543);
        $visiteur2->setPrix(158);

        $this->reservation->setTypeBillet("demi");
        $this->reservation->calculePrixTotal();

        $this->assertEquals(350.5, $this->reservation->getPrixTotal());

        $visiteur1->setPrix(11);
        $visiteur2->setPrix(0);

        $this->reservation->calculePrixTotal();

        $this->assertEquals(5.5, $this->reservation->getPrixTotal());

        $visiteur1->setPrix(11);
        $visiteur2->setPrix(null);

        $this->reservation->calculePrixTotal();

        $this->assertEquals(5.5, $this->reservation->getPrixTotal());

    }

    public function testvaliderEmailConfirmation()
    {
        $this->reservation->setEmail("test@gmail.com");
        $this->reservation->setEmailConfirmation("test@gmail.com");

        $context = $this->createMock(ExecutionContextInterface::class);

        $this->assertEquals(true, $this->reservation->validerEmailConfirmation($context));

        $this->reservation->setEmail("test@gmail.com");
        $this->reservation->setEmailConfirmation("azer@gmail.com");

    }

    public function testactualiseDateReservation()
    {
        $this->reservation->actualiseDateReservation();

        $this->assertNotNull($this->reservation->getDateReservation());
        $this->assertTrue($this->reservation->getDateReservation() instanceof \DateTime );
    }

    public function testactualiseCodeConfirmation()
    {
        $this->reservation->actualiseCodeConfirmation();

        $this->assertNotNull($this->reservation->getCodeConfirmation());
        $this->assertGreaterThan(0, strlen($this->reservation->getCodeConfirmation()));
    }
}
