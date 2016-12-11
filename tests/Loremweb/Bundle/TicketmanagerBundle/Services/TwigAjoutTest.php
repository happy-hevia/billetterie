<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 07/12/2016
 * Time: 18:14
 */

namespace tests\Loremweb\Bundle\TicketmanagerBundle\Services;
use Loremweb\Bundle\TicketmanagerBundle\Entity\Reservation;
use Loremweb\Bundle\TicketmanagerBundle\Entity\Visiteur;
use Loremweb\Bundle\TicketmanagerBundle\Services\TwigAjout;


class TwigAjoutTest extends \PHPUnit_Framework_TestCase
{

    public function testListeTarif()
    {
        $twigAjout = new TwigAjout();

//        Chaine vide lorsque que le paramètre est null
        $this->assertEquals("", $twigAjout->listeTarif(null));

        $reservation = new Reservation();

        $visiteur = new Visiteur();
        $visiteur->setTarif('reduit');
        $visiteur2 = new Visiteur();
        $visiteur2->setTarif('senior');
        $visiteur3 = new Visiteur();
        $visiteur3->setTarif('normal');

        $reservation->addVisiteur(1, $visiteur);
        $reservation->addVisiteur(2, $visiteur2);
        $reservation->addVisiteur(3, $visiteur3);



//        Vérifie qu'il y a le bon nombre d'élément de liste
        $this->assertEquals(3, substr_count($twigAjout->listeTarif($reservation->getVisiteurs()), '<li class="resume-elementBillet" >'));
    }
}
