<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, ['label'=>'Nom de l\'événement ', 'required'=>true])
            ->add('startingDateTime', DateType::class, [
                'label'=> 'Date de début ',
                'required'=> true,
                'model_timezone' => 'Europe/Paris'
            ])
            ->add('duration', NumberType::class,['label'=>'Durée','required'=>true])
            ->add('duration_unit', ChoiceType::class, [
                'choices' => [
                    'heure' => 'heures',
                    'jour' => 'jours',
                    'semaine' => 'semaines',
                    'mois' => 'mois',
                ],
                'mapped' => false,
                'label' => "Unité"
            ])
            ->add('inscriptionDeadLine',DateType::class,['label'=>'Date limite d\'inscription ','required'=>true])
            ->add('nbMaxRegistration', NumberType::class,['label'=>'Nombre maximum de participants ','required'=>true])
            ->add('infos', TextType::class, ['label'=>'Informations complémentaire ', 'required'=>true])
            ->add('location', LocationType::class, ['required'=>true])
            ->add('save', SubmitType::class, ['label'=>'Ajouter',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
