<?php

namespace Loremweb\Bundle\TicketmanagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Loremweb\Bundle\TicketmanagerBundle\Validator\Constraints as TicketManagerAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="Loremweb\Bundle\TicketmanagerBundle\Repository\ReservationRepository")
 */
class Reservation
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateVisite", type="date")
     * @TicketManagerAssert\DateValide
     *
     */
    private $dateVisite;

    /**
     * @var string
     *
     * @ORM\Column(name="typeBillet", type="string", length=255)
     */
    private $typeBillet;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $emailConfirmation;

    /**
     * @var int
     *
     * @ORM\Column(name="nombreBillet", type="integer")
     */
    private $nombreBillet;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cgu", type="boolean")
     */
    private $cgu;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateReservation", type="datetime")
     */
    private $dateReservation;

    /**
     * @var array
     *
     * @ORM\Column(name="visiteurs", type="array")
     */
    private $visiteurs;

    /**
     * @var int
     *
     * @ORM\Column(name="prixTotal", type="integer")
     */
    private $prixTotal;

    /**
     * @var guid
     *
     * @ORM\Column(name="codeConfirmation", type="guid", unique=true)
     */
    private $codeConfirmation;


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
     * Set dateVisite
     *
     * @param \DateTime $dateVisite
     *
     * @return Reservation
     */
    public function setDateVisite($dateVisite)
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }

    /**
     * Get dateVisite
     *
     * @return \DateTime
     */
    public function getDateVisite()
    {
        return $this->dateVisite;
    }

    /**
     * Set typeBillet
     *
     * @param string $typeBillet
     *
     * @return Reservation
     */
    public function setTypeBillet($typeBillet)
    {
        $this->typeBillet = $typeBillet;

        return $this;
    }

    /**
     * Get typeBillet
     *
     * @return string
     */
    public function getTypeBillet()
    {
        return $this->typeBillet;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Reservation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nombreBillet
     *
     * @param integer $nombreBillet
     *
     * @return Reservation
     */
    public function setNombreBillet($nombreBillet)
    {
        $this->nombreBillet = $nombreBillet;

        return $this;
    }

    /**
     * Get nombreBillet
     *
     * @return int
     */
    public function getNombreBillet()
    {
        return $this->nombreBillet;
    }

    /**
     * Set dateReservation
     *
     * @param \DateTime $dateReservation
     *
     * @return Reservation
     */
    public function setDateReservation($dateReservation)
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    /**
     * Get dateReservation
     *
     * @return \DateTime
     */
    public function getDateReservation()
    {
        return $this->dateReservation;
    }

    /**
     * Set visiteurs
     *
     * @param array $visiteurs
     *
     * @return Reservation
     */
    public function setVisiteurs($visiteurs)
    {
        $this->visiteurs = $visiteurs;

        return $this;
    }

    public function addVisiteur($id, Visiteur $visiteur){
        $this->visiteurs[$id] = $visiteur;
    }

    /**
     * Get visiteurs
     *
     * @return array
     */
    public function getVisiteurs()
    {
        return $this->visiteurs;
    }

    /**
     * Set prixTotal
     *
     * @param integer $prixTotal
     *
     * @return Reservation
     */
    public function setPrixTotal($prixTotal)
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    /**
     * Get prixTotal
     *
     * @return int
     */
    public function getPrixTotal()
    {
        return $this->prixTotal;
    }

    /**
     * Set codeConfirmation
     *
     * @param
     * guid $codeConfirmation
     *
     * @return Reservation
     */
    public function setCodeConfirmation($codeConfirmation)
    {
        $this->codeConfirmation = $codeConfirmation;

        return $this;
    }

    /**
     * Get codeConfirmation
     *
     * @return guid
     */
    public function getCodeConfirmation()
    {
        return $this->codeConfirmation;
    }

    /**
     * @return boolean
     */
    public function isCgu()
    {
        return $this->cgu;
    }

    /**
     * @param boolean $cgu
     */
    public function setCgu($cgu)
    {
        $this->cgu = $cgu;
    }

    /**
     * @return string
     */
    public function getEmailConfirmation()
    {
        return $this->emailConfirmation;
    }

    /**
     * @param string $emailConfirmation
     */
    public function setEmailConfirmation($emailConfirmation)
    {
        $this->emailConfirmation = $emailConfirmation;
    }

    /**
     * Calcule le prix total selon le tarif des visiteurs et le type de billet
     * Stocke le résultat dans la variable de l'entité
     */
    public function calculePrixTotal()
    {
        $prixTotal = 0;

        if (isset($this->visiteurs)) {
            foreach ($this->visiteurs as $visiteur) {
                $prixTotal += $visiteur->getPrix();
            }
        }
        if ($this->typeBillet == "demi") {
            $prixTotal /= 2 ;
        }
        $this->prixTotal = $prixTotal;
    }


    /**
     * @param $reservation
     * @param ExecutionContextInterface $context
     * Permet de vérifier que les 2 champs mails sont identiques
     * @Assert\Callback
     */
    public function validerEmailConfirmation(ExecutionContextInterface $context)
    {
        // check if the name is actually a fake name
        if ($this->email != $this->emailConfirmation) {
            $context->buildViolation('Les 2 mails doivent être identiques')
                ->atPath('emailConfirmation')
                ->addViolation();
        }
    }

}

