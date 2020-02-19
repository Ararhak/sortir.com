<?php

namespace App\Form;

use App\Entity\Member;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Prénom',
                ]
            )
            ->add(
                'surname',
                TextType::class,
                [
                    'label' => 'Nom',
                ]
            )
            ->add(
                'mail',
                EmailType::class,
                [
                    'label' => 'Email',
                ]
            )
            ->add(
                'isAdmin',
                CheckboxType::class,
                [
                    'label' => 'Accorder les privilèges administrateur',
                ]
            )
            ->add('site', EntityType::class, ['class' => Site::class, 'choice_label' => 'name']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Member::class,
            ]
        );
    }
}
