<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
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
            ->add('name',TextType::class, ['label'=>'Nom de l\'événement : ', 'required'=>true])
            ->add('startingDateTime', DateType::class,['label'=>'Date de début : ','required'=>true])
            ->add('duration', NumberType::class,['label'=>'Durée :','required'=>true])
            ->add('inscriptionDeadLine',DateType::class,['label'=>'Date de début : ','required'=>true])
            ->add('nbMaxRegistration', NumberType::class,['label'=>'Durée :','required'=>true])
            ->add('infos', TextType::class, ['label'=>'Nom de l\'événement : ', 'required'=>true])
            ->add('location', TextType::class, ['label'=>'Nom de l\'événement : ', 'required'=>true])
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
