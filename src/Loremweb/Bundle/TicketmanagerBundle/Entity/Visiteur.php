<?php

namespace Loremweb\Bundle\TicketmanagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * visiteur
 *
 * @ORM\Table(name="visiteur")
 * @ORM\Entity(repositoryClass="Loremweb\Bundle\TicketmanagerBundle\Repository\visiteurRepository")
 */
class Visiteur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Length(min = 2, max = 50)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Length(min = 2, max = 50)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date")
     * @Assert\NotNull()
     * @Assert\Date()
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     * @Assert\Type("string")
     */
    private $pays;

    /**
     * @var bool
     *
     * @ORM\Column(name="tarifReduit", type="boolean")
     * @Assert\Type("bool")
     */
    private $tarifReduit;

    /**
     * @var string
     *
     * @ORM\Column(name="tarif", type="string", length=255)
     */
    private $tarif;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="Loremweb\Bundle\TicketmanagerBundle\Entity\Reservation", inversedBy="visiteurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservation;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return visiteur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return visiteur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return visiteur
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return visiteur
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set tarifReduit
     *
     * @param boolean $tarifReduit
     *
     * @return visiteur
     */
    public function setTarifReduit($tarifReduit)
    {
        $this->tarifReduit = $tarifReduit;

        return $this;
    }

    /**
     * Get tarifReduit
     *
     * @return bool
     */
    public function getTarifReduit()
    {
        return $this->tarifReduit;
    }

    /**
     * Set tarif
     *
     * @param string $tarif
     *
     * @return visiteur
     */
    public function setTarif($tarif)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return string
     */
    public function getTarif()
    {
        return $this->tarif;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return visiteur
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @return mixed
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * @param mixed $reservation
     */
    public function setReservation($reservation)
    {
        $this->reservation = $reservation;
    }


    /**
     * @param $parameters
     * @return mixed
     * Permet de trouver le tarif et le prix qui lui est associé (il prend également en compte les réductions dut au tarif réduit
     */
    public function actualiseTarifPrix($parameters)
    {

        $maintenant = new \DateTime("now");
        $dateNaissance = $this->dateNaissance;

//        calcule l'age du visiteur en année
        $age = $dateNaissance->diff($maintenant)->y;

//        définit le tarif selon age
        foreach ($parameters['tarifs'] as $cle => $valeur) {
            if ($age >= $valeur[0] && $age <= $valeur[1]) {
                $this->tarif = $cle;
                $this->prix = $valeur[2];
            }
        }

//        définit tarif réduit si selectionné
        if ($this->tarifReduit === true) {
            $this->tarif = "reduit";
            $this->prix = $parameters['reduit'][0];
        }
    }
}

