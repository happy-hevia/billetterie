<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 10/11/2016
 * Time: 17:49
 */

namespace Loremweb\Bundle\TicketmanagerBundle\Form\Type;


use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Loremweb\Bundle\TicketmanagerBundle\Entity\Reservation',
            'parameters' => null,
        ));
    }

    /**
     * @param $nombreVisiteur
     * Créer un tableau associatif avec comme clé et valeur le nombre de place que l'internaute peut selectionner
     * @return array
     */
    public function choixNombreVisiteur($nombreVisiteur){
        $tableauChoix = array();

        for ($i = 1; $i <= $nombreVisiteur; $i++) {
            $tableauChoix[$i] = $i;
        }

        return $tableauChoix;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateVisite', DateType::class, array('label' => 'Date de la visite :', 'widget' => 'single_text', 'attr' => array('data-param' => json_encode($options['parameters']))))
            ->add('typeBillet', ChoiceType::class, array('label' => 'Type de billet :',
                                                        'choices' =>
                                                            array(
                                                                'Journée' => 'journee',
                                                                'Demi-journée' => 'demi',
                                                            ),
                                                        'expanded' => true,
                                                        'multiple' => false))
            ->add('nombreBillet', ChoiceType::class, array('label' => 'Nombre de billet :', 'choices' => $this->choixNombreVisiteur($options['parameters']['nb_tickets_max'])))
            ->add('email', EmailType::class, array("label" => "Mail :"))
            ->add('emailConfirmation', EmailType::class, array("label" => "Confirmer mail :"))
            ->add('cgu', CheckboxType::class, array("label" => "J'accepte les conditions générales de vente"))
            ->add('submit', SubmitType::class, array('label' => 'Passer à l\'étape suivante'));
    }
}
