<?php

namespace App\Form;

use App\Entity\Event;
use App\Service\DurationUnit;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new \DateTime('now');

        $builder
            ->add('name', TextType::class, ['label' => 'Nom de l\'événement ', 'required' => true])

            ->add(
                'startingDate',
                DateType::class,
                [
                    'label' => 'Date',
                    'widget' => 'single_text',
                    'required' => true,
                    'input' => 'string',
                    'data' => date_format($date,'Y-m-d'),
                ]
            )
            ->add(
                'startingTime',
                TimeType::class,
                [
                    'label' => 'Horaire',
                    'input' => 'string',
                    'widget' => 'choice',
                    'required' => true,
                    'data' =>date_format($date,'H:i:s'),
                    'minutes' => [0 , 15 , 30 , 45]
                ]
            )
            ->add(
                'duration',
                IntegerType::class,
                [
                    'label' => 'Durée',
                    'required' => true,
                    'empty_data' => 0,
                ]
            )
            ->add(
                'duration_unit',
                ChoiceType::class,
                [
                    'choices' => [
                        DurationUnit::hour() => DurationUnit::hour(),
                        DurationUnit::day() => DurationUnit::day(),
                        DurationUnit::week() => DurationUnit::week(),
                        DurationUnit::month() => DurationUnit::month(),
                    ],
                    'label' => "Unité",
                ]
            )
            ->add(
                'deadLineDate',
                DateType::class,
                [
                    'label' => 'Date limite d\'inscription',
                    'widget' => 'single_text',
                    'required' => true,
                    'input' => 'string',
                    'data' => date_format($date,'Y-m-d'),
                ]
            )
            ->add(
                'deadLineTime',
                TimeType::class,
                [
                    'label' => 'Heure limite d\'inscription',
                    'input' => 'string',
                    'widget' => 'choice',
                    'required' => true,
                    'data' =>date_format($date,'H:i:s'),
                    'minutes' => [0 , 15 , 30 , 45]
                ]
            )
            ->add(
                'nbMaxRegistration',
                IntegerType::class,
                [
                    'label' => 'Nombre maximum de participants ',
                    'required' => true,
                    'empty_data' => 0,
                ]
            )
            ->add('infos', TextType::class, ['label' => 'Informations complémentaires ', 'required' => false])
            ->add('location', LocationType::class, ['required' => true])
            ->add('save', SubmitType::class, ['label' => 'Valider',]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Event::class,
            ]
        );
    }
}
