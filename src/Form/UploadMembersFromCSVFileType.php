<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UploadMembersFromCSVFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'csvFile',
                FileType::class,
                [
                    'label' => 'Fichier CSV',
                    'mapped' => false,
                    'required' => true,
                    'multiple' => false,
                    'constraints' => [
                        new File(
                            [
                                'maxSize' => '1024k',
//                                'mimeTypes' => [
//                                'application/csvm+json'
//                                ],
                                'mimeTypesMessage' => 'S\'il vous plait, veuillez uploader un document csv valide !'
                            ]

                        )],
                ])
            ->add('save',SubmitType::class,['label'=>'Importer']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                // Configure your form options here
            ]
        );
    }
}
