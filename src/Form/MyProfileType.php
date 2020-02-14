<?php

namespace App\Form;

use App\Entity\Member;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MyProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, ['label'=>'Pseudo ', 'required'=>true])
            ->add('name', TextType::class, ['label'=>'Prénom ', 'required'=>true])
            ->add('surname', TextType::class, ['label'=>'Nom ', 'required'=>true])
            ->add('phone', TextType::class, ['label'=>'Téléphone', 'required' => false])
            ->add('mail', EmailType::class, ['label'=>'Email ', 'required'=>true])
            ->add('site', EntityType::class, ['class' => Site::class, 'choice_label' =>'name' , 'label' => 'Site ENI'])
            ->add('save', SubmitType::class, ['label'=>'Enregistrer',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);

    }
}
