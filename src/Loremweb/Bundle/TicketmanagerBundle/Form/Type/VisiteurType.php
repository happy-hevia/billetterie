<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 15/11/2016
 * Time: 22:14
 */

namespace Loremweb\Bundle\TicketmanagerBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisiteurType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Loremweb\Bundle\TicketmanagerBundle\Entity\Visiteur',
        ));
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, array('label' => 'Prénom :'))
            ->add('nom', TextType::class, array('label' => 'nom :'))
            ->add('pays', ChoiceType::class, array('label' => 'pays :',
                'choices' => array('France' => 'france',
                                    'Angleterre' => 'angleterre',
                                    'Brésil' => 'bresil',
                                    'Chine' => 'chine',
                                    'États-Unis' => 'etats-unis',
                                    'Italie' => 'italie',
                                    'autre' => 'autre',
                    )))
            ->add('dateNaissance', DateType::class, array('label' => 'Date de naissance :', 'widget' => 'single_text'))
            ->add('tarifReduit', CheckboxType::class, array('label' => 'tarif Réduit'))
            ->add('submit', SubmitType::class, array('label' => 'Passer à l\'étape suivante'));
    }
}
