<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 20/11/2016
 * Time: 22:39
 */
namespace Loremweb\Bundle\TicketmanagerBundle\Services;

use Twig_Function_Function;
use Twig_SimpleFunction;

class TwigAjout extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('listetarif', array($this, 'listeTarif'))
        );
    }

    /**
     * @param $visiteurs
     * @return string
     * Définit une liste html des nombres de visiteur pour chaque tarif associé à leurs prix
     */
    public function listeTarif($visiteurs)
    {
        $tabTarif = [];
        $htmlTarif = "";


        if ($visiteurs !== null) {
            foreach ($visiteurs as $visiteur) {
                if (isset($tabTarif[$visiteur->getTarif()])) {
                    $tabTarif[$visiteur->getTarif()] = $tabTarif[$visiteur->getTarif()] + 1;
                } else {
                    $tabTarif[$visiteur->getTarif()] = 1;
                }
            }
        }

        foreach ($tabTarif as $tarif => $nombre) {
            $htmlTarif .= '<li class="resume-elementBillet" >' . $nombre . ' billet(s) "' . $tarif . '"</li >';
        }
            return $htmlTarif;

    }

    public function getName()
    {
        return 'twig_ajout';
    }
}
